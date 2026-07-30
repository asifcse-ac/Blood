<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Registration - BloodLink</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
        }
        
        .card {
            border: 1px solid rgba(102,126,234,.2);
        }
        
        .card-header {
            background: rgba(102,126,234,.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus fa-3x mb-3"></i>
                        <h2>User Registration</h2>
                        <p>Join our blood donation community</p>
                    </div>
                    <div class="p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('user.register') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            
                            <!-- Basic Info -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" name="full_name" value="{{ old('full_name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Blood Group</label>
                                <select class="form-select" name="blood_group">
                                    <option value="">Select (required if donor)</option>
                                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
                            </div>

                            <!-- Donor checkbox -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="register_as_donor" name="register_as_donor" onchange="toggleDonorFields()" {{ old('register_as_donor') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="register_as_donor">
                                        <strong>Register as Blood Donor</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Check if you want to become a blood donor (additional health questions will appear)</small>
                            </div>

                            <!-- Donor fields -->
                            <div id="donor_fields" style="display: {{ old('register_as_donor') ? 'block' : 'none' }};">
                                <div class="card mb-3">
                                    <div class="card-header"><h6 class="mb-0">Donor Information</h6></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Age *</label>
                                                <input type="number" class="form-control" name="age" min="18" max="65" value="{{ old('age') }}" placeholder="18–65">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gender *</label>
                                                <select class="form-select" name="gender">
                                                    <option value="">Select Gender</option>
                                                    @foreach (['Male', 'Female', 'Other'] as $gender)
                                                        <option value="{{ $gender }}" {{ old('gender') === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Donation Date (Optional)</label>
                                            <input type="date" class="form-control" name="last_donation_date" value="{{ old('last_donation_date') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header"><h6 class="mb-0">Health Information</h6></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Are you a smoker?</label>
                                                <select class="form-select" name="is_smoker">
                                                    <option value="no" {{ old('is_smoker') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('is_smoker') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Do you have hepatitis?</label>
                                                <select class="form-select" name="has_hepatitis">
                                                    <option value="no" {{ old('has_hepatitis') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('has_hepatitis') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Other Medical Conditions</label>
                                            <textarea class="form-control" name="medical_conditions" rows="2">{{ old('medical_conditions') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Medical Certificate (Optional)</label>
                                            <input type="file" class="form-control" name="medical_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Password fields -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-user-plus"></i> Register
                            </button>
                        </form>

                        <div class="text-center">
                            <p>Already have an account? <a href="{{ route('user.login') }}">Login here</a></p>
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDonorFields() {
            const isDonor = document.getElementById('register_as_donor').checked;
            document.getElementById('donor_fields').style.display = isDonor ? 'block' : 'none';
        }

        function validateForm() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return false;
            }
            if (password !== confirm) {
                alert('Passwords do not match');
                return false;
            }

            const isDonor = document.getElementById('register_as_donor').checked;
            if (isDonor) {
                const age = document.querySelector('input[name="age"]').value;
                const gender = document.querySelector('select[name="gender"]').value;
                const bloodGroup = document.querySelector('select[name="blood_group"]').value;

                if (!age || !gender || !bloodGroup) {
                    alert('Age, gender, and blood group are required when registering as donor');
                    return false;
                }
                if (age < 18 || age > 65) {
                    alert('Age must be between 18 and 65 to register as donor');
                    return false;
                }
            }

            return true;
        }

        // Run on load
        window.addEventListener('load', toggleDonorFields);
    </script>
</body>
</html>
