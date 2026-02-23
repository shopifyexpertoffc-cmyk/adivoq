<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CreatorPay Admin</title>
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
        .form-group {
            margin-bottom: 20px;
        }
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
        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--primary);
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-gray);
            margin-top: 24px;
            font-size: 14px;
        }
        .back-link:hover {
            color: var(--primary);
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
                <div class="logo-icon">
                    <i data-lucide="zap"></i>
                </div>
                <span class="text-2xl font-bold">Creator<span class="text-green">Pay</span></span>
            </div>
            
            <h1 class="login-title">Forgot Password?</h1>
            <p class="login-subtitle">Enter your email to receive a reset link</p>
            
            @if(session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.password.email') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-input" 
                        value="{{ old('email') }}"
                        placeholder="admin@creatorpay.in"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i data-lucide="mail"></i>
                    <span>Send Reset Link</span>
                </button>
                
                <a href="{{ route('admin.login') }}" class="back-link">
                    <i data-lucide="arrow-left"></i>
                    Back to Login
                </a>
            </form>
        </div>
    </div>
    
    <script>lucide.createIcons();</script>
</body>
</html>