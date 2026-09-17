<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS — Student Activity | Skill Development</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{
            --purple:#7F4797; --purple2:#9A6BB2; --purple-dark:#6A3A82;
            --dark:#0f0f12; --card:#1c1b1e; --card2:#252429; --border:#2b2a2e;
            --text:#F4F4F4; --muted:#A0A0A8; --dim:#6B6B73;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--dark);color:var(--text);line-height:1.7;overflow-x:hidden}
        a{color:var(--purple2);text-decoration:none}
        /* Nav */
        nav{position:sticky;top:0;z-index:50;background:rgba(15,15,18,.8);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:14px 20px;display:flex;justify-content:space-between;align-items:center}
        nav .brand{display:flex;align-items:center;gap:10px;font-weight:900;letter-spacing:-.5px;color:var(--text);text-decoration:none}
        nav .brand img{width:32px;height:32px;border-radius:8px;object-fit:cover}
        .btn-primary{background:linear-gradient(135deg,var(--purple),var(--purple-dark));color:#fff;padding:11px 24px;border-radius:999px;font-weight:700;display:inline-flex;align-items:center;gap:8px;border:none;cursor:pointer;transition:.2s}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(127,71,151,.45);color:#fff;text-decoration:none}
        .btn-ghost{border:1px solid var(--border);color:var(--text);padding:11px 24px;border-radius:999px;font-weight:700;background:transparent}
        .btn-ghost:hover{border-color:var(--purple);color:#fff;text-decoration:none}
        /* Hero */
        .hero-wrap{position:relative;overflow:hidden}
        .hero-bg{position:absolute;inset:0;background:
            radial-gradient(600px 400px at 20% 10%, rgba(127,71,151,.25), transparent 60%),
            radial-gradient(700px 500px at 90% 30%, rgba(106,58,130,.18), transparent 60%),
            linear-gradient(180deg, #1a1620 0%, var(--dark) 60%);
            z-index:-1}
        .hero{max-width:1100px;margin:0 auto;padding:28px 20px 10px;text-align:center}
        .banner{width:100%;max-width:820px;margin:18px auto 0;display:block;border-radius:20px;overflow:hidden;border:1px solid var(--border);box-shadow:0 24px 70px rgba(0,0,0,.6)}
        .banner img{width:100%;display:block}
        .hero h1{font-size:3.4rem;font-weight:900;letter-spacing:-1.5px;margin-top:26px}
        .hero .sub{color:var(--purple2);font-weight:800;letter-spacing:1.2px;text-transform:uppercase;font-size:.92rem;margin-top:6px}
        .hero .tagline{color:var(--muted);max-width:720px;margin:16px auto 0;font-size:1.05rem}
        .hero .tagline strong{color:#fff}
        .hero .cta{margin:26px 0 8px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
        .trust{margin-top:14px;color:var(--dim);font-size:.82rem;display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
        .trust i{color:var(--purple2)}
        /* Sections */
        .container{max-width:1100px;margin:0 auto;padding:0 20px 60px}
        .section{margin-top:56px}
        .eyebrow{font-size:.75rem;letter-spacing:1.6px;text-transform:uppercase;color:var(--purple2);font-weight:800;margin-bottom:8px}
        .section h2{font-size:1.9rem;font-weight:900;letter-spacing:-.5px;margin-bottom:10px}
        .section p.lead{color:var(--muted);max-width:800px}
        .pill{ display:inline-flex; align-items:center; gap:6px; font-size:.7rem; font-weight:800; letter-spacing:.8px; text-transform:uppercase; padding:6px 10px; border-radius:999px; border:1px solid var(--border); color:var(--muted); background:rgba(255,255,255,.03)}
        /* Councils */
        .council-grid{ display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:14px; margin-top:18px}
        .council-card{ background:var(--card); border:1px solid var(--border); border-radius:18px; padding:18px; position:relative; overflow:hidden; transition:.2s}
        .council-card:hover{ transform:translateY(-4px); border-color:rgba(127,71,151,.5); box-shadow:0 12px 30px rgba(0,0,0,.4)}
        .council-card.academic{ background: linear-gradient(135deg, rgba(127,71,151,.18), rgba(37,36,41,.9)); border-color: rgba(127,71,151,.35)}
        .council-card .icon{ width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; margin-bottom:12px}
        .council-card.academic .icon{ background: linear-gradient(135deg,var(--purple),var(--purple-dark)); color:#fff}
        .council-card.oc .icon{ background: #2a2a2e; color: var(--muted); border:1px solid var(--border)}
        .council-card h3{ font-size:1rem; font-weight:800; margin-bottom:6px}
        .council-card p{ font-size:.88rem; color:var(--muted); margin:0}
        .council-card .tag{ position:absolute; top:12px; right:12px; font-size:.6rem; font-weight:800; letter-spacing:.6px; text-transform:uppercase; padding:4px 8px; border-radius:999px}
        .tag-academic{ background: var(--purple); color:#fff}
        .tag-oc{ background:#2a2a2e; color:var(--muted); border:1px solid var(--border)}
        .divider{ height:1px; background: var(--border); margin:18px 0}
        /* Two-col layout for OC grouping */
        .oc-groups{ display:grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap:16px; margin-top:16px}
        .group{ background:var(--card); border:1px solid var(--border); border-radius:18px; padding:18px}
        .group h4{ font-size:.85rem; letter-spacing:.8px; text-transform:uppercase; color:var(--muted); margin-bottom:10px; display:flex; align-items:center; gap:8px}
        .group h4 i{ color:var(--purple2)}
        .group ul{ list-style:none; margin:0; padding:0}
        .group ul li{ display:flex; align-items:center; gap:10px; padding:10px 10px; border-radius:10px; transition:.15s}
        .group ul li:hover{ background: rgba(127,71,151,.08)}
        .group ul li i{ width:28px; height:28px; border-radius:8px; background:#232225; border:1px solid var(--border); display:flex; align-items:center; justify-content:center; font-size:12px; color:var(--muted); flex-shrink:0}
        /* Benefits */
        .benefits{ display:grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap:14px; margin-top:16px}
        .benefit{ background:var(--card); border:1px solid var(--border); border-radius:16px; padding:18px; display:flex; gap:12px}
        .benefit i{ width:36px; height:36px; border-radius:10px; background: rgba(127,71,151,.15); color:var(--purple2); display:flex; align-items:center; justify-content:center; flex-shrink:0}
        .benefit h4{ font-size:.95rem; font-weight:800}
        .benefit p{ font-size:.85rem; color:var(--muted); margin-top:4px}
        /* CTA band */
        .cta-band{ margin-top:40px; background: linear-gradient(135deg, var(--purple) 0%, var(--purple-dark) 60%, #3a1f4a 100%); border-radius:20px; padding:28px; display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap}
        .cta-band h3{ font-size:1.3rem; font-weight:900}
        .cta-band p{ color: rgba(255,255,255,.85); font-size:.9rem; margin-top:4px}
        .cta-band .btn-light{ background:#fff; color:var(--purple-dark); padding:12px 22px; border-radius:999px; font-weight:800; display:inline-flex; align-items:center; gap:8px}
        .cta-band .btn-light:hover{ text-decoration:none; transform:translateY(-1px)}
        .footer{ text-align:center; padding:28px 20px; border-top:1px solid var(--border); color:var(--dim); font-size:.82rem}
        @media(max-width:640px){ .hero h1{font-size:2.3rem} }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('landing') }}" class="brand">
        <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS" onerror="this.style.display='none'"> ThreeDOS
    </a>
    <div style="display:flex;gap:10px;align-items:center">
        <a href="{{ route('registration.form') }}" class="btn-ghost">Register</a>
        <a href="{{ route('login') }}" class="btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
</nav>

<div class="hero-wrap">
    <div class="hero-bg"></div>
    <div class="hero">
        <div class="banner">
            @php $banner = public_path('img/FB_IMG_1765489279685.jpg'); @endphp
            @if(file_exists($banner))
                <img src="{{ asset('img/FB_IMG_1765489279685.jpg') }}" alt="ThreeDOS Banner">
            @else
                <img src="{{ asset('img/ThreeDOS.jpg') }}" alt="ThreeDOS Banner" style="max-height:420px;object-fit:cover">
            @endif
        </div>
        <h1>ThreeDOS</h1>
        <div class="sub">Student Activity • Skill Development • Market-Ready Training</div>
        <p class="tagline"><strong>Empowering the next generation</strong> of tech, business, and creative leaders — bridging the gap between academic knowledge and real market requirements.</p>
        <div class="cta">
            <a href="{{ route('registration.form') }}" class="btn-primary"><i class="fas fa-rocket"></i> Join ThreeDOS</a>
            <a href="https://www.facebook.com/share/17QPZqK2Qi/" target="_blank" class="btn-ghost"><i class="fab fa-facebook"></i> Facebook</a>
        </div>
        <div class="trust">
            <span><i class="fas fa-check-circle"></i> Real company structure</span>
            <span><i class="fas fa-check-circle"></i> 11 councils</span>
            <span><i class="fas fa-check-circle"></i> Portfolio-based growth</span>
        </div>
    </div>
</div>

<div class="container">

    <section class="section">
        <div class="eyebrow">Who we are</div>
        <h2>We operate like a real company — inside campus.</h2>
        <p class="lead">Multi-council collaboration, real projects &amp; deadlines, internal systems, and continuous skill development. Not just an activity — a professional environment.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px">
            <span class="pill"><i class="fas fa-bullseye" style="color:var(--purple2)"></i> Mission: real experience, real work, real opportunities</span>
            <span class="pill"><i class="fas fa-users"></i> For beginners → advanced</span>
        </div>
    </section>

    <!-- ACADEMIC COUNCILS -->
    <section class="section">
        <div style="display:flex;align-items:end;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div class="eyebrow">Academic Councils</div>
                <h2>Choose your track</h2>
                <p class="lead">Core learning councils where you get trained &amp; evaluated. Apply to one via registration.</p>
            </div>
            <span class="pill" style="background:rgba(127,71,151,.12);border-color:rgba(127,71,151,.3);color:#d8b8e8"><i class="fas fa-graduation-cap"></i> Apply in 2 minutes</span>
        </div>
        <div class="council-grid">
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-code"></i></div>
                <h3>Backend Development</h3>
                <p>PHP • Laravel • MySQL • APIs • Auth • System Architecture</p>
            </div>
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-paint-brush"></i></div>
                <h3>Frontend Development</h3>
                <p>HTML • CSS • JS • Responsive • UI/UX Principles</p>
            </div>
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-bullhorn"></i></div>
                <h3>Marketing</h3>
                <p>Campaigns • Research • Branding • Outreach • Strategy</p>
            </div>
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-crown"></i></div>
                <h3>CEO</h3>
                <p>Leadership • Strategy • Decision making • Org management</p>
            </div>
            <div class="council-card academic">
                <span class="tag tag-academic">Academic</span>
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h3>Stock Market</h3>
                <p>Trading • Analysis • Financial literacy • Market simulation</p>
            </div>
        </div>
        <p style="color:var(--dim);font-size:.8rem;margin-top:10px"><i class="fas fa-info-circle"></i> Tip: Your registration “Position Preference” = one of the 5 Academic Councils above.</p>
    </section>

    <!-- OC -->
    <section class="section">
        <div class="eyebrow">Organizing Committee — OC</div>
        <h2>The engine behind every event &amp; operation</h2>
        <p class="lead">OC powers logistics, partnerships, media, and people. Members cross-collaborate with Academic Councils on real deliverables.</p>

        <div class="oc-groups">
            <div class="group">
                <h4><i class="fas fa-handshake"></i> Business &amp; Operations</h4>
                <ul>
                    <li><i class="fas fa-bullhorn"></i><span><strong>Public Relations</strong><br><small style="color:var(--muted)">Partnerships • External comms • Pitches</small></span></li>
                    <li><i class="fas fa-hand-holding-usd"></i><span><strong>Fundraising</strong><br><small style="color:var(--muted)">Sponsors • Negotiation • Budgeting</small></span></li>
                    <li><i class="fas fa-users"></i><span><strong>Human Resources</strong><br><small style="color:var(--muted)">Recruitment • Evaluation • Culture</small></span></li>
                    <li><i class="fas fa-chalkboard-teacher"></i><span><strong>Training &amp; Development</strong><br><small style="color:var(--muted)">Soft skills • Coaching • Leadership</small></span></li>
                    <li><i class="fas fa-boxes"></i><span><strong>Logistics (Stock)</strong><br><small style="color:var(--muted)">Events • Resources • Operations</small></span></li>
                </ul>
            </div>
            <div class="group">
                <h4><i class="fas fa-photo-video"></i> Creative &amp; Media</h4>
                <ul>
                    <li><i class="fas fa-palette"></i><span><strong>Graphic Design</strong><br><small style="color:var(--muted)">Branding • Visuals • Social assets</small></span></li>
                    <li><i class="fas fa-video"></i><span><strong>Media Production</strong><br><small style="color:var(--muted)">Photo • Video • Editing • Coverage</small></span></li>
                    <li><i class="fas fa-share-alt"></i><span><strong>Social Media</strong><br><small style="color:var(--muted)">Content • Analytics • Growth</small></span></li>
                    <li><i class="fas fa-ad"></i><span><strong>Content &amp; Copy</strong><br><small style="color:var(--muted)">Storytelling • Campaigns • Voice</small></span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="eyebrow">Why ThreeDOS</div>
        <h2>What you will gain</h2>
        <div class="benefits">
            <div class="benefit"><i class="fas fa-briefcase"></i><div><h4>Real Experience</h4><p>Internal products, campaigns, events with deadlines.</p></div></div>
            <div class="benefit"><i class="fas fa-tools"></i><div><h4>Market-Aligned Skills</h4><p>Tools &amp; workflows used in real companies.</p></div></div>
            <div class="benefit"><i class="fas fa-folder-open"></i><div><h4>Strong Portfolio</h4><p>Tasks &amp; achievements you can showcase.</p></div></div>
            <div class="benefit"><i class="fas fa-network-wired"></i><div><h4>Professional Network</h4><p>Connect across tech, business, creative.</p></div></div>
            <div class="benefit"><i class="fas fa-comments"></i><div><h4>Soft Skills</h4><p>Communication, teamwork, leadership, time mgmt.</p></div></div>
            <div class="benefit"><i class="fas fa-trophy"></i><div><h4>Leadership Path</h4><p>Head / Vice / Lead based on performance.</p></div></div>
        </div>
        <div class="divider"></div>
        <h3 style="font-size:1rem;color:var(--muted)"><i class="fas fa-building" style="color:var(--purple2)"></i> Why companies love ThreeDOS candidates</h3>
        <p style="color:var(--muted);font-size:.9rem">They understand workflows, work cross-functionally, handle deadlines, communicate professionally, and have org-structure experience — instantly more employable.</p>
    </section>

    <div class="cta-band">
        <div>
            <h3>Ready to build, lead &amp; grow?</h3>
            <p>Beginners to advanced — if you want growth, not just a title, this is your place.</p>
        </div>
        <a href="{{ route('registration.form') }}" class="btn-light"><i class="fas fa-rocket"></i> Register Now</a>
    </div>

    <section class="section" style="text-align:center">
        <p style="color:var(--muted)"><i class="fab fa-facebook" style="color:#1877F2"></i> Facebook: <a href="https://www.facebook.com/share/17QPZqK2Qi/" target="_blank">ThreeDOS on Facebook</a> &nbsp;•&nbsp; <a href="{{ route('registration.form') }}">Register</a> &nbsp;•&nbsp; <a href="{{ route('login') }}">Login</a></p>
    </section>
</div>

<div class="footer">© 2026 ThreeDOS — Empowering Student Leadership</div>
</body>
</html>
