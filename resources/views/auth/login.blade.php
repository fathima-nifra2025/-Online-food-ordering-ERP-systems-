<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login – FoodERP System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary:   #ff5e3a;
            --primary-h: #ff471a;
            --dark:      #0f172a;
            --accent:    #1e293b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--dark);
            overflow-x: hidden;
        }

        h1,h2,h3,h4,h5,h6 { font-family: 'Outfit', sans-serif; }

        /* ── LEFT HERO PANEL ─────────────────────────────────── */
        .hero-panel {
            flex: 1;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            top: -120px; left: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,94,58,.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            bottom: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,94,58,.10) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .brand-logo .icon-wrap {
            width: 54px; height: 54px;
            background: var(--primary);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff;
            box-shadow: 0 8px 25px rgba(255,94,58,.45);
        }

        .brand-logo h2 {
            font-size: 1.8rem; font-weight: 800;
            color: #fff; letter-spacing: -0.5px;
        }

        .hero-headline {
            font-size: 3rem; font-weight: 800;
            color: #fff; line-height: 1.15;
            margin-bottom: 20px;
            position: relative; z-index: 1;
        }

        .hero-headline span { color: var(--primary); }

        .hero-sub {
            font-size: 1.05rem; color: #94a3b8;
            line-height: 1.7; max-width: 420px;
            position: relative; z-index: 1;
            margin-bottom: 40px;
        }

        .hero-features {
            display: flex; flex-direction: column; gap: 14px;
            position: relative; z-index: 1;
        }

        .feature-pill {
            display: flex; align-items: center; gap: 12px;
            color: #cbd5e1; font-size: .9rem; font-weight: 500;
        }

        .feature-pill .dot {
            width: 8px; height: 8px;
            background: var(--primary);
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 0 8px rgba(255,94,58,.7);
        }

        /* ── RIGHT LOGIN PANEL ───────────────────────────────── */
        .login-panel {
            width: 480px;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 48px;
            overflow-y: auto;
        }

        .login-panel h3 {
            font-size: 1.9rem; font-weight: 800;
            color: var(--dark); margin-bottom: 6px;
        }

        .login-panel p.sub {
            color: #64748b; font-size: .93rem;
            margin-bottom: 32px;
        }

        /* ── Form Fields ─── */
        .field-group { margin-bottom: 20px; }

        .field-group label {
            font-size: .8rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .6px;
            color: #475569; margin-bottom: 8px; display: block;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute; left: 16px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 1rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: .95rem;
            color: var(--dark);
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255,94,58,.12);
            background: #fff;
        }

        .input-wrap input.is-invalid {
            border-color: #ef4444;
        }

        .invalid-msg {
            color: #ef4444; font-size: .8rem;
            margin-top: 6px; display: flex;
            align-items: center; gap: 6px;
        }

        /* ── Remember + Forgot ─── */
        .remember-row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-row label {
            font-size: .87rem; color: #475569;
            display: flex; align-items: center; gap: 8px;
            cursor: pointer;
        }

        .remember-row input[type=checkbox] {
            accent-color: var(--primary);
            width: 16px; height: 16px;
        }

        .forgot-link {
            font-size: .85rem; color: var(--primary);
            text-decoration: none; font-weight: 600;
        }

        .forgot-link:hover { text-decoration: underline; }

        /* ── Submit Button ─── */
        .btn-login {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem; font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        .btn-login:hover {
            background: var(--primary-h);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255,94,58,.35);
        }

        /* ── Divider ─── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 28px 0 20px;
        }

        .divider hr { flex: 1; border-color: #e2e8f0; }

        .divider span {
            font-size: .8rem; color: #94a3b8;
            font-weight: 600; white-space: nowrap;
        }

        /* ── Demo Credential Cards ─── */
        .demo-cards { display: flex; flex-direction: column; gap: 10px; }

        .demo-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 16px;
            cursor: pointer;
            transition: all .2s;
            background: #f8fafc;
            position: relative;
            overflow: hidden;
        }

        .demo-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 4px;
        }

        .demo-card.admin::before  { background: #ff5e3a; }
        .demo-card.customer::before { background: #3b82f6; }

        .demo-card:hover {
            border-color: var(--primary);
            background: #fff;
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
        }

        .demo-card .dc-header {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .demo-card .dc-role {
            font-size: .72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1px;
            padding: 3px 10px; border-radius: 20px;
        }

        .demo-card.admin  .dc-role { background: #fff3f0; color: var(--primary); }
        .demo-card.customer .dc-role { background: #eff6ff; color: #2563eb; }

        .demo-card .dc-click {
            font-size: .72rem; color: #94a3b8;
            font-weight: 600; display: flex; align-items: center; gap: 4px;
        }

        .demo-card .dc-cred {
            display: flex; flex-direction: column; gap: 3px;
        }

        .demo-card .dc-row {
            display: flex; align-items: center; gap: 8px;
            font-size: .82rem; color: #475569;
        }

        .demo-card .dc-row i { color: #94a3b8; width: 14px; }
        .demo-card .dc-row code {
            background: #e2e8f0; border-radius: 4px;
            padding: 1px 6px; font-size: .8rem; color: var(--dark);
        }

        /* Register link */
        .register-link {
            text-align: center; margin-top: 24px;
            font-size: .87rem; color: #64748b;
        }

        .register-link a {
            color: var(--primary); font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover { text-decoration: underline; }

        /* Error alert */
        .error-alert {
            background: #fef2f2; border: 1.5px solid #fecaca;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; color: #dc2626;
            font-size: .88rem; display: flex; gap: 10px; align-items: flex-start;
        }

        .error-alert i { flex-shrink: 0; font-size: 1rem; margin-top: 1px; }

        /* ── Responsive ─── */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .hero-panel { padding: 36px 28px 28px; }
            .hero-headline { font-size: 2rem; }
            .login-panel { width: 100%; padding: 36px 28px; }
        }

        @media (max-width: 480px) {
            .hero-panel { display: none; }
            .login-panel { padding: 32px 20px; }
        }
    </style>
</head>
<body>

    <!-- ═══ LEFT HERO ═════════════════════════════════════════════ -->
    <div class="hero-panel d-none d-md-flex">
        <div class="brand-logo">
            <div class="icon-wrap"><i class="bi bi-egg-fried"></i></div>
            <h2>FoodERP</h2>
        </div>

        <h1 class="hero-headline">
            The Smart Way to<br>Run Your <span>Restaurant</span>
        </h1>
        <p class="hero-sub">
            A full-stack ERP solution for food ordering, kitchen management,
            delivery tracking, and real-time business analytics.
        </p>

        <div class="hero-features">
            <div class="feature-pill"><span class="dot"></span> Real-time Order Pipeline Dashboard</div>
            <div class="feature-pill"><span class="dot"></span> Kitchen & Delivery Staff Management</div>
            <div class="feature-pill"><span class="dot"></span> Sales History & PDF Invoice Billing</div>
            <div class="feature-pill"><span class="dot"></span> Menu, Stock & Category Management</div>
            <div class="feature-pill"><span class="dot"></span> Customer Order Tracking Portal</div>
        </div>
    </div>

    <!-- ═══ RIGHT LOGIN PANEL ══════════════════════════════════════ -->
    <div class="login-panel">

        <!-- Mobile Brand -->
        <div class="brand-logo d-flex d-md-none mb-4">
            <div class="icon-wrap" style="width:42px;height:42px;font-size:1.3rem;border-radius:12px;">
                <i class="bi bi-egg-fried"></i>
            </div>
            <h2 style="font-size:1.4rem;">FoodERP</h2>
        </div>

        <h3>Welcome back!</h3>
        <p class="sub">Sign in to your account to continue</p>

        {{-- Global error --}}
        @if($errors->has('email'))
            <div class="error-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>{{ $errors->first('email') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:20px;color:#166534;font-size:.88rem;display:flex;gap:10px;align-items:center;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- LOGIN FORM --}}
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="field-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="email"
                        autofocus
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        required>
                </div>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required>
                </div>
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In to FoodERP
            </button>
        </form>

        {{-- DEMO CREDENTIAL CARDS --}}
        <div class="divider">
            <hr><span>Quick Demo Login</span><hr>
        </div>

        <div class="demo-cards">
            {{-- Admin card --}}
            <div class="demo-card admin" onclick="fillCredentials('admin@gmail.com','123')" title="Click to auto-fill">
                <div class="dc-header">
                    <span class="dc-role"><i class="bi bi-shield-fill-check me-1"></i>Admin</span>
                    <span class="dc-click"><i class="bi bi-cursor-fill"></i> Click to fill</span>
                </div>
                <div class="dc-cred">
                    <div class="dc-row">
                        <i class="bi bi-envelope"></i>
                        <span>Email:</span>
                        <code>admin@gmail.com</code>
                    </div>
                    <div class="dc-row">
                        <i class="bi bi-key"></i>
                        <span>Password:</span>
                        <code>123</code>
                    </div>
                </div>
            </div>

            {{-- Customer card --}}
            <div class="demo-card customer" onclick="fillCredentials('customer@gmail.com','1234')" title="Click to auto-fill">
                <div class="dc-header">
                    <span class="dc-role" style="background:#eff6ff;color:#2563eb;">
                        <i class="bi bi-person-fill me-1"></i>Customer
                    </span>
                    <span class="dc-click"><i class="bi bi-cursor-fill"></i> Click to fill</span>
                </div>
                <div class="dc-cred">
                    <div class="dc-row">
                        <i class="bi bi-envelope"></i>
                        <span>Email:</span>
                        <code>customer@gmail.com</code>
                    </div>
                    <div class="dc-row">
                        <i class="bi bi-key"></i>
                        <span>Password:</span>
                        <code>1234</code>
                    </div>
                </div>
            </div>
        </div>

        {{-- Register link --}}
        <div class="register-link">
            Don't have an account?
            <a href="{{ route('register') }}">Create one free</a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /**
         * Auto-fills the email + password fields and submits the form.
         * Called when user clicks a demo credential card.
         */
        function fillCredentials(email, password) {
            document.getElementById('email').value    = email;
            document.getElementById('password').value = password;

            // Brief visual flash on the card
            event.currentTarget.style.background = '#fff3f0';
            event.currentTarget.style.borderColor = '#ff5e3a';
            setTimeout(function() {
                document.getElementById('loginForm').submit();
            }, 300);
        }
    </script>
</body>
</html>
