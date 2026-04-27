@extends('admin.layouts.app')

@section('title', 'Liste des Agents')
@section('page-title', 'Gestion de l\'Équipe')
@section('page-subtitle', 'Consultez et gérez les agents de l\'agence')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-black text-primary italic lowercase"><span class="uppercase">L</span>iste des agents</h2>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Total : {{ $agents->count() }} agent(s) actif(s)</p>
    </div>
    <a href="{{ route('admin.agents.create') }}" class="bg-primary text-white px-6 py-3 rounded-2xl font-black italic text-sm hover:bg-primary-dark transition-all shadow-lg flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> AJOUTER UN AGENT
    </a>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <th class="px-8 py-5">Nom Complet</th>
                    <th class="px-8 py-5">Email</th>
                    <th class="px-8 py-5 text-center">Rôle</th>
                    <th class="px-8 py-5 text-center">Date d'ajout</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($agents as $agent)
                <tr class="hover:bg-gray-50/30 transition-colors group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary font-black text-xs">
                                {{ substr($agent->name, 0, 2) }}
                            </div>
                            <span class="text-sm font-black text-gray-800">{{ $agent->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-gray-500">{{ $agent->email }}</span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-blue-100">
                            {{ $agent->role }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $agent->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="p-2 bg-gray-50 text-gray-400 rounded-lg hover:bg-secondary hover:text-white transition shadow-sm">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('Supprimer cet agent ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-gray-50 text-gray-400 rounded-lg hover:bg-red-500 hover:text-white transition shadow-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic font-medium">
                        Aucun agent enregistré pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
