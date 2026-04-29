@extends('admin.layouts.app')

@section('title', 'Ajouter un locataire')
@section('page-title', 'Nouveau Locataire')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center gap-4 bg-gradient-to-r from-gray-50 to-white">
            <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-white shadow-lg shadow-secondary/20">
                <i class="fa-solid fa-user-plus text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-primary italic">Inscription Locataire</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Saisissez les informations contractuelles</p>
            </div>
        </div>

        <form action="{{ route('admin.locataires.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                {{-- Identité --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Identité</h3>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nom</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('name') border-red-500 @enderror" placeholder="Ex: KOUASSI">
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Prénoms</label>
                        <input type="text" name="prenoms" value="{{ old('prenoms') }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('prenoms') border-red-500 @enderror" placeholder="Ex: Jean Marc">
                        @error('prenoms') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email (Obligatoire)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('email') border-red-500 @enderror" placeholder="client@exemple.com">
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Contact (Téléphone)</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('contact') border-red-500 @enderror" placeholder="07 00 00 00 00">
                        @error('contact') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Situation --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Situation & Logement</h3>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession') }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('profession') border-red-500 @enderror" placeholder="Ex: Informaticien">
                        @error('profession') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Adresse actuelle</label>
                        <textarea name="adresse" required rows="3" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('adresse') border-red-500 @enderror" placeholder="Quartier, Rue...">{{ old('adresse') }}</textarea>
                        @error('adresse') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Bien Attribué</label>
                        @if(isset($selectedBien) && $selectedBien)
                            <div class="p-4 bg-primary/5 border-2 border-primary/10 rounded-2xl flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-black text-primary">{{ $selectedBien->reference }}</p>
                                    <p class="text-[11px] text-gray-400 font-bold italic">{{ $selectedBien->commune }}</p>
                                </div>
                                <div class="px-3 py-1 bg-primary text-white text-[10px] font-black rounded-lg uppercase">Sélectionné</div>
                                <input type="hidden" name="bien_id" value="{{ $selectedBien->id }}">
                            </div>
                        @else
                            <select name="bien_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm appearance-none @error('bien_id') border-red-500 @enderror">
                                <option value="">Sélectionner le logement</option>
                                @foreach($biens as $bien)
                                    <option value="{{ $bien->id }}" {{ old('bien_id') == $bien->id ? 'selected' : '' }}>{{ $bien->reference }} - {{ $bien->commune }} ({{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} FCFA)</option>
                                @endforeach
                            </select>
                            @error('bien_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Agent de Recouvrement (État des Lieux Entrée)</label>
                        <select name="agent_etat_lieux" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm appearance-none @error('agent_etat_lieux') border-red-500 @enderror">
                            <option value="">Sélectionner un agent de recouvrement (Optionnel)</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_etat_lieux') == $agent->id ? 'selected' : '' }}>{{ $agent->name }} {{ $agent->prenoms }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1 italic">Un code OTP sera envoyé au locataire lors de l'état des lieux.</p>
                        @error('agent_etat_lieux') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="space-y-8">
                <h3 class="text-sm font-black text-primary uppercase tracking-[2px] border-l-4 border-primary pl-3">Documents Justificatifs</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Obligatoires --}}
                    <div class="p-6 bg-orange-50/50 rounded-3xl border border-orange-100">
                        <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-4">Documents Obligatoires</p>
                        
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Pièce d'identité</label>
                                <input type="file" name="piece_identite" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white transition-all @error('piece_identite') border-red-500 @enderror">
                                @error('piece_identite') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Contrat de bail</label>
                                <input type="file" name="contrat_bail" required class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white transition-all @error('contrat_bail') border-red-500 @enderror">
                                @error('contrat_bail') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Optionnels Travail --}}
                    <div class="p-6 bg-blue-50/30 rounded-3xl border border-blue-50">
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-4">Documents Professionnels (Optionnels)</p>
                        
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Attestation de travail</label>
                                <input type="file" name="attestation_travail" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Bulletin de salaire</label>
                                <input type="file" name="bulletin_salaire" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Autres Documents --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Document Extra 1</label>
                        <input type="file" name="doc_extra_1" class="block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-100">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Document Extra 2</label>
                        <input type="file" name="doc_extra_2" class="block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-100">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Document Extra 3</label>
                        <input type="file" name="doc_extra_3" class="block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-100">
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-4 border-top pt-8">
                <button type="reset" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-500 font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                    Vider les champs
                </button>
                <button type="submit" class="px-10 py-4 rounded-2xl bg-secondary text-white font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-xl shadow-secondary/20 transition-all active:scale-95">
                    Enregistrer le locataire
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
