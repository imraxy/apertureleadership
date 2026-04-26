<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- begin::Head -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Aperture') }} - Admin Login</title>
    <meta name="description" content="Admin Login">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Cinematic Admin Login Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a0a0c 0%, #121216 50%, #0a0a0c 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ambient glow effects */
        body::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(212, 166, 90, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 166, 90, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-container {
            background: linear-gradient(145deg, #1a1a1e 0%, #151519 100%);
            border: 1px solid #2a2a30;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(212, 166, 90, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }

        /* Gold accent line at top */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #d4a65a 30%, #e4b66a 50%, #d4a65a 70%, transparent 100%);
        }

        /* Logo section */
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo img {
            max-height: 60px;
            margin-bottom: 16px;
        }

        .login-logo h2 {
            color: #d4a65a;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        /* Admin icon */
        .admin-icon {
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

        .admin-icon i {
            color: #d4a65a;
        }

        /* Title */
        .login-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 32px;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: none; /* Hide labels, using placeholders */
        }

        .form-control {
            background: #121216;
            border: 1px solid #2a2a30;
            color: #ffffff;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 15px;
            width: 100%;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            border-color: #d4a65a;
            outline: none;
            box-shadow: 0 0 0 4px rgba(212, 166, 90, 0.15), 0 0 20px rgba(212, 166, 90, 0.1);
            background: #0a0a0c;
        }

        .form-control::placeholder {
            color: #6a6a70;
        }

        /* Remember me checkbox */
        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #d4a65a;
            cursor: pointer;
        }

        .remember-row label {
            color: #a0a0a8;
            font-size: 14px;
            cursor: pointer;
        }

        /* Submit button */
        .login-btn {
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
            font-family: 'Poppins', sans-serif;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #e4b66a 0%, #d4a65a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(212, 166, 90, 0.4);
        }

        /* Alert messages */
        .alert {
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
        }

        .alert i {
            font-size: 18px;
        }

        /* Error messages */
        .invalid-feedback {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 8px;
            display: block;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 36px 24px;
            }

            .login-title {
                font-size: 22px;
            }

            .admin-icon {
                width: 70px;
                height: 70px;
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Logo -->
            <div class="login-logo">
                @if (config('settings.site_logo') != null)
                    <img src="{{ asset('application/storage/app/public/'.config('settings.site_logo')) }}" alt="Logo">
                @else
                    <h2>{{ config('app.name', 'Aperture') }}</h2>
                @endif
            </div>

            <!-- Admin Icon -->
            <div class="admin-icon">
                <i class="fa fa-user-shield"></i>
            </div>

            <!-- Title -->
            <h1 class="login-title">Admin Sign In</h1>

            <!-- Error Alert -->
            @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <!-- Login Form -->
            <form method="post" action="{{ route('admin_login') }}">
                @csrf

                <div class="form-group">
                    <input 
                        class="form-control @error('email') is-invalid @enderror" 
                        type="email" 
                        placeholder="Email Address" 
                        name="email" 
                        value="{{ old('email') }}" 
                        autofocus 
                        autocomplete="email"
                        required
                    >
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <input 
                        class="form-control @error('password') is-invalid @enderror" 
                        type="password" 
                        placeholder="Password" 
                        name="password" 
                        autocomplete="current-password"
                        required
                    >
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</body>

</html>
