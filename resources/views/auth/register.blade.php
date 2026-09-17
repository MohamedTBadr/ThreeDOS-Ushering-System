<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | ThreeDOS Ushering System</title>
            <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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

    --success: #10b981;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0;
    padding: 2rem 0;
    background: radial-gradient(
        circle at top right,
        #252429,
        #19191C
    );
}

.signup-card {
    background: var(--card);
    padding: 2.5rem;
    border-radius: 1.5rem;
    width: 100%;
    max-width: 450px;
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
    margin-bottom: 1.2rem;
}

label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text);
}

input,
select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #19191C;
    border: 1px solid var(--border);
    border-radius: 0.75rem;
    color: var(--text);
    box-sizing: border-box;
    transition: all 0.3s;
    font-family: inherit;
}

input::placeholder {
    color: #6F6F73;
}

input:focus,
select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(127, 71, 151, 0.35);
}

button {
    width: 100%;
    padding: 0.85rem;
    background: linear-gradient(
        135deg,
        var(--primary),
        var(--primary-dark)
    );
    color: white;
    border: none;
    border-radius: 0.75rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-top: 1rem;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(127, 71, 151, 0.45);
}

button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.links {
    margin-top: 1.5rem;
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.links a {
    color: var(--primary-light);
    text-decoration: none;
    font-weight: 600;
}

.links a:hover {
    text-decoration: underline;
}

.status-msg {
    font-size: 0.85rem;
    margin-top: 1rem;
    text-align: center;
    display: none;
    padding: 0.75rem;
    border-radius: 0.5rem;
}

.error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.25);
}

.success {
    background: rgba(16, 185, 129, 0.12);
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.25);
}

/* =========================
   RESPONSIVE DESIGN
========================= */
@media (max-width: 768px) {
    .signup-card {
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

    .signup-card {
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

    input,
    select {
        padding: 0.65rem 0.875rem;
        font-size: 0.9rem;
    }

    button {
        padding: 0.7rem;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .links {
        font-size: 0.8rem;
    }

    .status-msg {
        font-size: 0.8rem;
        padding: 0.65rem;
    }
}
</style>
</head>
<body>
    <div class="signup-card">
        <h1>Create Account</h1>
        <p>Register as a council member or administrator.</p>
        <form id="signupForm" method="POST" action="{{ route('signup.post') }}">
            @csrf

            @if ($errors->any())
                <div class="status-msg error" style="display: block;">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="status-msg success" style="display: block;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required placeholder="johndoe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role" required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role</option>
                    <option value="VP" {{ old('role') == 'VP' ? 'selected' : '' }}>VP (Vice President)</option>
                    <option value="Head" {{ old('role') == 'Head' ? 'selected' : '' }}>Head</option>
                    <option value="Instructor" {{ old('role') == 'Instructor' ? 'selected' : '' }}>Instructor / Interviewer</option>
                    <option value="President" {{ old('role') == 'President' ? 'selected' : '' }}>President</option>
                    <option value="OR" {{ old('role') == 'OR' ? 'selected' : '' }}>OR</option>
                </select>
            </div>
            <div class="form-group">
                <label>Council</label>
                <select name="council" id="council">
                    <option value="" disabled {{ old('council') ? '' : 'selected' }}>Select your council</option>
                    <option value="Backend Development" {{ old('council') == 'Backend Development' ? 'selected' : '' }}>Backend Development</option>
                    <option value="Frontend Development" {{ old('council') == 'Frontend Development' ? 'selected' : '' }}>Frontend Development</option>
                    <option value="Marketing" {{ old('council') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="CEO" {{ old('council') == 'CEO' ? 'selected' : '' }}>CEO</option>
                    <option value="Stock Market" {{ old('council') == 'Stock Market' ? 'selected' : '' }}>Stock Market</option>
                </select>
            </div>
            <button type="submit" id="submitBtn"><i class="fas fa-user-plus"></i> Sign Up</button>
        </form>
        <div class="links">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', () => {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
        });
    </script>
</body>
</html>
