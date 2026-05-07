<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Turnos - SENA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        sena: { 
                            yellow: '#FFB500',
                            blue: '#10069F',
                            orange: '#FF671F',
                            500: '#10069F', // Mapping old sena-500 to sena-blue for compatibility
                            600: '#0c047a',
                            50: '#f0f0ff'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Hide scrollbar for the turns list to make it cleaner */
        .overflow-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes callAttention {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 6, 159, 0.4); }
            70% { transform: scale(1.02); box-shadow: 0 0 0 20px rgba(16, 6, 159, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 6, 159, 0); }
        }
        .animate-call {
            animation: callAttention 2s infinite;
        }
    </style>
</head>

<body class="bg-[#f0f2f5] text-gray-800 font-sans h-screen flex flex-col overflow-hidden">

    <!-- Header (Fluid padding and height) -->
    <header
        class="bg-white px-4 lg:px-8 py-3 lg:py-4 flex justify-between items-center shrink-0 border-b border-gray-200 shadow-sm relative z-20">
        <div class="flex items-center space-x-3 lg:space-x-6">
            <img src="{{ asset('images/logo.jpeg') }}" class="h-10 lg:h-16 w-auto object-contain" alt="SENA Logo">
            <div class="h-8 lg:h-10 w-px bg-gray-200 hidden sm:block"></div>
            <div class="hidden sm:block">
                <h1 class="text-xl lg:text-3xl font-poppins font-black text-gray-900 tracking-tight leading-none mb-0.5 lg:mb-1">SENA</h1>
                <p class="text-[10px] lg:text-xs font-bold text-gray-500 tracking-widest uppercase">Sistema de Gestión de Turnos</p>
            </div>
        </div>
        <div class="flex items-center space-x-4 lg:space-x-10 text-right">
            <div>
                <p class="text-xl lg:text-3xl font-black text-gray-800 tracking-tight" id="current-time">10:45 AM</p>
                <p class="text-[10px] lg:text-sm font-medium text-gray-500 mt-0.5 lg:mt-1" id="current-date">Actualizando...</p>
            </div>
            <div
                class="flex items-center space-x-2 lg:space-x-3 text-gray-700 bg-gray-50 px-3 lg:px-4 py-1.5 lg:py-2 flex-col rounded-xl border border-gray-100 shadow-inner hidden xs:flex">
                <div class="flex space-x-1 lg:space-x-2 items-center">
                    <i class="fa-regular fa-sun text-sena-orange text-lg lg:text-2xl"></i>
                    <span class="text-lg lg:text-xl font-black text-gray-800">24°C</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-layout" class="flex-1 flex flex-col lg:flex-row p-4 lg:p-6 gap-4 lg:gap-6 overflow-hidden min-h-0 relative z-10 w-full transition-all duration-700">

        <!-- Left Column: Turnos (Approx 38% width for better fit) -->
        <div
            class="w-full lg:w-[38%] bg-white rounded-[2rem] lg:rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden relative transition-all duration-500">

            <!-- Header Left (Compacted for Laptops) -->
            <div class="p-4 lg:p-6 flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-list-check text-sena-500 text-xl lg:text-2xl"></i>
                    <h2 class="text-xl lg:text-2xl font-black text-gray-800 tracking-tight">Turnos en Atención</h2>
                </div>
                <span
                    class="bg-sena-yellow/20 text-sena-orange text-[10px] lg:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">En
                    vivo</span>
            </div>

            <!-- Table Header -->
            <div class="grid grid-cols-5 bg-[#f8fafc] px-4 lg:px-6 py-2 lg:py-3 border-y border-gray-100 text-[9px] font-bold text-gray-500 uppercase tracking-wider shrink-0">
                <div class="col-span-2 text-left ml-4 lg:ml-6">TURNO</div>
                <div class="col-span-3">MÓDULO / PROFESIONAL</div>
            </div>

            <!-- List / Dynamic Container -->
            <div class="flex-1 overflow-auto pb-32" id="contenedor-principal-lista">
                <!-- Zona de Turnos en Espera -->
                <div id="contenedor-espera" class="p-4 space-y-3">
                    @forelse($turnosEnEspera as $turno)
                        @php
                            $perfil = $turno->tur_perfil ?? $turno->tur_tipo ?? 'General';
                            $isVictima    = in_array($perfil, ['Victima','Victimas']);
                            $isEmpresario = $perfil === 'Empresario';
                            $isPriority   = $perfil === 'Prioritario';
                            $sideColor  = $isVictima ? 'bg-rose-500' : ($isEmpresario ? 'bg-purple-600' : ($isPriority ? 'bg-sena-orange' : 'bg-sena-blue'));
                            $badgeClass = $isVictima ? 'bg-rose-100 text-rose-600' : ($isEmpresario ? 'bg-purple-100 text-purple-700' : ($isPriority ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600'));
                            $badgeLabel = $isVictima ? 'Víctima' : ($isEmpresario ? 'Empresario' : ($isPriority ? 'Prioritario' : 'General'));
                        @endphp
                        <div class="bg-gray-50/50 rounded-2xl p-3 lg:p-4 grid grid-cols-5 items-center border border-gray-100 hover:bg-white hover:shadow-lg transition-all duration-300 group" data-id="{{ $turno->tur_id }}">
                            {{-- Col 1-2: Turno --}}
                            <div class="col-span-2 flex items-center space-x-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl {{ $sideColor }} flex items-center justify-center text-white text-base lg:text-xl font-black shadow-md group-hover:rotate-3 transition-transform shrink-0">
                                    {{ substr($turno->tur_numero, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-lg lg:text-2xl font-black text-gray-900 tracking-tighter leading-none">{{ $turno->tur_numero }}</h4>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">En espera</p>
                                </div>
                            </div>
                            {{-- Col 3-5: Módulo / Profesional --}}
                            <div class="col-span-3 flex items-center justify-between pl-3 border-l border-gray-100">
                                <div>
                                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Módulo / Profesional</p>
                                    @if($turno->asesor_sugerido && $turno->asesor_sugerido->persona)
                                        <p class="text-[9px] font-black text-gray-500 mt-0.5">
                                            Módulo {{ sprintf('%02d', $turno->asesor_sugerido->ase_id) }}
                                        </p>
                                        <p class="text-xs font-black text-gray-700 leading-tight">
                                            {{ $turno->asesor_sugerido->persona->pers_nombres }}
                                            {{ $turno->asesor_sugerido->persona->pers_apellidos }}
                                        </p>
                                    @else
                                        <p class="text-xs font-black text-gray-400 mt-0.5">— En cola</p>
                                    @endif
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $badgeClass }} shrink-0">
                                    {{ $badgeLabel }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400 text-base font-medium italic">No hay turnos en espera</div>
                    @endforelse
                </div>

                <!-- Zona de Turno en Atención -->
                <div id="contenedor-atencion" class="px-4 pb-4">
                    @if($turnoActual)
                        @php
                            $asesorNombre = $turnoActual->asesor_nombre ?? ('Módulo ' . sprintf('%02d', $turnoActual->modulo ?? '01'));
                        @endphp
                        <div class="bg-emerald-50 rounded-2xl p-4 lg:p-5 grid grid-cols-5 items-center border-2 border-emerald-200 shadow-lg shadow-emerald-100 animate-call relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-emerald-500 text-white text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest">Atendiendo</span>
                            </div>
                            {{-- Col 1-2: Turno --}}
                            <div class="col-span-2 flex items-center space-x-3">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white text-base lg:text-xl font-black shadow-md shrink-0">
                                    {{ substr($turnoActual->tur_numero, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-lg lg:text-2xl font-black text-emerald-700 tracking-tighter leading-none">{{ $turnoActual->tur_numero }}</h4>
                                    <p class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mt-0.5">Atendiendo</p>
                                </div>
                            </div>
                            {{-- Col 3-5: Módulo / Profesional --}}
                            <div class="col-span-3 flex items-center gap-3 pl-3 border-l border-emerald-200">
                                <img src="{{ asset($turnoActual->ase_foto ?? 'images/foto de perfil.jpg') }}" class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl border-2 border-white shadow-md object-cover shrink-0">
                                <div>
                                    <p class="text-[8px] font-black text-emerald-600/60 uppercase tracking-widest">Módulo {{ sprintf('%02d', $turnoActual->modulo ?? '01') }}</p>
                                    <p class="text-xs lg:text-sm font-black text-emerald-900 leading-tight">{{ $asesorNombre }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Fixed Box — Siguiente en turno -->
            <div class="absolute bottom-4 lg:bottom-5 left-4 lg:left-5 right-4 lg:right-5 bg-gradient-to-br from-gray-900 to-black rounded-2xl p-4 lg:p-5 flex justify-between items-center text-white shadow-2xl border border-white/10 overflow-hidden group z-10">
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-sena-orange/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="relative z-10">
                    <p class="text-gray-500 text-[9px] font-black tracking-[0.3em] uppercase mb-1">Siguiente en turno</p>
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl lg:text-4xl font-black text-white tracking-tighter" id="box-proximo-numero">{{ $turnoActual->tur_numero ?? ($turnosEnEspera->first()->tur_numero ?? '---') }}</span>
                        <div class="px-3 py-1 bg-white/5 rounded-xl border border-white/10 backdrop-blur-sm">
                            <span class="text-sena-yellow text-[9px] lg:text-xs font-bold uppercase tracking-widest">Sala de espera</span>
                        </div>
                    </div>
                </div>
                <div class="relative z-10 flex items-center gap-3">
                    <!-- Botón activar sonido -->
                    <button id="btn-audio" onclick="activarAudio()" title="Activar sonido"
                        class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center transition-all">
                        <i id="audio-icon" class="fa-solid fa-volume-xmark text-white/50 text-sm"></i>
                    </button>
                    <div class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl lg:rounded-2xl bg-sena-orange flex items-center justify-center shadow-lg shadow-sena-orange/20">
                        <i class="fa-solid fa-bolt-lightning text-white text-base lg:text-2xl animate-pulse"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Media and Cards (Approx 62% width) -->
        <div class="w-full lg:w-[62%] flex flex-col gap-4 lg:gap-6 h-full overflow-hidden">

            <!-- Video/Image Box -->
            <div
                class="flex-1 relative rounded-[2rem] lg:rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-100 bg-black flex flex-col group transition-all duration-500">
                <!-- Video Player Component (YouTube API) -->
                <div class="relative flex-1 bg-gray-900 overflow-hidden pointer-events-none">
                    <!-- Scale transform helps the iframe mimic object-cover by bleeding edges out of view -->
                    <div class="absolute inset-0 w-full h-full transform scale-[1.35] transition-opacity duration-700" id="video-container">
                        <div id="youtube-player" class="w-full h-full"></div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/20 to-transparent"></div>
                </div>

                <!-- Text Overlay Layer (Inside video, bottom) -->
                <div class="absolute bottom-[5.5rem] left-10 right-10 z-10">
                    <h2 id="video-title" class="text-[2.2rem] font-bold text-white leading-tight drop-shadow-md transition-all duration-500">
                        SENA: Transformando el futuro de Colombia
                    </h2>
                </div>

                <!-- Bottom Solid Text Bar (Reduced size for more video space) -->
                <div class="bg-sena-blue/90 backdrop-blur-md px-8 py-4 min-h-[4rem] flex items-center z-10 border-t border-white/10">
                    <h3 id="video-subtitle" class="text-lg lg:text-xl font-medium text-gray-300 tracking-wide m-0 leading-tight transition-all duration-500">
                        Conoce nuestras nuevas convocatorias de formación titulada 2026
                    </h3>
                </div>
            </div>

            <!-- El video ahora ocupa todo el alto disponible -->

        </div>
    </main>


    <!-- Modal de Llamado (Glassmorphism Ultra-Adaptativo) -->
    <div id="llamado-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-10 bg-black/50 backdrop-blur-md transition-all duration-700 opacity-0 pointer-events-none">
        <div class="bg-white/10 backdrop-blur-3xl w-full max-w-5xl rounded-[2.5rem] lg:rounded-[4rem] p-6 lg:p-16 2xl:p-20 shadow-[0_32px_64px_-15px_rgba(0,0,0,0.6)] flex flex-col items-center text-center space-y-6 lg:space-y-12 border border-white/20 relative overflow-hidden transform scale-95 transition-transform duration-700">
            
            <!-- Decorative Light Effects (Ajustados) -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-sena-orange/20 rounded-full blur-[80px]"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-sena-blue/20 rounded-full blur-[80px]"></div>
            
            <div class="relative shrink-0">
                <div class="absolute inset-0 bg-sena-orange/30 rounded-full animate-ping"></div>
                <div class="w-16 h-16 lg:w-24 lg:h-24 bg-gradient-to-br from-sena-orange to-orange-600 rounded-2xl lg:rounded-3xl flex items-center justify-center text-white text-3xl lg:text-5xl shadow-2xl relative z-10">
                    <i class="fa-solid fa-microphone-lines"></i>
                </div>
            </div>

            <div class="space-y-1 lg:space-y-4 w-full">
                <p class="text-xs lg:text-xl 2xl:text-2xl font-black text-sena-yellow uppercase tracking-[0.4em] drop-shadow-sm">Llamando al turno</p>
                <h3 id="modal-turno-numero" class="text-5xl lg:text-7xl 2xl:text-9xl font-poppins font-black text-white tracking-tighter leading-none drop-shadow-2xl italic">
                    ---
                </h3>
                <!-- Citizen Name Section (Fluido) -->
                <p id="modal-ciudadano-nombre" class="text-xl lg:text-4xl 2xl:text-5xl font-bold text-white/90 tracking-tight transition-all duration-500 truncate max-w-full px-4">
                    ---
                </p>
            </div>

            <div class="w-full flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-8 lg:space-x-12 bg-white/5 px-6 lg:px-16 py-6 lg:py-10 rounded-[2rem] lg:rounded-[3.5rem] border border-white/10 shadow-2xl backdrop-blur-md">
                <div class="text-center sm:text-left">
                    <p class="text-[9px] lg:text-sm font-black text-white/50 uppercase tracking-[0.3em] mb-1 lg:mb-3">Por favor diríjase al:</p>
                    <span id="modal-modulo-numero" class="text-4xl lg:text-7xl 2xl:text-8xl font-poppins font-black text-sena-yellow italic drop-shadow-lg">Módulo --</span>
                </div>
                <div class="hidden sm:block h-16 lg:h-24 w-px bg-white/10"></div>
                <div class="relative shrink-0">
                    <div class="absolute inset-0 bg-white/10 rounded-[1.5rem] lg:rounded-[2rem] blur-xl"></div>
                    <img id="modal-ase-foto" src="{{ asset('images/foto de perfil.jpg') }}" class="w-20 h-20 lg:w-32 lg:h-40 rounded-[1.5rem] lg:rounded-[2.5rem] border-2 lg:border-4 border-white/20 shadow-2xl object-cover relative z-10">
                </div>
            </div>

            <div class="flex items-center space-x-4 lg:space-x-6 text-sena-yellow/80 animate-pulse shrink-0">
                <div class="w-2 h-2 lg:w-3 lg:h-3 rounded-full bg-sena-yellow shadow-[0_0_15px_rgba(255,181,0,0.8)]"></div>
                <span class="text-xs lg:text-xl 2xl:text-2xl font-black uppercase tracking-[0.5em] text-white">Atención Inmediata</span>
                <div class="w-2 h-2 lg:w-3 lg:h-3 rounded-full bg-sena-yellow shadow-[0_0_15px_rgba(255,181,0,0.8)]"></div>
            </div>
        </div>
    </div>


    <!-- Footer Removed to maximize video space -->

    <!-- Scripts: Reloj, Video y Lógica de Notificaciones -->
    <script>
        // --- CONFIGURACIÓN Y ESTADO ---
        const AUDIO_CONFIG = { voiceRate: 0.85, voicePitch: 1.0, voiceLang: 'es-CO', voiceVolume: 1.0 };
        let announcementQueue = [];
        let isSpeaking = false;
        
        let lastTurnIds = @json($turnosEnEspera->pluck('tur_id'));
        let lastCurrentAtncId = @json($turnoActual->atnc_id ?? null);
        const pollingInterval = 3000;

        // --- LÓGICA DE ROTACIÓN (24 HORAS) ---
        function applyRotation() {
            const layout = document.getElementById('main-layout');
            if (!layout) return;
            const day = new Date().getDate();
            if (day % 2 === 0) {
                layout.classList.add('lg:flex-row-reverse');
                layout.classList.remove('lg:flex-row');
            } else {
                layout.classList.add('lg:flex-row');
                layout.classList.remove('lg:flex-row-reverse');
            }
        }
        applyRotation();

        // --- SISTEMA DE AUDIO CORREGIDO ---
        // El AudioContext DEBE crearse después de una interacción del usuario.
        // Usamos un botón visible para activarlo la primera vez.
        let audioCtx = null;
        let audioEnabled = false;

        function activarAudio() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                audioCtx.resume().then(() => {
                    audioEnabled = true;
                    const icon = document.getElementById('audio-icon');
                    const btn  = document.getElementById('btn-audio');
                    if (icon) icon.className = 'fa-solid fa-volume-high text-sena-yellow text-sm';
                    if (btn)  btn.classList.add('border-sena-yellow/50', 'bg-white/20');
                    // Chime de confirmación
                    playProfessionalChime();
                });
            } catch(e) { console.error('Error audio:', e); }
        }

        // Auto-intentar al primer click/touch en cualquier parte de la página
        document.addEventListener('click', function onFirstInteraction() {
            if (!audioEnabled) activarAudio();
            document.removeEventListener('click', onFirstInteraction);
        }, { once: true });

        // También intentar al cargar (funciona en Chrome con flags de kiosco)
        window.addEventListener('load', () => {
            try {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'running') {
                    audioEnabled = true;
                    const icon = document.getElementById('audio-icon');
                    if (icon) icon.className = 'fa-solid fa-volume-high text-sena-yellow text-sm';
                }
            } catch(e) {}
        });

        function playProfessionalChime() {
            if (!audioCtx) return;
            if (audioCtx.state === 'suspended') {
                audioCtx.resume().then(() => _playChime());
            } else {
                _playChime();
            }
        }

        function _playChime() {
            try {
                const now = audioCtx.currentTime;
                // Acorde ascendente: Do-Mi-Sol con fade
                [[523.25, 0, 0.20], [659.25, 0.18, 0.20], [783.99, 0.36, 0.25]].forEach(([freq, delay, dur]) => {
                    const osc  = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, now + delay);
                    gain.gain.setValueAtTime(0, now + delay);
                    gain.gain.linearRampToValueAtTime(0.22, now + delay + 0.06);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + delay + dur + 1.2);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(now + delay);
                    osc.stop(now + delay + dur + 1.2);
                });
                audioEnabled = true;
            } catch(e) { console.error('Chime error:', e); }
        }

        // Iniciar polling automáticamente al cargar
        setInterval(checkUpdates, pollingInterval);

        // --- SISTEMA DE COLA DE ANUNCIOS ---
        function anunciarTurno(numero, modulo, asesorNombre) {
            announcementQueue.push({ numero, modulo, asesorNombre });
            processQueue();
        }

        async function processQueue() {
            if (isSpeaking || announcementQueue.length === 0) return;
            isSpeaking = true;
            const turno = announcementQueue.shift();

            playProfessionalChime();

            setTimeout(() => {
                if (!window.speechSynthesis) { isSpeaking = false; return; }
                const moduloTexto = turno.modulo ? `módulo ${turno.modulo}` : 'el módulo asignado';
                const texto = `Turno ${turno.numero.replace('-', ' ')}, por favor diríjase al ${moduloTexto}`;
                const msg = new SpeechSynthesisUtterance(texto);
                msg.lang    = AUDIO_CONFIG.voiceLang;
                msg.rate    = AUDIO_CONFIG.voiceRate;
                msg.pitch   = AUDIO_CONFIG.voicePitch;
                msg.volume  = AUDIO_CONFIG.voiceVolume;
                msg.onend   = () => { isSpeaking = false; setTimeout(processQueue, 800); };
                msg.onerror = () => { isSpeaking = false; };
                window.speechSynthesis.cancel(); // cancelar cualquier voz anterior
                window.speechSynthesis.speak(msg);
            }, 1400);
        }

        // --- POLLING Y ACTUALIZACIÓN ---
        async function checkUpdates() {
            try {
                const response = await fetch('{{ route("pantalla.api.data") }}');
                const data = await response.json();
                
                const currentTurnIds = data.turnosEnEspera.map(t => t.tur_id);
                const listChanged = currentTurnIds.length !== lastTurnIds.length || 
                                   currentTurnIds.some((id, index) => id !== lastTurnIds[index]);
                
                if (listChanged) {
                    updateWaitingList(data.turnosEnEspera);
                    lastTurnIds = currentTurnIds;
                }

                if (data.turnoActual && data.turnoActual.atnc_id !== lastCurrentAtncId) {
                    mostrarModalLlamado(data.turnoActual);
                    updateCurrentTurnBox(data.turnoActual);
                    lastCurrentAtncId = data.turnoActual.atnc_id;
                } else if (!data.turnoActual) {
                    lastCurrentAtncId = null;
                    updateCurrentTurnBox(null);
                }
            } catch (error) {
                console.error("Error al obtener actualizaciones:", error);
            }
        }

        function mostrarModalLlamado(turno) {
            const modal = document.getElementById('llamado-modal');
            const innerModal = modal.querySelector('div');
            
            document.getElementById('modal-turno-numero').textContent = turno.tur_numero;
            document.getElementById('modal-ciudadano-nombre').textContent = turno.ciudadano || 'Ciudadano';
            const moduloFormatted = String(turno.modulo).padStart(2, '0');
            document.getElementById('modal-modulo-numero').textContent = `Módulo ${moduloFormatted}`;
            document.getElementById('modal-ase-foto').src = turno.ase_foto;

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            innerModal.classList.remove('scale-95');
            innerModal.classList.add('scale-100');

            anunciarTurno(turno.tur_numero, turno.modulo, turno.asesor_nombre);

            setTimeout(() => {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
                innerModal.classList.remove('scale-100');
                innerModal.classList.add('scale-95');
            }, 12000);
        }

        function updateWaitingList(turnos) {
            const container = document.getElementById('contenedor-espera');
            if (!container) return;
            if (turnos.length === 0) {
                container.innerHTML = '<div class="p-10 text-center text-gray-400 text-base font-medium italic">No hay turnos en espera</div>';
                return;
            }
            let html = '';
            turnos.forEach(t => {
                const perfil = t.tur_perfil || t.tur_tipo || 'General';
                const isVictima    = ['Victima','Victimas'].includes(perfil);
                const isEmpresario = perfil === 'Empresario';
                const isPriority   = perfil === 'Prioritario';
                const sideColor  = isVictima ? 'bg-rose-500' : (isEmpresario ? 'bg-purple-600' : (isPriority ? 'bg-sena-orange' : 'bg-sena-blue'));
                const badgeClass = isVictima ? 'bg-rose-100 text-rose-600' : (isEmpresario ? 'bg-purple-100 text-purple-700' : (isPriority ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600'));
                const badgeLabel = isVictima ? 'Víctima' : (isEmpresario ? 'Empresario' : (isPriority ? 'Prioritario' : 'General'));
                const firstChar  = t.tur_numero.charAt(0);
                html += `
                    <div class="bg-gray-50/50 rounded-2xl p-3 lg:p-4 grid grid-cols-5 items-center border border-gray-100 hover:bg-white hover:shadow-lg transition-all duration-300 group animate-fade-in" data-id="${t.tur_id}">
                        <div class="col-span-2 flex items-center space-x-3">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl ${sideColor} flex items-center justify-center text-white text-base lg:text-xl font-black shadow-md group-hover:rotate-3 transition-transform shrink-0">
                                ${firstChar}
                            </div>
                            <div>
                                <h4 class="text-lg lg:text-2xl font-black text-gray-900 tracking-tighter leading-none">${t.tur_numero}</h4>
                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">En espera</p>
                            </div>
                        </div>
                        <div class="col-span-3 flex items-center justify-between pl-3 border-l border-gray-100">
                            <div>
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Módulo / Profesional</p>
                                ${t.modulo_sugerido
                                    ? `<p class="text-[9px] font-black text-gray-500 mt-0.5">Módulo ${String(t.modulo_sugerido).padStart(2,'0')}</p><p class="text-xs font-black text-gray-700 leading-tight">${t.asesor_nombre_sugerido || ''}</p>`
                                    : `<p class="text-xs font-black text-gray-400 mt-0.5">— En cola</p>`
                                }
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest ${badgeClass} shrink-0">
                                ${badgeLabel}
                            </span>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function updateCurrentTurnBox(turno) {
            const container = document.getElementById('contenedor-atencion');
            const proximoDisplay = document.getElementById('box-proximo-numero');
            const proximoBadge   = document.getElementById('box-proximo-badge');

            if (!turno) {
                // Limpiar la tarjeta de atención
                container.innerHTML = '';
                // Actualizar la barra inferior con el siguiente en espera o ---
                const primerEnEspera = document.querySelector('#contenedor-espera [data-id]');
                if (proximoDisplay) {
                    if (primerEnEspera) {
                        const h4 = primerEnEspera.querySelector('h4');
                        proximoDisplay.textContent = h4 ? h4.textContent.trim() : '---';
                    } else {
                        proximoDisplay.textContent = '---';
                    }
                }
                return;
            }

            // Hay turno activo — mostrar en barra y en tarjeta
            if (proximoDisplay) proximoDisplay.textContent = turno.tur_numero;
            const moduloFormatted = String(turno.modulo).padStart(2, '0');
            const asesorNombre = turno.asesor_nombre || ('Módulo ' + moduloFormatted);
            container.innerHTML = `
                <div class="bg-emerald-50 rounded-2xl p-3 lg:p-4 grid grid-cols-5 items-center border-2 border-emerald-200 shadow-lg shadow-emerald-100 animate-call relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2">
                        <span class="bg-emerald-500 text-white text-[8px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest">Atendiendo</span>
                    </div>
                    <div class="col-span-2 flex items-center space-x-3">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white text-base lg:text-xl font-black shadow-md shrink-0">
                            ${turno.tur_numero.charAt(0)}
                        </div>
                        <div>
                            <h4 class="text-lg lg:text-2xl font-black text-emerald-700 tracking-tighter leading-none">${turno.tur_numero}</h4>
                            <p class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mt-0.5">Atendiendo</p>
                        </div>
                    </div>
                    <div class="col-span-3 flex items-center gap-3 pl-3 border-l border-emerald-200">
                        <img src="${turno.ase_foto}" class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl border-2 border-white shadow-md object-cover shrink-0">
                        <div>
                            <p class="text-[8px] font-black text-emerald-600/60 uppercase tracking-widest">Módulo ${moduloFormatted}</p>
                            <p class="text-xs lg:text-sm font-black text-emerald-900 leading-tight">${asesorNombre}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        // --- LÓGICA DE VIDEO (YOUTUBE) ---
        const playlistIds = [
            { id: 'LT42fRHkxEc', title: 'Somos SENA', subtitle: 'Transformando el futuro de Colombia con educación' },
            { id: 'SqBeOiTOhE4', title: 'Formación para el Trabajo', subtitle: 'Capacitación integral para conectar con nuevas oportunidades' },
            { id: '7fQpAnZpEbk', title: 'Orgullo SENA', subtitle: 'Miles de talentos construyendo un mejor país' },
            { id: 'fmneZiWgtEU', title: 'Innovación y Futuro', subtitle: 'Apostando por la tecnología y el desarrollo regional' },
            { id: '2TVT-v56W9M', title: 'Crecemos Contigo', subtitle: 'Nuevas opciones de aprendizaje técnico y tecnológico' },
            { id: 'J5tfdua9zLo', title: 'Apoyo al Emprendimiento', subtitle: 'Tus ideas hechas realidad con Fondo Emprender' },
            { id: 'f2LA_i2MsPk', title: 'SENA es Empleo', subtitle: 'La Agencia Pública de Empleo más grande del país' }
        ];

        let currentVideoIndex = 0;
        const titleEl = document.getElementById('video-title');
        const subtitleEl = document.getElementById('video-subtitle');
        var player;

        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '100%', width: '100%', videoId: playlistIds[currentVideoIndex].id,
                playerVars: { 'autoplay': 1, 'controls': 0, 'mute': 1, 'rel': 0, 'showinfo': 0 },
                events: { 'onReady': (e) => { updateTextOverlay(); e.target.playVideo(); }, 'onStateChange': onPlayerStateChange }
            });
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                currentVideoIndex = (currentVideoIndex + 1) % playlistIds.length;
                player.loadVideoById(playlistIds[currentVideoIndex].id);
                updateTextOverlay();
            }
        }

        function updateTextOverlay() {
            const videoData = playlistIds[currentVideoIndex];
            titleEl.style.opacity = '0';
            setTimeout(() => {
                titleEl.textContent = videoData.title;
                subtitleEl.textContent = videoData.subtitle;
                titleEl.style.opacity = '1';
            }, 500);
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-CO', { hour: 'numeric', minute: '2-digit', hour12: true }).toUpperCase();
            document.getElementById('current-date').textContent = now.toLocaleDateString('es-CO', { year: 'numeric', month: 'long', day: 'numeric' });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>