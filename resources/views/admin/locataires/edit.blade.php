@extends('admin.layouts.app')

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

        <form action="{{ route('admin.locataires.update', $locataire->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Identité</h3>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nom</label>
                        <input type="text" name="name" value="{{ old('name', $locataire->name) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('name') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Prénoms</label>
                        <input type="text" name="prenoms" value="{{ old('prenoms', $locataire->prenoms) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('prenoms') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $locataire->email) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('email') border-red-500 @enderror">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Contact</label>
                        <input type="text" name="contact" value="{{ old('contact', $locataire->contact) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm @error('contact') border-red-500 @enderror">
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Logement</h3>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Profession</label>
                        <input type="text" name="profession" value="{{ old('profession', $locataire->profession) }}" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Adresse</label>
                        <textarea name="adresse" required rows="3" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm">{{ old('adresse', $locataire->adresse) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Bien Attribué</label>
                        <select name="bien_id" required class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm">
                            @foreach($biens as $bien)
                                <option value="{{ $bien->id }}" {{ old('bien_id', $locataire->bien_id) == $bien->id ? 'selected' : '' }}>
                                    {{ $bien->reference }} - {{ $bien->commune }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-50 flex justify-end gap-4">
                <a href="{{ route('admin.locataires.index') }}" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-500 font-black text-xs uppercase tracking-widest">Annuler</a>
                <button type="submit" class="px-10 py-4 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 transition-all">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
