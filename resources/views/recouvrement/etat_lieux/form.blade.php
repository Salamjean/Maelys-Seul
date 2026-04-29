@extends('recouvrement.layouts.app')

@section('title', 'Saisie de l\'État des Lieux')
@section('page-title', 'Formulaire d\'État des Lieux')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 bg-gray-50/20">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-primary italic">État des Lieux : {{ ucfirst($etatLieu->type) }}</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Locataire : {{ $etatLieu->user->name }} | Bien : {{ $bien->reference }}</p>
            </div>
        </div>
        <p class="text-sm text-gray-600 font-bold">Veuillez inspecter chaque pièce du bien et noter son état.</p>
    </div>

    <form action="{{ route('recouvrement.etat_lieux.store', $etatLieu->id) }}" method="POST" class="p-8 space-y-8">
        @csrf
        
        <div class="space-y-6">
            <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Inspection par pièce</h3>
            
            @foreach($pieces as $piece)
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 mb-6">
                    <h4 class="font-black text-primary text-xl mb-4 border-b pb-2">{{ $piece }}</h4>
                    <div class="space-y-4">
                    @foreach($subElements as $element)
                        @php $obsId = 'obs_' . Str::slug($piece) . '_' . Str::slug($element); @endphp
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="w-full md:w-1/4">
                                <p class="font-bold text-gray-700">{{ $element }}</p>
                            </div>
                            
                            <div class="w-full md:w-2/4 flex items-center gap-2 bg-gray-100/50 p-1 rounded-xl">
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="pieces[{{ $piece }}][{{ $element }}][etat]" value="bon" required onchange="checkEtat(this, '{{ $obsId }}')" class="peer sr-only">
                                    <div class="text-center px-3 py-2 rounded-lg text-xs font-black uppercase tracking-wider text-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:shadow-sm transition-all hover:text-gray-700">Bon</div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="pieces[{{ $piece }}][{{ $element }}][etat]" value="moyen" required onchange="checkEtat(this, '{{ $obsId }}')" class="peer sr-only">
                                    <div class="text-center px-3 py-2 rounded-lg text-xs font-black uppercase tracking-wider text-gray-500 peer-checked:bg-orange-500 peer-checked:text-white peer-checked:shadow-sm transition-all hover:text-gray-700">Moyen</div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="pieces[{{ $piece }}][{{ $element }}][etat]" value="mauvais" required onchange="checkEtat(this, '{{ $obsId }}')" class="peer sr-only">
                                    <div class="text-center px-3 py-2 rounded-lg text-xs font-black uppercase tracking-wider text-gray-500 peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-sm transition-all hover:text-gray-700">Mauvais</div>
                                </label>
                            </div>
                            
                            <div class="w-full md:w-1/4 hidden" id="{{ $obsId }}">
                                <input type="text" name="pieces[{{ $piece }}][{{ $element }}][observations]" placeholder="Observation obligatoire..." class="w-full px-4 py-2 bg-red-50 border border-red-200 rounded-xl focus:border-red-500 outline-none transition-all font-bold text-xs text-red-700 placeholder-red-300">
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="space-y-6">
            <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Informations Complémentaires (Relevés & Clés)</h3>
            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Index Compteur d'Eau</label>
                    <input type="text" name="compteur_eau" placeholder="Ex: 12345 m3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-secondary outline-none transition-all font-bold text-sm text-gray-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Index Compteur d'Électricité</label>
                    <input type="text" name="compteur_electricite" placeholder="Ex: 67890 kWh" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-secondary outline-none transition-all font-bold text-sm text-gray-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nombre de Clés</label>
                    <input type="number" name="nombre_cles" min="0" placeholder="Ex: 3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-secondary outline-none transition-all font-bold text-sm text-gray-700">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Conclusion générale</h3>
            <textarea name="remarques_globales" rows="4" placeholder="Remarques globales sur l'état du bien..." class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm"></textarea>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-10 py-4 bg-secondary text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-orange-600 transition shadow-lg shadow-secondary/20">
                Terminer et Enregistrer l'État des Lieux
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function checkEtat(select, obsId) {
        const obsContainer = document.getElementById(obsId);
        const input = obsContainer.querySelector('input');
        if (select.value === 'mauvais') {
            obsContainer.classList.remove('hidden');
            input.required = true;
        } else {
            obsContainer.classList.add('hidden');
            input.required = false;
            input.value = ''; // Réinitialiser si l'état change
        }
    }
</script>
@endpush
@endsection
