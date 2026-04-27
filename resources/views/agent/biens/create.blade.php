@extends('agent.layouts.app')

@section('title', 'Ajouter un bien')
@section('page-title', 'Ajouter un bien')

@section('content')
    <div style="padding: 0 5%;">
        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Success --}}
        @if (session('success'))
            <div
                style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 18px; margin-bottom:24px; display:flex; align-items:center; gap:10px;">
                <i class="fa-solid fa-circle-check" style="color:#16a34a;"></i>
                <span style="font-size:14px; color:#15803d;">{{ session('success') }}</span>
            </div>
        @endif

        {{-- ===== STEP INDICATOR ===== --}}
        <div
            style="display:flex; align-items:center; justify-content:center; margin-bottom:40px; background:white; padding:30px; border-radius:16px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); width:100%; box-sizing:border-box;">
            @php
                $steps = [
                    ['num' => 1, 'icon' => 'fa-circle-info', 'label' => 'Infos générales'],
                    ['num' => 2, 'icon' => 'fa-tag', 'label' => 'Utilisation'],
                    ['num' => 3, 'icon' => 'fa-coins', 'label' => 'Finances'],
                    ['num' => 4, 'icon' => 'fa-images', 'label' => 'Médias'],
                ];
            @endphp
            @foreach ($steps as $s)
                <div style="display:flex; align-items:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:8px; position:relative;">
                        <div id="step-circle-{{ $s['num'] }}"
                            style="width:54px; height:54px; border-radius:50%;
                                background:{{ $s['num'] === 1 ? '#ff5e14' : '#f3f4f6' }};
                                display:flex; align-items:center; justify-content:center;
                                font-size:20px; color:{{ $s['num'] === 1 ? 'white' : '#9ca3af' }};
                                transition:all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                                box-shadow:{{ $s['num'] === 1 ? '0 0 0 4px rgba(255,94,20,0.1)' : 'none' }};">
                            <i class="fa-solid {{ $s['icon'] }}"></i>
                        </div>
                        <span id="step-label-{{ $s['num'] }}"
                            style="font-size:12px; font-weight:700;
                                color:{{ $s['num'] === 1 ? '#ff5e14' : '#9ca3af' }};
                                white-space:nowrap; transition:color 0.4s;">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    @if (!$loop->last)
                        <div id="step-line-{{ $s['num'] }}"
                            style="width:120px; height:4px; background:#f3f4f6;
                                margin:0 15px; margin-bottom:24px; border-radius:10px;
                                transition:background 0.4s;">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ===== FORM ===== --}}
        <form method="POST" action="{{ route('agent.biens.store') }}" enctype="multipart/form-data" id="main-form">
            @csrf

            {{-- STEP 1 é Informations générales --}}
            <div id="step-1" class="form-step">
                <div
                    style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <h3
                        style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:30px; display:flex; align-items:center; gap:12px;">
                        <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i
                                class="fa-solid fa-circle-info" style="color:#ff5e14;"></i></span>
                        Informations générales du bien
                    </h3>

                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; margin-bottom:20px;">
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Type
                                de bien <span style="color:#ef4444;">*</span></label>
                            <select name="type_bien"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                                <option value="">-- Sélectionner le type --</option>
                                @foreach (['appartement' => 'Appartement', 'maison' => 'Maison', 'bureau' => 'Bureau'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('type_bien') == $val ? 'selected' : '' }}>
                                        {{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('type_bien')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Configuration
                                / Typologie du logement <span style="color:#ef4444;">*</span></label>
                            <select name="typologie"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                                <option value="">-- Sélectionner la typologie --</option>
                                <option value="studio" {{ old('typologie') == 'studio' ? 'selected' : '' }}>Studio</option>
                                <option value="f1" {{ old('typologie') == 'f1' ? 'selected' : '' }}>F1</option>
                                <option value="f2" {{ old('typologie') == 'f2' ? 'selected' : '' }}>F2</option>
                                <option value="f3" {{ old('typologie') == 'f3' ? 'selected' : '' }}>F3</option>
                                <option value="f4" {{ old('typologie') == 'f4' ? 'selected' : '' }}>F4</option>
                                <option value="f5_plus" {{ old('typologie') == 'f5_plus' ? 'selected' : '' }}>F5 et plus
                                </option>
                                <option value="duplex" {{ old('typologie') == 'duplex' ? 'selected' : '' }}>Duplex</option>
                                <option value="triplex" {{ old('typologie') == 'triplex' ? 'selected' : '' }}>Triplex
                                </option>
                                <option value="villa" {{ old('typologie') == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="chambre_salon" {{ old('typologie') == 'chambre_salon' ? 'selected' : '' }}>
                                    Chambre salon</option>
                                <option value="autre"
                                    {{ old('typologie') == 'autre' || old('typologie') == 'autres' ? 'selected' : '' }}>
                                    Autre</option>
                            </select>
                            @error('typologie')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; margin-bottom:20px;">
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Commune
                                / Emplacement <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="commune" value="{{ old('commune') }}"
                                placeholder="Ex : Hydra, Alger"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            @error('commune')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Lien Google Maps <span style="font-weight:400; color:#9ca3af;">(Optionnel)</span></label>
                            <div style="position:relative;">
                                <input type="url" name="google_maps_url" value="{{ old('google_maps_url') }}"
                                    placeholder="Coller le lien Google Maps ici"
                                    style="width:100%; padding:14px 14px 14px 45px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                    onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                                <span style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#9ca3af;">
                                    <i class="fa-solid fa-map-location-dot"></i>
                                </span>
                            </div>
                            @error('google_maps_url')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:20px;">
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Superficie
                                (m²) <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="superficie" value="{{ old('superficie') }}" min="1"
                                placeholder="Ex : 90"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            @error('superficie')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Pièces
                                <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="nb_pieces" value="{{ old('nb_pieces', 1) }}" min="0"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            @error('nb_pieces')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Toilettes
                                <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="nb_toilettes" value="{{ old('nb_toilettes', 1) }}" min="0"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            @error('nb_toilettes')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div style="display:flex; align-items:flex-end;">
                            <div
                                style="display:flex; align-items:center; gap:10px; background:#f8fafc; padding:10px 15px; border-radius:12px; border:2px solid #f3f4f6; width:100%; min-height:52px; box-sizing:border-box;">
                                <input type="checkbox" name="garage" id="garage" value="1"
                                    {{ old('garage') ? 'checked' : '' }}
                                    style="width:20px; height:20px; accent-color:#ff5e14; cursor:pointer;">
                                <label for="garage"
                                    style="font-size:14px; font-weight:600; color:#374151; cursor:pointer; white-space:nowrap;">Garage
                                    inclus</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2 é Utilisation & Description --}}
            <div id="step-2" class="form-step" style="display:none;">
                <div style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <h3
                        style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:30px; display:flex; align-items:center; gap:12px;">
                        <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i class="fa-solid fa-tag"
                                style="color:#ff5e14;"></i></span>
                        Utilisation & Description
                    </h3>

                    <div style="margin-bottom:40px;">
                        <label
                            style="display:block; font-size:15px; font-weight:700; color:#374151; margin-bottom:20px; text-align:center;">Quel
                            est le type d'utilisation de ce bien ?</label>

                        @php
                            $oldUtil = old('type_utilisation', '');
                            $isCustom = $oldUtil !== '' && !in_array($oldUtil, ['habitation', 'bureau']);
                        @endphp

                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; max-width:900px; margin:0 auto;">
                            <label id="card-habitation"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $oldUtil === 'habitation' ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $oldUtil === 'habitation' ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                                onclick="selectUtilisation('habitation')">
                                <div
                                    style="width:60px; height:60px; background:{{ $oldUtil === 'habitation' ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">
                                    <i class="fa-solid fa-house"
                                        style="font-size:28px; color:{{ $oldUtil === 'habitation' ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span
                                    style="font-size:16px; font-weight:700; color:{{ $oldUtil === 'habitation' ? '#ff5e14' : '#374151' }};">Habitation</span>
                            </label>

                            <label id="card-bureau"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $oldUtil === 'bureau' ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $oldUtil === 'bureau' ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                                onclick="selectUtilisation('bureau')">
                                <div
                                    style="width:60px; height:60px; background:{{ $oldUtil === 'bureau' ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">
                                    <i class="fa-solid fa-briefcase"
                                        style="font-size:28px; color:{{ $oldUtil === 'bureau' ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span
                                    style="font-size:16px; font-weight:700; color:{{ $oldUtil === 'bureau' ? '#ff5e14' : '#374151' }};">Bureau</span>
                            </label>

                            <label id="card-autre"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $isCustom ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $isCustom ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                                onclick="selectUtilisation('autre')">
                                <div
                                    style="width:60px; height:60px; background:{{ $isCustom ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">
                                    <i class="fa-solid fa-ellipsis"
                                        style="font-size:28px; color:{{ $isCustom ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span
                                    style="font-size:16px; font-weight:700; color:{{ $isCustom ? '#ff5e14' : '#374151' }};">Autre</span>
                            </label>
                        </div>

                        <input type="hidden" id="type_utilisation_hidden" name="type_utilisation"
                            value="{{ old('type_utilisation', '') }}">

                        <div id="custom_utilisation_badge"
                            style="display:{{ $isCustom ? 'flex' : 'none' }}; align-items:center; justify-content:center; gap:12px;
                                       margin:25px auto 0; padding:15px 25px; max-width:400px;
                                       background:#fff7ed; border:2px dashed #fed7aa;
                                       border-radius:15px; font-size:15px; color:#c2410c;">
                            <i class="fa-solid fa-pencil"></i>
                            <span id="custom_utilisation_text"
                                style="font-weight:700;">{{ $isCustom ? 'Utilisation : ' . $oldUtil : '' }}</span>
                            <button type="button" onclick="changeCustomUtilisation()"
                                style="margin-left:10px; background:#ff5e14; border:none; color:white; padding:5px 12px; border-radius:8px; cursor:pointer; font-size:12px; font-weight:700; transition:all 0.2s;">Modifier</button>
                        </div>
                        @error('type_utilisation')
                            <p style="text-align:center; color:#ef4444; font-size:13px; margin-top:10px;">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div style="max-width:900px; margin:0 auto;">
                        <label
                            style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:12px;">Description
                            détaillée du bien</label>
                        <textarea name="description" rows="8" placeholder="Décrivez les atouts du bien, le quartier, etc..."
                            style="width:100%; padding:18px; border:2px solid #f3f4f6; border-radius:15px; font-size:15px; color:#111827; background:#f9fafb; outline:none; resize:none; transition:all 0.2s;"
                            onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">{{ old('description') }}</textarea>
                        @error('description')
                            <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- STEP 3 é Finances --}}
            <div id="step-3" class="form-step" style="display:none;">
                <div style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <h3
                        style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:30px; display:flex; align-items:center; gap:12px;">
                        <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i class="fa-solid fa-coins"
                                style="color:#ff5e14;"></i></span>
                        Informations financiéres (FCFA)
                    </h3>

                    <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:30px;">
                        @foreach ([['name' => 'loyer_mensuel', 'label' => 'Loyer mensuel', 'req' => true, 'icon' => 'fa-money-bill-wave', 'readonly' => false, 'suffix' => 'FCFA', 'step' => '100', 'value' => old('loyer_mensuel')], ['name' => 'avance', 'label' => 'Avance', 'req' => false, 'icon' => 'fa-hand-holding-dollar', 'readonly' => false, 'suffix' => 'mois', 'step' => '1', 'min' => 0, 'value' => old('avance', 1)], ['name' => 'caution', 'label' => 'Caution', 'req' => false, 'icon' => 'fa-shield-halved', 'readonly' => false, 'suffix' => 'mois', 'step' => '1', 'min' => 0, 'value' => old('caution', 1)], ['name' => 'frais_agence', 'label' => 'Agence', 'req' => false, 'icon' => 'fa-briefcase', 'readonly' => true, 'suffix' => 'mois', 'step' => '1', 'value' => old('frais_agence', 1)], ['name' => 'montant_total', 'label' => 'Total', 'req' => false, 'icon' => 'fa-calculator', 'readonly' => true, 'suffix' => 'FCFA', 'step' => '100', 'value' => old('montant_total')]] as $f)
                            <div style="margin-bottom:10px;">
                                <label
                                    style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">{{ $f['label'] }}
                                    @if ($f['req'])
                                        <span style="color:#ef4444;">*</span>
                                    @endif
                                </label>
                                <div style="position:relative;">
                                    <span
                                        style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#9ca3af;"><i
                                            class="fa-solid {{ $f['icon'] }}"></i></span>
                                    <input type="number" name="{{ $f['name'] }}" value="{{ $f['value'] }}"
                                        min="{{ $f['min'] ?? 0 }}" step="{{ $f['step'] }}" placeholder="0"
                                        {{ $f['readonly'] ?? false ? 'readonly' : '' }}
                                        style="width:100%; padding:14px 14px 14px 45px; border:2px solid {{ $f['readonly'] ?? false ? '#e5e7eb' : '#f3f4f6' }}; border-radius:12px; font-size:15px; color:#111827; background:{{ $f['readonly'] ?? false ? '#e5e7eb' : '#f9fafb' }}; outline:none; transition:all 0.2s;"
                                        onfocus="if(!this.hasAttribute('readonly')) { this.style.borderColor='#ff5e14'; this.style.background='white'; }">
                                    <span
                                        style="position:absolute; right:15px; top:50%; transform:translateY(-50%); font-size:12px; font-weight:800; color:#9ca3af;">{{ $f['suffix'] }}</span>
                                </div>
                                @error($f['name'])
                                    <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Jour
                                de paiement habituel <span style="color:#9ca3af; font-weight:400;">(1 – 31)</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color:#9ca3af;"><i
                                        class="fa-solid fa-calendar-day"></i></span>
                                <input type="number" name="date_paiement" value="{{ old('date_paiement') }}" min="1"
                                    max="31" step="1" placeholder="Ex : 5"
                                    style="width:100%; padding:14px 14px 14px 45px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                    onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            </div>
                            @error('date_paiement')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 4 é Médias --}}
            <div id="step-4" class="form-step" style="display:none;">
                <div style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <h3
                        style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:30px; display:flex; align-items:center; gap:12px;">
                        <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i class="fa-solid fa-images"
                                style="color:#ff5e14;"></i></span>
                        Photos & Médias
                    </h3>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:15px;">Photo
                                principale <span style="color:#ef4444;">*</span></label>
                            <div style="position:relative;">
                                <label for="photo_principale"
                                    style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:15px; height:250px; border:3px dashed #e5e7eb; border-radius:20px; cursor:pointer; background:#f9fafb; transition:all 0.3s;"
                                    onmouseover="this.style.borderColor='#ff5e14'; this.style.background='#fff7ed'"
                                    onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'">
                                    <div id="icon_principale" style="display:flex; flex-direction:column; align-items:center;">
                                        <i class="fa-solid fa-cloud-arrow-up"
                                            style="font-size:48px; color:#9ca3af; margin-bottom:10px;"></i>
                                        <span style="font-size:14px; font-weight:700; color:#6b7280;">Choisir la photo
                                            principale</span>
                                        <span style="font-size:12px; color:#9ca3af;">Recommandé : 1200x800px</span>
                                    </div>
                                    <img id="preview_principale" src="" alt=""
                                        style="display:none; position:absolute; inset:0; width:100%; height:100%; object-fit:cover; border-radius:17px;">
                                    <input type="file" id="photo_principale" name="photo_principale" accept="image/*"
                                        required style="display:none;"
                                        onchange="previewImage(this,'preview_principale','icon_principale')">
                                </label>
                                <button type="button" onclick="document.getElementById('photo_principale').click()"
                                    style="margin-top:10px; width:100%; padding:10px; background:#f3f4f6; border:none; border-radius:10px; font-size:13px; font-weight:700; color:#4b5563; cursor:pointer;">Changer
                                    l'image</button>
                            </div>
                            @error('photo_principale')
                                <p style="color:#ef4444; font-size:12px; margin-top:10px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:15px;">Photos
                                supplémentaires <span style="font-weight:400; color:#9ca3af;">(Max 10)</span></label>
                            <label for="photos_supplementaires"
                                style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; padding:30px; border:3px dashed #e5e7eb; border-radius:20px; cursor:pointer; background:#f9fafb; transition:all 0.3s;"
                                onmouseover="this.style.borderColor='#ff5e14'; this.style.background='#fff7ed'"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'">
                                <i class="fa-solid fa-photo-film" style="font-size:32px; color:#9ca3af;"></i>
                                <span style="font-size:14px; font-weight:700; color:#6b7280;">Galerie photos</span>
                                <span id="photos_count" style="font-size:12px; color:#9ca3af;">Aucune photo sélectionnée</span>
                                <input type="file" id="photos_supplementaires" name="photos_supplementaires[]"
                                    accept="image/*" multiple style="display:none;" onchange="previewMultiple(this)">
                            </label>
                            <div id="preview_supplementaires"
                                style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:12px; margin-top:15px;">
                            </div>
                            @error('photos_supplementaires')
                                <p style="color:#ef4444; font-size:12px; margin-top:10px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-top:40px;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:12px;">
                            Visite virtuelle / Vidéo 3D (Fichier Vidéo)
                        </label>
                        <div
                            style="border:2px dashed #e5e7eb; border-radius:15px; padding:20px; text-align:center; background:#f9fafb; position:relative; transition:all 0.2s;">
                            <input type="file" name="video_3d" accept="video/*"
                                style="position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index:2;"
                                onchange="previewVideo(this)">
                            <div id="video_placeholder">
                                <i class="fa-solid fa-cloud-arrow-up"
                                    style="font-size:32px; color:#9ca3af; margin-bottom:10px;"></i>
                                <p style="font-size:14px; color:#6b7280; margin:0;">
                                    Cliquez ou glissez votre vidéo ici
                                </p>
                                <p style="font-size:12px; color:#9ca3af; margin-top:5px;">
                                    MP4, WebM (Max 20MB)
                                </p>
                            </div>
                            <div id="video_preview_container"
                                style="display:none; align-items:center; justify-content:center; gap:15px;">
                                <i class="fa-solid fa-film" style="font-size:24px; color:#ff5e14;"></i>
                                <span id="video_filename" style="font-size:14px; font-weight:600; color:#111827;"></span>
                                <button type="button" onclick="resetVideoInput()"
                                    style="background:#fef2f2; color:#ef4444; border:none; padding:5px 10px; border-radius:8px; font-size:12px; cursor:pointer; font-weight:600;">
                                    Changer
                                </button>
                            </div>
                        </div>
                        @error('video_3d')
                            <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Navigation Controls --}}
            <div style="margin-top:40px; display:flex; justify-content:space-between; align-items:center;">
                <button type="button" id="btn-prev" onclick="prevStep()"
                    style="display:none; padding:15px 35px; border:2px solid #e5e7eb; border-radius:15px; font-size:15px; font-weight:700; color:#4b5563; background:white; cursor:pointer; transition:all 0.2s;"
                    onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#d1d5db'">
                    <i class="fa-solid fa-arrow-left" style="margin-right:10px;"></i> Précédent
                </button>

                <div style="margin-left:auto; display:flex; gap:15px;">
                    <a href="{{ route('agent.biens.index') }}"
                        style="padding:15px 30px; border-radius:15px; font-size:15px; font-weight:700; color:#6b7280; text-decoration:none; display:flex; align-items:center;">Annuler</a>

                    <button type="button" id="btn-next" onclick="nextStep()"
                        style="padding:15px 45px; background:#ff5e14; color:white; border:none; border-radius:15px; font-size:15px; font-weight:800; cursor:pointer; box-shadow:0 10px 15px -3px rgba(255,94,20,0.3); transition:all 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 20px -3px rgba(255,94,20,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(255,94,20,0.3)'">
                        Suivant <i class="fa-solid fa-arrow-right" style="margin-left:10px;"></i>
                    </button>

                    <button type="submit" id="btn-submit"
                        style="display:none; padding:15px 45px; background:#16a34a; color:white; border:none; border-radius:15px; font-size:15px; font-weight:800; cursor:pointer; box-shadow:0 10px 15px -3px rgba(22,163,74,0.3); transition:all 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 20px -3px rgba(22,163,74,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 15px -3px rgba(22,163,74,0.3)'">
                        <i class="fa-solid fa-floppy-disk" style="margin-right:10px;"></i> Enregistrer le bien
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const TOTAL_STEPS = 4;
        const STEP_ICONS = ['fa-circle-info', 'fa-tag', 'fa-coins', 'fa-images'];
        let currentStep = 1;
        let selectedFiles = new DataTransfer();

        function showStep(step) {
            for (let i = 1; i <= TOTAL_STEPS; i++) {
                document.getElementById('step-' + i).style.display = (i === step) ? 'block' : 'none';
                const circle = document.getElementById('step-circle-' + i);
                const label = document.getElementById('step-label-' + i);

                if (i < step) {
                    circle.style.background = '#16a34a';
                    circle.style.boxShadow = 'none';
                    circle.style.color = 'white';
                    circle.innerHTML = '<i class="fa-solid fa-check"></i>';
                    label.style.color = '#16a34a';
                } else if (i === step) {
                    circle.style.background = '#ff5e14';
                    circle.style.boxShadow = '0 0 0 4px rgba(255,94,20,0.1)';
                    circle.style.color = 'white';
                    circle.innerHTML = `<i class="fa-solid ${STEP_ICONS[i-1]}"></i>`;
                    label.style.color = '#ff5e14';
                } else {
                    circle.style.background = '#f3f4f6';
                    circle.style.boxShadow = 'none';
                    circle.style.color = '#9ca3af';
                    circle.innerHTML = `<i class="fa-solid ${STEP_ICONS[i-1]}"></i>`;
                    label.style.color = '#9ca3af';
                }

                if (i < TOTAL_STEPS) {
                    document.getElementById('step-line-' + i).style.background = (i < step) ? '#16a34a' : '#f3f4f6';
                }
            }
            document.getElementById('btn-prev').style.display = step > 1 ? 'inline-block' : 'none';
            document.getElementById('btn-next').style.display = step < TOTAL_STEPS ? 'inline-block' : 'none';
            document.getElementById('btn-submit').style.display = step === TOTAL_STEPS ? 'inline-block' : 'none';
        }

        function warn(msg) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: msg,
                confirmButtonColor: '#ff5e14',
                confirmButtonText: 'Compris'
            });
            return false;
        }

        function validateStep(step) {
            if (step === 1) {
                if (!document.querySelector('[name="type_bien"]').value) return warn('Le type de bien est requis.');
                if (!document.querySelector('[name="commune"]').value.trim()) return warn('La commune est requise.');
                if (!document.querySelector('[name="superficie"]').value) return warn('La superficie est requise.');
            }
            if (step === 2) {
                if (!document.getElementById('type_utilisation_hidden').value) return warn(
                    "Veuillez choisir un type d'utilisation.");
            }
            if (step === 3) {
                const l = document.querySelector('[name="loyer_mensuel"]').value;
                if (!l || parseFloat(l) < 0) return warn('Le loyer mensuel est obligatoire.');
            }
            return true;
        }

        function nextStep() {
            if (!validateStep(currentStep)) return;
            if (currentStep < TOTAL_STEPS) {
                currentStep++;
                showStep(currentStep);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function selectUtilisation(value) {
            if (value === 'autre') {
                Swal.fire({
                    title: "Type d'utilisation",
                    text: "Précisez l'usage de ce bien :",
                    input: 'text',
                    inputPlaceholder: 'Ex : Commerce, Entrepét, Studio photo...',
                    showCancelButton: true,
                    confirmButtonColor: '#ff5e14',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Valider',
                    cancelButtonText: 'Annuler',
                    inputValidator: v => {
                        if (!v || !v.trim()) return "Vous devez entrer un texte.";
                    }
                }).then(result => {
                    if (result.isConfirmed && result.value) setUtilisationCard('autre', result.value.trim());
                });
            } else {
                setUtilisationCard(value, null);
            }
        }

        function setUtilisationCard(value, customLabel) {
            const hidden = document.getElementById('type_utilisation_hidden');
            const badge = document.getElementById('custom_utilisation_badge');
            const badgeText = document.getElementById('custom_utilisation_text');

            ['habitation', 'bureau', 'autre'].forEach(v => {
                const card = document.getElementById('card-' + v);
                if (!card) return;
                card.style.border = '3px solid #f3f4f6';
                card.style.background = '#f9fafb';
                const iconDiv = card.querySelector('div');
                iconDiv.style.background = '#e5e7eb';
                iconDiv.querySelector('i').style.color = '#9ca3af';
                card.querySelector('span').style.color = '#374151';
            });

            const active = document.getElementById('card-' + value);
            if (active) {
                active.style.border = '3px solid #ff5e14';
                active.style.background = '#fff7ed';
                const iconDiv = active.querySelector('div');
                iconDiv.style.background = '#ff5e14';
                iconDiv.querySelector('i').style.color = 'white';
                active.querySelector('span').style.color = '#ff5e14';
            }

            if (value === 'autre' && customLabel) {
                hidden.value = customLabel;
                badgeText.textContent = 'Utilisation : ' + customLabel;
                badge.style.display = 'flex';
            } else {
                hidden.value = value;
                badge.style.display = 'none';
            }
        }

        function changeCustomUtilisation() {
            selectUtilisation('autre');
        }

        function calculateMontantTotal() {
            const loyerField = document.querySelector('[name="loyer_mensuel"]');
            const avanceField = document.querySelector('[name="avance"]');
            const cautionField = document.querySelector('[name="caution"]');
            const fraisAgenceField = document.querySelector('[name="frais_agence"]');
            const montantTotalField = document.querySelector('[name="montant_total"]');

            if (!loyerField || !avanceField || !cautionField || !montantTotalField) return;

            const loyer = parseFloat(loyerField.value) || 0;
            const avance = parseFloat(avanceField.value) || 0;
            const caution = parseFloat(cautionField.value) || 0;
            const fraisAgence = 1;

            montantTotalField.value = (loyer * (avance + caution + fraisAgence)).toFixed(0);
        }

        function initFinanceCalculator() {
            ['loyer_mensuel', 'avance', 'caution'].forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) field.addEventListener('input', calculateMontantTotal);
            });
            calculateMontantTotal();
        }

        function previewImage(input, previewId, iconId) {
            const preview = document.getElementById(previewId);
            const icon = document.getElementById(iconId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    icon.style.opacity = '0';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewMultiple(input) {
            const container = document.getElementById('preview_supplementaires');
            const counter = document.getElementById('photos_count');
            if (input.files && input.files.length > 0) {
                if (input.files !== selectedFiles.files) {
                    Array.from(input.files).forEach(file => {
                        if (selectedFiles.items.length < 10) selectedFiles.items.add(file);
                    });
                }
                input.files = selectedFiles.files;
            }
            container.innerHTML = '';
            const files = Array.from(selectedFiles.files);
            if (counter) counter.textContent = files.length + ' photo(s) sélectionnée(s)';
            files.forEach((file, index) => {
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative; border-radius:14px; overflow:hidden; border:2px solid #e5e7eb; width:100%; height:140px;';
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.cssText = 'width:100%; height:100%; object-fit:cover; display:block;';
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '<i class="fa-solid fa-times"></i>';
                removeBtn.type = 'button';
                removeBtn.style.cssText = 'position:absolute; top:5px; right:5px; background:#ef4444; color:white; border:none; border-radius:50%; width:22px; height:22px; cursor:pointer;';
                removeBtn.onclick = () => removePhoto(index);
                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                container.appendChild(wrapper);
            });
        }

        function removePhoto(index) {
            const dt = new DataTransfer();
            const { files } = selectedFiles;
            for (let i = 0; i < files.length; i++) if (i !== index) dt.items.add(files[i]);
            selectedFiles = dt;
            const input = document.getElementById('photos_supplementaires');
            input.files = selectedFiles.files;
            previewMultiple(input);
        }

        function previewVideo(input) {
            const placeholder = document.getElementById('video_placeholder');
            const previewContainer = document.getElementById('video_preview_container');
            const filenameSpan = document.getElementById('video_filename');
            if (input.files && input.files[0]) {
                placeholder.style.display = 'none';
                previewContainer.style.display = 'flex';
                filenameSpan.textContent = input.files[0].name;
            }
        }

        function resetVideoInput() {
            const input = document.querySelector('input[name="video_3d"]');
            input.value = '';
            document.getElementById('video_placeholder').style.display = 'block';
            document.getElementById('video_preview_container').style.display = 'none';
        }

        initFinanceCalculator();
        showStep(1);
    </script>
@endsection
