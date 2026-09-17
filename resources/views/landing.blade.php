<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content=width=device-width, initial-scale=1.0>
    <title>ThreeDOS </title>
    <link rel=icon type=image/png href={{ asset('img/ThreeDOS.jpg') }}>
    <link href=https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap rel=stylesheet>
    <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css>
    <style>
        :root{
            --purple:#7F4797; --purple2:#9A6BB2; --purple-dark:#6A3A82;
            --dark:#0f0f12; --card:#1c1b1e; --card2:#252429; --border:#2b2a2e;
            --text:#F4F4F4; --muted:#A0A0A8; --dim:#6B6B73;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        bo</style>
</head>
<bo<nav>
    <a href={{ route('landing') }} class=brand>
        <img src={{ asset('img/ThreeDOS.jpg') }} alt=ThreeDOS onerror=this.style.display='none'> ThreeDOS
    </a>
    <div style=display:flex;gap:10px;align-items:center>
        <a href={{ route('registration.form') }} class=btn-ghost>Register</a>
        <a href={{ route('login') }} class=btn-primary><i class=fas fa-sign-in-alt></i> Login</a>
    </div>
</nav>

<div class=hero-wrap>
    <div class=hero-bg></div>
    <div class=hero>
        <div class=banner>
            @php $banner = public_path('img/FB_IMG_1765489279685.jpg'); @endphp
            @if(file_exists($banner))
                <img src={{ asset('img/FB_IMG_1765489279685.jpg') }} alt=ThreeDOS Banner>
            @else
                <img src={{ asset('img/ThreeDOS.jpg') }} alt=ThreeDOS Banner style=max-height:420px;object-fit:cover>
            @endif
        </div>
        <h1>ThreeDOS</h1>
        <div class=sub>Student Activity </div>
        <p class=tagline><strong>Empowering the next generation</strong> of tech, business, and creative leaders </p>
        <div class=cta>
            <a href={{ route('registration.form') }} class=btn-primary><i class=fas fa-rocket></i> Join ThreeDOS</a>
            <a href=https://www.facebook.com/share/17QPZqK2Qi/ target=_blank class=btn-ghost><i class=fab fa-facebook></i> Facebook</a>
        </div>
        <div class=trust>
            <span><i class=fas fa-check-circle></i> Real company structure</span>
            <span><i class=fas fa-check-circle></i> 11 councils</span>
            <span><i class=fas fa-check-circle></i> Portfolio-based growth</span>
        </div>
    </div>
</div>

<div class=container>

    <section class=section>
        <div class=eyebrow>Who we are</div>
        <h2>We operate like a real company </h2>
        <p class=lead>Multi-council collaboration, real projects &amp; deadlines, internal systems, and continuous skill development. Not just an activity </p>
        <div style=display:flex;gap:10px;flex-wrap:wrap;margin-top:16px>
            <span class=pill><i class=fas fa-bullseye style=color:var(--purple2)></i> Mission: real experience, real work, real opportunities</span>
            <span class=pill><i class=fas fa-users></i> For beginners </span>
        </div>
    </section>

    <!-- ACADEMIC COUNCILS -->
    <section class=section>
        <div style=display:flex;align-items:end;justify-content:space-between;flex-wrap:wrap;gap:12px>
            <div>
                <div class=eyebrow>Academic Councils</div>
                <h2>Choose your track</h2>
                <p class=lead>Core learning councils where you get trained &amp; evaluated. Apply to one via registration.</p>
            </div>
            <span class=pill style=background:rgba(127,71,151,.12);border-color:rgba(127,71,151,.3);color:#d8b8e8><i class=fas fa-graduation-cap></i> Apply in 2 minutes</span>
        </div>
        <div class=council-grid>
            <div class=council-card academic>
                <span class=tag tag-academic>Academic</span>
                <div class=icon><i class=fas fa-code></i></div>
                <h3>Backend Development</h3>
                <p>PHP </p>
            </div>
            <div class=council-card academic>
                <span class=tag tag-academic>Academic</span>
                <div class=icon><i class=fas fa-paint-brush></i></div>
                <h3>Frontend Development</h3>
                <p>HTML </p>
            </div>
            <div class=council-card academic>
                <span class=tag tag-academic>Academic</span>
                <div class=icon><i class=fas fa-bullhorn></i></div>
                <h3>Marketing</h3>
                <p>Campaigns </p>
            </div>
            <div class=council-card academic>
                <span class=tag tag-academic>Academic</span>
                <div class=icon><i class=fas fa-crown></i></div>
                <h3>CEO</h3>
                <p>Leadership </p>
            </div>
            <div class=council-card academic>
                <span class=tag tag-academic>Academic</span>
                <div class=icon><i class=fas fa-chart-line></i></div>
                <h3>Stock Market</h3>
                <p>Trading </p>
            </div>
        </div>
        <p style=color:var(--dim);font-size:.8rem;margin-top:10px><i class=fas fa-info-circle></i> Tip: Your registration </p>
    </section>

    <!-- OC -->
    <section class=section>
        <div class=eyebrow>Organizing Committee </div>
        <h2>The engine behind every event &amp; operation</h2>
        <p class=lead>OC powers logistics, partnerships, media, and people. Members cross-collaborate with Academic Councils on real deliverables.</p>

        <div class=oc-groups>
            <div class=group>
                <h4><i class=fas fa-handshake></i> Business &amp; Operations</h4>
                <ul>
                    <li><i class=fas fa-bullhorn></i><span><strong>Public Relations</strong><br><small style=color:var(--muted)>Partnerships </small></span></li>
                    <li><i class=fas fa-hand-holding-usd></i><span><strong>Fundraising</strong><br><small style=color:var(--muted)>Sponsors </small></span></li>
                    <li><i class=fas fa-users></i><span><strong>Human Resources</strong><br><small style=color:var(--muted)>Recruitment </small></span></li>
                    <li><i class=fas fa-chalkboard-teacher></i><span><strong>Training &amp; Development</strong><br><small style=color:var(--muted)>Soft skills </small></span></li>
                    <li><i class=fas fa-boxes></i><span><strong>Logistics (Stock)</strong><br><small style=color:var(--muted)>Events </small></span></li>
                </ul>
            </div>
            <div class=group>
                <h4><i class=fas fa-photo-video></i> Creative &amp; Media</h4>
                <ul>
                    <li><i class=fas fa-palette></i><span><strong>Graphic Design</strong><br><small style=color:var(--muted)>Branding </small></span></li>
                    <li><i class=fas fa-video></i><span><strong>Media Production</strong><br><small style=color:var(--muted)>Photo </small></span></li>
                    <li><i class=fas fa-share-alt></i><span><strong>Social Media</strong><br><small style=color:var(--muted)>Content </small></span></li>
                    <li><i class=fas fa-ad></i><span><strong>Content &amp; Copy</strong><br><small style=color:var(--muted)>Storytelling </small></span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class=section>
        <div class=eyebrow>Why ThreeDOS</div>
        <h2>What you will gain</h2>
        <div class=benefits>
            <div class=benefit><i class=fas fa-briefcase></i><div><h4>Real Experience</h4><p>Internal products, campaigns, events with deadlines.</p></div></div>
            <div class=benefit><i class=fas fa-tools></i><div><h4>Market-Aligned Skills</h4><p>Tools &amp; workflows used in real companies.</p></div></div>
            <div class=benefit><i class=fas fa-folder-open></i><div><h4>Strong Portfolio</h4><p>Tasks &amp; achievements you can showcase.</p></div></div>
            <div class=benefit><i class=fas fa-network-wired></i><div><h4>Professional Network</h4><p>Connect across tech, business, creative.</p></div></div>
            <div class=benefit><i class=fas fa-comments></i><div><h4>Soft Skills</h4><p>Communication, teamwork, leadership, time mgmt.</p></div></div>
            <div class=benefit><i class=fas fa-trophy></i><div><h4>Leadership Path</h4><p>Head / Vice / Lead based on performance.</p></div></div>
        </div>
        <div class=divider></div>
        <h3 style=font-size:1rem;color:var(--muted)><i class=fas fa-building style=color:var(--purple2)></i> Why companies love ThreeDOS candidates</h3>
        <p style=color:var(--muted);font-size:.9rem>They understand workflows, work cross-functionally, handle deadlines, communicate professionally, and have org-structure experience </p>
    </section>

    <div class=cta-band>
        <div>
            <h3>Rea</h3>
            <p>Beginners to advanced </p>
        </div>
        <a href={{ route('registration.form') }} class=btn-light><i class=fas fa-rocket></i> Register Now</a>
    </div>

    <section class=section style=text-align:center>
        <p style=color:var(--muted)><i class=fab fa-facebook style=color:#1877F2></i> Facebook: <a href=https://www.facebook.com/share/17QPZqK2Qi/ target=_blank>ThreeDOS on Facebook</a> &nbsp;<a href={{ route('registration.form') }}>Register</a> &nbsp;<a href={{ route('login') }}>Login</a></p>
    </section>
</div>

<div class=footer></div>
</body>
</html>


