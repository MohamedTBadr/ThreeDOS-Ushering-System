<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | ThreeDOS Ushering System</title>
            <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">

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
        <form id="signupForm">
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="username" required placeholder="johndoe">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" required placeholder="john@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="password" required placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select id="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="VP">VP (Vice President)</option>
                    <option value="Head">Head</option>
                    <option value="Instructor">Instructor / Interviewer</option>
                    <option value="President">President</option>
                    <option value="OR">OR</option>

                </select>
            </div>
            <div class="form-group">
                <label>Council</label>
                <select id="council" >
                    <option value="" disabled selected>Select your council</option>
                    <option value="Backend Development">Backend Development</option>
                    <option value="Frontend Development">Frontend Development</option>
                    <option value="Marketing">Marketing</option>
                    <option value="CEO">CEO</option>
                    <option value="Stock Market">Stock Market</option>
                </select>
            </div>
            <button type="submit" id="submitBtn">Sign Up</button>
            <div id="statusMsg" class="status-msg"></div>
        </form>
        <div class="links">
            Already have an account? <a href="register.html">Sign In</a>
        </div>
    </div>

    <script>
        // Fetch Councils on Load
        // async function fetchCouncils() {
        //     try {
        //         const res = await fetch('../{{ url('api/councils') }}');
        //         const result = await res.json();
        //         const select = document.getElementById('council');
                
        //         if (result.status === 'success') {
        //             select.innerHTML = '<option value="" disabled selected>Select your council</option>';
        //             result.data.forEach(c => {
        //                 const opt = document.createElement('option');
        //                 opt.value = c.id;
        //                 opt.textContent = c.name;
        //                 select.appendChild(opt);
        //             });
        //         } else {
        //             select.innerHTML = '<option value="" disabled>Error loading councils</option>';
        //         }
        //     } catch (err) {
        //         document.getElementById('council').innerHTML = '<option value="" disabled>Connection error</option>';
        //     }
        // }

        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const msg = document.getElementById('statusMsg');
            
            btn.disabled = true;
            btn.innerText = 'Creating account...';
            msg.style.display = 'none';

            const payload = {
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                role: document.getElementById('role').value,
                council: document.getElementById('council').value
            };

            try {
                const res = await fetch('../{{ url('api/signup') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'success') {
                    msg.innerText = 'Account created! Redirecting to login...';
                    msg.className = 'status-msg success';
                    msg.style.display = 'block';
                    setTimeout(() => window.location.href = 'register.html', 2000);
                } else {
                    msg.innerText = data.message;
                    msg.className = 'status-msg error';
                    msg.style.display = 'block';
                    btn.disabled = false;
                    btn.innerText = 'Sign Up';
                }
            } catch (err) {
                msg.innerText = 'Connection error. Try again.';
                msg.className = 'status-msg error';
                msg.style.display = 'block';
                btn.disabled = false;
                btn.innerText = 'Sign Up';
            }
        });

        // // Initialize
        // fetchCouncils();
    </script>
</body>
</html>

