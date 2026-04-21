<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Gunawan's Laundry Management System">
    <title>Login - Gunawan's Laundry</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111827;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px;
            width: 420px;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 25px rgba(0,0,0,.05);
        }
        .login-logo {
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px;
        }
        .login-logo .icon {
            width: 48px; height: 48px;
            background: #4f46e5;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 24px;
        }
        .login-logo h1 { font-size: 20px; font-weight: 800; color: #111827; }
        .login-logo p  { font-size: 12px; color: #64748b; margin-top: 2px; }
        .separator {
            height: 1px; background: #e2e8f0; margin-bottom: 28px;
        }
        h2 { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .subtitle { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #94a3b8; font-size: 16px;
        }
        .form-control {
            width: 100%; padding: 12px 14px 12px 42px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 10px; color: #111827;
            font-size: 14px; font-family: inherit;
            transition: all .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
            background: #fff;
        }
        .form-control::placeholder { color: #9ca3af; }
        .show-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; cursor: pointer; border: none;
            background: none; font-size: 16px;
        }
        .show-pass:hover { color: #64748b; }
        .error-msg { color: #dc2626; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
        .remember-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 24px;
        }
        .remember-row input[type="checkbox"] { accent-color: #4f46e5; width: 16px; height: 16px; cursor: pointer; }
        .remember-row label { font-size: 14px; color: #4b5563; cursor: pointer; }
        .btn-login {
            width: 100%; padding: 13px;
            background: #4f46e5;
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(79,70,229,.2);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,.3); }
        .btn-login:active { transform: translateY(0); }
        .hint {
            margin-top: 24px; text-align: center;
            font-size: 12px; color: #9ca3af;
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
            border: 1px dashed #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div>
                <h1>Gunawan's Laundry</h1>
                <p>Sistem Manajemen Laundry</p>
            </div>
        </div>
        <div class="separator"></div>

        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan login untuk mengakses sistem</p>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope-at-fill"></i>
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
                    <i class="bi bi-lock-fill"></i>
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
                <label for="remember">Ingat Saya</label>
            </div>

            <button type="submit" class="btn-login">
                Masuk Ke Sistem
            </button>
        </form>

        <div class="hint">
            Default: admin@laundry.com / admin123, operator@laundry.com / operator123, pimpinan@laundry.com / pimpinan123
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeBtn');
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
