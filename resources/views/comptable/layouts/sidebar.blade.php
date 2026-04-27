{{-- Sidebar Comptable --}}
<aside class="custom-scrollbar-hide"
    style="width:280px; min-height:100vh; background: linear-gradient(180deg, #02245b 0%, #01163a 100%); flex-shrink:0; position:fixed; top:0; left:0; bottom:0; overflow-y:auto; z-index:40; box-shadow: 4px 0 24px rgba(0,0,0,0.15); scrollbar-width: none; -ms-overflow-style: none;">

    <style>
        .custom-scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    {{-- Logo / Header --}}
    <div style="padding:32px 24px; display:flex; flex-direction:column; align-items:center; text-align:center;">
        <div style="position:relative; margin-bottom:15px;">
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Maelys-imo"
                style="width:70px; height:70px; object-fit:cover; border-radius:18px; border:3px solid rgba(255,94,20,0.2); box-shadow:0 8px 16px rgba(0,0,0,0.3);">
            <div style="position:absolute; bottom:-5px; right:-5px; width:20px; height:20px; background:#10b981; border:3px solid #02245b; border-radius:50%;"
                title="Comptable en ligne"></div>
        </div>
        <div>
            <div
                style="font-size:22px; font-weight:900; color:white; letter-spacing:-1px; font-style: italic; text-transform: uppercase;">
                MAELYS-<span style="color:#ff5e14;">IMO</span>
            </div>
            <div
                style="font-size:11px; color:rgba(255,255,255,0.4); letter-spacing:3px; text-transform:uppercase; font-weight:700; margin-top:2px;">
                Espace Comptable
            </div>
        </div>
    </div>

    {{-- Menu Separation --}}
    <div style="padding:0 24px 10px;">
        <div style="height:1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);">
        </div>
    </div>

    {{-- Nav --}}
    <nav style="padding:15px 16px;">
        <p
            style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:0 12px 10px; font-weight:800; opacity: 0.8;">
            NAVIGATION
        </p>

        <a href="{{ route('comptable.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('comptable.dashboard') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('comptable.dashboard') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-gauge-high" style="font-size:16px;"></i>
            </div>
            Tableau de bord
        </a>

        <a href="{{ route('comptable.versements.index') }}"
            class="sidebar-link {{ request()->routeIs('comptable.versements.*') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('comptable.versements.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-vault" style="font-size:16px;"></i>
            </div>
            Versements Agents
        </a>

        <a href="{{ route('comptable.rappels.index') }}"
            class="sidebar-link {{ request()->routeIs('comptable.rappels.*') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('comptable.rappels.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-bell" style="font-size:16px;"></i>
            </div>
            <span style="flex:1;">Rappels & Relances</span>
            @if($count_rappels > 0)
                <span
                    style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; font-weight:800;">
                    {{ $count_rappels }}
                </span>
            @endif
        </a>

        <a href="{{ route('comptable.locataires.index') }}"
            class="sidebar-link {{ request()->routeIs('comptable.locataires.index') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('comptable.locataires.index') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-users" style="font-size:16px;"></i>
            </div>
            Liste Locataires
        </a>

        {{-- Dropdown Paiements --}}
        <div x-data="{ open: {{ request()->routeIs('comptable.payments.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link w-full"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease; background:none; border:none; cursor:pointer;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('comptable.payments.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size:16px;"></i>
                </div>
                <span style="flex:1; text-align:left;">Paiements
                    @if($count_paiements_en_attente > 0)
                        <span
                            style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; margin-left:5px; font-weight:800;">
                            {{ $count_paiements_en_attente }}
                        </span>
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>

            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('comptable.payments.pending') }}"
                    class="sidebar-sublink {{ request()->routeIs('comptable.payments.pending') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('comptable.payments.pending') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    À Valider
                </a>
                <a href="{{ route('comptable.payments.history') }}"
                    class="sidebar-sublink {{ request()->routeIs('comptable.payments.history') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('comptable.payments.history') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Historique
                </a>
            </div>
        </div>
    </nav>
</aside>