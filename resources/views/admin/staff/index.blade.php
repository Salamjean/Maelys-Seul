@extends('admin.layouts.app')

@section('title', 'Gestion du Personnel')
@section('page-title', 'Personnel (Comptabilité & Recouvrement)')

@section('content')
<div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/30">
        <div>
            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Équipe <span class="text-secondary">Administrative</span></h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Gérez les accès comptables et recouvrement</p>
        </div>

        <a href="{{ route('admin.staff.create') }}" class="h-12 px-8 bg-primary text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-secondary transition-all shadow-lg shadow-blue-900/20 hover:shadow-orange-500/20 flex items-center justify-center gap-3">
            <i class="fa-solid fa-user-plus"></i> Inviter un collaborateur
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/30 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                    <th class="px-8 py-6">Nom & Prénom</th>
                    <th class="px-8 py-6 text-center">Rôle</th>
                    <th class="px-8 py-6 text-center">Email</th>
                    <th class="px-8 py-6 text-center">Statut</th>
                    <th class="px-8 py-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($staff as $member)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-black border border-primary/5 shadow-sm group-hover:scale-110 transition-all">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tighter italic leading-tight">{{ $member->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Membre de l'équipe</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($member->role === 'comptable')
                                <span class="px-4 py-1.5 bg-blue-100 text-blue-600 rounded-xl text-[9px] font-black uppercase tracking-widest italic">Comptabilité</span>
                            @else
                                <span class="px-4 py-1.5 bg-orange-100 text-orange-600 rounded-xl text-[9px] font-black uppercase tracking-widest italic">Recouvrement</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="text-xs font-black text-primary lowercase">{{ $member->email }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($member->onboarding_token)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[9px] font-black uppercase tracking-widest">En attente d'activation</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[9px] font-black uppercase tracking-widest">Actif</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce collaborateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm flex items-center justify-center">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-gray-200 text-gray-200 text-4xl">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest italic">Aucun collaborateur enregistré.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
