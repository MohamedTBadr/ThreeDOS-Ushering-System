<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ThreeDOS Ushering System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">

    <style>
        :root {
            /* THREEDOS Brand System */
            --primary: #7F4797;
            --primary-dark: #6A3A82;
            --primary-light: #9A6BB2;

            --bg: #19191C;
            --card: #252429;

            --text: #F4F4F4;
            --text-muted: #9F9F9F;

            --border: #565657;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: radial-gradient(circle at top right,
                    #252429,
                    #19191C);
        }

        /* Global Loader Styles */
        #global-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: linear-gradient(135deg, #19191C 0%, #252429 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .global-loader-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #252429;
            box-shadow: 0 0 0 4px rgba(127, 71, 151, 0.2), 0 0 40px rgba(127, 71, 151, 0.4);
            overflow: hidden;
            animation: logoPulse 2s infinite ease-in-out;
            position: relative;
        }

        .global-loader-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .global-loader-text {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 4px;
            color: #F4F4F4;
            background: linear-gradient(90deg, #9A6BB2, #FF6B8B, #9A6BB2);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textShimmer 3s linear infinite;
        }

        .global-loader-bar {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .global-loader-bar::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 40%;
            background: #7F4797;
            border-radius: 4px;
            box-shadow: 0 0 10px #7F4797;
            animation: barLoading 1.5s ease-in-out infinite;
        }

        @keyframes logoPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 4px rgba(127, 71, 151, 0.2), 0 0 40px rgba(127, 71, 151, 0.4);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 8px rgba(127, 71, 151, 0.3), 0 0 60px rgba(127, 71, 151, 0.6);
            }
        }

        @keyframes textShimmer {
            to {
                background-position: 200% center;
            }
        }

        @keyframes barLoading {
            0% {
                left: -40%;
            }

            50% {
                left: 40%;
                width: 60%;
            }

            100% {
                left: 100%;
                width: 40%;
            }
        }

        .login-card {
            background: var(--card);
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid var(--border);
        }

        h1 {
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #19191C;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            color: var(--text);
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input::placeholder {
            color: #6F6F73;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(127, 71, 151, 0.35);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg,
                    var(--primary),
                    var(--primary-dark));
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(127, 71, 151, 0.45);
        }

        .error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 1rem;
            text-align: center;
            display: none;
        }

            
        /* ==================== HEADER & LOGO ==================== */
        .header-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .logo-container {
            width: 120px;
            height: 120px;
            background: var(--logo-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md), var(--logo-glow);
            position: relative;
            overflow: hidden;
            animation: logoFloat 3s ease-in-out infinite;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }
        
        @keyframes logoFloat {
            0%, 100% { 
                transform: translateY(0) rotate(0deg); 
                box-shadow: var(--shadow-md), var(--logo-glow);
            }
            50% { 
                transform: translateY(-10px) rotate(1deg); 
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5), 0 0 40px rgba(127, 71, 151, 0.6);
            }
        }
        
        .logo-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            animation: shine 3s infinite linear;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .logo-img {
            width: 85%;
            height: 85%;
            object-fit: contain;
            border-radius: 12px;
            position: relative;
            z-index: 2;
            transition: var(--transition);
        }
        
        .logo-container:hover .logo-img {
            transform: scale(1.05);
        }
        
        .logo-fallback {
            font-size: 3rem;
            color: white;
            position: relative;
            z-index: 2;
        }
        
        .org-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .org-name {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--white) 0%, var(--threedos-purple-light) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            letter-spacing: 1px;
            text-shadow: 0 2px 10px rgba(127, 71, 151, 0.3);
            line-height: 1;
        }
        
        .org-subtitle {
            font-size: 0.9rem;
            color: var(--light-gray);
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            padding: 0 10px;
        }
        
        .org-subtitle::before,
        .org-subtitle::after {
            content: 'â€¢';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: var(--threedos-purple);
        }
        
        .org-subtitle::before {
            left: 0;
        }
        
        .org-subtitle::after {
            right: 0;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 1.8rem;
            color: var(--white);
            font-weight: 600;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 1rem;
            line-height: 1.3;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--threedos-purple);
            border-radius: 2px;
            background: linear-gradient(to right, transparent, var(--threedos-purple), transparent);
        }
        
        .form-intro {
            text-align: center;
            color: var(--light-gray);
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.6;
            padding: 0 10px;
        }
    
        /* =========================
   RESPONSIVE DESIGN
========================= */
        @media (max-width: 768px) {
            .login-card {
                padding: 2rem;
                max-width: 90%;
            }

            h1 {
                font-size: 1.5rem;
            }

            p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .login-card {
                padding: 1.5rem;
                max-width: 100%;
                border-radius: 1rem;
            }

            h1 {
                font-size: 1.35rem;
            }

            p {
                font-size: 0.8rem;
                margin-bottom: 1.5rem;
            }

            input {
                padding: 0.65rem 0.875rem;
                font-size: 0.9rem;
            }

            button {
                padding: 0.65rem;
                font-size: 0.9rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <!-- Global Loader -->
    <div id="global-loader">
        <div class="global-loader-logo">
            <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS Logo">
        </div>
        <div class="global-loader-text">ThreeDOS</div>
        <div class="global-loader-bar"></div>
    </div>
    <div class="login-card">
         <!-- ThreeDOS Header with Logo -->
    <div class="header-logo">
        <div class="logo-container">
            <!-- Logo Image with fallback -->
            <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS Logo" class="logo-img" id="logoImage" 
                 onerror="this.style.display='none'; document.getElementById('logoFallback').style.display='block';">
            <div class="logo-fallback" id="logoFallback" style="display: none;">
                <i class="fas fa-users-crown"></i>
            </div>
        </div>
        <div class="org-title">
            <div class="org-name">ThreeDOS</div>
            <div class="org-subtitle">Academic Councils</div>
        </div>
    </div>
        <h1>Welcome Back</h1>
        <p>Enter your credentials to manage applicants.</p>
        <form id="loginForm">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" required placeholder="name@council.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" required placeholder="********">
            </div>
            <button type="submit" id="submitBtn">Sign In</button>
            <div id="errorMsg" class="error"></div>
        </form>
        <div style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #94a3b8;">
            Don't have an account? <a href="signuppp.html"
                style="color: #6366f1; text-decoration: none; font-weight: 600;">Sign Up</a>
        </div>
    </div>

    <script>
        // Global Loader Logic
        window.addEventListener('load', () => {
            // Safety fallback: Hide loader after 5s max (simpler for login)
            const safetyTimer = setTimeout(hideGlobalLoader, 5000);
            setTimeout(hideGlobalLoader, 800);

            function hideGlobalLoader() {
                clearTimeout(safetyTimer);
                const globalLoader = document.getElementById('global-loader');
                if (globalLoader && !globalLoader.classList.contains('hidden')) {
                    globalLoader.classList.add('hidden');
                    setTimeout(() => globalLoader.style.display = 'none', 500);
                }
            }
        });

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const error = document.getElementById('errorMsg');

            btn.disabled = true;
            btn.innerText = 'Authenticating...';
            error.style.display = 'none';

            try {
                const res = await fetch('../{{ url('api/auth/login') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });
                const data = await res.json();

                if (data.status === 'success') {
                    localStorage.setItem('usher_token', data.data.token);
                    localStorage.setItem('user_role', data.data.role);
                    localStorage.setItem('user_council', data.data.council);
                    localStorage.setItem('user_name',data.data.username);
                    window.location.href = 'dashboard.html';
                } else {
                    error.innerText = data.message;
                    error.style.display = 'block';
                }
            } catch (err) {
                error.innerText = 'Connection error. Try again.';
                error.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.innerText = 'Sign In';
            }
        });
    </script>
</body>

</html>
