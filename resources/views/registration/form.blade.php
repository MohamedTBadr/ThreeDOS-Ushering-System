<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <title>ThreeDOS - Academic Councils Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">

    <style>
        :root {
            /* THREEDOS Brand Colors */
            --threedos-purple: #7F4797;
            --threedos-purple-dark: #6A3A82;
            --threedos-purple-light: #9A6BB2;
            --threedos-purple-glow: rgba(127, 71, 151, 0.2);

            --dark: #19191C;
            --gray: #252429;
            --light-gray: #9F9F9F;
            --lighter-gray: #565657;
            --white: #F4F4F4;

            /* Gradients */
            --bg-gradient: linear-gradient(135deg, #19191C, #252429);
            --button-gradient: linear-gradient(135deg, var(--threedos-purple), var(--threedos-purple-dark));
            --header-gradient: linear-gradient(135deg, var(--threedos-purple-dark), var(--threedos-purple));
            --logo-gradient: linear-gradient(135deg, var(--threedos-purple), var(--threedos-purple-light));

            /* Transitions */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            /* Shadows */
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.65);
            --shadow-md: 0 10px 20px rgba(0, 0, 0, 0.4);
            --shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.3);
            --glow: 0 0 20px var(--threedos-purple-glow);
            --logo-glow: 0 0 30px rgba(127, 71, 151, 0.5);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: var(--bg-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            padding: 20px;

        }

        .container {
            width: 100%;
            max-width: 500px;
            background: rgba(25, 25, 28, 0.95);
            padding: 35px;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.6s ease-out;
            border: 1px solid var(--lighter-gray);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--header-gradient);
            z-index: 1;
        }

        .container::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(127, 71, 151, 0.05) 0%, transparent 70%);
            z-index: -1;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
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

            0%,
            100% {
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
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
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
            -webkit-text-fill-color: transparent;
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

        /* ==================== MULTISTEP ==================== */
        .steps-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 2rem;
        }
        .step-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            border: 2px solid var(--lighter-gray);
            color: var(--light-gray);
            background: transparent;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .step-dot.active {
            border-color: var(--threedos-purple);
            background: var(--threedos-purple);
            color: var(--white);
            box-shadow: 0 0 12px var(--threedos-purple-glow);
        }
        .step-dot.completed {
            border-color: var(--threedos-purple);
            background: var(--threedos-purple-dark);
            color: var(--white);
        }
        .step-line {
            flex: 1;
            max-width: 60px;
            height: 2px;
            background: var(--lighter-gray);
            transition: var(--transition);
        }
        .step-line.filled { background: var(--threedos-purple); }
        .step-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            margin-top: -1rem;
        }
        .step-label {
            font-size: 11px;
            color: var(--light-gray);
            text-transform: uppercase;
            letter-spacing: 1px;
            flex: 1;
            text-align: center;
            transition: var(--transition);
        }
        .step-label.active { color: var(--threedos-purple-light); font-weight: 600; }

        .form-step { display: none; animation: stepFade 0.4s ease; }
        .form-step.active { display: block; }
        @keyframes stepFade {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .step-actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }
        .step-actions button { flex: 1; margin-top: 0; }
        .btn-secondary {
            background: transparent !important;
            border: 2px solid var(--lighter-gray) !important;
            color: var(--light-gray) !important;
            box-shadow: none !important;
        }
        .btn-secondary:hover {
            border-color: var(--threedos-purple) !important;
            color: var(--white) !important;
            background: rgba(127,71,151,0.1) !important;
            transform: translateY(-2px);
        }
        .btn-secondary::before { display: none; }

        /* ==================== FORM STYLES ==================== */
        .form-group {
            margin-bottom: 25px;
            position: relative;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.5s ease-out forwards;
        }

        @keyframes formSlideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            display: block;
            font-size: 14px;
            color: var(--light-gray);
            margin-bottom: 8px;
            font-weight: 500;
            padding-left: 8px;
        }

        .input-container {
            position: relative;
        }

        .input-container i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light-gray);
            font-size: 18px;
            transition: var(--transition);
            z-index: 2;
        }

        input,
        select {
            width: 100%;
            padding: 16px 16px 16px 55px;
            border-radius: 14px;
            border: 2px solid var(--lighter-gray);
            background: rgba(25, 25, 28, 0.8);
            color: var(--white);
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: var(--transition);
            appearance: none;
        }

        input:focus,
        select:focus {
            border-color: var(--threedos-purple);
            box-shadow: 0 0 0 4px var(--threedos-purple-glow);
            background: rgba(25, 25, 28, 1);
            transform: translateY(-2px);
        }

        input:focus+i,
        select:focus+i {
            color: var(--threedos-purple);
            transform: translateY(-50%) scale(1.2);
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%239F9F9F' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            padding-right: 45px;
            cursor: pointer;
        }

        /* ==================== BUTTON STYLES ==================== */
        button {
            width: 100%;
            padding: 18px;
            margin-top: 15px;
            border-radius: 14px;
            border: none;
            background: var(--button-gradient);
            color: var(--white);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.7s;
        }

        button:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md), var(--glow);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        button:disabled::before {
            display: none;
        }

        /* ==================== THANK YOU MESSAGE ==================== */
        .thank-you {
            display: none;
            text-align: center;
            padding: 40px 0;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .thank-you .success-icon {
            width: 100px;
            height: 100px;
            background: var(--header-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 42px;
            animation: pulse 2s infinite;
            box-shadow: 0 10px 30px rgba(127, 71, 151, 0.4);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 var(--threedos-purple-glow);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(127, 71, 151, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(127, 71, 151, 0);
            }
        }

        .thank-you h3 {
            color: var(--threedos-purple);
            margin-bottom: 15px;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .thank-you p {
            color: var(--light-gray);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* ==================== FOOTER ==================== */
        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: var(--light-gray);
            padding-top: 20px;
            border-top: 1px solid var(--lighter-gray);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: var(--threedos-purple);
        }

        /* ==================== STATUS CONTAINER ==================== */
        #statusContainer {
            margin-bottom: 20px;
        }

        .status-message {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
            border-left: 4px solid;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .status-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f44336;
            border-left-color: #f44336;
        }

        .status-success {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #4CAF50;
            border-left-color: #4CAF50;
        }

        /* ==================== FORM VALIDATION STYLES ==================== */
        .input-container.valid i {
            color: #4CAF50;
        }

        .input-container.invalid i {
            color: #f44336;
        }

        .validation-message {
            font-size: 12px;
            margin-top: 6px;
            padding-left: 8px;
            display: none;
        }

        .input-container.invalid .validation-message {
            display: block;
            color: #f44336;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* ==================== RESPONSIVE DESIGN ==================== */
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 30px;
            }

            .logo-container {
                width: 100px;
                height: 100px;
            }

            .org-name {
                font-size: 2.4rem;
            }

            h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .container {
                max-width: 100%;
                padding: 25px 20px;
                border-radius: 20px;
            }

            .header-logo {
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .logo-container {
                width: 90px;
                height: 90px;
                border-radius: 16px;
            }

            .logo-img {
                width: 80%;
                height: 80%;
            }

            .org-name {
                font-size: 2rem;
            }

            .org-subtitle {
                font-size: 0.8rem;
                letter-spacing: 1px;
            }

            h2 {
                font-size: 1.4rem;
                margin-bottom: 1.5rem;
            }

            .form-group {
                margin-bottom: 20px;
            }

            label {
                font-size: 13px;
            }

            input,
            select {
                padding: 14px 14px 14px 50px;
                font-size: 14px;
            }

            .input-container i {
                font-size: 16px;
                left: 15px;
            }

            button {
                padding: 16px;
                font-size: 15px;
            }

            .thank-you h3 {
                font-size: 1.8rem;
            }

            .thank-you p {
                font-size: 15px;
            }

            footer {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container" id="formContainer">
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

        <h2>Student Council Registration</h2>

        <p class="form-intro">
            Join the ThreeDOS Student Council and be part of shaping campus life.
            Fill out this form to apply for available leadership positions.
        </p>

        <!-- Status Messages Container -->
        <div id="statusContainer"></div>

        <!-- Steps Indicator -->
        <div class="steps-indicator" id="stepsIndicator">
            <div class="step-dot active" data-step="1">1</div>
            <div class="step-line" id="line1"></div>
            <div class="step-dot" data-step="2">2</div>
            <div class="step-line" id="line2"></div>
            <div class="step-dot" data-step="3">3</div>
        </div>
        <div class="step-labels">
            <span class="step-label active" data-label="1">Personal</span>
            <span class="step-label" data-label="2">Academic</span>
            <span class="step-label" data-label="3">Preference</span>
        </div>

        <form id="registrationForm" novalidate>
            <!-- STEP 1: Personal Info -->
            <div class="form-step active" data-step="1">
                <div class="form-group" style="animation-delay: 0.1s">
                    <label for="name">Full Name</label>
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" id="name" required placeholder="Enter your full name">
                        <div class="validation-message">Please enter your full name</div>
                    </div>
                </div>
                <div class="form-group" style="animation-delay: 0.2s">
                    <label for="email">Email Address</label>
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" required placeholder="your.email@example.com">
                        <div class="validation-message">Please enter a valid email address</div>
                    </div>
                </div>
                <div class="form-group" style="animation-delay: 0.3s">
                    <label for="phone">Phone Number</label>
                    <div class="input-container">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" id="phone" required placeholder="01123986721">
                        <div class="validation-message">Please enter a valid phone number</div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-next" onclick="nextStep()"><i class="fas fa-arrow-right"></i> Next</button>
                </div>
            </div>

            <!-- STEP 2: Academic Info -->
            <div class="form-step" data-step="2">
                <div class="form-group" style="animation-delay: 0.1s">
                    <label for="college">College/University</label>
                    <div class="input-container">
                        <i class="fas fa-university"></i>
                        <input type="text" name="college" id="college" required placeholder="Enter your college name">
                        <div class="validation-message">Please enter your college name</div>
                    </div>
                </div>
                <div class="form-group" style="animation-delay: 0.2s">
                    <label for="level">Academic Level</label>
                    <div class="input-container">
                        <i class="fas fa-graduation-cap"></i>
                        <select name="level" id="level" required>
                            <option value="" selected disabled>Select your academic level</option>
                            <option value="Level 1">Level 1 - Freshman</option>
                            <option value="Level 2">Level 2 - Sophomore</option>
                            <option value="Level 3">Level 3 - Junior</option>
                            <option value="Level 4">Level 4 - Senior</option>
                        </select>
                        <div class="validation-message">Please select your academic level</div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn-next" onclick="nextStep()">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3: Preference -->
            <div class="form-step" data-step="3">
                <div class="form-group" style="animation-delay: 0.1s">
                    <label for="preferences">Position Preference</label>
                    <div class="input-container">
                        <i class="fas fa-star"></i>
                        <select name="preferences" id="preferences" required>
                            <option value="" selected disabled>Select your preferred position</option>
                            <option value="Backend Development">Backend Development</option>
                            <option value="Frontend Development">Frontend Development</option>
                            <option value="CEO">CEO</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Stock Market">Stock Market</option>
                        </select>
                        <div class="validation-message">Please select your preferred position</div>
                    </div>
                </div>
                <div class="form-group" style="animation-delay: 0.2s">
                    <label for="event_type">Event Type</label>
                    <div class="input-container">
                        <i class="fas fa-calendar-alt"></i>
                        <select name="event_type" id="event_type" required>
                            <option value="Offline" selected>Offline</option>
                            <option value="Online">Online</option>
                        </select>
                        <div class="validation-message">Please select event type</div>
                    </div>
                </div>
                <div class="form-group" style="animation-delay: 0.3s">
                    <label for="ushered">Ushered By</label>
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" name="usher" id="ushered" required placeholder="Enter who Made Usher">
                        <div class="validation-message">Please enter Person Name</div>
                    </div>
                </div>
                <div class="step-actions">
                    <button type="button" class="btn-secondary" onclick="prevStep()"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="submit" id="submitBtn"><i class="fas fa-paper-plane"></i> Submit Registration</button>
                </div>
            </div>
        </form>

        <div class="thank-you" id="thankYou">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3>Registration Successful! ðŸŽ‰</h3>
            <p>Your application to join ThreeDOS Student Council has been received successfully.<br>
                Our team will review your submission and contact you soon.</p>
            <button onclick="window.location.reload();" style="max-width: 220px; margin: 0 auto;">
                <i class="fas fa-redo"></i> Register Another Student
            </button>
        </div>

        <footer>
            Â© 2026 ThreeDOS Academic Councils | Empowering Student Leadership
        </footer>
    </div>

    <script>
        const form = document.getElementById('registrationForm');
        const thankYou = document.getElementById('thankYou');
        const submitBtn = document.getElementById('submitBtn');
        const statusContainer = document.getElementById('statusContainer');
        const logoImage = document.getElementById('logoImage');
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
            document.querySelectorAll('.step-dot').forEach(dot => {
                const n = parseInt(dot.dataset.step);
                dot.classList.remove('active','completed');
                if (n === step) dot.classList.add('active');
                else if (n < step) dot.classList.add('completed');
            });
            document.querySelectorAll('.step-label').forEach(lbl => {
                lbl.classList.toggle('active', parseInt(lbl.dataset.label) === step);
            });
            document.getElementById('line1').classList.toggle('filled', step > 1);
            document.getElementById('line2').classList.toggle('filled', step > 2);
            currentStep = step;
            clearStatus();
            // re-animate groups in new step
            document.querySelectorAll(`.form-step[data-step="${step}"] .form-group`).forEach((g,i)=>{
                g.style.animation='none'; void g.offsetWidth;
                g.style.animation='formSlideUp 0.5s ease-out forwards';
                g.style.animationDelay=`${i*0.1}s`;
            });
            window.scrollTo({top:0, behavior:'smooth'});
        }
        function validateStep(step) {
            const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);
            const fields = stepEl.querySelectorAll('input, select');
            let valid = true;
            fields.forEach(field => {
                const container = field.closest('.input-container');
                if (!validateField(field, container)) {
                    valid = false;
                }
            });
            if (!valid) showStatus('Please fill in all required fields correctly.', 'error');
            return valid;
        }
        function nextStep() {
            if (!validateStep(currentStep)) return;
            if (currentStep < totalSteps) showStep(currentStep + 1);
        }
        function prevStep() {
            if (currentStep > 1) showStep(currentStep - 1);
        }

        // Preload logo image to handle errors properly
        window.addEventListener('load', function () {
            const img = new Image();
            img.src = '{{ asset('img/ThreeDOS.jpg') }}';
            img.onerror = function () {
                document.getElementById('logoFallback').style.display = 'block';
            };
            img.onload = function () {
                // Image loaded successfully, ensure it's visible
                logoImage.style.display = 'block';
            };
        });

        // Show status message
        function showStatus(message, type = 'error') {
            statusContainer.innerHTML = `
            <div class="status-message status-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        }

        // Clear status message
        function clearStatus() {
            statusContainer.innerHTML = '';
        }

        // Add validation to inputs
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            const container = input.closest('.input-container');

            input.addEventListener('focus', function () {
                container.classList.remove('invalid');
                clearStatus();
            });

            input.addEventListener('blur', function () {
                validateField(this, container);
            });

            // Real-time validation for email
            if (input.type === 'email') {
                input.addEventListener('input', function () {
                    validateEmail(this, container);
                });
            }
        });


        function validateField(field, container) {
            if (field.hasAttribute('required') && field.value.trim() === '') {
                container.classList.add('invalid');
                return false;
            }

            if (!field.hasAttribute('required') && field.value.trim() === '') {
                container.classList.remove('invalid');
                return true;
            }

            if (field.type === 'email') {
                return validateEmail(field, container);
            }

            if (field.id === 'phone' && field.value.trim() !== '') {
                const phoneDigits = field.value.replace(/\D/g, '');

                if (phoneDigits.length !== 11 || !phoneDigits.startsWith('01')) {
                    container.classList.add('invalid');
                    container.querySelector('.validation-message').textContent =
                        'Please enter a valid 11-digit mobile number (e.g. 01122968127)';
                    return false;
                }
            }


            container.classList.remove('invalid');
            container.classList.add('valid');
            return true;
        }

        function validateEmail(field, container) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                container.classList.add('invalid');
                return false;
            }
            container.classList.remove('invalid');
            container.classList.add('valid');
            return true;
        }

        function validateForm() {
            let isValid = true;
            inputs.forEach(input => {
                const container = input.closest('.input-container');
                if (!validateField(input, container)) {
                    isValid = false;
                    // Scroll to first invalid field
                    if (isValid === false && !document.querySelector('.scrolled-to-invalid')) {
                        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        container.classList.add('scrolled-to-invalid');
                        setTimeout(() => container.classList.remove('scrolled-to-invalid'), 1000);
                    }
                }
            });
            return isValid;
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            if (!validateForm()) {
                showStatus('Please fill in all required fields correctly.', 'error');
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value.replace(/\D/g, ''),
                college: document.getElementById('college').value,
                level: document.getElementById('level').value,
                council: document.getElementById('preferences').value,
                event_type: document.getElementById('event_type').value,
                ushered_by: document.getElementById('ushered').value
            };

            // REAL API CALL - No simulation
            try {
                const response = await fetch('{{ url('api/registrations') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                // Check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.status === 'success') {
                    form.style.display = 'none';
                    document.getElementById('stepsIndicator').style.display = 'none';
                    document.querySelector('.step-labels').style.display = 'none';
                    thankYou.style.display = 'block';
                    thankYou.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    showStatus('Error: ' + result.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration';
                }
            } catch (error) {
                console.error('Error:', error);
                showStatus('Failed to submit registration. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration';
            }
        });

        function resetForm() {
            form.style.display = 'block';
            document.getElementById('stepsIndicator').style.display = 'flex';
            document.querySelector('.step-labels').style.display = 'flex';
            thankYou.style.display = 'none';
            form.reset();
            clearStatus();
            document.querySelectorAll('.input-container').forEach(container => {
                container.classList.remove('valid', 'invalid', 'scrolled-to-invalid');
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Registration';
            showStep(1);
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }



        // Init first step animation
        document.addEventListener('DOMContentLoaded', function () {
            showStep(1);
        });

        // Handle logo image load error
        logoImage.addEventListener('error', function () {
            this.style.display = 'none';
            document.getElementById('logoFallback').style.display = 'block';
        });

        // Handle Event Type change
        // Handle Event Type change
        function toggleUsherRequirement() {
            const eventType = document.getElementById('event_type');
            const usherInput = document.getElementById('ushered');
            const container = usherInput.closest('.input-container');

            if (eventType.value === 'Online') {
                usherInput.removeAttribute('required');
                container.classList.remove('invalid');
                container.classList.add('valid'); // Mark as valid so it doesn't block submission
                usherInput.placeholder = "Ushered By (Optional)";
            } else {
                usherInput.setAttribute('required', 'required');
                usherInput.placeholder = "Enter who Made Usher";

                // If value is empty, remove valid class
                if (usherInput.value.trim() === '') {
                    container.classList.remove('valid');
                }
            }
        }

        const eventTypeSelect = document.getElementById('event_type');
        eventTypeSelect.addEventListener('change', toggleUsherRequirement);

        // Run on load
        toggleUsherRequirement();
    </script>

</body>

</html>
