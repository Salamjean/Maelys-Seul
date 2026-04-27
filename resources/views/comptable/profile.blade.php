@extends('comptable.layouts.app')

@section('title', 'Mon Profil - Comptabilité')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-12">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-primary italic uppercase tracking-tighter">Mon <span class="text-secondary">Profil</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[2px] mt-1">Gérez vos informations de comptable</p>
        </div>
        <div class="w-16 h-16 bg-primary/5 rounded-[2rem] flex items-center justify-center text-primary text-2xl shadow-inner border border-primary/10">
            <i class="fa-solid fa-user-shield"></i>
        </div>
    </div>

    <form action="{{ route('comptable.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Photo & Identity --}}
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm text-center">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-8">Photo de Profil</h3>
                    
                    <div class="relative w-48 h-48 mx-auto mb-6 group">
                        <div class="w-full h-full rounded-[3rem] overflow-hidden border-4 border-gray-50 shadow-xl relative">
                            @if($admin->photo)
                                <img src="{{ Storage::url($admin->photo) }}" alt="Profil" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-primary flex items-center justify-center text-white text-5xl font-black italic">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <label class="absolute -bottom-2 -right-2 w-12 h-12 bg-secondary text-white rounded-2xl flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform border-4 border-white">
                            <i class="fa-solid fa-camera"></i>
                            <input type="file" name="photo" class="hidden" onchange="previewImage(this)">
                        </label>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Format JPG, PNG (Max 2Mo)</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest border-b border-gray-50 pb-4">Identité</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Nom</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Prénoms</label>
                            <input type="text" name="prenoms" value="{{ old('prenoms', $admin->prenoms) }}" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Contact & Security --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest border-b border-gray-50 pb-4">Coordonnées & Sécurité</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Adresse Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required readonly
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Numéro de Contact</label>
                            <input type="text" name="contact" value="{{ old('contact', $admin->contact) }}" required
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm text-primary">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Nouveau mot de passe</label>
                            <input type="password" name="new_password" placeholder="Laisser vide pour ne pas changer"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest ml-1">Confirmation</label>
                            <input type="password" name="new_password_confirmation" placeholder="Confirmer le nouveau passe"
                                   class="w-full bg-gray-50 border-2 border-gray-50 focus:border-secondary h-14 px-6 rounded-2xl outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 mt-6">
                        <div class="bg-primary/5 p-6 rounded-[2rem] border-2 border-dashed border-primary/10">
                            <label class="text-[10px] font-black uppercase text-primary mb-3 block tracking-widest text-center">Validation requise</label>
                            <input type="password" name="current_password" required placeholder="Saisir votre mot de passe actuel"
                                   class="w-full bg-white border-2 border-primary/20 focus:border-primary h-14 px-6 rounded-2xl outline-none transition-all font-black text-sm text-center shadow-sm placeholder:font-bold placeholder:text-primary/30">
                            <p class="text-[9px] text-primary/40 font-bold text-center mt-3 uppercase tracking-tighter italic">Indispensable pour enregistrer les modifications</p>
                            @error('current_password') <p class="text-red-500 text-[10px] font-black mt-2 text-center">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="h-16 px-12 bg-primary text-white rounded-[2rem] font-black uppercase text-xs tracking-[2px] hover:bg-secondary hover:shadow-xl hover:shadow-secondary/20 transition-all flex items-center gap-4 group">
                        <span>Mettre à jour le profil</span>
                        <i class="fa-solid fa-floppy-disk text-sm group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = input.closest('.relative').querySelector('img');
                if (imgContainer) {
                    imgContainer.src = e.target.result;
                } else {
                    const div = input.closest('.relative').querySelector('.bg-primary');
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    div.classList.remove('bg-primary', 'flex', 'items-center', 'justify-center', 'text-white', 'text-5xl', 'font-black', 'italic');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
