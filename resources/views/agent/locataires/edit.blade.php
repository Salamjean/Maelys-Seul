@extends('agent.layouts.app')

@section('title', 'Modifier le locataire')
@section('page-title', 'Édition Locataire')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center gap-4 bg-gradient-to-r from-gray-50 to-white">
            <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/20">
                <i class="fa-solid fa-user-pen text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-primary italic">Modifier le Dossier</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Mise à jour des informations de {{ $locataire->name }}</p>
            </div>
        </div>

        <form action="{{ route('agent.locataires.update', $locataire->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                {{-- Identité --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Identité</h3>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nom</label>
                        <input type="text" name="name" value="{{ old('name', $locataire->name) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Prénoms</label>
                        <input type="text" name="prenoms" value="{{ old('prenoms', $locataire->prenoms) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('prenoms') border-red-500 @enderror">
                        @error('prenoms') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $locataire->email) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Contact (Téléphone)</label>
                        <input type="text" name="contact" value="{{ old('contact', $locataire->contact) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('contact') border-red-500 @enderror">
                        @error('contact') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Situation --}}
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Situation & Logement</h3>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession', $locataire->profession) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('profession') border-red-500 @enderror">
                        @error('profession') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Adresse actuelle</label>
                        <textarea name="adresse" required rows="3" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('adresse') border-red-500 @enderror">{{ old('adresse', $locataire->adresse) }}</textarea>
                        @error('adresse') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Bien Attribué</label>
                        <select name="bien_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm appearance-none @error('bien_id') border-red-500 @enderror">
                            @foreach($biens as $bien)
                                <option value="{{ $bien->id }}" {{ old('bien_id', $locataire->bien_id) == $bien->id ? 'selected' : '' }}>
                                    {{ $bien->reference }} - {{ $bien->commune }} ({{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} FCFA)
                                    @if($bien->id == $locataire->bien_id) (Actuel) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('bien_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="space-y-8">
                <h3 class="text-sm font-black text-primary uppercase tracking-[2px] border-l-4 border-primary pl-3">Documents Justificatifs <small class="text-[10px] normal-case opacity-50">(Laissez vide pour conserver l'ancien)</small></h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Obligatoires --}}
                    <div class="p-6 bg-orange-50/50 rounded-3xl border border-orange-100">
                        <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-4">Documents Principaux</p>
                        
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Pièce d'identité</label>
                                <input type="file" name="piece_identite" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white transition-all">
                                @if($locataire->piece_identite)
                                    <a href="{{ Storage::url($locataire->piece_identite) }}" target="_blank" class="text-[9px] font-bold text-primary mt-1 inline-block underline italic">Voir l'actuel</a>
                                @endif
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Contrat de bail</label>
                                <input type="file" name="contrat_bail" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white transition-all">
                                @if($locataire->contrat_bail)
                                    <a href="{{ Storage::url($locataire->contrat_bail) }}" target="_blank" class="text-[9px] font-bold text-primary mt-1 inline-block underline italic">Voir l'actuel</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Optionnels --}}
                    <div class="p-6 bg-blue-50/30 rounded-3xl border border-blue-50">
                        <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-4">Documents Professionnels</p>
                        
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Attestation de travail</label>
                                <input type="file" name="attestation_travail" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 transition-all">
                                @if($locataire->attestation_travail)
                                    <a href="{{ Storage::url($locataire->attestation_travail) }}" target="_blank" class="text-[9px] font-bold text-primary mt-1 inline-block underline italic">Voir l'actuel</a>
                                @endif
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 mb-2">Bulletin de salaire</label>
                                <input type="file" name="bulletin_salaire" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-gray-200 file:text-gray-700 transition-all">
                                @if($locataire->bulletin_salaire)
                                    <a href="{{ Storage::url($locataire->bulletin_salaire) }}" target="_blank" class="text-[9px] font-bold text-primary mt-1 inline-block underline italic">Voir l'actuel</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end gap-4 border-top pt-8">
                <a href="{{ route('agent.locataires.index') }}" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-500 font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center justify-center">
                    Annuler
                </a>
                <button type="submit" class="px-10 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-xl shadow-primary/20 transition-all active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
