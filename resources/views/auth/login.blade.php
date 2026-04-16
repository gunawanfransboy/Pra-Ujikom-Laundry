<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Laundry-Wit Management System">
    <title>Login - Laundry-Wit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .bg-orbs {
            position: fixed; inset: 0; pointer-events: none; overflow: hidden;
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: .25;
            animation: float 8s ease-in-out infinite;
        }
        .orb1 { width: 400px; height: 400px; background: #4f46e5; top: -100px; left: -100px; animation-delay: 0s; }
        .orb2 { width: 300px; height: 300px; background: #06b6d4; bottom: -80px; right: -80px; animation-delay: 3s; }
        .orb3 { width: 200px; height: 200px; background: #8b5cf6; top: 50%; right: 20%; animation-delay: 6s; }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(20px, -30px) scale(1.05); }
        }
        .login-card {
            background: rgba(30,41,59,.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 24px;
            padding: 48px 40px;
            width: 420px;
            position: relative;
            z-index: 10;
            box-shadow: 0 24px 48px rgba(0,0,0,.4);
        }
        .login-logo {
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 54px; height: 54px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px;
            box-shadow: 0 8px 24px rgba(79,70,229,.4);
        }
        .login-logo h1 { font-size: 22px; font-weight: 800; color: #e2e8f0; }
        .login-logo p  { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        .separator {
            height: 1px; background: rgba(255,255,255,.08); margin-bottom: 28px;
        }
        h2 { font-size: 20px; font-weight: 700; color: #e2e8f0; margin-bottom: 6px; }
        .subtitle { font-size: 13px; color: #94a3b8; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #64748b; font-size: 15px;
        }
        .form-control {
            width: 100%; padding: 12px 14px 12px 42px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px; color: #e2e8f0;
            font-size: 14px; font-family: inherit;
            transition: all .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.25);
            background: rgba(255,255,255,.09);
        }
        .form-control::placeholder { color: #475569; }
        .show-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #64748b; cursor: pointer; border: none;
            background: none; font-size: 14px;
            transition: color .2s;
        }
        .show-pass:hover { color: #94a3b8; }
        .error-msg { color: #f87171; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
        .remember-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
        }
        .remember-row input[type="checkbox"] { accent-color: #4f46e5; width: 15px; height: 15px; cursor: pointer; }
        .remember-row label { font-size: 13px; color: #94a3b8; cursor: pointer; }
        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; border: none; border-radius: 12px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s;
            box-shadow: 0 6px 20px rgba(79,70,229,.35);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(79,70,229,.45); }
        .btn-login:active { transform: translateY(0); }
        .hint {
            margin-top: 20px; text-align: center;
            font-size: 12px; color: #475569;
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
    </div>

    <div class="login-card">
        <div class="login-logo">
            <div>
                <h1>Laundry-Wit</h1>
                <p>Sistem Manajemen Laundry</p>
            </div>
        </div>
        <div class="separator"></div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan masuk untuk mengakses sistem</p>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <div class="input-wrap">
                    <input id="email" type="email" name="email" class="form-control"
                        placeholder="admin@laundry.com"
                        value="{{ old('email') }}" autocomplete="email" autofocus>
                </div>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <input id="password" type="password" name="password" class="form-control"
                        placeholder="Masukkan password" autocomplete="current-password">
                    <button type="button" class="show-pass" onclick="togglePassword()" id="eyeBtn">
                        </button>
                </div>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                Masuk ke Sistem
            </button>
        </form>

        <!-- <div class="hint">
            Default: admin@laundry.com / admin123
        </div> -->
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
