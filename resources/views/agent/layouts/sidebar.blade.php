{{-- Sidebar Agent --}}
<aside
    class="custom-scrollbar-hide"
    style="width:280px; min-height:100vh; background: linear-gradient(180deg, #02245b 0%, #01163a 100%); flex-shrink:0; position:fixed; top:0; left:0; bottom:0; overflow-y:auto; z-index:40; box-shadow: 4px 0 24px rgba(0,0,0,0.15); scrollbar-width: none; -ms-overflow-style: none;">

    <style>
        .custom-scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>


    {{-- Logo / Header --}}
    <div style="padding:32px 24px; display:flex; flex-direction:column; align-items:center; text-align:center;">
        <div style="position:relative; margin-bottom:15px;">
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="ImmoSeul"
                style="width:70px; height:70px; object-fit:cover; border-radius:18px; border:3px solid rgba(255,94,20,0.2); box-shadow:0 8px 16px rgba(0,0,0,0.3);">
            <div style="position:absolute; bottom:-5px; right:-5px; width:20px; height:20px; background:#10b981; border:3px solid #02245b; border-radius:50%;"
                title="Agent en ligne"></div>
        </div>
        <div>
            <div style="font-size:22px; font-weight:900; color:white; letter-spacing:-1px; font-style: italic; text-transform: uppercase;">
                MAELYS-<span style="color:#ff5e14;">IMO</span>
            </div>
            <div
                style="font-size:11px; color:rgba(255,255,255,0.4); letter-spacing:3px; text-transform:uppercase; font-weight:700; margin-top:2px;">
                Espace Agent
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
        <p style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:0 12px 10px; font-weight:800; opacity: 0.8;">
            NAVIGATION
        </p>

        <a href="{{ route('agent.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-gauge-high" style="font-size:16px;"></i>
            </div>
            Tableau de bord
        </a>

        <p style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:20px 12px 10px; font-weight:800; opacity: 0.8;">
            IMMOBILIER
        </p>

        {{-- Dropdown Biens --}}
        <div x-data="{ open: {{ request()->routeIs('agent.biens.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('agent.biens.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-house-chimney" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Gestion des Biens</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;" :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>
                <a href="{{ route('agent.biens.create') }}" class="sidebar-sublink {{ request()->routeIs('agent.biens.create') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Nouveau Bien</a>
                <a href="{{ route('agent.biens.index') }}" class="sidebar-sublink {{ request()->routeIs('agent.biens.index') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Liste des Biens</a>
            </div>
        </div>

        {{-- Dropdown Locataires --}}
        <div x-data="{ open: {{ request()->routeIs('agent.locataires.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('agent.locataires.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-users" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Locataires</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;" :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>
                <a href="{{ route('agent.locataires.create') }}" class="sidebar-sublink {{ request()->routeIs('agent.locataires.create') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Ajouter un locataire</a>
                <a href="{{ route('agent.locataires.index') }}" class="sidebar-sublink {{ request()->routeIs('agent.locataires.index') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Liste des locataires</a>
            </div>
        </div>

        <p style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:20px 12px 10px; font-weight:800; opacity: 0.8;">
            GESTION
        </p>

        {{-- Dropdown Paiements --}}
        <div x-data="{ open: {{ request()->routeIs('agent.payments.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('agent.payments.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size:16px;"></i>
                </div>
                <div style="flex:1; display:flex; align-items:center; justify-content:space-between;">
                    <span>Paiements</span>
                    @if($count_paiements_en_attente > 0)
                        <span style="background:#ff5e14; color:white; font-size:9px; font-weight:900; padding:2px 6px; border-radius:6px; box-shadow: 0 2px 6px rgba(255,94,20,0.3);">
                            {{ $count_paiements_en_attente }}
                        </span>
                    @endif
                </div>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;" :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>
                <a href="{{ route('agent.payments.pending') }}" class="sidebar-sublink {{ request()->routeIs('agent.payments.pending') ? 'active' : '' }}" 
                   style="display:flex; justify-content:space-between; align-items:center; padding:8px 12px 8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">
                   <span>À Valider</span>
                   @if($count_paiements_en_attente > 0)
                        <span style="font-size:10px; color:#ff5e14; font-weight:800;">({{ $count_paiements_en_attente }})</span>
                   @endif
                </a>
                <a href="{{ route('agent.payments.history') }}" class="sidebar-sublink {{ request()->routeIs('agent.payments.history') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Historique</a>
            </div>
        </div>

        {{-- Onglet Rappel --}}
        <a href="{{ route('agent.rappels.index') }}"
            class="sidebar-link {{ request()->routeIs('agent.rappels.index') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('agent.rappels.index') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-bell-concierge" style="font-size:16px;"></i>
            </div>
            <span style="flex:1;">Rappel
                @if($count_rappels > 0)
                    <span style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; margin-left:5px; font-weight:800; animation: pulse 2s infinite;">
                        {{ $count_rappels }}
                    </span>
                @endif
            </span>
        </a>


        <a href="{{ route('agent.support.index') }}"
            class="sidebar-link {{ request()->routeIs('agent.support.*') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-headset" style="font-size:16px;"></i>
            </div>
            SAV / Support
        </a>

        {{-- Dropdown Visites --}}
        <div x-data="{ open: {{ request()->routeIs('agent.visites.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('agent.visites.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-calendar-days" style="font-size:16px;"></i>
                </div>
                <div style="flex:1; display:flex; align-items:center; justify-content:space-between;">
                    <span>Visites</span>
                    @if($count_visites_en_attente > 0)
                        <span style="background:#ff5e14; color:white; font-size:9px; font-weight:900; padding:2px 6px; border-radius:6px;">
                            {{ $count_visites_en_attente }}
                        </span>
                    @endif
                </div>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;" :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>
                <a href="{{ route('agent.visites.index') }}" class="sidebar-sublink {{ request()->routeIs('agent.visites.index') ? 'active' : '' }}" 
                   style="display:flex; justify-content:space-between; align-items:center; padding:8px 12px 8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">
                   <span>Demandes</span>
                   @if($count_visites_en_attente > 0)
                        <span style="font-size:10px; color:#ff5e14; font-weight:800;">({{ $count_visites_en_attente }})</span>
                   @endif
                </a>
                <a href="{{ route('agent.visites.effectuees') }}" class="sidebar-sublink {{ request()->routeIs('agent.visites.effectuees') ? 'active' : '' }}" 
                   style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500;">Effectuées</a>
            </div>
        </div>

        <p style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:20px 12px 10px; font-weight:800; opacity: 0.8;">
            OUTILS
        </p>

        <a href="#" class="sidebar-link"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px;">
            <div style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-folder-open" style="font-size:16px;"></i>
            </div>
            Mes Fichiers
        </a>
    </nav>


</aside>

