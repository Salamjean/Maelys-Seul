@extends('admin.layouts.app')

@section('title', 'Modifier le bien ' . $bien->reference)
@section('page-title', 'Modifier le bien')

@section('content')

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div style="padding: 0 5%;">
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
        <form method="POST" action="{{ route('admin.biens.update', $bien->id) }}" enctype="multipart/form-data" id="main-form">
            @csrf
            @method('PUT')

            {{-- STEP 1 — Informations générales --}}
            <div id="step-1" class="form-step">
                <div
                    style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                        <h3
                            style="font-size:20px; font-weight:800; color:#02245b; margin:0; display:flex; align-items:center; gap:12px;">
                            <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i
                                    class="fa-solid fa-circle-info" style="color:#ff5e14;"></i></span>
                            Modifier : {{ $bien->reference }}
                        </h3>
                        <div>
                            <label style="font-size:14px; font-weight:700; color:#374151; margin-right:10px;">Statut :</label>
                            <select name="statut" 
                                style="padding:8px 15px; border-radius:10px; border:2px solid #f3f4f6; font-weight:700; outline:none;"
                                onfocus="this.style.borderColor='#ff5e14'">
                                <option value="actif" {{ old('statut', $bien->statut) == 'actif' ? 'selected' : '' }}>ACTIF (Public)</option>
                                <option value="inactif" {{ old('statut', $bien->statut) == 'inactif' ? 'selected' : '' }}>INACTIF</option>
                                <option value="loué" {{ old('statut', $bien->statut) == 'loué' ? 'selected' : '' }}>LOUÉ</option>
                            </select>
                        </div>
                    </div>

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
                                    <option value="{{ $val }}" {{ old('type_bien', $bien->type_bien) == $val ? 'selected' : '' }}>
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
                                / Typologie <span style="color:#ef4444;">*</span></label>
                            <select name="typologie"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                                <option value="">-- Sélectionner la typologie --</option>
                                @foreach(['studio' => 'Studio', 'f1' => 'F1', 'f2' => 'F2', 'f3' => 'F3', 'f4' => 'F4', 'f5_plus' => 'F5 et plus', 'duplex' => 'Duplex', 'triplex' => 'Triplex', 'villa' => 'Villa', 'chambre_salon' => 'Chambre salon', 'autre' => 'Autre'] as $v => $l)
                                    <option value="{{ $v }}" {{ old('typologie', $bien->typologie) == $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
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
                                <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="commune" value="{{ old('commune', $bien->commune) }}"
                                placeholder="Ex : Hydra, Alger"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                            @error('commune')
                                <p style="color:#ef4444; font-size:12px; margin-top:6px;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Lien Google Maps</label>
                            <div style="position:relative;">
                                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $bien->google_maps_url) }}"
                                    placeholder="Lien Google Maps"
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
                            <input type="number" name="superficie" value="{{ old('superficie', (int)$bien->superficie) }}" min="1"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                        </div>
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Pièces</label>
                            <input type="number" name="nb_pieces" value="{{ old('nb_pieces', $bien->nb_pieces) }}" min="0"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                        </div>
                        <div>
                            <label
                                style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Toilettes</label>
                            <input type="number" name="nb_toilettes" value="{{ old('nb_toilettes', $bien->nb_toilettes) }}" min="0"
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; font-size:15px; color:#111827; background:#f9fafb; outline:none; transition:all 0.2s;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                        </div>
                        <div style="display:flex; align-items:flex-end;">
                            <div
                                style="display:flex; align-items:center; gap:10px; background:#f8fafc; padding:10px 15px; border-radius:12px; border:2px solid #f3f4f6; width:100%; min-height:52px; box-sizing:border-box;">
                                <input type="checkbox" name="garage" id="garage" value="1"
                                    {{ old('garage', $bien->garage) ? 'checked' : '' }}
                                    style="width:20px; height:20px; accent-color:#ff5e14; cursor:pointer;">
                                <label for="garage"
                                    style="font-size:14px; font-weight:600; color:#374151; cursor:pointer;">Garage
                                    inclus</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2 — Utilisation & Description --}}
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
                            style="display:block; font-size:15px; font-weight:700; color:#374151; margin-bottom:20px; text-align:center;">Type d'utilisation</label>

                        @php
                            $oldUtil = old('type_utilisation', $bien->type_utilisation);
                            $isCustom = $oldUtil !== '' && !in_array($oldUtil, ['habitation', 'bureau']);
                        @endphp

                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; max-width:900px; margin:0 auto;">
                            <label id="card-habitation"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $oldUtil === 'habitation' ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $oldUtil === 'habitation' ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s;"
                                onclick="selectUtilisation('habitation')">
                                <div style="width:60px; height:60px; background:{{ $oldUtil === 'habitation' ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-house" style="font-size:28px; color:{{ $oldUtil === 'habitation' ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span style="font-size:16px; font-weight:700; color:{{ $oldUtil === 'habitation' ? '#ff5e14' : '#374151' }};">Habitation</span>
                            </label>

                            <label id="card-bureau"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $oldUtil === 'bureau' ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $oldUtil === 'bureau' ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s;"
                                onclick="selectUtilisation('bureau')">
                                <div style="width:60px; height:60px; background:{{ $oldUtil === 'bureau' ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-briefcase" style="font-size:28px; color:{{ $oldUtil === 'bureau' ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span style="font-size:16px; font-weight:700; color:{{ $oldUtil === 'bureau' ? '#ff5e14' : '#374151' }};">Bureau</span>
                            </label>

                            <label id="card-autre"
                                style="display:flex; flex-direction:column; align-items:center; gap:15px; padding:35px 20px;
                                           border:3px solid {{ $isCustom ? '#ff5e14' : '#f3f4f6' }};
                                           border-radius:20px; cursor:pointer;
                                           background:{{ $isCustom ? '#fff7ed' : '#f9fafb' }};
                                           transition:all 0.3s;"
                                onclick="selectUtilisation('autre')">
                                <div style="width:60px; height:60px; background:{{ $isCustom ? '#ff5e14' : '#e5e7eb' }}; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-ellipsis" style="font-size:28px; color:{{ $isCustom ? 'white' : '#9ca3af' }};"></i>
                                </div>
                                <span style="font-size:16px; font-weight:700; color:{{ $isCustom ? '#ff5e14' : '#374151' }};">Autre</span>
                            </label>
                        </div>

                        <input type="hidden" id="type_utilisation_hidden" name="type_utilisation" value="{{ old('type_utilisation', $bien->type_utilisation) }}">

                        <div id="custom_utilisation_badge"
                            style="display:{{ $isCustom ? 'flex' : 'none' }}; align-items:center; justify-content:center; gap:12px;
                                       margin:25px auto 0; padding:15px 25px; max-width:400px;
                                       background:#fff7ed; border:2px dashed #fed7aa;
                                       border-radius:15px; font-size:15px; color:#c2410c;">
                            <i class="fa-solid fa-pencil"></i>
                            <span id="custom_utilisation_text" style="font-weight:700;">{{ $isCustom ? 'Utilisation : ' . $oldUtil : '' }}</span>
                            <button type="button" onclick="changeCustomUtilisation()"
                                style="margin-left:10px; background:#ff5e14; border:none; color:white; padding:5px 12px; border-radius:8px; cursor:pointer; font-size:12px; font-weight:700;">Modifier</button>
                        </div>
                    </div>

                    <div style="max-width:900px; margin:0 auto;">
                        <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:12px;">Description</label>
                        <textarea name="description" rows="8"
                            style="width:100%; padding:18px; border:2px solid #f3f4f6; border-radius:15px; font-size:15px; background:#f9fafb; outline:none; resize:none; transition:all 0.2s;"
                            onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">{{ old('description', $bien->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- STEP 3 — Finances --}}
            <div id="step-3" class="form-step" style="display:none;">
                <div style="background:white; border-radius:20px; padding:40px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);">
                    <h3
                        style="font-size:20px; font-weight:800; color:#02245b; margin-bottom:30px; display:flex; align-items:center; gap:12px;">
                        <span style="background:#fff7ed; padding:10px; border-radius:12px;"><i class="fa-solid fa-coins"
                                style="color:#ff5e14;"></i></span>
                        Informations financières (FCFA)
                    </h3>

                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:30px;">
                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Loyer mensuel <span style="color:#ef4444;">*</span></label>
                            <input type="number" name="loyer_mensuel" value="{{ old('loyer_mensuel', $bien->loyer_mensuel) }}" 
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; background:#f9fafb; outline:none;"
                                onfocus="this.style.borderColor='#ff5e14'; this.style.background='white'">
                        </div>
                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Avance (mois)</label>
                            <input type="number" name="avance" value="{{ old('avance', $bien->avance) }}" 
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; background:#f9fafb; outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Caution (mois)</label>
                            <input type="number" name="caution" value="{{ old('caution', $bien->caution) }}" 
                                style="width:100%; padding:14px; border:2px solid #f3f4f6; border-radius:12px; background:#f9fafb; outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:10px;">Total Estimé</label>
                            <input type="number" name="montant_total" value="{{ old('montant_total', $bien->montant_total) }}" readonly
                                style="width:100%; padding:14px; border:2px solid #e5e7eb; border-radius:12px; background:#e5e7eb; color:#4b5563;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 4 — Médias --}}
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
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:15px;">Photo principale</label>
                            <div style="position:relative;">
                                <label for="photo_principale"
                                    style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:250px; border:3px dashed #e5e7eb; border-radius:20px; cursor:pointer; background:#f9fafb; overflow:hidden;">
                                    <img id="preview_principale" src="{{ Storage::url($bien->photo_principale) }}" 
                                        style="width:100%; height:100%; object-fit:cover;">
                                    <input type="file" id="photo_principale" name="photo_principale" accept="image/*" 
                                        style="display:none;" onchange="previewImage(this,'preview_principale')">
                                </label>
                                <p style="font-size:12px; color:#9ca3af; margin-top:8px;">Cliquez pour changer l'image</p>
                            </div>
                        </div>

                        <div>
                            <label style="display:block; font-size:14px; font-weight:600; color:#374151; margin-bottom:15px;">Visite 3D / Vidéo</label>
                            <label style="display:block; border:2px dashed #e5e7eb; padding:20px; text-align:center; border-radius:15px; background:#f9fafb; cursor:pointer;">
                                @if($bien->video_3d)
                                    <i class="fa-solid fa-film text-secondary text-2xl mb-2"></i>
                                    <p style="font-size:13px; font-weight:600;">Vidéo actuelle présente</p>
                                @else
                                    <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-2xl mb-2"></i>
                                    <p style="font-size:13px;">Ajouter une vidéo 3D</p>
                                @endif
                                <input type="file" name="video_3d" accept="video/*" style="display:none;">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Controls --}}
            <div style="margin-top:50px; display:flex; justify-content:space-between; align-items:center; padding:20px; background:#f8fafc; border-radius:20px; border:1px solid #e2e8f0;">
                {{-- Gauche : Annuler --}}
                <a href="{{ route('admin.biens.index') }}"
                   style="padding:15px 30px; border-radius:15px; font-size:14px; font-weight:700; color:#ef4444; background:#fef2f2; text-decoration:none; display:flex; align-items:center; gap:8px; border:1px solid #fee2e2; transition: all 0.2s;"
                   onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <i class="fa-solid fa-times"></i> Annuler
                </a>

                {{-- Droite : Navigation --}}
                <div style="display:flex; gap:15px;">
                    <button type="button" id="btn-prev" onclick="prevStep()"
                        style="display:none; padding:15px 35px; border:2px solid #e5e7eb; border-radius:15px; font-size:14px; font-weight:700; color:#4b5563; background:white; cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='#f9fafb'">
                        <i class="fa-solid fa-arrow-left" style="margin-right:10px;"></i> Précédent
                    </button>

                    <button type="button" id="btn-next" onclick="nextStep()"
                        style="padding:15px 45px; background:#ff5e14; color:white; border:none; border-radius:15px; font-size:15px; font-weight:800; cursor:pointer; box-shadow:0 10px 15px -3px rgba(255,94,20,0.3); transition:all 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        Suivant <i class="fa-solid fa-arrow-right" style="margin-left:10px;"></i>
                    </button>

                    <button type="submit" id="btn-submit"
                        style="display:none; padding:15px 45px; background:#16a34a; color:white; border:none; border-radius:15px; font-size:15px; font-weight:800; cursor:pointer; box-shadow:0 10px 15px -3px rgba(22,163,74,0.3); transition:all 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                        <i class="fa-solid fa-floppy-disk" style="margin-right:10px;"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const TOTAL_STEPS = 4;

        function showStep(s) {
            for(let i=1; i<=TOTAL_STEPS; i++) {
                document.getElementById('step-' + i).style.display = (i === s) ? 'block' : 'none';
                const circle = document.getElementById('step-circle-' + i);
                circle.style.background = (i <= s) ? (i === s ? '#ff5e14' : '#16a34a') : '#f3f4f6';
                circle.style.color = (i <= s) ? 'white' : '#9ca3af';
            }
            document.getElementById('btn-prev').style.display = s > 1 ? 'inline-block' : 'none';
            document.getElementById('btn-next').style.display = s < TOTAL_STEPS ? 'inline-block' : 'none';
            document.getElementById('btn-submit').style.display = s === TOTAL_STEPS ? 'inline-block' : 'none';
        }

        function nextStep() { if(currentStep < TOTAL_STEPS) { currentStep++; showStep(currentStep); window.scrollTo(0, 0); } }
        function prevStep() { if(currentStep > 1) { currentStep--; showStep(currentStep); window.scrollTo(0, 0); } }

        function selectUtilisation(v) {
            if(v === 'autre') {
                Swal.fire({
                    title: 'Utilisation personnalisée',
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonColor: '#ff5e14',
                }).then(res => { if(res.isConfirmed) setUtilValue(res.value); });
            } else { setUtilValue(v); }
        }

        function setUtilValue(v) {
            document.getElementById('type_utilisation_hidden').value = v;
            // Mise à jour visuelle simple pour la démo
            const cards = ['habitation', 'bureau', 'autre'];
            cards.forEach(c => {
                const card = document.getElementById('card-' + c);
                card.style.borderColor = (c === v || (c==='autre' && !['habitation','bureau'].includes(v))) ? '#ff5e14' : '#f3f4f6';
            });
        }

        function previewImage(input, id) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => { document.getElementById(id).src = e.target.result; };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Calcul auto
        function calc() {
            const loyer = parseFloat(document.querySelector('[name="loyer_mensuel"]').value) || 0;
            const avance = parseFloat(document.querySelector('[name="avance"]').value) || 0;
            const caution = parseFloat(document.querySelector('[name="caution"]').value) || 0;
            document.querySelector('[name="montant_total"]').value = Math.round(loyer * (avance + caution + 1));
        }

        document.querySelectorAll('input[type="number"]').forEach(i => i.addEventListener('input', calc));
    </script>
@endsection
