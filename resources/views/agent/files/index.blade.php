@extends('agent.layouts.app')

@section('title', 'Mes Fichiers')
@section('page-title', 'Mes Fichiers')

@section('content')
<div class="w-full" x-data="{ showUpload: false }">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-primary italic">Bibliothèque de Documents</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Accédez rapidement aux pièces jointes et vos documents personnels</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-secondary">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <div class="pr-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase leading-none">Total Fichiers</p>
                    <p class="text-lg font-black text-primary leading-tight">{{ count($files) }}</p>
                </div>
            </div>
            <button @click="showUpload = true" class="px-6 py-4 rounded-2xl bg-secondary text-white font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-xl shadow-secondary/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                Ajouter un fichier
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            <span class="text-sm font-bold text-green-600">{{ session('success') }}</span>
        </div>
    @endif

    @if(empty($files))
        <div class="bg-white rounded-3xl p-20 text-center shadow-xl border border-gray-100">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-file-circle-minus text-4xl text-gray-200"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-400 italic">Aucun fichier trouvé</h3>
            <p class="text-sm text-gray-400 mt-2">Commencez par ajouter un fichier ou inscrivez des locataires.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($files as $file)
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden">
                    {{-- Décoration arrière plan --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full opacity-50 group-hover:bg-secondary/5 transition-colors"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            @php
                                $ext = strtolower($file['extension']);
                                $icon = 'fa-file-lines';
                                $color = 'text-blue-500 bg-blue-50';
                                if(in_array($ext, ['pdf'])) { $icon = 'fa-file-pdf'; $color = 'text-red-500 bg-red-50'; }
                                elseif(in_array($ext, ['jpg', 'jpeg', 'png'])) { $icon = 'fa-file-image'; $color = 'text-green-500 bg-green-50'; }
                                elseif(in_array($ext, ['doc', 'docx'])) { $icon = 'fa-file-word'; $color = 'text-blue-600 bg-blue-50'; }
                            @endphp
                            <div class="w-12 h-12 rounded-2xl {{ $color }} flex items-center justify-center text-xl shadow-inner">
                                <i class="fa-solid {{ $icon }}"></i>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-[9px] font-black uppercase px-2 py-1 bg-gray-100 text-gray-400 rounded-md">{{ $ext }}</span>
                                @if($file['is_general'])
                                    <span class="text-[8px] font-black uppercase px-2 py-0.5 bg-orange-100 text-orange-600 rounded-md tracking-tighter">Personnel</span>
                                @endif
                            </div>
                        </div>

                        <h4 class="text-sm font-black text-primary line-clamp-2 min-h-[40px] mb-2" title="{{ $file['name'] }}">
                            {{ $file['name'] }}
                        </h4>

                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-user text-[10px] text-gray-300"></i>
                                <span class="text-[11px] font-bold text-gray-500">{{ $file['owner'] }}</span>
                            </div>
                            @if(!$file['is_general'])
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-house-user text-[10px] text-gray-300"></i>
                                    <span class="text-[11px] font-bold text-gray-400 italic">Bien: {{ $file['property'] }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-tag text-[10px] text-gray-300"></i>
                                    <span class="text-[11px] font-bold text-gray-400">{{ $file['type'] }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar text-[10px] text-gray-300"></i>
                                <span class="text-[11px] font-bold text-gray-400">{{ $file['date'] }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4 border-t border-gray-50">
                            <a href="{{ $file['url'] }}" target="_blank" class="flex-1 py-2.5 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest text-center hover:bg-primary/90 transition-all">
                                <i class="fa-solid fa-eye mr-1"></i> Voir
                            </a>
                            <a href="{{ $file['url'] }}" download class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            @if($file['is_general'])
                                <form action="{{ route('agent.files.destroy', explode('_', $file['id'])[0]) }}" method="POST" onsubmit="return confirm('Supprimer ce fichier ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal Upload --}}
    <div x-show="showUpload" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden" @click.away="showUpload = false">
            <div class="p-8 bg-gradient-to-br from-primary to-primary/90 text-white relative">
                <button @click="showUpload = false" class="absolute right-6 top-6 w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="w-16 h-16 bg-secondary rounded-2xl flex items-center justify-center text-3xl shadow-lg mb-4">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <h3 class="text-xl font-black italic">Nouveau Document</h3>
                <p class="text-xs text-white/60 font-bold uppercase tracking-wider">Ajouter un fichier à votre bibliothèque</p>
            </div>

            <form action="{{ route('agent.files.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nom du fichier</label>
                    <input type="text" name="name" required placeholder="Ex: Modèle Contrat de Bail" 
                           class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Type de document</label>
                    <select name="type" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm appearance-none">
                        <option value="Général">Général</option>
                        <option value="Contrat">Modèle de Contrat</option>
                        <option value="Note">Note Interne</option>
                        <option value="Fiche">Fiche Technique</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Sélectionner le fichier</label>
                    <div class="relative group">
                        <input type="file" name="file" required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full px-5 py-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-secondary group-hover:bg-orange-50/50 transition-all flex flex-col items-center justify-center gap-2">
                            <i class="fa-solid fa-file-circle-plus text-2xl text-gray-300 group-hover:text-secondary"></i>
                            <p class="text-[11px] font-bold text-gray-400 group-hover:text-secondary">Cliquez ou glissez un fichier ici</p>
                            <p class="text-[9px] text-gray-300 uppercase">PDF, JPG, PNG, DOC (Max 10MB)</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-2xl bg-secondary text-white font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-xl shadow-secondary/20 transition-all flex items-center justify-center gap-2">
                        Lancer le téléchargement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endsection
