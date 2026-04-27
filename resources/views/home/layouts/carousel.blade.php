{{-- Hero / Carousel Section --}}
<section style="position:relative; width:100%; overflow:hidden;">
    {{-- Slides container --}}
    <div id="carousel-wrapper" style="position:relative; width:100%; height:75vh; overflow:hidden;">

        @forelse ($carouselBiens as $i => $prop)
            <div class="carousel-slide" data-index="{{ $i }}"
                style="position:absolute; inset:0; width:100%; height:100%;
                    opacity:{{ $i === 0 ? '1' : '0' }};
                    transition: opacity 0.9s ease;
                    display:flex; align-items:center; justify-content:center;">

                {{-- Background image --}}
                <img src="{{ Storage::url($prop->photo_principale) }}" alt="{{ $prop->reference }}"
                    style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">

                {{-- Dark overlay --}}
                <div
                    style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(2,36,91,0.7) 0%, rgba(2,36,91,0.5) 60%, rgba(10,31,68,0.6) 100%); z-index:1;">
                </div>

                {{-- Slide content --}}
                <div
                    style="position:relative; z-index:10; width:100%; padding:5rem 5% 3rem; display:flex; align-items:center; justify-content:center; flex-wrap:wrap; text-align:center;">

                    {{-- Left: text --}}
                    <div style="flex:1; min-width:280px; text-align:center;">
                        {{-- Tag --}}
                        <span
                            style="display:inline-block; background-color:rgba(255,94,20,0.18); border:1px solid rgba(255,94,20,0.45);
                                 color:#ff5e14; font-size:12px; font-weight:700; padding:5px 14px; border-radius:999px; margin-bottom:20px;
                                 letter-spacing:1px; text-transform:uppercase;">
                            <span
                                style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#ff5e14; margin-right:6px; vertical-align:middle; animation:pulse 1.5s infinite;"></span>
                            {{ $prop->type_bien }} — Dernière annonce
                        </span>

                        {{-- Title --}}
                        <h1
                            style="font-size:clamp(2rem,4vw,3.5rem); font-weight:900; color:white; line-height:1.15; margin-bottom:16px; text-transform:uppercase;">
                            {{ $prop->typologie }} à {{ $prop->commune }}
                        </h1>

                        {{-- Location --}}
                        <p
                            style="color:rgba(147,197,253,0.9); font-size:15px; margin-bottom:18px; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-location-dot" style="color:#ff5e14;"></i>
                            {{ $prop->commune }}
                        </p>

                        {{-- Description --}}
                        <p style="color:#ffffff; font-size:16px; line-height:1.7; max-width:700px; margin:0 auto 30px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $prop->description ?: 'Venez découvrir ce magnifique bien immobilier idéalement situé à ' . $prop->commune . '.' }}
                        </p>

                        {{-- Specs --}}
                        <div
                            style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:32px; justify-content:center;">
                            <div style="display:flex; align-items:center; gap:7px; background:rgba(255,255,255,0.08); padding:8px 16px; border-radius:10px;">
                                <i class="fa-solid fa-bed" style="color:#ff5e14;"></i>
                                <span style="color:white; font-size:14px; font-weight:600;">{{ $prop->nb_pieces }} pièces</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:7px; background:rgba(255,255,255,0.08); padding:8px 16px; border-radius:10px;">
                                <i class="fa-solid fa-expand" style="color:#ff5e14;"></i>
                                <span style="color:white; font-size:14px; font-weight:600;">{{ (int)$prop->superficie }} m²</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:7px; background:rgba(255,255,255,0.08); padding:8px 16px; border-radius:10px;">
                                <i class="fa-solid fa-tag" style="color:#ff5e14;"></i>
                                <span style="color:white; font-size:14px; font-weight:600;">{{ number_format($prop->loyer_mensuel, 0, ',', ' ') }} FCFA/mois</span>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div style="display:flex; gap:14px; flex-wrap:wrap; justify-content:center;">
                            <a href="{{ route('biens.show', $prop->id) }}"
                                style="background-color:#ff5e14; color:white; padding:14px 32px; border-radius:12px;
                                           font-weight:700; font-size:15px; text-decoration:none;
                                           box-shadow:0 6px 20px rgba(255,94,20,0.4); transition:opacity 0.2s;"
                                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                                <i class="fa-solid fa-eye" style="margin-right:8px;"></i>Voir ce bien
                            </a>
                            <a href="{{ route('visite.create', $prop->id) }}"
                                style="background-color:rgba(255,255,255,0.1); color:white; padding:14px 32px; border-radius:12px;
                                           font-weight:700; font-size:15px; text-decoration:none;
                                           border:2px solid white; transition:all 0.2s;"
                                onmouseover="this.style.background='white'; this.style.color='#02245b'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='white'">
                                <i class="fa-solid fa-calendar-check" style="margin-right:8px;"></i>Demander une visite
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            {{-- Fallback Slide --}}
            <div class="carousel-slide" data-index="0"
                style="position:absolute; inset:0; width:100%; height:100%;
                    opacity:1;
                    display:flex; align-items:center; justify-content:center;">

                <img src="{{ asset('assets/images/kk.jpg') }}" alt="Maelys Immobilier"
                    style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;">

                <div
                    style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(2,36,91,0.8) 0%, rgba(2,36,91,0.5) 60%, rgba(10,31,68,0.7) 100%); z-index:1;">
                </div>

                <div style="position:relative; z-index:10; width:100%; padding:5rem 5% 3rem; text-align:center; color:white;">
                    <span style="display:inline-block; background-color:rgba(255,94,20,0.18); border:1px solid rgba(255,94,20,0.45); color:#ff5e14; font-size:12px; font-weight:700; padding:5px 14px; border-radius:999px; margin-bottom:20px; letter-spacing:1px; text-transform:uppercase;">
                        Bienvenue chez Maelys-IMO
                    </span>
                    <h1 style="font-size:clamp(2.5rem,5vw,4rem); font-weight:900; line-height:1.1; margin-bottom:20px; text-transform:uppercase;">
                        Trouvez le logement <br> de vos <span style="color:#ff5e14;">rêves</span>
                    </h1>
                    <p style="font-size:18px; max-width:600px; margin:0 auto 35px; opacity:0.9;">
                        Nous vous accompagnons dans la recherche de votre futur foyer avec une sélection de biens de qualité.
                    </p>
                    <a href="{{ route('biens.all') }}"
                        style="background-color:#ff5e14; color:white; padding:16px 40px; border-radius:12px; font-weight:700; font-size:16px; text-decoration:none; box-shadow:0 8px 25px rgba(255,94,20,0.4);">
                        Explorer nos offres
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Navigation arrows (only if multiple) --}}
        @if ($carouselBiens->count() > 1)
            <button onclick="carouselPrev()"
                style="position:absolute; left:2%; top:50%; transform:translateY(-50%); z-index:20;
                   width:46px; height:46px; border-radius:50%; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
                   color:white; font-size:16px; cursor:pointer; transition:background 0.2s; backdrop-filter:blur(6px);"
                onmouseover="this.style.background='rgba(255,94,20,0.5)'"
                onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button onclick="carouselNext()"
                style="position:absolute; right:2%; top:50%; transform:translateY(-50%); z-index:20;
                   width:46px; height:46px; border-radius:50%; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
                   color:white; font-size:16px; cursor:pointer; transition:background 0.2s; backdrop-filter:blur(6px);"
                onmouseover="this.style.background='rgba(255,94,20,0.5)'"
                onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            {{-- Dots --}}
            <div
                style="position:absolute; bottom:28px; left:50%; transform:translateX(-50%); display:flex; gap:10px; z-index:20;">
                @foreach ($carouselBiens as $i => $prop)
                    <button onclick="carouselGo({{ $i }})" id="dot-{{ $i }}"
                        style="width:{{ $i === 0 ? '28px' : '10px' }}; height:10px; border-radius:999px; border:none; cursor:pointer; transition:all 0.3s;
                               background:{{ $i === 0 ? '#ff5e14' : 'rgba(255,255,255,0.35)' }};"></button>
                @endforeach
            </div>
        @endif
    </div>

</section>

@push('scripts')
    @if ($carouselBiens->count() > 1)
    <script>
        let current = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = slides.length;
        let timer = setInterval(() => carouselNext(), 5000);

        function carouselGo(n) {
            slides[current].style.opacity = '0';
            document.getElementById('dot-' + current).style.width = '10px';
            document.getElementById('dot-' + current).style.background = 'rgba(255,255,255,0.35)';

            current = (n + slides.length) % slides.length;

            slides[current].style.opacity = '1';
            document.getElementById('dot-' + current).style.width = '28px';
            document.getElementById('dot-' + current).style.background = '#ff5e14';

            clearInterval(timer);
            timer = setInterval(() => carouselNext(), 5000);
        }

        function carouselNext() {
            carouselGo(current + 1);
        }

        function carouselPrev() {
            carouselGo(current - 1);
        }
    </script>
    @endif
@endpush
