<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeDOS · Interviewer Analytics</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ThreeDOS.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary:#7F4797; --primary-dark:#6A3A82; --primary-light:#9A6BB2;
            --bg-dark:#19191C; --bg-card:#252429; --dark-lighter:#343338; --border:#565657;
            --text-main:#F4F4F4; --text-secondary:#B0B0B0; --sidebar-width:280px;
            --font-sans:'Inter',sans-serif; --font-mono:'JetBrains Mono',monospace;
        }
        *{margin:0;padding:0;box-sizing:border-box}
        *{scrollbar-width:thin;scrollbar-color:#7F4797 #19191C}
        *::-webkit-scrollbar{width:8px;height:8px}
        *::-webkit-scrollbar-track{background:#19191C;border-radius:8px}
        *::-webkit-scrollbar-thumb{background:linear-gradient(180deg,#7F4797,#6A3A82);border-radius:8px;border:2px solid #19191C}
        *::-webkit-scrollbar-thumb:hover{background:#9A6BB2}
        body{font-family:var(--font-sans);background:linear-gradient(135deg,var(--bg-dark) 0%,var(--bg-card) 100%);color:var(--text-main);min-height:100vh;overflow-x:hidden}
        .dashboard-container{display:grid;grid-template-columns:var(--sidebar-width) 1fr;min-height:100vh;width:100%;transition:grid-template-columns .3s ease}
        .dashboard-container.sidebar-closed{grid-template-columns:0 1fr}
        .main-content{padding:2rem;min-width:0;width:100%;overflow-x:hidden}
        .page-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem}
        .page-header-left{display:flex;align-items:center;gap:.75rem}
        .menu-toggle{display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:12px;background:rgba(127,71,151,.14);border:1px solid rgba(127,71,151,.35);color:var(--text-main);font-size:1.2rem;cursor:pointer;transition:all .2s}
        .menu-toggle:hover{background:var(--primary);color:#fff;transform:translateY(-1px);box-shadow:0 6px 18px rgba(127,71,151,.35)}
        .page-title h1{font-size:1.85rem;font-weight:700;display:flex;align-items:center;gap:.6rem;letter-spacing:-.02em}
        .page-title h1 i{color:var(--primary-light)}
        .page-title p{color:var(--text-secondary);font-size:.88rem;margin-top:.2rem}
        .header-actions{display:flex;align-items:center;gap:.6rem;flex-wrap:wrap}
        .btn-refresh{background:rgba(127,71,151,.15);border:1px solid var(--primary-light);color:var(--primary-light);padding:.6rem 1.1rem;border-radius:12px;font-weight:600;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;cursor:pointer;transition:all .25s}
        .btn-refresh:hover{background:var(--primary);color:#fff;transform:translateY(-2px);box-shadow:0 6px 18px rgba(127,71,151,.35)}
        .stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:1.5rem}
        .stat-card{background:linear-gradient(135deg,var(--bg-card),var(--bg-dark));border:1px solid var(--border);border-radius:1rem;padding:1.5rem;display:flex;flex-direction:column;gap:.6rem;position:relative;overflow:hidden;transition:all .25s}
        .stat-card:hover{transform:translateY(-3px);border-color:var(--primary);box-shadow:0 12px 28px rgba(0,0,0,.35)}
        .stat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;background:rgba(127,71,151,.15);border:1px solid rgba(127,71,151,.3);color:var(--primary-light)}
        .stat-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-secondary)}
        .stat-value{font-size:2.2rem;font-weight:800;font-family:var(--font-mono);line-height:1;color:#fff}
        .stat-note{font-size:.78rem;color:var(--primary-light);background:rgba(127,71,151,.12);border:1px solid rgba(127,71,151,.22);padding:.2rem .65rem;border-radius:40px;display:inline-flex;align-items:center;gap:.3rem;width:fit-content}
        /* tabs */
        .council-tabs{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.25rem}
        .council-tab{padding:.55rem 1rem;border-radius:40px;font-weight:600;font-size:.85rem;cursor:pointer;border:1px solid var(--border);background:rgba(255,255,255,.04);color:var(--text-secondary);transition:all .2s}
        .council-tab:hover{background:rgba(127,71,151,.15);color:var(--primary-light);border-color:var(--primary-light);transform:translateY(-1px)}
        .council-tab.active{background:var(--primary);color:#fff;border-color:var(--primary-light);box-shadow:0 4px 14px rgba(127,71,151,.35)}
        /* cards */
        .leaderboard-card{background:linear-gradient(135deg,var(--bg-card),var(--bg-dark));border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 12px 28px rgba(0,0,0,.35);margin-bottom:1.5rem}
        .card-header{padding:1.25rem 1.5rem;border-bottom:1px solid rgba(86,86,87,.5);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;background:var(--dark-lighter)}
        .card-header h2{font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:.5rem}
        .card-header p{color:var(--text-secondary);font-size:.82rem;margin-top:.15rem}
        .chip{background:rgba(127,71,151,.14);border:1px solid rgba(127,71,151,.35);color:var(--primary-light);padding:.3rem .85rem;border-radius:40px;font-size:.75rem;font-weight:600}
        .table-responsive{overflow-x:auto}
        table{width:100%;border-collapse:collapse;min-width:520px}
        th{text-align:left;padding:.9rem 1.25rem;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-secondary);background:#2a292e;border-bottom:1px solid rgba(127,71,151,.18);white-space:nowrap}
        td{padding:.9rem 1.25rem;border-bottom:1px solid rgba(255,255,255,.06);color:var(--text-main);font-size:.9rem}
        tbody tr:hover{background:rgba(127,71,151,.08)}
        .rank-badge{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:10px;font-weight:800;font-family:var(--font-mono);background:rgba(255,255,255,.06);border:1px solid var(--border);color:var(--text-secondary);font-size:.85rem}
        .top-rank-1 .rank-badge{background:rgba(251,191,36,.14);border-color:#fbbf24;color:#fcd34d}
        .top-rank-2 .rank-badge{background:rgba(203,213,225,.12);border-color:#cbd5e1;color:#e2e8f0}
        .top-rank-3 .rank-badge{background:rgba(180,83,9,.14);border-color:#b45309;color:#f59e0b}
        .user-cell{display:flex;align-items:center;gap:.75rem}
        .avatar{width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#3f3651,#2d243a);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;border:1px solid rgba(255,255,255,.08);text-transform:uppercase;flex-shrink:0}
        .role-badge{font-size:.68rem;font-weight:700;padding:.15rem .5rem;border-radius:40px;border:1px solid transparent;display:inline-block}
        .role-head{background:rgba(127,71,151,.18);color:#d8b4fe;border-color:rgba(127,71,151,.35)}
        .role-instructor{background:rgba(59,130,246,.12);color:#93c5fd;border-color:rgba(59,130,246,.3)}
        .role-vp{background:rgba(251,191,36,.14);color:#fcd34d;border-color:rgba(251,191,36,.4)}
        .count-pill{display:inline-flex;align-items:center;justify-content:center;min-width:56px;height:36px;background:rgba(127,71,151,.14);border:1px solid rgba(127,71,151,.35);border-radius:40px;font-family:var(--font-mono);font-size:.95rem;font-weight:800;color:var(--primary-light);padding:0 .85rem}
        /* per council grid */
        .council-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;margin-bottom:1.5rem}
        .council-card{background:linear-gradient(135deg,var(--bg-card),var(--bg-dark));border:1px solid var(--border);border-radius:1rem;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,.25)}
        .council-card-header{padding:1rem 1.25rem;background:rgba(127,71,151,.08);border-bottom:1px solid rgba(127,71,151,.18);display:flex;align-items:center;justify-content:space-between}
        .council-card-header h3{font-size:.95rem;font-weight:700;display:flex;align-items:center;gap:.5rem}
        .council-card-header span{font-size:.75rem;color:var(--text-secondary);background:rgba(255,255,255,.06);padding:.2rem .6rem;border-radius:40px;border:1px solid rgba(255,255,255,.08)}
        .council-card table{ min-width:0}
        .council-card th{padding:.7rem 1rem}
        .council-card td{padding:.7rem 1rem}
        .hidden{display:none !important}
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1500;backdrop-filter:blur(4px)}
        .sidebar-overlay.active{display:block}
        @media(max-width:1024px){.dashboard-container{grid-template-columns:1fr}.main-content{padding:1.25rem}.stat-grid{grid-template-columns:1fr}.council-grid{grid-template-columns:1fr}.page-title h1{font-size:1.45rem}}
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
                        <h1><i class="fas fa-user-check"></i> Interviewer Analytics</h1>
                        <p>who interviewed how much · per council · instructors vs heads</p>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn-refresh" onclick="window.location.reload()"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="stat-label">Total interviews</div>
                    <div class="stat-value">{{ $totalInterviews }}</div>
                    <div class="stat-note"><i class="fas fa-check-double"></i> all councils</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    <div class="stat-label">Active interviewers</div>
                    <div class="stat-value">{{ $activeInterviewers }}</div>
                    <div class="stat-note">who did at least one</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
                    <div class="stat-label">Avg. per interviewer</div>
                    <div class="stat-value">{{ $avgPer }}</div>
                    <div class="stat-note">productivity rate</div>
                </div>
            </div>

            <div class="council-tabs" id="councilTabs">
                <button class="council-tab active" data-council="all"><i class="fas fa-globe"></i> All Councils</button>
                @foreach($councils as $c)
                    <button class="council-tab" data-council="{{ $c }}"><i class="fas fa-sitemap"></i> {{ $c }}</button>
                @endforeach
            </div>

            <!-- Overall Leaderboard -->
            <div class="leaderboard-card" id="overallCard">
                <div class="card-header">
                    <div>
                        <h2><i class="fas fa-trophy" style="color:#fbbf24"></i> Top Interviewers · Overall</h2>
                        <p>ranked by interviews conducted across all councils</p>
                    </div>
                    <div class="chip"><i class="fas fa-bolt"></i> interview influence</div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead><tr><th style="width:80px">Rank</th><th>Interviewer</th><th>Role</th><th>Council</th><th style="text-align:center">Interviews</th></tr></thead>
                        <tbody>
                        @forelse($overall as $idx=>$row)
                            @php $u = $usersMap->get($row->name); $role = $u->role ?? '—'; $council = $detailed->firstWhere('name',$row->name)->council ?? ($u->council ?? '—'); @endphp
                            <tr class="{{ $idx==0 ? 'top-rank-1' : ($idx==1 ? 'top-rank-2' : ($idx==2 ? 'top-rank-3' : '')) }}">
                                <td><div class="rank-badge">{{ $idx==0 ? '🥇' : ($idx==1 ? '🥈' : ($idx==2 ? '🥉' : $idx+1)) }}</div></td>
                                <td><div class="user-cell"><div class="avatar">{{ strtoupper(substr($row->name,0,2)) }}</div><span style="font-weight:600">{{ $row->name }}</span></div></td>
                                <td><span class="role-badge {{ $role=='Instructor' ? 'role-instructor' : ($role=='VP'?'role-vp':'role-head') }}">{{ $role }}</span></td>
                                <td style="color:var(--text-secondary)">{{ $council }}</td>
                                <td style="text-align:center"><span class="count-pill">{{ $row->count }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;padding:2.5rem;color:var(--text-secondary)">No interviews recorded yet</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Heads vs Instructors split -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.5rem" id="splitCards">
                <div class="leaderboard-card" style="margin-bottom:0">
                    <div class="card-header" style="background:rgba(59,130,246,.08)">
                        <div><h2><i class="fas fa-chalkboard-teacher" style="color:#93c5fd"></i> Instructors — who made how much</h2><p>all instructors with interview count (0 = none yet)</p></div>
                        <div class="chip" style="background:rgba(59,130,246,.12);border-color:rgba(59,130,246,.35);color:#93c5fd">{{ $instructorRoster->where('count','>',0)->count() }} / {{ $instructorRoster->count() }} active</div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>Interviewer</th><th>Council</th><th style="text-align:center">Count</th></tr></thead>
                            <tbody>
                            @forelse($instructorRoster as $row)
                                <tr style="{{ $row['count']==0 ? 'opacity:0.55' : '' }}"><td><div class="user-cell"><div class="avatar">{{ strtoupper(substr($row['username'],0,2)) }}</div><span style="font-weight:600">{{ $row['username'] }}</span></div></td><td style="color:var(--text-secondary)">{{ $row['council'] }}</td><td style="text-align:center"><span class="count-pill" style="{{ $row['count']==0 ? 'background:rgba(255,255,255,.06);border-color:var(--border);color:var(--text-secondary)' : '' }}">{{ $row['count'] }}</span></td></tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center;padding:1.5rem;color:var(--text-secondary)">No instructors found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="leaderboard-card" style="margin-bottom:0">
                    <div class="card-header" style="background:rgba(127,71,151,.08)">
                        <div><h2><i class="fas fa-crown" style="color:#d8b4fe"></i> Heads / VP</h2><p>interviews by heads & leadership</p></div>
                        <div class="chip">{{ $headOverall->count() }} active</div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead><tr><th>Interviewer</th><th>Role</th><th style="text-align:center">Count</th></tr></thead>
                            <tbody>
                            @forelse($headOverall as $row)
                                @php $u=$usersMap->get($row->name); @endphp
                                <tr><td><div class="user-cell"><div class="avatar">{{ strtoupper(substr($row->name,0,2)) }}</div><span style="font-weight:600">{{ $row->name }}</span></div></td><td><span class="role-badge {{ $u && $u->role=='VP'?'role-vp':'role-head' }}">{{ $u->role ?? '—' }}</span></td><td style="text-align:center"><span class="count-pill">{{ $row->count }}</span></td></tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center;padding:1.5rem;color:var(--text-secondary)">No head interviews</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Per Council Cards -->
            <div id="perCouncilSection">
                <div style="margin-bottom:.85rem;display:flex;align-items:center;gap:.6rem"><h2 style="font-size:1.15rem;font-weight:700"><i class="fas fa-layer-group" style="color:var(--primary-light)"></i> Per Council Breakdown</h2><span style="color:var(--text-secondary);font-size:.85rem">· who did how much in each council</span></div>
                <div class="council-grid" id="councilGrid">
                    @forelse($perCouncil as $councilName => $rows)
                    <div class="council-card" data-council="{{ $councilName }}">
                        <div class="council-card-header">
                            <h3><i class="fas fa-sitemap" style="color:var(--primary-light)"></i> {{ $councilName ?: 'No Council' }}</h3>
                            <span>{{ $rows->count() }} interviewers · {{ $rows->sum('count') }} interviews</span>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead><tr><th>#</th><th>Interviewer</th><th>Role</th><th style="text-align:center">Count</th></tr></thead>
                                <tbody>
                                @foreach($rows->sortByDesc('count')->values() as $i=>$r)
                                    @php $u=$usersMap->get($r->name); $role=$u->role ?? '—'; @endphp
                                    <tr>
                                        <td><span style="font-family:var(--font-mono);font-weight:700;color:var(--text-secondary)">{{ $i+1 }}</span></td>
                                        <td><span style="font-weight:600">{{ $r->name }}</span></td>
                                        <td><span class="role-badge {{ $role=='Instructor'?'role-instructor':($role=='VP'?'role-vp':'role-head') }}" style="font-size:.65rem">{{ $role }}</span></td>
                                        <td style="text-align:center"><span class="count-pill" style="min-width:48px;height:32px;font-size:.9rem">{{ $r->count }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @empty
                    <div class="council-card" style="grid-column:1/-1;text-align:center;padding:2.5rem;color:var(--text-secondary)">No council data yet</div>
                    @endforelse
                </div>
            </div>

        </main>
    </div>
    <script>
        // council tab filtering
        document.querySelectorAll('.council-tab').forEach(btn=>{
            btn.addEventListener('click',()=>{
                document.querySelectorAll('.council-tab').forEach(b=>b.classList.remove('active'));
                btn.classList.add('active');
                const council = btn.dataset.council;
                const cards = document.querySelectorAll('.council-card');
                const overallCard = document.getElementById('overallCard');
                const splitCards = document.getElementById('splitCards');
                if(council==='all'){
                    cards.forEach(c=>c.classList.remove('hidden'));
                    overallCard.classList.remove('hidden');
                    splitCards.classList.remove('hidden');
                } else {
                    overallCard.classList.add('hidden');
                    splitCards.classList.add('hidden');
                    cards.forEach(c=>{
                        if(c.dataset.council===council) c.classList.remove('hidden');
                        else c.classList.add('hidden');
                    });
                }
            });
        });
    </script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>
