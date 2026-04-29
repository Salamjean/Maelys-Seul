@extends('home.layouts.app')

@section('title', 'Contactez-nous | Maelys Immobilier')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] pt-32 pb-24 relative overflow-hidden">
    {{-- Subtle Background Decorations --}}
    <div class="absolute top-0 right-0 w-[50%] h-[50%] bg-secondary/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-[40%] h-[40%] bg-primary/5 rounded-full blur-[120px] translate-y-1/4 -translate-x-1/4"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-6xl mx-auto">
            
            {{-- Section Title --}}
            <div class="text-center mb-20 space-y-4">
                <span class="inline-block px-4 py-1 bg-secondary/10 text-secondary text-[10px] font-black uppercase tracking-[3px] rounded-full">
                    Parlons de votre projet
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-primary leading-tight italic">
                    Une Expertise à <span class="text-secondary">votre écoute</span>
                </h1>
                <p class="text-gray-400 text-sm md:text-base font-medium max-w-2xl mx-auto">
                    Besoin d'un conseil ou d'une visite ? Notre équipe vous accompagne à chaque étape de votre recherche immobilière.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                {{-- Info Column --}}
                <div class="lg:col-span-4 flex flex-col gap-6">
                    {{-- Contact Cards --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex-1 flex flex-col justify-between">
                        <div class="space-y-10">
                            {{-- Phone --}}
                            <div class="flex items-center gap-6 group">
                                <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary text-xl transition-all duration-300 group-hover:bg-secondary group-hover:text-white">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Téléphone</p>
                                    <p class="text-base font-black text-primary">+225 07 00 00 00 00</p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-center gap-6 group">
                                <div class="w-14 h-14 bg-primary/5 rounded-2xl flex items-center justify-center text-primary text-xl transition-all duration-300 group-hover:bg-primary group-hover:text-white">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Email</p>
                                    <p class="text-base font-black text-primary">contact@maelys-imo.ci</p>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="flex items-center gap-6 group">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 text-xl transition-all duration-300 group-hover:bg-gray-800 group-hover:text-white">
                                    <i class="fa-solid fa-map-pin"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Siège Social</p>
                                    <p class="text-base font-black text-primary">Abidjan, Côte d'Ivoire</p>
                                </div>
                            </div>
                        </div>

                        {{-- Working Hours --}}
                        <div class="mt-12 pt-8 border-t border-gray-50">
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 italic">Horaires d'ouverture</p>
                                <div class="flex justify-between text-xs font-bold text-primary mb-2">
                                    <span>Lundi - Vendredi</span>
                                    <span>08h00 - 18h00</span>
                                </div>
                                <div class="flex justify-between text-xs font-bold text-secondary">
                                    <span>Samedi</span>
                                    <span>09h00 - 14h00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Column --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-[3rem] p-8 md:p-14 shadow-xl shadow-gray-200/50 border border-white h-full">
                        <div class="mb-10">
                            <h2 class="text-2xl font-black text-primary italic uppercase tracking-tighter">Écrivez-nous <span class="text-secondary">directement</span></h2>
                            <p class="text-gray-400 text-xs font-bold mt-2 italic uppercase">Traitement de votre demande sous 24h maximum.</p>
                        </div>

                        <form action="{{ route('contact.store') }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Name --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nom complet</label>
                                    <input type="text" name="name" required value="{{ old('name', Auth::user()->name ?? '') }}" 
                                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm text-primary placeholder:text-gray-300" 
                                        placeholder="Jean Kouassi">
                                    @error('name') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Adresse Email</label>
                                    <input type="email" name="email" required value="{{ old('email', Auth::user()->email ?? '') }}" 
                                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm text-primary placeholder:text-gray-300" 
                                        placeholder="votre@email.com">
                                    @error('email') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Phone --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Téléphone (Optionnel)</label>
                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->contact ?? '') }}" 
                                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm text-primary placeholder:text-gray-300" 
                                        placeholder="+225 00 00 00 00">
                                </div>

                                {{-- Subject --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sujet de la demande</label>
                                    <input type="text" name="subject" required 
                                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm text-primary placeholder:text-gray-300" 
                                        placeholder="Ex: Demande de visite">
                                    @error('subject') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Message --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Votre Message</label>
                                <textarea name="message" rows="5" required 
                                    class="w-full px-6 py-5 bg-gray-50 border-2 border-transparent rounded-[2rem] focus:border-secondary focus:bg-white outline-none transition-all font-bold text-sm text-primary placeholder:text-gray-300 resize-none" 
                                    placeholder="Comment pouvons-nous vous aider ?"></textarea>
                                @error('message') <p class="text-red-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-4">
                                <button type="submit" class="group w-full py-5 bg-primary text-white rounded-[2rem] font-black text-xs uppercase tracking-[4px] hover:bg-secondary hover:shadow-2xl hover:shadow-orange-500/30 transition-all duration-300 flex items-center justify-center gap-4">
                                    Envoyer mon message
                                    <i class="fa-solid fa-paper-plane group-hover:translate-x-2 group-hover:-translate-y-2 transition-transform"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
