@extends('agent.layouts.app')

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

    <form action="{{ route('agent.etat_lieux.store', $etatLieu->id) }}" method="POST" class="p-8 space-y-8">
        @csrf
        
        <div class="space-y-6">
            <h3 class="text-sm font-black text-secondary uppercase tracking-[2px] border-l-4 border-secondary pl-3">Inspection par pièce</h3>
            
            @foreach($elements as $index => $element)
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 flex flex-col md:flex-row gap-6 items-start md:items-center">
                    <div class="w-full md:w-1/4">
                        <input type="hidden" name="elements[{{ $index }}][nom]" value="{{ $element }}">
                        <p class="font-black text-primary text-lg">{{ $element }}</p>
                    </div>
                    
                    <div class="w-full md:w-1/4">
                        <select name="elements[{{ $index }}][etat]" required class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-secondary outline-none transition-all font-bold text-sm text-gray-700">
                            <option value="">-- État --</option>
                            <option value="tres_bon">Très Bon</option>
                            <option value="bon">Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvais">Mauvais</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:w-2/4">
                        <input type="text" name="elements[{{ $index }}][observations]" placeholder="Observations éventuelles..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:border-secondary outline-none transition-all font-bold text-sm text-gray-700">
                    </div>
                </div>
            @endforeach
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
@endsection
