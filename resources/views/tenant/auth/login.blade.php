<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ tenant('company_name') ?? tenant('name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }
        .login-title {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }
        .login-subtitle {
            color: var(--text-gray);
            text-align: center;
            margin-bottom: 32px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-gray);
        }
        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 16px;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 4px;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .forgot-link {
            color: var(--primary);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                @if(tenant('logo'))
                    <img src="{{ asset('storage/' . tenant('logo')) }}" alt="Logo" class="w-12 h-12 rounded-xl">
                @else
                    <div class="logo-icon">
                        <i data-lucide="zap"></i>
                    </div>
                @endif
                <span class="text-2xl font-bold">{{ tenant('company_name') ?? tenant('name') }}</span>
            </div>
            
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Login to your dashboard</p>
            
            <form method="POST" action="{{ route('tenant.login.submit', ['tenant' => tenant('id')]) }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="remember-forgot">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember" class="text-sm text-gray-400">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i data-lucide="log-in"></i>
                    <span>Login</span>
                </button>
            </form>
            
            <div class="text-center mt-6">
                <p class="text-sm text-gray-400">
                    Powered by <a href="{{ config('app.url') }}" class="text-primary">CreatorPay</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>lucide.createIcons();</script>
</body>
</html>