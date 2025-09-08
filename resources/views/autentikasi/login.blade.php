<!--
=========================================================
* Argon Dashboard - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com
=========================================================
-->

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistem Informasi Sarana & Prasarana - Politeknik Negeri Banyuwangi">
    <meta name="author" content="Politeknik Negeri Banyuwangi">
    <title>Login | {{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ url('favicon.png') }}" type="image/png">
    
    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('argon') }}/assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="{{ url('argon') }}/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    
    <!-- Iconify -->
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ url('argon') }}/assets/css/argon.css?v=1.2.0" type="text/css">
    <script src="{{ url('js/util.js') }}"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('/assets/css/halamanAwal.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #155DFC;
            --primary-hover:rgb(47, 108, 239);
            --text-primary: #333333;
            --text-secondary: #666666;
            --text-muted: #999999;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #e8e8e8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 48px 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            width: 280px;
            margin: 0 auto 24px;
            display: block;
            object-fit: contain;
        }


        .system-name {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 32px;
            text-decoration: none;
            display: inline-block;
        }

        .system-name:hover {
            color: var(--primary-hover);
            text-decoration: none;
        }

        .sso-btn {
            display: block;
            width: 100%;
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .sso-btn:hover {
            background-color: var(--primary-hover);
            color: var(--white);
            text-decoration: none;
            transform: translateY(-1px);
        }

        .terms-text {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .terms-link {
            color: var(--primary-color);
            text-decoration: none;
        }

        .terms-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .copyright {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 8px;
        }

        /* Form styles for non-SSO login */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: var(--white);
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.1);
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group-prepend {
            margin-right: 0;
        }

        .input-group-text {
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 12px 16px;
            color: var(--text-muted);
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .btn-primary {
            width: 100%;
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            color: var(--white);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .custom-control-label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Hide header for SSO only mode */
        .sso-only .header {
            display: none;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 32px 24px;
                margin: 16px;
            }

            .welcome-title {
                font-size: 22px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }
        }

        /* Loading state */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="@if (config('services.oauth_server.sso_enable')) sso-only @endif">
    
    @if (!config('services.oauth_server.sso_enable'))
    <!-- Header for non-SSO mode -->
    <div class="header bg-gradient-primary py-7">
        <div class="container">
            <div class="header-body text-center mb-5">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-2">
                        <img src="{{ asset(config('adminlte.logo_img')) }}" height="60" class="header-logo" alt="Logo">                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Login Container -->
    <div class="login-container">
        @if (config('services.oauth_server.sso_enable'))
            <!-- SSO Login -->
            <img src="{{ asset(config('adminlte.logo_img')) }}" alt="Logo Politeknik Negeri Banyuwangi" class="logo">
            
            <a href="{{ url('/oauth/login')}}" class="sso-btn" id="ssoBtn">
                Masuk dengan SSO POLIWANGI
            </a>
            
            <div class="terms-text">
                Dengan masuk, Anda menyetujui 
                <a href="#" class="terms-link">Syarat & Ketentuan</a> dan 
                <a href="#" class="terms-link">Kebijakan Privasi</a>
            </div>
            
            <div class="copyright">© 2024 Politeknik Negeri Banyuwangi</div>
        @else
            <!-- Regular Login Form -->
            <img src="{{ asset(config('adminlte.logo_img')) }}" alt="Logo" class="logo">
            
            <h1 class="welcome-title">Masuk ke Sistem</h1>
            <p class="welcome-subtitle">Silakan masukkan kredensial Anda</p>

            <form action="{{ $login_url }}" method="post" id="loginForm">
                @csrf

                <!-- Username field -->
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input type="text" 
                               name="username" 
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" 
                               placeholder="Username" 
                               autofocus 
                               required>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password field -->
                <div class="form-group">
                    <div class="input-group" style="position: relative;">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Password" 
                               required
                               id="passwordField">
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember me & Forgot password -->
                <div class="form-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                    @if($password_reset_url)
                        <a href="{{ $password_reset_url }}" class="forgot-password">Lupa Password?</a>
                    @endif
                </div>

                <!-- Login button -->
                <button type="submit" class="btn-primary" id="loginBtn">
                    Masuk
                </button>
            </form>

            @if($register_url)
                <div style="margin-top: 20px; font-size: 14px; color: var(--text-secondary);">
                    Belum punya akun? 
                    <a href="{{ $register_url }}" class="terms-link">Daftar sekarang</a>
                </div>
            @endif

            <div class="copyright">© 2024 Politeknik Negeri Banyuwangi</div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="{{ url('argon') }}/assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('argon') }}/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('argon') }}/assets/js/argon.js?v=1.2.0"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submission loading state
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const ssoBtn = document.getElementById('ssoBtn');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    const submitBtn = document.getElementById('loginBtn');
                    submitBtn.innerHTML = '<span class="spinner"></span>Masuk...';
                    submitBtn.disabled = true;
                });
            }
            
            if (ssoBtn) {
                ssoBtn.addEventListener('click', function() {
                    this.innerHTML = '<span class="spinner"></span>Menghubungkan...';
                    this.classList.add('loading');
                });
            }
        });
    </script>
</body>

</html>