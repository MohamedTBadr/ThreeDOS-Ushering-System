<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS · Ushering Analytics</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7F4797;
            --primary-dark: #6A3A82;
            --primary-light: #9A6BB2;
            --bg-dark: #19191C;
            --bg-card: #252429;
            --dark-lighter: #343338;
            --border: #565657;
            --text-main: #F4F4F4;
            --text-secondary: #B0B0B0;
            --text-muted: #94a3b8;
            --sidebar-width: 280px;
            --font-sans: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        * { scrollbar-width: thin; scrollbar-color: #7F4797 #19191C; }
        *::-webkit-scrollbar { width: 8px; height: 8px; }
        *::-webkit-scrollbar-track { background: #19191C; border-radius: 8px; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #7F4797, #6A3A82); border-radius: 8px; border: 2px solid #19191C; }
        *::-webkit-scrollbar-thumb:hover { background: #9A6BB2; }
        body { font-family: var(--font-sans); background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-card) 100%); color: var(--text-main); min-height: 100vh; overflow-x: hidden; }
        .dashboard-container { display: grid; grid-template-columns: var(--sidebar-width) 1fr; min-height: 100vh; width: 100%; transition: grid-template-columns .3s ease; }
        .dashboard-container.sidebar-closed { grid-template-columns: 0 1fr; }
        .main-content { padding: 2rem; min-width: 0; width: 100%; overflow-x: hidden; }
        /* header */
        .page-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.75rem; }
        .page-header-left { display:flex; align-items:center; gap:.75rem; }
        .menu-toggle { display:inline-flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:12px; background: rgba(127,71,151,.14); border:1px solid rgba(127,71,151,.35); color: var(--text-main); font-size:1.2rem; cursor:pointer; transition: all .2s; flex-shrink:0; }
        .menu-toggle:hover { background: var(--primary); color:#fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(127,71,151,.35); }
        .page-title h1 { font-size:1.85rem; font-weight:700; display:flex; align-items:center; gap:.6rem; letter-spacing:-.02em; }
        .page-title h1 i { color: var(--primary-light); }
        .page-title p { color: var(--text-secondary); font-size:.88rem; margin-top:.2rem; }
        .header-actions { display:flex; align-items:center; gap:.6rem; }
        .btn-refresh { background: rgba(127,71,151,.15); border:1px solid var(--primary-light); color: var(--primary-light); padding:.6rem 1.1rem; border-radius:12px; font-weight:600; font-size:.9rem; display:inline-flex; align-items:center; gap:.5rem; cursor:pointer; transition: all .25s; }
        .btn-refresh:hover { background: var(--primary); color:#fff; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(127,71,151,.35); }
        /* stats cards */
        .stat-grid { display:grid; grid-template-columns: repeat(3,1fr); gap:1.25rem; margin-bottom:1.75rem; }
        .stat-card { background: linear-gradient(135deg, var(--bg-card), var(--bg-dark)); border:1px solid var(--border); border-radius:1rem; padding:1.5rem; display:flex; flex-direction:column; gap:.6rem; transition: all .25s; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; inset:0; background: radial-gradient(circle at 30% 20%, rgba(127,71,151,.08), transparent 60%); pointer-events:none; }
        .stat-card:hover { transform: translateY(-3px); border-color: var(--primary); box-shadow: 0 12px 28px rgba(0,0,0,.35); }
        .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; background: rgba(127,71,151,.15); border:1px solid rgba(127,71,151,.3); color: var(--primary-light); }
        .stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color: var(--text-secondary); }
        .stat-value { font-size:2.2rem; font-weight:800; font-family: var(--font-mono); line-height:1; color:#fff; }
        .stat-note { font-size:.78rem; color: var(--primary-light); background: rgba(127,71,151,.12); border:1px solid rgba(127,71,151,.22); padding:.2rem .65rem; border-radius:40px; display:inline-flex; align-items:center; gap:.3rem; width:fit-content; }
        /* leaderboard card */
        .leaderboard-card { background: linear-gradient(135deg, var(--bg-card), var(--bg-dark)); border:1px solid var(--border); border-radius:1rem; overflow:hidden; box-shadow: 0 12px 28px rgba(0,0,0,.35); }
        .card-header { padding:1.25rem 1.5rem; border-bottom:1px solid rgba(86,86,87,.5); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; background: var(--dark-lighter); }
        .card-header h2 { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
        .card-header p { color: var(--text-secondary); font-size:.82rem; margin-top:.15rem; }
        .chip { background: rgba(127,71,151,.14); border:1px solid rgba(127,71,151,.35); color: var(--primary-light); padding:.3rem .85rem; border-radius:40px; font-size:.75rem; font-weight:600; }
        .table-responsive { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; min-width:520px; }
        th { text-align:left; padding:.95rem 1.5rem; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color: var(--text-secondary); background: #2a292e; border-bottom:1px solid rgba(127,71,151,.18); white-space:nowrap; }
        td { padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,.06); color: var(--text-main); font-size:.92rem; }
        tbody tr:hover { background: rgba(127,71,151,.08); }
        .rank-badge { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:10px; font-weight:800; font-family: var(--font-mono); background: rgba(255,255,255,.06); border:1px solid var(--border); color: var(--text-secondary); font-size:.9rem; }
        .top-rank-1 .rank-badge { background: rgba(251,191,36,.14); border-color: #fbbf24; color:#fcd34d; }
        .top-rank-2 .rank-badge { background: rgba(203,213,225,.12); border-color: #cbd5e1; color:#e2e8f0; }
        .top-rank-3 .rank-badge { background: rgba(180,83,9,.14); border-color: #b45309; color:#f59e0b; }
        .usher-cell { display:flex; align-items:center; gap:.85rem; }
        .avatar { width:40px; height:40px; border-radius:12px; background: linear-gradient(135deg,#3f3651,#2d243a); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.9rem; color:#fff; border:1px solid rgba(255,255,255,.08); text-transform:uppercase; flex-shrink:0; }
        .count-pill { display:inline-flex; align-items:center; justify-content:center; min-width:64px; height:38px; background: rgba(127,71,151,.14); border:1px solid rgba(127,71,151,.35); border-radius:40px; font-family: var(--font-mono); font-size:1rem; font-weight:800; color: var(--primary-light); padding:0 .9rem; }
        .top-performer .count-pill { background: rgba(251,191,36,.14); border-color: rgba(251,191,36,.45); color:#fcd34d; }
        .empty-state { padding:3.5rem 2rem; text-align:center; color: var(--text-secondary); }
        .loading-state { display:flex; flex-direction:column; align-items:center; gap:1rem; padding:2.5rem; }
        .spinner { width:40px; height:40px; border:3px solid rgba(127,71,151,.15); border-top-color: var(--primary); border-radius:50%; animation: spin .8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .fade-in { animation: fadeIn .35s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform: translateY(6px);} to {opacity:1; transform: translateY(0);} }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1500; backdrop-filter: blur(4px); }
        .sidebar-overlay.active { display:block; }
        @media (max-width:1024px){ .dashboard-container{grid-template-columns:1fr} .main-content{padding:1.25rem} .stat-grid{grid-template-columns:1fr} .page-title h1{font-size:1.45rem} th,td{padding:.85rem 1rem} }
        @media (max-width:480px){ .stat-value{font-size:1.8rem} .header-actions{width:100%} .btn-refresh{flex:1; justify-content:center} }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="dashboard-container" id="dashboardContainer">
        @include('layouts.partials.sidebar')
        <main class="main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <button class="menu-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
                    <div class="page-title">
                        <h1><i class="fas fa-medal"></i> Ushering Influence</h1>
                        <p>recruitment analytics · who brought the most applicants</p>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-refresh" onclick="fetchUsheringData()" id="refreshButton"><i class="fas fa-sync"></i> Update data</button>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div class="stat-label">Total applications</div>
                    <div class="stat-value" id="totalApplicants">—</div>
                    <div class="stat-note"><i class="fas fa-arrow-trend-up"></i> lifetime volume</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-label">Active ushers</div>
                    <div class="stat-value" id="activeUshers">—</div>
                    <div class="stat-note">current cycle</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-label">Avg. per usher</div>
                    <div class="stat-value" id="averagePerUsher">—</div>
                    <div class="stat-note">conversion efficiency</div>
                </div>
            </div>

            <div class="leaderboard-card">
                <div class="card-header">
                    <div>
                        <h2><i class="fas fa-trophy" style="color:#fbbf24"></i> Top Ushers · Leaderboard</h2>
                        <p>ranked by applicants brought to ThreeDOS</p>
                    </div>
                    <div class="chip"><i class="fas fa-bolt"></i> recruitment influence</div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:90px;">Rank</th>
                                <th>Usher</th>
                                <th style="text-align:center;">Applicants</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr><td colspan="3"><div class="loading-state"><div class="spinner"></div><p style="color:var(--text-secondary);font-size:.92rem;">Establishing secure link · loading statistics</p></div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const INITIAL_STATS = @json($stats ?? []);
        const INITIAL_TOTAL = {{ $totalApplicants ?? 0 }};
        const API_URL = '/api/stats/ushers';
        const TOKEN = localStorage.getItem('usher_token');
        function getInitials(name){ if(!name) return 'U'; return name.trim().split(' ').map(w=>w.charAt(0).toUpperCase()).slice(0,2).join('')||'U'; }
        function formatRankBadge(rank){ if(rank===1) return '🥇'; if(rank===2) return '🥈'; if(rank===3) return '🥉'; return rank; }
        function createUsherRow(usher,index){
            const rank=index+1;
            let rankClass=''; if(rank===1) rankClass='top-rank-1'; else if(rank===2) rankClass='top-rank-2'; else if(rank===3) rankClass='top-rank-3';
            const rowClass = rank===1 ? 'top-performer' : '';
            const initials=getInitials(usher.name);
            const badge=formatRankBadge(rank);
            return `<tr class="${rowClass} fade-in" style="animation-delay:${index*0.04}s">
                <td><div class="rank-badge ${rankClass}">${badge}</div></td>
                <td><div class="usher-cell"><div class="avatar">${initials}</div><span style="font-weight:600;color:var(--text-main)">${usher.name||'Unnamed'}</span></div></td>
                <td style="text-align:center;"><div class="count-pill">${usher.count}</div></td>
            </tr>`;
        }
        function renderUsherData(statsData,totalAppsCount){
            const tbody=document.getElementById('tableBody');
            if(!statsData||statsData.length===0){
                tbody.innerHTML=`<tr><td colspan="3" style="padding:3.5rem 2rem;text-align:center;color:var(--text-secondary)"><span style="font-size:2.5rem;display:block;margin-bottom:.8rem;">📭</span><h3 style="font-weight:600;margin-bottom:.3rem;">No ushers found</h3><p style="font-size:.9rem;">Start recruiting to populate the leaderboard</p></td></tr>`;
                document.getElementById('totalApplicants').textContent=totalAppsCount||0;
                document.getElementById('activeUshers').textContent='0';
                document.getElementById('averagePerUsher').textContent='0';
                return;
            }
            const active=statsData.length;
            let total=totalAppsCount; if(!total&&total!==0) total=statsData.reduce((a,u)=>a+(Number(u.count)||0),0);
            const avg= active>0 ? (total/active).toFixed(1):'0.0';
            document.getElementById('totalApplicants').textContent=total;
            document.getElementById('activeUshers').textContent=active;
            document.getElementById('averagePerUsher').textContent=avg;
            tbody.innerHTML=statsData.map((u,i)=>createUsherRow(u,i)).join('');
        }
        async function fetchUsheringData(){
            if(INITIAL_STATS && INITIAL_STATS.length>0) renderUsherData(INITIAL_STATS, INITIAL_TOTAL);
            try{
                const headers={'Accept':'application/json'}; if(TOKEN) headers['X-Token']=TOKEN;
                const res=await fetch(API_URL,{headers}); if(!res.ok) return;
                const result=await res.json();
                if(result?.status==='success'&&result.data){
                    const statsData=result.data.data||[];
                    const total=result.data.total_applicants ?? INITIAL_TOTAL;
                    renderUsherData(statsData,total);
                }
            }catch(e){ console.log('[ThreeDOS] fallback:',e); }
        }
        window.addEventListener('DOMContentLoaded',()=>{ fetchUsheringData(); });
        window.fetchUsheringData=fetchUsheringData;
    </script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>
