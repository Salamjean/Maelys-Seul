{{-- Sidebar Recouvrement --}}
<aside
    class="fixed inset-y-0 left-0 w-72 bg-primary z-50 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl"
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
    style="background: linear-gradient(180deg, #02245b 0%, #01163a 100%); overflow-y:auto;">

    {{-- Logo / Header --}}
    <div style="padding:32px 24px; display:flex; flex-direction:column; align-items:center; text-align:center;">
        <div style="position:relative; margin-bottom:15px;">
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="ImmoSeul"
                style="width:70px; height:70px; object-fit:cover; border-radius:18px; border:3px solid rgba(255,94,20,0.2); box-shadow:0 8px 16px rgba(0,0,0,0.3);">
            <div style="position:absolute; bottom:-5px; right:-5px; width:20px; height:20px; background:#f59e0b; border:3px solid #02245b; border-radius:50%;"
                title="Recouvrement en ligne"></div>
        </div>
        <div>
            <div style="font-size:22px; font-weight:900; color:white; letter-spacing:-1px; font-style: italic; text-transform: uppercase;">
                MAELYS-<span style="color:#ff5e14;">IMO</span>
            </div>
            <div
                style="font-size:11px; color:rgba(255,255,255,0.4); letter-spacing:3px; text-transform:uppercase; font-weight:700; margin-top:2px;">
                Recouvrement
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav style="padding:15px 16px;">
        <p style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:0 12px 10px; font-weight:800; opacity: 0.8;">
            NAVIGATION
        </p>

        <a href="{{ route('recouvrement.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('recouvrement.dashboard') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-gauge-high" style="font-size:16px;"></i>
            </div>
            Tableau de bord
        </a>

        <a href="{{ route('recouvrement.tenants.late') }}"
            class="sidebar-link {{ request()->routeIs('recouvrement.tenants.late') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-user-clock" style="font-size:16px;"></i>
            </div>
            Locataires en retard
        </a>

        <a href="{{ route('recouvrement.my_payments') }}"
            class="sidebar-link {{ request()->routeIs('recouvrement.my_payments') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-wallet" style="font-size:16px;"></i>
            </div>
            Mes encaissements
        </a>

        <a href="{{ route('recouvrement.versements.index') }}"
            class="sidebar-link {{ request()->routeIs('recouvrement.versements.index') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-money-bill-transfer" style="font-size:16px;"></i>
            </div>
            Mes versements
        </a>
    </nav>

</aside>
