<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodStock;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AIController extends Controller
{
    /**
     * Handle AI chat requests.
     */
    public function chat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'action' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $message = $request->message;
        $action = $request->action ?? 'request';

        // Check if user is authenticated
        $user = auth('user')->user();

        // Extract details from message first
        $details = $this->extractDetails($message);

        // If blood group found, process as blood request
        if ($details['blood_group']) {
            return $this->processBloodRequest($details, $message, $user);
        }

        // Try to use GROQ API for general queries
        $apiKey = env('GROQ_API_KEY');

        if ($apiKey) {
            return $this->callGroqAPI($message, $action);
        }

        // Fallback to local processing
        return $this->localProcess($message, $action);
    }

    /**
     * Extract details from message.
     */
    private function extractDetails(string $message): array
    {
        $details = [
            'blood_group' => null,
            'units' => 1,
            'hospital' => null,
            'urgency' => 'Normal',
        ];

        // Extract blood group
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodGroups as $group) {
            if (stripos($message, $group) !== false) {
                $details['blood_group'] = $group;
                break;
            }
        }

        // Extract units
        if (preg_match('/(\d+)\s*(?:unit|bag|pint)/i', $message, $matches)) {
            $details['units'] = (int) $matches[1];
        }
        // Also check for number before blood group
        if (!$details['units'] || $details['units'] == 1) {
            if (preg_match('/(\d+)\s*(?:units?|bags?|pints?)?\s*(?:of\s+)?[ABO][\+\-]?/i', $message, $matches)) {
                $details['units'] = (int) $matches[1];
            }
        }

        // Extract urgency
        if (preg_match('/\b(critical|emergency)\b/i', $message)) {
            $details['urgency'] = 'Critical';
        } elseif (preg_match('/\b(urgent|urgently)\b/i', $message)) {
            $details['urgency'] = 'Urgent';
        }

        // Extract hospital
        if (preg_match('/(?:at|in|from|for)\s+([A-Z][A-Za-z\s]+(?:Hospital|Medical|Clinic|Center|Healthcare))/i', $message, $matches)) {
            $details['hospital'] = trim($matches[1]);
        }

        return $details;
    }

    /**
     * Process blood request - check stock and create request.
     */
    private function processBloodRequest(array $details, string $originalMessage, $user)
    {
        $bloodGroup = $details['blood_group'];
        $unitsNeeded = $details['units'];
        $hospital = $details['hospital'];
        $urgency = $details['urgency'];

        // Check blood stock
        $stock = BloodStock::findByBloodGroup($bloodGroup);
        $availableUnits = $stock ? $stock->quantity : 0;

        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => true,
                'type' => 'auth_required',
                'message' => "I understood your request for {$unitsNeeded} unit(s) of {$bloodGroup} blood.",
                'details' => $details,
                'stock_status' => [
                    'available' => $availableUnits,
                    'sufficient' => $availableUnits >= $unitsNeeded,
                ],
                'response' => 'Please login or register to create a blood request. Would you like me to help you with anything else?',
            ]);
        }

        // Check stock availability
        if ($availableUnits == 0) {
            // No stock available
            $response = $this->generateNoStockResponse($bloodGroup, $unitsNeeded, $hospital);
            
            return response()->json([
                'success' => true,
                'type' => 'no_stock',
                'message' => $response,
                'details' => $details,
                'stock_status' => [
                    'available' => 0,
                    'sufficient' => false,
                ],
            ]);
        }

        if ($availableUnits < $unitsNeeded) {
            // Insufficient stock
            $response = $this->generateInsufficientStockResponse($bloodGroup, $unitsNeeded, $availableUnits, $hospital);
            
            return response()->json([
                'success' => true,
                'type' => 'insufficient_stock',
                'message' => $response,
                'details' => $details,
                'stock_status' => [
                    'available' => $availableUnits,
                    'sufficient' => false,
                    'shortage' => $unitsNeeded - $availableUnits,
                ],
                'can_request_partial' => true,
                'partial_units' => $availableUnits,
            ]);
        }

        // Sufficient stock - create the request
        $bloodRequest = BloodRequest::create([
            'user_id' => $user->user_id,
            'blood_group' => $bloodGroup,
            'units_requested' => $unitsNeeded,
            'hospital_name' => $hospital ?? 'Not specified',
            'reason' => 'AI Assistant Request: ' . substr($originalMessage, 0, 200),
            'urgency' => $urgency,
            'status' => 'pending',
        ]);

        $response = $this->generateSuccessResponse($bloodRequest, $availableUnits);

        return response()->json([
            'success' => true,
            'type' => 'request_created',
            'message' => $response,
            'details' => $details,
            'request' => [
                'id' => $bloodRequest->request_id,
                'blood_group' => $bloodRequest->blood_group,
                'units' => $bloodRequest->units_requested,
                'hospital' => $bloodRequest->hospital_name,
                'urgency' => $bloodRequest->urgency,
                'status' => ucfirst($bloodRequest->status),
                'created_at' => now()->format('M d, Y H:i'),
            ],
            'stock_status' => [
                'available' => $availableUnits,
                'sufficient' => true,
                'remaining_after' => $availableUnits - $unitsNeeded,
            ],
        ]);
    }

    /**
     * Generate response for no stock.
     */
    private function generateNoStockResponse(string $bloodGroup, int $units, ?string $hospital): string
    {
        $response = "❌ Unfortunately, we currently have **no stock** of {$bloodGroup} blood available.\n\n";
        
        // Find compatible donors
        $compatibleDonors = Donor::where('blood_group', $bloodGroup)
            ->where('is_available', true)
            ->count();

        if ($compatibleDonors > 0) {
            $response .= "✅ Good news! We have **{$compatibleDonors} compatible donor(s)** registered in our system. ";
            $response .= "Our team will contact them urgently.\n\n";
        } else {
            $response .= "⚠️ There are currently no compatible donors registered for {$bloodGroup} blood.\n\n";
        }

        $response .= "Your request has been noted. Here are your options:\n";
        $response .= "• Try checking nearby blood banks\n";
        $response .= "• Contact emergency services if critical\n";
        $response .= "• We'll notify you when stock becomes available";

        return $response;
    }

    /**
     * Generate response for insufficient stock.
     */
    private function generateInsufficientStockResponse(string $bloodGroup, int $needed, int $available, ?string $hospital): string
    {
        $shortage = $needed - $available;
        
        $response = "⚠️ **Insufficient Stock Alert**\n\n";
        $response .= "You requested: **{$needed} unit(s)** of {$bloodGroup}\n";
        $response .= "Available: **{$available} unit(s)**\n";
        $response .= "Shortage: **{$shortage} unit(s)**\n\n";

        // Find compatible donors
        $compatibleDonors = Donor::where('blood_group', $bloodGroup)
            ->where('is_available', true)
            ->count();

        if ($compatibleDonors > 0) {
            $response .= "✅ We have **{$compatibleDonors} compatible donor(s)** who may be able to help with the remaining {$shortage} unit(s).\n\n";
        }

        $response .= "**Options available:**\n";
        $response .= "• Request the available **{$available} unit(s)** now and wait for more\n";
        $response .= "• Request all {$needed} units and wait for stock replenishment\n";
        $response .= "• Check other blood banks for the remaining units";

        return $response;
    }

    /**
     * Generate success response.
     */
    private function generateSuccessResponse(BloodRequest $request, int $currentStock): string
    {
        $urgencyIcon = match($request->urgency) {
            'Critical' => '🚨',
            'Urgent' => '⚡',
            default => '✅'
        };

        $response = "{$urgencyIcon} **Blood Request Created Successfully!**\n\n";
        $response .= "**Request Details:**\n";
        $response .= "• Blood Group: **{$request->blood_group}**\n";
        $response .= "• Units: **{$request->units_requested}**\n";
        $response .= "• Hospital: **{$request->hospital_name}**\n";
        $response .= "• Urgency: **{$request->urgency}**\n";
        $response .= "• Status: **Pending Approval**\n\n";
        
        $remaining = $currentStock - $request->units_requested;
        $response .= "**Stock Status:**\n";
        $response .= "• Current availability: {$currentStock} units\n";
        $response .= "• After approval: {$remaining} units remaining\n\n";
        
        $response .= "Your request #{$request->request_id} is now pending admin approval. ";
        $response .= "You'll receive a notification once it's processed.";

        return $response;
    }

    /**
     * Create partial blood request.
     */
    public function createPartialRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_group' => 'required|string',
            'units' => 'required|integer|min:1',
            'hospital' => 'nullable|string',
            'urgency' => 'nullable|string',
            'original_message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth('user')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to create a request.',
            ], 401);
        }

        $bloodRequest = BloodRequest::create([
            'user_id' => $user->user_id,
            'blood_group' => $request->blood_group,
            'units_requested' => $request->units,
            'hospital_name' => $request->hospital ?? 'Not specified',
            'reason' => 'AI Assistant Request' . ($request->original_message ? ': ' . substr($request->original_message, 0, 200) : ''),
            'urgency' => $request->urgency ?? 'Normal',
            'status' => 'pending',
        ]);

        $stock = BloodStock::findByBloodGroup($request->blood_group);
        $availableUnits = $stock ? $stock->quantity : 0;

        return response()->json([
            'success' => true,
            'message' => 'Request created successfully!',
            'request' => [
                'id' => $bloodRequest->request_id,
                'blood_group' => $bloodRequest->blood_group,
                'units' => $bloodRequest->units_requested,
                'hospital' => $bloodRequest->hospital_name,
                'urgency' => $bloodRequest->urgency,
                'status' => ucfirst($bloodRequest->status),
            ],
        ]);
    }

    /**
     * Call GROQ API for AI processing.
     */
    private function callGroqAPI(string $message, string $action)
    {
        $systemPrompt = $this->getSystemPrompt($action);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message],
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';

                return response()->json([
                    'success' => true,
                    'type' => 'ai_response',
                    'message' => $content,
                    'used' => 'groq',
                ]);
            }
        } catch (\Exception $e) {
            // Fall back to local processing
        }

        return $this->localProcess($message, $action);
    }

    /**
     * Local processing fallback.
     */
    private function localProcess(string $message, string $action)
    {
        switch ($action) {
            case 'summarize':
                return $this->localSummarizeRequests();
            case 'analyze':
                return $this->getStockInfo();
            case 'suggest':
                return $this->generateOutreachMessage($message);
            case 'recommend':
                return $this->recommendRetentionStrategies();
            case 'extract':
                $details = $this->extractDetails($message);
                if ($details['blood_group']) {
                    return response()->json([
                        'success' => true,
                        'type' => 'extracted_details',
                        'message' => 'I have extracted the request details from your text.',
                        'details' => $details,
                        'used' => 'local',
                    ]);
                }
                break;
        }

        // Check for stock inquiry
        if (preg_match('/\b(stock|availability|available|have)\b/i', $message)) {
            return $this->getStockInfo();
        }

        // Check for donor inquiry
        if (preg_match('/\b(donor|donors|find\s+donor)\b/i', $message)) {
            return $this->getDonorInfo($message);
        }

        // Default helpful response
        return response()->json([
            'success' => true,
            'type' => 'help',
            'message' => "I'm here to help with blood requests! You can say things like:\n\n" .
                "• \"I need 2 units of A+ blood at City Hospital\"\n" .
                "• \"3 units O- urgently for surgery\"\n" .
                "• \"Check blood stock availability\"\n" .
                "• \"Find B+ donors\"\n\n" .
                "How can I assist you today?",
            'used' => 'local',
        ]);
    }

    /**
     * Get stock information.
     */
    private function getStockInfo()
    {
        $stocks = BloodStock::all();
        $stockInfo = [];
        $critical = [];
        $low = [];

        foreach ($stocks as $stock) {
            $stockInfo[$stock->blood_group] = [
                'quantity' => $stock->quantity,
                'status' => $stock->getStatusLabel(),
            ];

            if ($stock->quantity == 0) {
                $critical[] = $stock->blood_group;
            } elseif ($stock->quantity <= 5) {
                $low[] = $stock->blood_group;
            }
        }

        $message = "**Current Blood Stock Status:**\n\n";
        
        foreach ($stockInfo as $group => $info) {
            $icon = $info['quantity'] > 5 ? '✅' : ($info['quantity'] > 0 ? '⚠️' : '❌');
            $message .= "{$icon} **{$group}**: {$info['quantity']} units ({$info['status']})\n";
        }

        if (!empty($critical)) {
            $message .= "\n🚨 **Critical**: No stock for " . implode(', ', $critical);
        }
        if (!empty($low)) {
            $message .= "\n⚠️ **Low stock**: " . implode(', ', $low);
        }

        return response()->json([
            'success' => true,
            'type' => 'stock_info',
            'message' => $message,
            'stocks' => $stockInfo,
            'critical' => $critical,
            'low' => $low,
        ]);
    }

    /**
     * Get donor information.
     */
    private function getDonorInfo(string $message)
    {
        // Try to extract blood group from message
        $bloodGroup = null;
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        foreach ($bloodGroups as $group) {
            if (stripos($message, $group) !== false) {
                $bloodGroup = $group;
                break;
            }
        }

        $query = Donor::where('is_available', true);
        
        if ($bloodGroup) {
            $query->where('blood_group', $bloodGroup);
        }

        $donors = $query->take(5)->get();
        $totalDonors = $query->count();

        if ($totalDonors == 0) {
            return response()->json([
                'success' => true,
                'type' => 'donor_info',
                'message' => $bloodGroup 
                    ? "No available donors found for {$bloodGroup} blood group."
                    : "No available donors found in the system.",
            ]);
        }

        $message = $bloodGroup 
            ? "**Available {$bloodGroup} Donors:** ({$totalDonors} found)\n\n"
            : "**Available Donors:** ({$totalDonors} total)\n\n";

        foreach ($donors as $donor) {
            $message .= "• **{$donor->full_name}** - {$donor->blood_group}\n";
            $city = $donor->city ?? 'Location not specified';
            $message .= "  📍 {$city}\n\n";
        }

        if ($totalDonors > 5) {
            $message .= "_...and " . ($totalDonors - 5) . " more donors available._";
        }

        return response()->json([
            'success' => true,
            'type' => 'donor_info',
            'message' => $message,
            'donors_count' => $totalDonors,
            'blood_group' => $bloodGroup,
        ]);
    }

    /**
     * Get system prompt based on action.
     */
    private function getSystemPrompt(string $action): string
    {
        return 'You are a helpful blood bank assistant. Provide concise, helpful responses about blood donation, blood requests, and related medical information. Keep responses under 200 words.';
    }

    /**
     * Local summary of blood requests.
     */
    private function localSummarizeRequests()
    {
        $totalRequests = BloodRequest::count();
        $pending = BloodRequest::where('status', 'pending')->count();
        $approved = BloodRequest::where('status', 'approved')->count();
        $rejected = BloodRequest::where('status', 'rejected')->count();

        $topGroups = BloodRequest::selectRaw('blood_group, COUNT(*) as requests')
            ->groupBy('blood_group')
            ->orderByDesc('requests')
            ->limit(3)
            ->get();

        $message = "**Latest Blood Request Summary:**\n\n";
        $message .= "• Total requests: {$totalRequests}\n";
        $message .= "• Pending: {$pending}\n";
        $message .= "• Approved: {$approved}\n";
        $message .= "• Rejected: {$rejected}\n\n";

        if ($topGroups->isNotEmpty()) {
            $message .= "**Top requested blood groups:**\n";
            foreach ($topGroups as $group) {
                $message .= "• {$group->blood_group}: {$group->requests} requests\n";
            }
        }

        return response()->json([
            'success' => true,
            'type' => 'summary',
            'message' => $message,
            'used' => 'local',
        ]);
    }

    /**
     * Generate a local outreach message.
     */
    private function generateOutreachMessage(string $message)
    {
        $details = $this->extractDetails($message);
        $bloodGroup = $details['blood_group'] ?? 'all blood groups';
        $units = $details['units'] ?? 'several';
        $urgency = $details['urgency'] === 'Critical' ? 'immediately' : 'as soon as possible';

        $outreach = "Attention donors! We urgently need {$units} unit(s) of {$bloodGroup} blood {$urgency}. " .
            "If you are eligible and available, please donate at our nearest center or contact our team today. " .
            "Your donation can save lives.";

        return response()->json([
            'success' => true,
            'type' => 'suggestion',
            'message' => $outreach,
            'used' => 'local',
        ]);
    }

    /**
     * Recommend donor retention strategies.
     */
    private function recommendRetentionStrategies()
    {
        $message = "Here are some effective donor retention strategies:\n\n";
        $message .= "• Maintain regular communication with donors through email and SMS updates.\n";
        $message .= "• Offer easy scheduling and reminders for repeat donations.\n";
        $message .= "• Share stories of impact to reinforce the value of each donation.\n";
        $message .= "• Provide recognition and thank-you messages after each donation.\n";
        $message .= "• Host donor appreciation events and blood drives to build community engagement.";

        return response()->json([
            'success' => true,
            'type' => 'recommendation',
            'message' => $message,
            'used' => 'local',
        ]);
    }

    /**
     * Show AI chat UI.
     */
    public function showChatUI()
    {
        return view('user.ai-chat');
    }

    /**
     * Show Admin AI chat UI.
     */
    public function showAdminChatUI()
    {
        return view('admin.ai-chat');
    }
}
