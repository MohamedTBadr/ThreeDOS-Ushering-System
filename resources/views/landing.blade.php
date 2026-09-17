<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS · Student Activity</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{
            --purple:#7F4797; --purple2:#9A6BB2; --purple-dark:#6A3A82;
            --purple-glow: rgba(127,71,151,0.35);
            --dark:#0b0b0e; --card:#161519; --card2:#1f1e24; --border:#2a292f;
            --text:#F4F4F4; --muted:#A0A0A8; --dim:#6B6B73;
            --radius-card: 24px;
            --radius-btn: 50px;
            --shadow-sm: 0 8px 20px rgba(0,0,0,0.6);
            --shadow-glow: 0 0 30px rgba(127,71,151,0.25);
            --transition-smooth: all 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        a{text-decoration: none; color: inherit;}
        img{max-width:100%; display:block;}
        .container{
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ---------- NAV ---------- */
        nav{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            background: rgba(11,11,14,0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .brand{
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            color: var(--text);
        }
        .brand img{
            height: 38px;
            width: auto;
            border-radius: 10px;
            object-fit: cover;
            background: var(--card2);
            border: 1px solid rgba(255,255,255,0.06);
        }
        .nav-actions{
            display: flex;
            gap: 12px;
            align-items: center;
        }
        .btn-ghost, .btn-primary, .btn-light{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: var(--radius-btn);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .btn-ghost{
            background: transparent;
            border-color: rgba(255,255,255,0.1);
            color: var(--muted);
        }
        .btn-ghost i{font-size: 0.9rem;}
        .btn-ghost:hover{
            border-color: var(--purple2);
            color: var(--text);
            background: rgba(127,71,151,0.1);
            transform: translateY(-1px);
        }
        .btn-primary{
            background: var(--purple);
            color: white;
            box-shadow: 0 6px 20px rgba(127,71,151,0.3);
        }
        .btn-primary i{font-size: 0.9rem;}
        .btn-primary:hover{
            background: var(--purple-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(127,71,151,0.5);
        }
        .btn-light{
            background: white;
            color: var(--dark);
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(0,0,0,0.4);
        }
        .btn-light:hover{
            background: #f0f0f8;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.6);
        }

        /* ---------- HERO ---------- */
        .hero-wrap{
            position: relative;
            padding: 64px 24px 48px;
            text-align: center;
            overflow: hidden;
        }
        .hero-bg{
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle, rgba(127,71,151,0.2) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
            animation: pulse-glow 6s ease-in-out infinite alternate;
        }
        @keyframes pulse-glow {
            0% { opacity: 0.6; transform: translateX(-50%) scale(1); }
            100% { opacity: 1; transform: translateX(-50%) scale(1.1); }
        }
        .hero{
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
        }
        .hero h1{
            font-size: clamp(3rem, 10vw, 5.5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #ffffff 0%, #d8b8e8 70%, #9A6BB2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
            margin-bottom: 8px;
        }
        .hero .sub{
            font-size: clamp(1rem, 3vw, 1.4rem);
            font-weight: 600;
            color: var(--purple2);
            letter-spacing: 0.25em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .tagline{
            font-size: 1.25rem;
            color: var(--muted);
            max-width: 650px;
            margin: 0 auto 32px;
            line-height: 1.6;
        }
        .tagline strong{
            color: #d8b8e8;
            font-weight: 700;
        }
        .hero .cta{
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
            margin-bottom: 44px;
        }
        .trust{
            display: flex;
            flex-wrap: wrap;
            gap: 18px 32px;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--muted);
            background: rgba(255,255,255,0.03);
            padding: 18px 32px;
            border-radius: 60px;
            border: 1px solid rgba(255,255,255,0.06);
            max-width: fit-content;
            margin: 0 auto;
            backdrop-filter: blur(8px);
        }
        .trust span{
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .trust i{
            color: var(--purple2);
            font-size: 1rem;
        }

        /* ---------- SECTIONS ---------- */
        .section{
            padding: 56px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .section:last-of-type{
            border-bottom: none;
        }
        .eyebrow{
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--purple2);
            margin-bottom: 12px;
        }
        h2{
            font-size: clamp(2rem, 5vw, 2.8rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.2;
            margin-bottom: 18px;
        }
        .lead{
            font-size: 1.15rem;
            color: var(--muted);
            max-width: 700px;
            line-height: 1.6;
        }
        .pill{
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 18px;
            border-radius: 40px;
            background: var(--card2);
            border: 1px solid var(--border);
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--muted);
            transition: var(--transition-smooth);
        }
        .pill:hover{
            border-color: rgba(127,71,151,0.5);
            background: rgba(127,71,151,0.1);
        }
        .pill i{
            color: var(--purple2);
        }

        /* ---------- COUNCIL GRID ---------- */
        .council-grid{
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 27px;
            margin-top: 32px;
        }
        .council-card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 26px 18px 22px;
            transition: var(--transition-smooth);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .council-card:hover{
            transform: translateY(-6px);
            border-color: rgba(127,71,151,0.6);
            box-shadow: 0 24px 36px -16px rgba(0,0,0,0.9), var(--shadow-glow);
            background: var(--card2);
        }
        .council-card .icon{
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(127,71,151,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--purple2);
            margin-bottom: 18px;
            transition: var(--transition-smooth);
        }
        .council-card:hover .icon{
            background: rgba(127,71,151,0.3);
            color: #d8b8e8;
            transform: scale(1.05);
        }
        .council-card h3{
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .council-card .tech-tags{
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
            margin-bottom: 6px;
        }
        .council-card .tech-tag{
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 3px 10px;
            border-radius: 30px;
            background: rgba(127,71,151,0.12);
            color: #c8a8d8;
            border: 1px solid rgba(127,71,151,0.25);
            transition: var(--transition-smooth);
        }
        .council-card:hover .tech-tag{
            background: rgba(127,71,151,0.25);
            border-color: rgba(127,71,151,0.5);
            color: #d8b8e8;
        }
        .council-card p{
            font-size: 0.85rem;
            color: var(--dim);
            margin-top: auto;
        }
        .tag{
            position: absolute;
            top: 14px;
            right: 14px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 4px 10px;
            border-radius: 30px;
        }
        .tag-academic{
            background: rgba(127,71,151,0.2);
            color: #d8b8e8;
            border: 1px solid rgba(127,71,151,0.4);
        }

        /* ---------- OC GROUPS ---------- */
        .oc-groups{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }
        .group{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 30px 26px;
            transition: var(--transition-smooth);
        }
        .group:hover{
            border-color: rgba(127,71,151,0.5);
            box-shadow: 0 16px 28px -12px rgba(0,0,0,0.7);
        }
        .group h4{
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            color: var(--purple2);
            letter-spacing: -0.01em;
        }
        .group ul{
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .group li{
            display: flex;
            align-items: flex-start;
            gap: 14px;
            font-size: 0.95rem;
            color: var(--muted);
            transition: var(--transition-smooth);
        }
        .group li:hover{
            color: var(--text);
            transform: translateX(4px);
        }
        .group li i{
            width: 20px;
            color: var(--purple2);
            font-size: 1rem;
            margin-top: 3px;
            flex-shrink: 0;
        }
        .group li strong{
            color: var(--text);
            font-weight: 600;
        }

        /* ---------- BENEFITS ---------- */
        .benefits{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin: 32px 0 8px;
        }
        .benefit{
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 22px;
            background: var(--card);
            border-radius: 20px;
            border: 1px solid var(--border);
            transition: var(--transition-smooth);
        }
        .benefit:hover{
            border-color: rgba(127,71,151,0.5);
            background: var(--card2);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(0,0,0,0.6);
        }
        .benefit i{
            font-size: 1.7rem;
            color: var(--purple2);
            width: 38px;
            flex-shrink: 0;
            text-align: center;
            margin-top: 2px;
            transition: var(--transition-smooth);
        }
        .benefit:hover i{
            color: #d8b8e8;
            transform: scale(1.1);
        }
        .benefit h4{
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }
        .benefit p{
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.5;
        }
        .divider{
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border), transparent);
            margin: 44px 0 32px;
        }

        /* ---------- CTA BAND ---------- */
        .cta-band{
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            background: linear-gradient(145deg, #2a1e30, #1a121f);
            border: 1px solid rgba(127,71,151,0.35);
            border-radius: 32px;
            padding: 44px 52px;
            margin: 48px 0 24px;
            box-shadow: 0 30px 50px -20px rgba(0,0,0,0.9), 0 0 40px rgba(127,71,151,0.15);
            position: relative;
            overflow: hidden;
        }
        .cta-band::before{
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(127,71,151,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .cta-band h3{
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        .cta-band p{
            color: var(--muted);
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }
        .cta-band .btn-light{
            position: relative;
            z-index: 1;
        }

        /* ---------- FOOTER LINKS ---------- */
        .footer-links{
            text-align: center;
            padding: 36px 24px;
            color: var(--muted);
            font-size: 0.95rem;
        }
        .footer-links a{
            color: var(--purple2);
            font-weight: 500;
            transition: var(--transition-smooth);
            margin: 0 6px;
        }
        .footer-links a:hover{
            color: #d8b8e8;
            text-decoration: underline;
        }
        .footer{
            height: 24px;
        }

        /* ---------- SCROLL REVEAL ---------- */
        .reveal{
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible{
            opacity: 1;
            transform: translateY(0);
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 700px){
            nav{
                padding: 14px 20px;
                flex-wrap: wrap;
                gap: 12px;
            }
            .brand span{
                font-size: 1.1rem;
            }
            .nav-actions{
                gap: 8px;
            }
            .btn-ghost, .btn-primary{
                padding: 8px 16px;
                font-size: 0.85rem;
            }
            .hero-wrap{
                padding: 40px 16px 32px;
            }
            .trust{
                gap: 12px 20px;
                font-size: 0.8rem;
                padding: 14px 20px;
                border-radius: 40px;
            }
            .section{
                padding: 36px 0;
            }
            .cta-band{
                padding: 32px 24px;
                flex-direction: column;
                text-align: center;
            }
            .cta-band h3{
                font-size: 1.6rem;
            }
            .council-grid{
                grid-template-columns: repeat(2, 1fr);
            }
            .benefits{
                grid-template-columns: 1fr;
            }
            .oc-groups{
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 420px){
            .council-grid{
                grid-template-columns: 1fr;
            }
            .hero .cta{
                flex-direction: column;
                align-items: stretch;
            }
            .trust{
                flex-direction: column;
                align-items: center;
                border-radius: 24px;
            }
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('landing') }}" class="brand">
        <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS" onerror="this.style.display='none'">
        <span>ThreeDOS</span>
    </a>
    <div class="nav-actions">
        <a href="{{ route('registration.form') }}" class="btn-ghost">Register</a>
        <a href="{{ route('login') }}" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
</nav>

<div class="hero-wrap">
    <div class="hero-bg"></div>
    <div class="hero">
        <h1>ThreeDOS</h1>
        <div class="sub">Student Activity</div>
        <p class="tagline"><strong>Empowering the next generation</strong> of tech, business, and creative leaders</p>
        <div class="cta">
            <a href="{{ route('registration.form') }}" class="btn-primary"><i class="fas fa-rocket"></i> Join ThreeDOS</a>
            <a href="https://www.facebook.com/share/17QPZqK2Qi/" target="_blank" class="btn-ghost"><i class="fab fa-facebook"></i> Facebook</a>
        </div>
        <div class="trust">
            <span><i class="fas fa-check-circle"></i> Real company structure</span>
            <span><i class="fas fa-check-circle"></i> 5 councils</span>
            <span><i class="fas fa-check-circle"></i> Portfolio-based growth</span>
        </div>
    </div>
</div>

<div class="container">

    <section class="section reveal">
        <div class="eyebrow">Who we are</div>
        <h2>We operate like a real company</h2>
        <p class="lead">Multi-council collaboration, real projects &amp; deadlines, internal systems, and continuous skill development. Not just an activity.</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:24px;">
            <span class="pill"><i class="fas fa-bullseye" style="color:var(--purple2)"></i> Mission: real experience, real work, real opportunities</span>
            <span class="pill"><i class="fas fa-users"></i> For beginners</span>
        </div>
    </section>

    <!-- ACADEMIC COUNCILS -->
    <section class="section reveal">
        <div style="display:flex; align-items:end; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:8px;">
            <div>
                <div class="eyebrow">Academic Councils</div>
                <h2>Choose your track</h2>
                <p class="lead">Core learning councils where you get trained &amp; evaluated. Apply to one via registration.</p>
            </div>
            <span class="pill" style="background:rgba(127,71,151,.12); border-color:rgba(127,71,151,.3); color:#d8b8e8;"><i class="fas fa-graduation-cap"></i> Apply in 2 minutes</span>
        </div>
        <div class="council-grid">
            <!-- Backend Development — enhanced with full tech stack -->
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-code"></i></div>
                <h3>Backend Development</h3>
                <div class="tech-tags">
                    <span class="tech-tag">PHP</span>
                    <span class="tech-tag">MySQL</span>
                    <span class="tech-tag">ERD</span>
                    <span class="tech-tag">Laravel</span>
                    <span class="tech-tag">APIs</span>
                    <span class="tech-tag">Security</span>
                </div>
                <p>Server logic, databases &amp; robust APIs</p>
            </div>
            <!-- Frontend Development -->
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-paint-brush"></i></div>
                <h3>Frontend Development</h3>
                <div class="tech-tags">
                    <span class="tech-tag">HTML</span>
                    <span class="tech-tag">CSS</span>
                    <span class="tech-tag">JS</span>
                    <span class="tech-tag">UI/UX</span>
                </div>
                <p>Interfaces, interactions &amp; experiences</p>
            </div>
            <!-- Marketing -->
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-bullhorn"></i></div>
                <h3>Marketing</h3>
                <div class="tech-tags">
                    <span class="tech-tag">Strategy</span>
                    <span class="tech-tag">Campaigns</span>
                    <span class="tech-tag">Analytics</span>
                </div>
                <p>Campaigns, growth &amp; strategy</p>
            </div>
            <!-- CEO -->
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-crown"></i></div>
                <h3>CEO</h3>
                <div class="tech-tags">
                    <span class="tech-tag">Leadership</span>
                    <span class="tech-tag">Vision</span>
                    <span class="tech-tag">Strategy</span>
                </div>
                <p>Leadership, vision &amp; decision-making</p>
            </div>
            <!-- Stock Market -->
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h3>Stock Market</h3>
                <div class="tech-tags">
                    <span class="tech-tag">Trading</span>
                    <span class="tech-tag">Analysis</span>
                    <span class="tech-tag">Portfolio</span>
                </div>
                <p>Trading, analysis &amp; market insight</p>
            </div>
        </div>
        <p style="color:var(--dim); font-size:.8rem; margin-top:16px;"><i class="fas fa-info-circle"></i> Tip: Your registration helps us place you in the right council.</p>
    </section>

    <!-- OC -->
    <section class="section reveal">
        <div class="eyebrow">Organizing Committee</div>
        <h2>The engine behind every event &amp; operation</h2>
        <p class="lead">OC powers logistics, partnerships, media, and people. Members cross-collaborate with Academic Councils on real deliverables.</p>

        <div class="oc-groups">
            <div class="group">
                <h4><i class="fas fa-handshake"></i> Business &amp; Operations</h4>
                <ul>
                    <li><i class="fas fa-bullhorn"></i><span><strong>Public Relations</strong><br><small style="color:var(--muted)">Partnerships</small></span></li>
                    <li><i class="fas fa-hand-holding-usd"></i><span><strong>Fundraising</strong><br><small style="color:var(--muted)">Sponsors</small></span></li>
                    <li><i class="fas fa-users"></i><span><strong>Human Resources</strong><br><small style="color:var(--muted)">Recruitment</small></span></li>
                    <li><i class="fas fa-chalkboard-teacher"></i><span><strong>Training &amp; Development</strong><br><small style="color:var(--muted)">Soft skills</small></span></li>
                    <li><i class="fas fa-boxes"></i><span><strong>Logistics (Stock)</strong><br><small style="color:var(--muted)">Events</small></span></li>
                </ul>
            </div>
            <div class="group">
                <h4><i class="fas fa-photo-video"></i> Creative &amp; Media</h4>
                <ul>
                    <li><i class="fas fa-palette"></i><span><strong>Graphic Design</strong><br><small style="color:var(--muted)">Branding</small></span></li>
                    <li><i class="fas fa-video"></i><span><strong>Media Production</strong><br><small style="color:var(--muted)">Photo &amp; video</small></span></li>
                    <li><i class="fas fa-share-alt"></i><span><strong>Social Media</strong><br><small style="color:var(--muted)">Content</small></span></li>
                    <li><i class="fas fa-ad"></i><span><strong>Content &amp; Copy</strong><br><small style="color:var(--muted)">Storytelling</small></span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section reveal">
        <div class="eyebrow">Why ThreeDOS</div>
        <h2>What you will gain</h2>
        <div class="benefits">
            <div class="benefit"><i class="fas fa-briefcase"></i><div><h4>Real Experience</h4><p>Internal products, campaigns, events with deadlines.</p></div></div>
            <div class="benefit"><i class="fas fa-tools"></i><div><h4>Market-Aligned Skills</h4><p>Tools &amp; workflows used in real companies.</p></div></div>
            <div class="benefit"><i class="fas fa-folder-open"></i><div><h4>Strong Portfolio</h4><p>Tasks &amp; achievements you can showcase.</p></div></div>
            <div class="benefit"><i class="fas fa-network-wired"></i><div><h4>Professional Network</h4><p>Connect across tech, business, creative.</p></div></div>
            <div class="benefit"><i class="fas fa-comments"></i><div><h4>Soft Skills</h4><p>Communication, teamwork, leadership, time mgmt.</p></div></div>
            <div class="benefit"><i class="fas fa-trophy"></i><div><h4>Leadership Path</h4><p>Leader , CoLeader.</p></div></div>
        </div>
        <div class="divider"></div>
        <h3 style="font-size:1rem; color:var(--muted);"><i class="fas fa-building" style="color:var(--purple2)"></i> Why companies love ThreeDOS candidates</h3>
        <p style="color:var(--muted); font-size:.9rem; max-width:700px;">They understand workflows, work cross-functionally, handle deadlines, communicate professionally, and have org-structure experience.</p>
    </section>

    <div class="cta-band reveal">
        <div>
            <h3>Ready to join?</h3>
            <p>Beginners to advanced — everyone is welcome.</p>
        </div>
        <a href="{{ route('registration.form') }}" class="btn-light"><i class="fas fa-rocket"></i> Register Now</a>
    </div>

    <section class="section" style="text-align:center; padding-bottom:24px;">
        <p style="color:var(--muted);">
            <i class="fab fa-facebook" style="color:#1877F2; margin-right:6px;"></i>
            <a href="https://www.facebook.com/share/17QPZqK2Qi/" target="_blank">ThreeDOS on Facebook</a>
            &nbsp;·&nbsp;
            <a href="{{ route('registration.form') }}">Register</a>
            &nbsp;·&nbsp;
            <a href="{{ route('login') }}">Login</a>
        </p>
    </section>
</div>

<div class="footer"></div>

<script>
    // Scroll reveal animation
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));
</script>

</body>
</html>