@extends('locataire.layouts.app')

@section('title', 'Mes États des Lieux')
@section('page-title', 'Mes États des Lieux')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/20">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Historique de vos États des Lieux</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Consultez les rapports d'entrée et de sortie</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <i class="fa-solid fa-clipboard-check text-xl"></i>
        </div>
    </div>

    <div class="p-8">
        @if($etatsLieux->isEmpty())
            <div class="text-center py-16 bg-gray-50/50 rounded-2xl border border-gray-100 border-dashed">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4 shadow-sm">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-gray-700">Aucun état des lieux</h3>
                <p class="text-sm text-gray-500 mt-2">Vous n'avez pas encore d'état des lieux finalisé.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($etatsLieux as $el)
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <!-- Décoration -->
                        <div class="absolute top-0 left-0 w-1 h-full {{ $el->type == 'entree' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $el->type == 'entree' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $el->type == 'entree' ? 'Entrée' : 'Sortie' }}
                                </span>
                                <p class="text-sm font-bold text-gray-500 mt-2">Le {{ $el->date_etat_lieux->format('d/m/Y') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                <i class="fa-solid fa-home"></i>
                            </div>
                        </div>

                        <h3 class="font-black text-primary text-lg mb-1">{{ $el->bien->reference }}</h3>
                        <p class="text-xs text-gray-400 font-bold mb-6 italic"><i class="fa-solid fa-location-dot mr-1"></i>{{ $el->bien->commune }}</p>

                        <div class="pt-4 border-t border-gray-50">
                            <a href="{{ route('locataire.etat_lieux.pdf', $el->id) }}" target="_blank" class="w-full py-3 bg-gray-50 hover:bg-primary hover:text-white text-primary text-xs font-black uppercase tracking-widest rounded-xl transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file-pdf"></i>
                                Télécharger le PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
