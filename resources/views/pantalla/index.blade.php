<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Turnos - SENA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css" crossorigin="anonymous">
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

            <!-- Header Left -->
            <div class="p-4 lg:p-6 flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-list-check text-sena-500 text-xl lg:text-2xl"></i>
                    <h2 class="text-xl lg:text-2xl font-black text-gray-800 tracking-tight">Turnos en Atención</h2>
                </div>
                <span class="bg-sena-yellow/20 text-sena-orange text-[10px] lg:text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">En vivo</span>
            </div>

            <!-- Table Header -->
            <div class="grid grid-cols-5 bg-[#f8fafc] px-4 lg:px-6 py-2 lg:py-4 border-y border-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider shrink-0">
                <div class="col-span-2 text-left ml-4 lg:ml-6">TURNO</div>
                <div class="col-span-3">MÓDULO / PROFESIONAL</div>
            </div>

            <!-- List / Dynamic Container -->
            <div class="flex-1 overflow-auto pb-4" id="contenedor-principal-lista">
                <!-- Turno en Atención — PRIMERO (arriba) -->
                <div id="contenedor-atencion" class="px-4 pt-4 pb-2">
                    @if($turnoActual)
                        <div class="bg-emerald-50 rounded-[1.5rem] lg:rounded-[2rem] p-3 lg:p-4 flex items-center justify-between border-2 border-emerald-200 shadow-lg shadow-emerald-100 animate-pulse relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">Atendiendo</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <h4 class="text-lg lg:text-xl font-black text-emerald-700 tracking-tighter">{{ $turnoActual->tur_numero }}</h4>
                                <div class="h-7 w-px bg-emerald-200"></div>
                                <div class="flex items-center space-x-2">
                                    <img src="{{ asset($turnoActual->ase_foto ?? 'images/foto de perfil.jpg') }}" class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl border-2 border-white shadow-md object-cover">
                                    <div>
                                        <p class="text-[8px] font-black text-emerald-600/60 uppercase tracking-widest">Pasar al:</p>
                                        <p class="text-sm font-black text-emerald-900 leading-none">Módulo {{ sprintf('%02d', $turnoActual->modulo ?? '01') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Turnos en Espera — DESPUÉS (abajo) -->
                <div id="contenedor-espera" class="p-4 space-y-4">
                    @forelse($turnosEnEspera as $turno)
                        @php
                            $isUrgent = $turno->tur_tipo == 'Victimas';
                            $isPriority = $turno->tur_tipo == 'Prioritario';
                            $sideColor = $isUrgent ? 'bg-rose-500' : ($isPriority ? 'bg-sena-orange' : 'bg-sena-blue');
                            $badgeClass = $isUrgent ? 'bg-rose-100 text-rose-600' : ($isPriority ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600');
                            $badgeLabel = $isUrgent ? 'Urgente' : ($isPriority ? 'Prioridad' : 'General');
                        @endphp
                        <div class="bg-gray-50/50 rounded-[1.5rem] lg:rounded-[2rem] p-3 lg:p-4 flex items-center justify-between border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-300 group" data-id="{{ $turno->tur_id }}">
                            <div class="flex items-center space-x-3 lg:space-x-4">
                                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl {{ $sideColor }} flex items-center justify-center text-white text-base lg:text-lg font-black shadow-lg group-hover:rotate-3 transition-transform">
                                    {{ substr($turno->tur_numero, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xl lg:text-2xl font-black text-gray-900 tracking-tighter">{{ $turno->tur_numero }}</h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">En espera</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400 text-lg font-medium italic">No hay turnos en espera</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Video (Approx 62% width) -->
        <div class="w-full lg:w-[62%] flex flex-col gap-4 lg:gap-6 h-full overflow-hidden">
            <div class="flex-1 relative rounded-[2rem] lg:rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-100 bg-black flex flex-col group transition-all duration-500">
                <div class="relative flex-1 bg-gray-900 overflow-hidden pointer-events-none">
                    <div class="absolute inset-0 w-full h-full transform scale-[1.35] transition-opacity duration-700" id="video-container">
                        <div id="youtube-player" class="w-full h-full"></div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/20 to-transparent"></div>
                </div>
                <div class="absolute bottom-[5.5rem] left-10 right-10 z-10">
                    <h2 id="video-title" class="text-[2.2rem] font-bold text-white leading-tight drop-shadow-md transition-all duration-500">
                        SENA: Transformando el futuro de Colombia
                    </h2>
                </div>
                <div class="bg-sena-blue/90 backdrop-blur-md px-8 py-4 min-h-[4rem] flex items-center z-10 border-t border-white/10">
                    <h3 id="video-subtitle" class="text-lg lg:text-xl font-medium text-gray-300 tracking-wide m-0 leading-tight transition-all duration-500">
                        Conoce nuestras nuevas convocatorias de formación titulada 2026
                    </h3>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de Llamado -->
    <div id="llamado-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 lg:p-8 bg-black/50 backdrop-blur-md transition-all duration-700 opacity-0 pointer-events-none">
        <div class="bg-white/10 backdrop-blur-3xl w-full max-w-2xl rounded-[2rem] lg:rounded-[3rem] p-5 lg:p-8 shadow-[0_32px_64px_-15px_rgba(0,0,0,0.6)] flex flex-col items-center text-center space-y-4 lg:space-y-6 border border-white/20 relative overflow-hidden transform scale-95 transition-transform duration-700">

            <div class="absolute -top-20 -right-20 w-48 h-48 bg-sena-orange/20 rounded-full blur-[60px]"></div>
            <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-sena-blue/20 rounded-full blur-[60px]"></div>

            <div class="relative shrink-0">
                <div class="absolute inset-0 bg-sena-orange/30 rounded-full animate-ping"></div>
                <div class="w-12 h-12 lg:w-16 lg:h-16 bg-gradient-to-br from-sena-orange to-orange-600 rounded-2xl flex items-center justify-center text-white text-2xl lg:text-3xl shadow-2xl relative z-10">
                    <i class="fa-solid fa-microphone-lines"></i>
                </div>
            </div>

            <div class="space-y-1 w-full">
                <p class="text-xs lg:text-sm font-black text-sena-yellow uppercase tracking-[0.4em] drop-shadow-sm">Llamando al turno</p>
                <h3 id="modal-turno-numero" class="text-4xl lg:text-6xl font-poppins font-black text-white tracking-tighter leading-none drop-shadow-2xl italic">
                    ---
                </h3>
                <p id="modal-ciudadano-nombre" class="text-base lg:text-xl font-bold text-white/90 tracking-tight transition-all duration-500 truncate max-w-full px-4">
                    ---
                </p>
            </div>

            <div class="w-full flex flex-row items-center justify-center space-x-6 lg:space-x-8 bg-white/5 px-5 lg:px-8 py-4 lg:py-5 rounded-2xl border border-white/10 shadow-2xl backdrop-blur-md">
                <div class="text-center sm:text-left">
                    <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.3em] mb-1">Por favor diríjase al:</p>
                    <span id="modal-modulo-numero" class="text-2xl lg:text-4xl font-poppins font-black text-sena-yellow italic drop-shadow-lg">Módulo --</span>
                </div>
                <div class="h-12 w-px bg-white/10"></div>
                <div class="relative shrink-0">
                    <img id="modal-ase-foto" src="{{ asset('images/foto de perfil.jpg') }}" class="w-14 h-14 lg:w-20 lg:h-20 rounded-2xl border-2 border-white/20 shadow-2xl object-cover relative z-10">
                </div>
            </div>

            <div class="flex items-center space-x-3 text-sena-yellow/80 animate-pulse shrink-0">
                <div class="w-1.5 h-1.5 rounded-full bg-sena-yellow"></div>
                <span class="text-xs lg:text-sm font-black uppercase tracking-[0.5em]">Atención Inmediata</span>
                <div class="w-1.5 h-1.5 rounded-full bg-sena-yellow"></div>
            </div>
        </div>
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

    <!-- Scripts: Reloj, Video y Lógica de Notificaciones -->
    <script>
        // --- CONFIGURACIÓN Y ESTADO ---
        const AUDIO_CONFIG = {
            voiceRate: 0.85,
            voicePitch: 1.0,
            voiceLang: 'es-CO',
            chimeVolume: 0.4,
            voiceVolume: 1.0
        };

        let audioEnabled = true;
        let audioCtx = null;
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

        // --- SISTEMA DE AUDIO ---
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        let vozEspanol = null;
        let audioListo = true;

        function cargarVoz() {
            const voces = window.speechSynthesis.getVoices();
            if (voces.length > 0) {
                vozEspanol = voces.find(v => v.lang === 'es-CO')
                          || voces.find(v => v.lang === 'es-ES')
                          || voces.find(v => v.lang.startsWith('es'))
                          || voces[0];
            }
        }

        function desbloquearAudio() {
            // AudioContext DEBE crearse dentro de un evento de usuario
            try {
                if (!audioCtx) {
                    audioCtx = new AudioContext();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                audioEnabled = true;
            } catch(e) {
                console.warn('AudioContext no disponible:', e);
            }

            // Desbloquear SpeechSynthesis con utterance silencioso
            try {
                window.speechSynthesis.cancel();
                const u = new SpeechSynthesisUtterance('.');
                u.volume = 0.01;
                u.lang = 'es-CO';
                u.rate = 2;
                window.speechSynthesis.speak(u);
            } catch(e) {}

            cargarVoz();
            audioListo = true;
        }

        // Polling arranca automáticamente (no necesita audio)
        document.addEventListener('DOMContentLoaded', function () {
            cargarVoz();
            window.speechSynthesis.onvoiceschanged = cargarVoz;
            setInterval(checkUpdates, pollingInterval);

            // Activar audio automáticamente con el primer clic/toque en cualquier parte
            const activarConGesto = () => {
                desbloquearAudio();
                document.removeEventListener('click', activarConGesto);
                document.removeEventListener('touchstart', activarConGesto);
                document.removeEventListener('keydown', activarConGesto);
            };
            document.addEventListener('click', activarConGesto);
            document.addEventListener('touchstart', activarConGesto);
            document.addEventListener('keydown', activarConGesto);
        });

        function playProfessionalChime() {
            try {
                if (!audioCtx) audioCtx = new AudioContext();
                if (audioCtx.state === 'suspended') audioCtx.resume();
                
                const now = audioCtx.currentTime;
                [440, 554.37, 659.25].forEach((freq, index) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, now + (index * 0.1));
                    gain.gain.setValueAtTime(0, now);
                    gain.gain.linearRampToValueAtTime(AUDIO_CONFIG.chimeVolume / 3, now + 0.2 + (index * 0.1));
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 2 + (index * 0.1));
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(now);
                    osc.stop(now + 3);
                });
            } catch(e) { console.warn("Error en timbre:", e); }
        }

        // --- SISTEMA DE COLA DE ANUNCIOS ---
        function anunciarTurno(numero, modulo) {
            announcementQueue.push({ numero, modulo });
            processQueue();
        }

        async function processQueue() {
            if (isSpeaking || announcementQueue.length === 0) return;
            isSpeaking = true;
            const turno = announcementQueue.shift();

            // Intentar sonar el timbre (el navegador lo permitirá si ya hubo interacción o permisos)
            playProfessionalChime();

            setTimeout(() => {
                try {
                    // Intentar reactivar audio context si está suspendido
                    if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume().catch(() => {});
                    
                    window.speechSynthesis.cancel();

                    const partes = turno.numero.split('-');
                    const letra  = partes[0] || '';
                    const num    = parseInt(partes[1]) || 0;
                    const texto  = `Turno ${letra} ${num}, por favor dirigirse al módulo ${turno.modulo}`;

                    const mensaje = new SpeechSynthesisUtterance(texto);
                    if (vozEspanol) mensaje.voice = vozEspanol;
                    mensaje.lang   = AUDIO_CONFIG.voiceLang;
                    mensaje.rate   = AUDIO_CONFIG.voiceRate;
                    mensaje.pitch  = AUDIO_CONFIG.voicePitch;
                    mensaje.volume = AUDIO_CONFIG.voiceVolume;

                    mensaje.onend = () => {
                        isSpeaking = false;
                        setTimeout(processQueue, 800);
                    };
                    mensaje.onerror = () => {
                        isSpeaking = false;
                        setTimeout(processQueue, 800);
                    };

                    window.speechSynthesis.speak(mensaje);

                    // Watchdog: liberar cola si no habla en 8s
                    setTimeout(() => {
                        if (isSpeaking) { isSpeaking = false; processQueue(); }
                    }, 8000);
                } catch(e) {
                    isSpeaking = false;
                }
            }, 1200);
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
            document.getElementById('modal-modulo-numero').textContent = `Módulo ${String(turno.modulo).padStart(2, '0')}`;
            document.getElementById('modal-ase-foto').src = turno.ase_foto;

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            innerModal.classList.remove('scale-95');
            innerModal.classList.add('scale-100');

            anunciarTurno(turno.tur_numero, turno.modulo);

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
                container.innerHTML = '<div class="p-10 text-center text-gray-400 text-lg font-medium italic">No hay turnos en espera</div>';
                return;
            }
            let html = '';
            turnos.forEach(t => {
                const isUrgent = t.tur_tipo === 'Victimas';
                const isPriority = t.tur_tipo === 'Prioritario';
                const sideColor = isUrgent ? 'bg-rose-500' : (isPriority ? 'bg-sena-orange' : 'bg-sena-blue');
                const badgeClass = isUrgent ? 'bg-rose-100 text-rose-600' : (isPriority ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600');
                const badgeLabel = isUrgent ? 'Urgente' : (isPriority ? 'Prioridad' : 'General');
                const firstChar = t.tur_numero.charAt(0);
                html += `
                    <div class="bg-gray-50/50 rounded-[1.5rem] lg:rounded-[2rem] p-3 lg:p-4 flex items-center justify-between border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-300 group animate-fade-in" data-id="${t.tur_id}">
                        <div class="flex items-center space-x-3 lg:space-x-4">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl ${sideColor} flex items-center justify-center text-white text-base lg:text-lg font-black shadow-lg group-hover:rotate-3 transition-transform">
                                ${firstChar}
                            </div>
                            <div>
                                <h4 class="text-xl lg:text-2xl font-black text-gray-900 tracking-tighter">${t.tur_numero}</h4>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">En espera</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest ${badgeClass}">
                            ${badgeLabel}
                        </span>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function updateCurrentTurnBox(turno) {
            const container = document.getElementById('contenedor-atencion');
            if (!turno) {
                container.innerHTML = '';
                return;
            }
            const moduloFormatted = String(turno.modulo).padStart(2, '0');
            container.innerHTML = `
                <div class="bg-emerald-50 rounded-[1.5rem] lg:rounded-[2rem] p-3 lg:p-4 flex items-center justify-between border-2 border-emerald-200 shadow-lg shadow-emerald-100 animate-pulse relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2">
                        <span class="bg-emerald-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest">Atendiendo</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <h4 class="text-lg lg:text-xl font-black text-emerald-700 tracking-tighter">${turno.tur_numero}</h4>
                        <div class="h-7 w-px bg-emerald-200"></div>
                        <div class="flex items-center space-x-2">
                            <img src="${turno.ase_foto}" class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl border-2 border-white shadow-md object-cover">
                            <div>
                                <p class="text-[8px] font-black text-emerald-600/60 uppercase tracking-widest">Pasar al:</p>
                                <p class="text-sm font-black text-emerald-900 leading-none">Módulo ${moduloFormatted}</p>
                            </div>
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