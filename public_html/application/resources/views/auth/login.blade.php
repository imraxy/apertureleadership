@extends('layouts.master')
@push('css')
<style>
/* Additional cinematic styles for login page */
.cinematic-auth-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0c 0%, #121216 50%, #0a0a0c 100%);
    position: relative;
    overflow: hidden;
    padding: 80px 0;
}

/* Ambient glow effects */
.cinematic-auth-wrapper::before {
    content: '';
    position: absolute;
    top: -20%;
    left: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(212, 166, 90, 0.15) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.cinematic-auth-wrapper::after {
    content: '';
    position: absolute;
    bottom: -20%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.auth-container {
    position: relative;
    z-index: 1;
}

/* Enhanced login box */
.cinematic-login-box {
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
.cinematic-login-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent 0%, #d4a65a 30%, #e4b66a 50%, #d4a65a 70%, transparent 100%);
}

/* Login icon */
.cinematic-login-icon {
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

.cinematic-login-icon i {
    color: #d4a65a;
}

/* Title styling */
.cinematic-title {
    color: #ffffff;
    font-size: 32px;
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
    margin-bottom: 24px;
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

/* Password field with forgot link */
.password-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.forgot-link {
    color: #d4a65a;
    font-size: 13px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #e4b66a;
    text-decoration: underline;
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

/* Sign up section */
.cinematic-signup-box {
    background: linear-gradient(145deg, #151519 0%, #121216 100%);
    border: 1px solid #2a2a30;
    border-radius: 20px;
    padding: 28px;
    margin-top: 24px;
    text-align: center;
}

.cinematic-signup-text {
    color: #a0a0a8;
    font-size: 15px;
}

.cinematic-signup-link {
    color: #d4a65a;
    font-weight: 600;
    text-decoration: none;
    margin-left: 8px;
    transition: color 0.3s ease;
}

.cinematic-signup-link:hover {
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
    
    .cinematic-login-box {
        padding: 32px 24px;
    }
    
    .cinematic-title {
        font-size: 26px;
    }
}
</style>
@endpush

@section('content')
<div class="cinematic-auth-wrapper">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                
                @if(session('login_error'))
                <div class="cinematic-alert">
                    <i class="fa fa-exclamation-circle"></i>
                    <span>{{ session('login_error') }}</span>
                </div>
                @endif
                
                <div class="cinematic-login-box">
                    <!-- Icon -->
                    <div class="cinematic-login-icon">
                        <i class="fa fa-user"></i>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="cinematic-title">Welcome Back</h1>
                    <p class="cinematic-subtitle">Sign in to access your account</p>
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="cinematic-form">
                        @csrf
                        
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
                                autofocus
                            >
                            @error('email')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="password-header">
                                <label for="password">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-link">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required 
                                autocomplete="current-password"
                            >
                            @error('password')
                                <span class="cinematic-error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="cinematic-btn">
                            Sign In
                        </button>
                    </form>
                </div>
                
                @if (Route::has('register'))
                <div class="cinematic-signup-box">
                    <span class="cinematic-signup-text">Don't have an account yet?</span>
                    <a href="{{ route('register') }}" class="cinematic-signup-link">Create Account</a>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection
