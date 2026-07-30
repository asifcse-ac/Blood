<?php $__env->startSection('title', 'AI Assistant'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .chat-container {
        height: 600px;
        display: flex;
        flex-direction: column;
        background: rgba(255,252,249,.96);
        border: 1px solid rgba(196,30,58,.09);
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(24,16,14,.07);
    }

    .chat-header {
        background: linear-gradient(135deg, #C41E3A, #8B0F24);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .chat-header .ai-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #FDF8F3;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
    }

    .message.user {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .message.assistant .message-avatar {
        background: linear-gradient(135deg, #C41E3A, #8B0F24);
        color: white;
    }

    .message.user .message-avatar {
        background: #7c3aed;
        color: white;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 18px;
        line-height: 1.5;
        font-size: 14px;
    }

    .message.assistant .message-content {
        background: white;
        border: 1px solid rgba(196,30,58,.08);
    }

    .message.user .message-content {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: white;
    }

    .chat-input-area {
        padding: 20px;
        background: white;
        border-top: 1px solid rgba(196,30,58,.08);
    }

    .chat-input-wrapper {
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        border: 2px solid rgba(196,30,58,.12);
        border-radius: 25px;
        padding: 12px 20px;
        font-size: 15px;
        transition: border-color 0.2s;
        background: #FDF8F3;
    }

    .chat-input:focus {
        outline: none;
        border-color: #C41E3A;
    }

    .send-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #C41E3A, #8B0F24);
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: transform 0.2s, opacity 0.2s;
    }

    .send-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .typing-indicator {
        display: none;
        padding: 12px 18px;
        background: white;
        border-radius: 18px;
        border: 1px solid rgba(196,30,58,.08);
        width: fit-content;
    }

    .typing-indicator.show {
        display: block;
    }

    .typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #C41E3A;
        border-radius: 50%;
        margin: 0 2px;
        animation: typing 1s infinite;
    }

    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 100% { opacity: 0.4; transform: translateY(0); }
        50% { opacity: 1; transform: translateY(-5px); }
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .quick-action-btn {
        padding: 10px 18px;
        border: 2px solid #C41E3A;
        background: white;
        color: #C41E3A;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-action-btn:hover {
        background: #C41E3A;
        color: white;
    }

    .welcome-message {
        text-align: center;
        padding: 40px 20px;
        color: #5C4033;
    }

    .welcome-message i {
        font-size: 60px;
        color: #C41E3A;
        margin-bottom: 20px;
    }

    .welcome-message h4 {
        font-family: 'Instrument Serif', serif;
        color: #18100E;
    }

    .extracted-details {
        background: rgba(196,30,58,.04);
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
        border: 1px solid rgba(196,30,58,.08);
    }

    .extracted-details h6 {
        color: #C41E3A;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .extracted-details .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(196,30,58,.06);
    }

    .extracted-details .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #5C4033;
    }

    .detail-value {
        font-weight: 600;
        color: #18100E;
    }

    .detail-value.blood-group {
        background: #C41E3A;
        color: white;
        padding: 2px 10px;
        border-radius: 8px;
    }

    .stats-preview {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .stat-item {
        background: white;
        padding: 12px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid rgba(196,30,58,.08);
    }

    .stat-item .num {
        font-family: 'Instrument Serif', serif;
        font-size: 24px;
        color: #C41E3A;
    }

    .stat-item .label {
        font-size: 11px;
        color: #5C4033;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-buttons {
        margin-top: 12px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        font-size: 12px;
        padding: 6px 12px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2><i class="fas fa-robot" style="color: #C41E3A;"></i> AI Assistant</h2>
        <div class="page-header-sub">Powered by AI for smarter blood bank management</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="quick-actions">
            <button class="quick-action-btn" onclick="sendQuickMessage('summarize', 'Summarize the latest blood requests')">
                <i class="fas fa-list-alt"></i> Summarize Requests
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('suggest', 'Generate donor outreach message for urgent B+ blood need')">
                <i class="fas fa-bullhorn"></i> Generate Outreach
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('analyze', 'Analyze current blood stock status')">
                <i class="fas fa-chart-pie"></i> Analyze Stock
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('extract', 'I need to process a blood request: Patient John needs 2 units of O- blood urgently at City Hospital')">
                <i class="fas fa-magic"></i> Extract Details
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('recommend', 'Recommend strategies to improve donor retention')">
                <i class="fas fa-lightbulb"></i> Get Recommendations
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="chat-container">
            <div class="chat-header">
                <div class="ai-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h5 class="mb-0" style="font-family: 'Instrument Serif', serif;">BloodLink Admin AI</h5>
                    <small><i class="fas fa-circle" style="color: #22c55e; font-size: 8px;"></i> Ready to assist</small>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="welcome-message">
                    <i class="fas fa-heartbeat"></i>
                    <h4>Welcome, Administrator!</h4>
                    <p>I'm your AI assistant for blood bank management. I can help you with:</p>
                    <ul class="list-unstyled text-start" style="max-width: 400px; margin: 0 auto;">
                        <li style="padding: 5px 0;"><i class="fas fa-check" style="color: #22c55e;"></i> Summarize and analyze blood requests</li>
                        <li style="padding: 5px 0;"><i class="fas fa-check" style="color: #22c55e;"></i> Generate donor outreach messages</li>
                        <li style="padding: 5px 0;"><i class="fas fa-check" style="color: #22c55e;"></i> Analyze blood stock trends</li>
                        <li style="padding: 5px 0;"><i class="fas fa-check" style="color: #22c55e;"></i> Extract request details from text</li>
                        <li style="padding: 5px 0;"><i class="fas fa-check" style="color: #22c55e;"></i> Provide management recommendations</li>
                    </ul>
                    <p class="mt-3" style="font-style: italic;">Try the quick actions above or type your request below!</p>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="chat-input-wrapper">
                    <input type="text" class="chat-input" id="messageInput" placeholder="Ask me anything about blood bank management..." maxlength="1000">
                    <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-info-circle"></i> AI Capabilities
            </div>
            <div class="dash-card-body" style="padding: 20px;">
                <div class="mb-3">
                    <strong style="color: #C41E3A;"><i class="fas fa-magic"></i> Extract</strong>
                    <p class="small text-muted mb-0">Extract structured details from natural language blood requests</p>
                </div>
                <div class="mb-3">
                    <strong style="color: #C41E3A;"><i class="fas fa-compress-alt"></i> Summarize</strong>
                    <p class="small text-muted mb-0">Get concise summaries of multiple blood requests</p>
                </div>
                <div class="mb-3">
                    <strong style="color: #C41E3A;"><i class="fas fa-bullhorn"></i> Suggest</strong>
                    <p class="small text-muted mb-0">Generate donor outreach messages for blood drives</p>
                </div>
                <div class="mb-3">
                    <strong style="color: #C41E3A;"><i class="fas fa-chart-line"></i> Analyze</strong>
                    <p class="small text-muted mb-0">Analyze blood stock and request trends</p>
                </div>
                <div>
                    <strong style="color: #C41E3A;"><i class="fas fa-lightbulb"></i> Recommend</strong>
                    <p class="small text-muted mb-0">Get actionable recommendations for operations</p>
                </div>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <i class="fas fa-key"></i> API Configuration
            </div>
            <div class="dash-card-body" style="padding: 20px;">
                <?php if(env('GROQ_API_KEY')): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle" style="color: #22c55e;"></i>
                        <span class="small">GROQ API Connected</span>
                    </div>
                    <p class="small text-muted mt-2 mb-0">Using Llama 3.1 70B model for enhanced AI responses</p>
                <?php else: ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-triangle" style="color: #d97706;"></i>
                        <span class="small">Using Local Processing</span>
                    </div>
                    <p class="small text-muted mt-2 mb-0">Add GROQ_API_KEY to .env for enhanced AI features</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    // Enable send on Enter
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendQuickMessage(action, message) {
        messageInput.value = message;
        sendMessage(action);
    }

    function sendMessage(forcedAction = null) {
        const message = messageInput.value.trim();
        if (!message) return;

        // Determine action
        const action = forcedAction || detectAction(message);

        // Clear input
        messageInput.value = '';

        // Hide welcome message
        const welcome = chatMessages.querySelector('.welcome-message');
        if (welcome) welcome.remove();

        // Add user message
        addMessage(message, 'user');

        // Show typing indicator
        showTypingIndicator();

        // Disable send button
        sendBtn.disabled = true;

        // Send to server
        fetch('<?php echo e(route("ai.chat")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            sendBtn.disabled = false;

            if (data.success) {
                if (data.details) {
                    // Format extracted details
                    let responseHtml = '<p>I\'ve extracted the following details:</p>';
                    responseHtml += '<div class="extracted-details">';
                    responseHtml += '<h6><i class="fas fa-clipboard-list"></i> Request Details</h6>';

                    if (data.details.blood_group) {
                        responseHtml += '<div class="detail-item"><span class="detail-label">Blood Group</span><span class="detail-value blood-group">' + data.details.blood_group + '</span></div>';
                    }

                    if (data.details.units) {
                        responseHtml += '<div class="detail-item"><span class="detail-label">Units Needed</span><span class="detail-value">' + data.details.units + '</span></div>';
                    }

                    if (data.details.hospital) {
                        responseHtml += '<div class="detail-item"><span class="detail-label">Hospital</span><span class="detail-value">' + data.details.hospital + '</span></div>';
                    }

                    if (data.details.urgency) {
                        let urgencyClass = data.details.urgency === 'Critical' ? 'color: #C41E3A;' : (data.details.urgency === 'Urgent' ? 'color: #d97706;' : 'color: #22c55e;');
                        responseHtml += '<div class="detail-item"><span class="detail-label">Urgency</span><span class="detail-value" style="' + urgencyClass + '">' + data.details.urgency + '</span></div>';
                    }

                    responseHtml += '</div>';

                    // Add action buttons
                    responseHtml += '<div class="action-buttons">';
                    responseHtml += '<a href="<?php echo e(route("admin.requests.index")); ?>" class="btn btn-sm btn-crime"><i class="fas fa-list"></i> View Requests</a>';
                    responseHtml += '<button class="btn btn-sm btn-outline-secondary" onclick="copyDetails(' + JSON.stringify(data.details).replace(/"/g, '&quot;') + ')"><i class="fas fa-copy"></i> Copy</button>';
                    responseHtml += '</div>';

                    addMessage(responseHtml, 'assistant', true);
                } else if (data.message) {
                    addMessage(data.message, 'assistant');
                } else if (data.result) {
                    addMessage(data.result, 'assistant');
                } else {
                    addMessage('I\'m sorry, I could not generate a response. Please try again with a different request.', 'assistant');
                }

                if (data.used) {
                    console.log('Processed by: ' + data.used);
                }
            } else {
                addMessage('Sorry, I couldn\'t process your request. Please try again.', 'assistant');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            sendBtn.disabled = false;
            addMessage('Sorry, there was an error processing your request. Please try again.', 'assistant');
            console.error('Error:', error);
        });
    }

    function detectAction(message) {
        const lowerMsg = message.toLowerCase();
        
        if (lowerMsg.includes('summarize') || lowerMsg.includes('summary')) {
            return 'summarize';
        } else if (lowerMsg.includes('suggest') || lowerMsg.includes('outreach') || lowerMsg.includes('message')) {
            return 'suggest';
        } else if (lowerMsg.includes('analyze') || lowerMsg.includes('analysis') || lowerMsg.includes('stock')) {
            return 'extract';
        } else if (lowerMsg.includes('recommend') || lowerMsg.includes('improve')) {
            return 'suggest';
        }
        
        return 'extract';
    }

    function addMessage(content, type, isHtml = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + type;

        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = type === 'user' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        if (isHtml) {
            contentDiv.innerHTML = content;
        } else {
            contentDiv.textContent = content;
        }

        messageDiv.appendChild(avatarDiv);
        messageDiv.appendChild(contentDiv);
        chatMessages.appendChild(messageDiv);

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTypingIndicator() {
        const indicatorDiv = document.createElement('div');
        indicatorDiv.className = 'message assistant';
        indicatorDiv.id = 'typingIndicator';

        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = '<i class="fas fa-robot"></i>';

        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator show';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';

        indicatorDiv.appendChild(avatarDiv);
        indicatorDiv.appendChild(typingDiv);
        chatMessages.appendChild(indicatorDiv);

        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    function copyDetails(details) {
        const text = 'Blood Group: ' + (details.blood_group || 'N/A') + '\n' +
                     'Units: ' + (details.units || 'N/A') + '\n' +
                     'Hospital: ' + (details.hospital || 'N/A') + '\n' +
                     'Urgency: ' + (details.urgency || 'N/A');
        
        navigator.clipboard.writeText(text).then(function() {
            alert('Details copied to clipboard!');
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Python\Web_dev\blood-bank-laravel\resources\views/admin/ai-chat.blade.php ENDPATH**/ ?>