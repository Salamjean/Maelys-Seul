@extends('admin.layouts.app')

@section('title', 'Ré-attribuer un bien')
@section('page-title', 'Ré-attribution Locataire')

@section('content')
<div class="w-full">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex items-center gap-4 bg-gradient-to-r from-gray-50 to-white">
                <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-white shadow-lg shadow-secondary/20">
                    <i class="fa-solid fa-house-chimney-medical text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-primary italic">Ré-attribution</h2>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Affecter un nouveau bien à {{ $locataire->name }} {{ $locataire->prenoms }}</p>
                </div>
            </div>

            <form action="{{ route('admin.locataires.reassign_process', $locataire->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <div class="space-y-8">
                    <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                        <p class="text-xs text-blue-700 font-bold leading-relaxed">
                            Ce locataire avait déménagé le <strong>{{ $locataire->moved_out_at->format('d/m/Y') }}</strong>. 
                        </p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Choisir le nouveau logement</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse($biens as $bien)
                                <label class="relative flex flex-col items-center p-6 bg-gray-50 rounded-3xl border-2 border-transparent hover:border-secondary cursor-pointer transition-all group has-[:checked]:border-secondary has-[:checked]:bg-white text-center">
                                    <input type="radio" name="bien_id" value="{{ $bien->id }}" required class="sr-only peer">
                                    <div class="w-14 h-14 rounded-2xl bg-white border-2 border-gray-100 flex items-center justify-center text-gray-300 group-hover:text-secondary peer-checked:text-secondary peer-checked:border-secondary transition-all mb-4 shadow-sm">
                                        <i class="fa-solid fa-house-chimney text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-black text-primary uppercase tracking-tighter">{{ $bien->reference }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $bien->commune }}</p>
                                        <p class="text-xs font-black text-secondary mt-2">{{ number_format($bien->loyer_mensuel, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition-all scale-0 peer-checked:scale-100">
                                        <i class="fa-solid fa-circle-check text-secondary text-xl"></i>
                                    </div>
                                </label>
                            @empty
                                <div class="p-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 col-span-full">
                                    <p class="text-xs font-bold text-gray-400">Aucun bien disponible actuellement.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Nouveau Contrat de Bail (Optionnel)</label>
                        <div class="p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary transition-all relative group">
                            <input type="file" name="contrat_bail" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-file-pdf text-2xl text-gray-300 group-hover:text-primary transition-colors"></i>
                                <p class="text-[11px] font-bold text-gray-400 group-hover:text-primary">Glissez le nouveau contrat ou cliquez ici</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-end gap-4">
                    <a href="{{ route('admin.locataires.moved_out') }}" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-500 font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all">
                        Retour
                    </a>
                    <button type="submit" class="px-10 py-4 rounded-2xl bg-secondary text-white font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-xl shadow-secondary/20 transition-all active:scale-95">
                        Valider la ré-attribution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
