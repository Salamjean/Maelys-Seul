{{-- Sidebar Admin --}}
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
            <img src="{{ asset('assets/images/maelys.jpg') }}" alt="Maelys-imo"
                style="width:70px; height:70px; object-fit:cover; border-radius:18px; border:3px solid rgba(255,94,20,0.2); box-shadow:0 8px 16px rgba(0,0,0,0.3);">
            <div style="position:absolute; bottom:-5px; right:-5px; width:20px; height:20px; background:#10b981; border:3px solid #02245b; border-radius:50%;"
                title="Admin en ligne"></div>
        </div>
        <div>
            <div style="font-size:22px; font-weight:900; color:white; letter-spacing:-1px; font-style: italic; text-transform: uppercase;">
                MAELYS-<span style="color:#ff5e14;">IMO</span>
            </div>
            <div
                style="font-size:11px; color:rgba(255,255,255,0.4); letter-spacing:3px; text-transform:uppercase; font-weight:700; margin-top:2px;">
                Dashboard Admin
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
            VUE D'ENSEMBLE
        </p>

        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('admin.dashboard') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                <i class="fa-solid fa-gauge-high" style="font-size:16px;"></i>
            </div>
            Tableau de bord
        </a>

        {{-- Dropdown Versements --}}
        <div x-data="{ open: {{ request()->routeIs('admin.versements.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.versements.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.versements.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-vault" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Versements</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>

                <a href="{{ route('admin.versements.index', ['tab' => 'agents']) }}"
                    class="sidebar-sublink {{ request('tab') == 'agents' || (request()->routeIs('admin.versements.index') && !request('tab')) ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request('tab') == 'agents' || (request()->routeIs('admin.versements.index') && !request('tab')) ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    État des caisses
                </a>
                <a href="{{ route('admin.versements.index', ['tab' => 'history']) }}"
                    class="sidebar-sublink {{ request('tab') == 'history' ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request('tab') == 'history' ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Historique Global
                </a>
            </div>
        </div>

        <p
            style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:20px 12px 10px; font-weight:800; opacity: 0.8;">
            IMMOBILIER
        </p>

        {{-- Dropdown Visites --}}
        <div x-data="{ open: {{ request()->routeIs('admin.visites.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.visites.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-calendar-days" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Visites 
                    @if($count_visites_en_attente > 0)
                        <span style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; margin-left:5px;">{{ $count_visites_en_attente }}</span>
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('admin.visites.demandees') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.visites.demandees') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Visites demandées
                </a>
                <a href="{{ route('admin.visites.effectuees') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.visites.effectuees') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Visites effectuées
                </a>
            </div>
        </div>

        {{-- Menu Paiements Manuels --}}
        <div x-data="{ open: {{ request()->routeIs('admin.payments.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link w-full"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease; background:none; border:none; cursor:pointer;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.payments.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-file-invoice-dollar" style="font-size:16px;"></i>
                </div>
                <span style="flex:1; text-align:left;">Paiements
                    @if($count_paiements_en_attente > 0)
                        <span style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; margin-left:5px; font-weight:800;">
                            {{ $count_paiements_en_attente }}
                        </span>
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>

            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;"></div>

                <a href="{{ route('admin.payments.pending') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.payments.pending') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.payments.pending') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    À Valider
                </a>
                <a href="{{ route('admin.payments.history') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.payments.history') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.payments.history') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Historique
                </a>
            </div>
        </div>

        {{-- Onglet Rappel --}}
        <a href="{{ route('admin.rappels.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.rappels.index') ? 'active' : '' }}"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px; transition: all 0.3s ease;">
            <div
                style="width:32px; height:32px; background:{{ request()->routeIs('admin.rappels.index') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
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


        {{-- Dropdown Biens --}}
        <div x-data="{ open: {{ request()->routeIs('admin.biens.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.biens.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-house-chimney" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Gestion des Biens</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                {{-- Ligne verticale de connexion --}}
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('admin.biens.create') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.biens.create') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Nouveau Bien
                </a>
                <a href="{{ route('admin.biens.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.biens.index') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Liste des Biens
                </a>
            </div>
        </div>

        {{-- Dropdown Locataires --}}
        <div x-data="{ open: {{ request()->routeIs('admin.locataires.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.locataires.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-users-gear" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Locataires</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('admin.locataires.create') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.locataires.create') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Ajouter un locataire
                </a>
                <a href="{{ route('admin.locataires.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.locataires.index') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.locataires.index') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Liste des locataires
                </a>
                <a href="{{ route('admin.locataires.moved_out') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.locataires.moved_out') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.locataires.moved_out') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Anciens Locataires
                </a>
            </div>
        </div>

        {{-- Dropdown Partenaires (Propriétaires) --}}
        <div x-data="{ open: {{ request()->routeIs('admin.proprietaires.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.proprietaires.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.proprietaires.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-handshake" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Partenaires</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('admin.proprietaires.create') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.proprietaires.create') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Ajouter
                </a>
                <a href="{{ route('admin.proprietaires.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.proprietaires.index') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:rgba(255,255,255,0.45); font-size:13px; font-weight:500; transition:color 0.2s;">
                    Listes
                </a>
            </div>
        </div>

        <div x-data="{ open: {{ request()->routeIs('admin.support.*') ? 'true' : 'false' }} }">
            <a href="{{ route('admin.support.index') }}" class="sidebar-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.support.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-headset" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">SAV / Support
                    @if($count_messages_en_attente > 0)
                        <span style="background:#ff5e14; color:white; font-size:9px; padding:2px 6px; border-radius:20px; margin-left:5px; font-weight:800;">
                            {{ $count_messages_en_attente }}
                        </span>
                    @endif
                </span>
            </a>
        </div>

        <p
            style="font-size:10px; color:#ff5e14; letter-spacing:2.5px; text-transform:uppercase; padding:20px 12px 10px; font-weight:800; opacity: 0.8;">
            COMMUNAUTÉ
        </p>

        @if(Auth::guard('admin')->user()->role === 'admin')
        {{-- Gestion du Personnel --}}
        <div x-data="{ open: {{ request()->routeIs('admin.staff.*') ? 'true' : 'false' }} }">
            <a href="{{ route('admin.staff.index') }}" class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.staff.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-user-tie" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Gestion du Personnel</span>
            </a>
        </div>

        {{-- Dropdown Agents --}}
        <div x-data="{ open: {{ request()->routeIs('admin.agents.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="sidebar-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:4px; width:100%; text-align:left; transition: all 0.3s ease;">
                <div
                    style="width:32px; height:32px; background:{{ request()->routeIs('admin.agents.*') ? '#ff5e14' : 'rgba(255,255,255,0.05)' }}; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-users-cog" style="font-size:16px;"></i>
                </div>
                <span style="flex:1;">Agents</span>
                <i class="fa-solid fa-chevron-down" style="font-size:10px; opacity:0.5; transition:transform 0.3s;"
                    :style="open ? 'transform:rotate(180deg)' : ''"></i>
            </button>
            <div x-show="open" x-transition style="margin-bottom:8px; padding-left: 44px; position:relative;">
                <div
                    style="position:absolute; left:32px; top:0; bottom:15px; width:2px; background:rgba(255,255,255,0.05); border-radius:10px;">
                </div>

                <a href="{{ route('admin.agents.create') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.agents.create') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.agents.create') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Ajouter
                </a>
                <a href="{{ route('admin.agents.index') }}"
                    class="sidebar-sublink {{ request()->routeIs('admin.agents.index') ? 'active' : '' }}"
                    style="display:block; padding:8px 0; text-decoration:none; color:{{ request()->routeIs('admin.agents.index') ? '#ff5e14' : 'rgba(255,255,255,0.45)' }}; font-size:13px; font-weight:500; transition:color 0.2s;">
                    Liste
                </a>
            </div>
        </div>
        @endif
        <a href="#" class="sidebar-link"
            style="display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; text-decoration:none; color:rgba(255,255,255,0.6); font-size:14px; font-weight:600; margin-bottom:8px;">
            <div
                style="width:32px; height:32px; background:rgba(255,255,255,0.05); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-comment-dots" style="font-size:16px;"></i>
            </div>
            Messages
        </a>
    </nav>


</aside>

