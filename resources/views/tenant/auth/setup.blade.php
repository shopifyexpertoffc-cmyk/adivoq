<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Your Account - CreatorPay</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .setup-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            max-width: 500px;
        }
        .setup-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #22c55e, #10b981);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .setup-title {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }
        .setup-subtitle {
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
        .welcome-box {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    
    <div class="setup-container">
        <div class="setup-card">
            <div class="setup-icon">
                <i data-lucide="rocket" style="width: 40px; height: 40px; color: white;"></i>
            </div>
            
            <h1 class="setup-title">Welcome to CreatorPay! 🎉</h1>
            <p class="setup-subtitle">Let's set up your account</p>
            
            <div class="welcome-box">
                <p class="text-sm">
                    <strong>{{ $tenant->name }}</strong>, your workspace is ready! 
                    Create your admin account to get started.
                </p>
            </div>
            
            <form method="POST" action="{{ route('setup.complete') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $tenant->name) }}" required autofocus>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $tenant->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Create Password</label>
                    <input type="password" name="password" class="form-input" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i data-lucide="check-circle"></i>
                    <span>Complete Setup</span>
                </button>
            </form>
        </div>
    </div>
    
    <script>lucide.createIcons();</script>
</body>
</html>