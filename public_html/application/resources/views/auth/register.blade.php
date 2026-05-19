@extends('layouts.master')

@push('css')
<style>
/* Cinematic Register Page Styles */
.cinematic-auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0c 0%, #121216 50%, #0a0a0c 100%);
    position: relative;
    overflow: hidden;
    padding: 60px 0;
}

/* Ambient glow effects */
.cinematic-auth-wrapper::before {
    content: '';
    position: absolute;
    top: -20%;
    right: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(212, 166, 90, 0.12) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.cinematic-auth-wrapper::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(212, 166, 90, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.auth-container {
    position: relative;
    z-index: 1;
}

/* Page header */
.cinematic-page-header {
    text-align: center;
    margin-bottom: 40px;
}

.cinematic-page-header h1 {
    color: #ffffff;
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 12px;
}

.cinematic-page-header p {
    color: #a0a0a8;
    font-size: 18px;
}

/* Enhanced register box */
.cinematic-register-box {
    background: linear-gradient(145deg, #1a1a1e 0%, #151519 100%);
    border: 1px solid #2a2a30;
    border-radius: 24px;
    padding: 48px;
    box-shadow: 
        0 25px 80px rgba(0, 0, 0, 0.6),
        0 0 0 1px rgba(212, 166, 90, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.05);
    position: relative;
    overflow: hidden;
}

/* Gold accent line at top */
.cinematic-register-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #d4a65a 30%, #e4b66a 50%, #d4a65a 70%, transparent 100%);
}

/* Register icon */
.cinematic-register-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(212, 166, 90, 0.2) 0%, rgba(212, 166, 90, 0.05) 100%);
    border: 2px solid rgba(212, 166, 90, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 32px;
    color: #d4a65a;
    box-shadow: 0 0 30px rgba(212, 166, 90, 0.2);
}

.cinematic-register-icon i {
    color: #d4a65a;
}

/* Title styling */
.cinematic-title {
    color: #ffffff;
    font-size: 28px;
    font-weight: 700;
    text-align: center;
    margin-bottom: 8px;
}

.cinematic-subtitle {
    color: #a0a0a8;
    font-size: 16px;
    text-align: center;
    margin-bottom: 32px;
}

/* Form styling */
.cinematic-form .form-group {
    margin-bottom: 20px;
}

.cinematic-form label {
    color: #a0a0a8;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 10px;
    display: block;
}

.cinematic-form input {
    background: #121216;
    border: 1px solid #2a2a30;
    color: #ffffff;
    border-radius: 12px;
    padding: 16px 20px;
    font-size: 16px;
    width: 100%;
    transition: all 0.3s ease;
}

.cinematic-form input:focus {
    border-color: #d4a65a;
    outline: none;
    box-shadow: 0 0 0 4px rgba(212, 166, 90, 0.15), 0 0 20px rgba(212, 166, 90, 0.1);
    background: #0a0a0c;
}

.cinematic-form input::placeholder {
    color: #6a6a70;
}

/* CAPTCHA styling */
.captcha-group {
    background: rgba(212, 166, 90, 0.05);
    border: 1px solid rgba(212, 166, 90, 0.2);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.captcha-group label {
    color: #d4a65a;
    font-weight: 600;
}

.captcha-group input {
    background: #121216;
    border-color: rgba(212, 166, 90, 0.3);
}

.captcha-group input:focus {
    border-color: #d4a65a;
    box-shadow: 0 0 0 4px rgba(212, 166, 90, 0.15);
}

/* Submit button */
.cinematic-btn {
    background: linear-gradient(135deg, #d4a65a 0%, #c49a4e 100%);
    color: #0a0a0c;
    border: none;
    border-radius: 12px;
    padding: 18px 40px;
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(212, 166, 90, 0.3);
    margin-top: 8px;
}

.cinematic-btn:hover {
    background: linear-gradient(135deg, #e4b66a 0%, #d4a65a 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(212, 166, 90, 0.4);
}

/* Login link section */
.cinematic-login-box {
    background: linear-gradient(145deg, #151519 0%, #121216 100%);
    border: 1px solid #2a2a30;
    border-radius: 20px;
    padding: 28px;
    margin-top: 24px;
    text-align: center;
}

.cinematic-login-text {
    color: #a0a0a8;
    font-size: 15px;
}

.cinematic-login-link {
    color: #d4a65a;
    font-weight: 600;
    text-decoration: none;
    margin-left: 8px;
    transition: color 0.3s ease;
}

.cinematic-login-link:hover {
    color: #e4b66a;
    text-decoration: underline;
}

/* Alert styling */
.cinematic-alert {
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: #ff6b6b;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.cinematic-alert.success {
    background: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.3);
    color: #6bcf7f;
}

.cinematic-alert i {
    font-size: 20px;
}

/* Error messages */
.cinematic-error {
    color: #ff6b6b;
    font-size: 13px;
    margin-top: 8px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .cinematic-auth-wrapper {
        padding: 40px 20px;
    }
    
    .cinematic-register-box {
        padding: 32px 24px;
    }
    
    .cinematic-page-header h1 {
        font-size: 32px;
    }
    
    .cinematic-title {
        font-size: 24px;
    }
}
</style>
@endpush

@section('content')
<div class="cinematic-auth-wrapper">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <!-- Page Header -->
                <div class="cinematic-page-header">
                    <h1>Create Account</h1>
                    <p>Join our community of photography enthusiasts</p>
                </div>
                
                @if (session('success'))
                <div class="cinematic-alert success">
                    <i class="fa fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                
                <div class="cinematic-register-box">
                    <!-- Icon -->
                    <div class="cinematic-register-icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="cinematic-title">Registration</h2>
                    <p class="cinematic-subtitle">Enter your information to get started</p>
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('register') }}" class="cinematic-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                required 
                                autocomplete="name" 
                                autofocus
                            >
                            @error('name')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                required 
                                autocomplete="email"
                            >
                            @error('email')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Create a password"
                                required 
                                autocomplete="new-password"
                            >
                            @error('password')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password-confirm">Confirm Password</label>
                            <input 
                                id="password-confirm" 
                                type="password" 
                                name="password_confirmation" 
                                placeholder="Confirm your password"
                                required 
                                autocomplete="new-password"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label>How will you use Aperture?</label>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="session_solo" name="session_type" value="solo" class="custom-control-input" {{ old('session_type', 'solo') === 'solo' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="session_solo" style="color:#e8e8ec;">Solo — browse the photo libraries</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="session_group_create" name="session_type" value="group_create" class="custom-control-input" {{ old('session_type') === 'group_create' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="session_group_create" style="color:#e8e8ec;">Group — start a new session (you receive an access code)</label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="session_group_join" name="session_type" value="group_join" class="custom-control-input" {{ old('session_type') === 'group_join' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="session_group_join" style="color:#e8e8ec;">Group — join with an access code</label>
                            </div>
                            @error('session_type')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="group-code-wrap" style="display: {{ old('session_type') === 'group_join' ? 'block' : 'none' }};">
                            <label for="group_code">Group access code</label>
                            <input
                                id="group_code"
                                type="text"
                                name="group_code"
                                value="{{ old('group_code') }}"
                                maxlength="6"
                                placeholder="6-character code"
                                autocomplete="off"
                                style="text-transform: uppercase;"
                            >
                            @error('group_code')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- CAPTCHA -->
                        @php
                            $num1 = rand(1, 10);
                            $num2 = rand(1, 10);
                            $captcha_answer = $num1 + $num2;
                            $captcha_hash = md5($captcha_answer . 'aperture_secret_key');
                        @endphp
                        <div class="captcha-group">
                            <label for="captcha">
                                <i class="fa fa-shield-alt"></i> Security Check: What is {{ $num1 }} + {{ $num2 }}?
                            </label>
                            <input 
                                id="captcha" 
                                type="text" 
                                name="captcha" 
                                placeholder="Enter the answer"
                                required
                            >
                            <input type="hidden" name="captcha_hash" value="{{ $captcha_hash }}">
                            <input type="hidden" name="captcha_num1" value="{{ $num1 }}">
                            <input type="hidden" name="captcha_num2" value="{{ $num2 }}">
                            @error('captcha')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="cinematic-btn">
                            Create Account
                        </button>
                    </form>
                </div>
                
                <!-- Login Link -->
                <div class="cinematic-login-box">
                    <span class="cinematic-login-text">Already have an account?</span>
                    <a href="{{ route('login') }}" class="cinematic-login-link">Sign In</a>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    function NumericValidation(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 46 || charCode > 57))
            return false;
        return true;
    }

    (function () {
        var radios = document.querySelectorAll('input[name="session_type"]');
        var codeWrap = document.getElementById('group-code-wrap');
        function toggleGroupCode() {
            var join = document.getElementById('session_group_join');
            if (codeWrap && join) {
                codeWrap.style.display = join.checked ? 'block' : 'none';
            }
        }
        radios.forEach(function (r) { r.addEventListener('change', toggleGroupCode); });
        toggleGroupCode();
    })();
</script>
@endpush
