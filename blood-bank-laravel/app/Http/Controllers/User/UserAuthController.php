<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UserAuthController extends Controller
{
    /**
     * Show the user login form.
     */
    public function showLoginForm()
    {
        if (auth('user')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('user.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('username'));
        }


        $credentials = $request->only('username', 'password');

        $user = User::where('username', $credentials['username'])
            ->where('status', 'active')
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            auth('user')->login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Invalid username or password.',
        ])->onlyInput('username');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        if (auth('user')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('user.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ];

        // Additional rules if registering as donor
        if ($request->has('register_as_donor')) {
            $rules['age'] = 'required|integer|min:18|max:65';
            $rules['gender'] = 'required|in:Male,Female,Other';
            $rules['blood_group'] = 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
            $rules['last_donation_date'] = 'nullable|date';
        }

        $request->validate($rules);

        // Handle file upload
        $medicalCertificate = null;
        if ($request->hasFile('medical_certificate')) {
            $file = $request->file('medical_certificate');
            $medicalCertificate = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('medical_certificates', $medicalCertificate, 'public');
        }

        // Create user
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'is_smoker' => $request->is_smoker ?? 'no',
            'has_hepatitis' => $request->has_hepatitis ?? 'no',
            'medical_conditions' => $request->medical_conditions,
            'medical_certificate' => $medicalCertificate,
        ]);

        // Create donor profile if registering as donor
        if ($request->has('register_as_donor')) {
            \App\Models\Donor::create([
                'full_name' => $request->full_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'last_donation_date' => $request->last_donation_date,
            ]);
        }

        return redirect()->route('user.login')
            ->with('success', 'Registration successful! You can now log in.');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        auth('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
