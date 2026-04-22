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
</head>

<body class="bg-[#f0f2f5] text-gray-800 font-sans h-screen flex flex-col overflow-hidden">

    <!-- Header -->
    <header
        class="bg-white px-8 py-4 flex justify-between items-center shrink-0 border-b border-gray-200 shadow-sm relative z-20">
        <div class="flex items-center space-x-6">
            <img src="{{ asset('images/logoSena.png') }}" class="h-16 w-auto object-contain" alt="SENA Logo">
            <div class="h-10 w-px bg-gray-200"></div>
            <div>
                <h1 class="text-3xl font-poppins font-black text-gray-900 tracking-tight leading-none mb-1">SENA</h1>
                <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Sistema de Gestión de Turnos</p>
            </div>
        </div>
        <div class="flex items-center space-x-10 text-right">
            <div>
                <p class="text-3xl font-black text-gray-800 tracking-tight" id="current-time">10:45 AM</p>
                <p class="text-sm font-medium text-gray-500 mt-1" id="current-date">Actualizando...</p>
            </div>
            <div
                class="flex items-center space-x-3 text-gray-700 bg-gray-50 px-4 py-2 flex-col rounded-xl border border-gray-100 shadow-inner">
                <div class="flex space-x-2 items-center">
                    <i class="fa-regular fa-sun text-sena-orange text-2xl"></i>
                    <span class="text-xl font-black text-gray-800">24°C</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex p-6 gap-6 overflow-hidden min-h-0 relative z-10 w-full">

        <!-- Left Column: Turnos (Approx 38% width for better fit) -->
        <div
            class="w-[38%] bg-white rounded-[2rem] shadow-sm border border-gray-200 flex flex-col overflow-hidden relative">

            <!-- Header Left -->
            <div class="p-6 md:p-8 flex justify-between items-center shrink-0">
                <div class="flex items-center space-x-4">
                    <i class="fa-solid fa-list-check text-sena-500 text-2xl"></i>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Turnos en Atención</h2>
                </div>
                <span
                    class="bg-sena-yellow/20 text-sena-orange text-sm font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">En
                    vivo</span>
            </div>

            <!-- Table Header -->
            <div
                class="grid grid-cols-5 bg-[#f8fafc] px-6 py-4 border-y border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0">
                <div class="col-span-2 text-left ml-6">TURNO</div>
                <div class="col-span-3">MÓDULO / PROFESIONAL</div>
            </div>

            <!-- List / Dynamic Container -->
            <div class="flex-1 overflow-auto pb-32" id="contenedor-principal-lista">
                <!-- Zona de Turnos en Espera -->
                <div id="contenedor-espera">
                    @forelse($turnosEnEspera as $turno)
                        <div class="grid grid-cols-5 px-6 py-6 border-b border-gray-100 items-center hover:bg-gray-50 transition relative" data-id="{{ $turno->tur_id }}">
                            <div class="absolute left-0 top-2 bottom-2 w-1.5 {{ $turno->tur_tipo == 'Victimas' ? 'bg-rose-500' : ($turno->tur_tipo == 'Prioritario' ? 'bg-sena-orange' : 'bg-sena-blue') }} rounded-r-full"></div>
                            <div class="col-span-2 text-[2.75rem] font-black text-[#2e384d] tracking-tight ml-4">{{ $turno->tur_numero }}</div>
                            <div class="col-span-3 text-lg font-semibold text-[#4a5568] leading-tight">
                                <span class="text-xs font-black uppercase tracking-widest px-2 py-1 rounded {{ $turno->tur_tipo == 'Victimas' ? 'bg-rose-500 text-white' : ($turno->tur_tipo == 'Prioritario' ? 'bg-sena-orange text-white' : 'bg-sena-yellow/20 text-sena-blue') }} mb-2 inline-block">
                                    {{ $turno->tur_tipo == 'Victimas' ? 'Urgente' : ($turno->tur_tipo == 'Prioritario' ? 'Prioridad' : 'General') }}
                                </span><br>
                                <span class="text-base font-medium text-gray-400">En espera</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-500 text-lg font-medium">No hay turnos en espera</div>
                    @endforelse
                </div>

                <!-- Zona de Turno en Atención (El que acaba de ser llamado) -->
                <div id="contenedor-atencion">
                    @if($turnoActual)
                        <div class="grid grid-cols-5 px-6 py-6 border-b border-gray-100 items-center bg-[#f0fdf4] relative animate-pulse">
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-sena-blue"></div>
                            <div class="col-span-2 text-[3rem] font-black text-sena-blue tracking-tight ml-4">{{ $turnoActual->tur_numero }}</div>
                            <div class="col-span-3 flex items-center space-x-4">
                                <img src="{{ asset($turnoActual->ase_foto ?? 'images/foto de perfil.jpg') }}" class="w-16 h-16 rounded-2xl border-2 border-white shadow-sm object-cover">
                                <div class="leading-tight">
                                    <p class="text-xs font-black text-sena-orange uppercase tracking-[0.2em] mb-1">Pasar a:</p>
                                    <span class="text-2xl font-black text-gray-900">Módulo {{ sprintf('%02d', $turnoActual->modulo ?? '01') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Fixed Box (Próximo en llamado) -->
            <div class="absolute bottom-6 left-6 right-6 bg-[#0f172a] rounded-2xl p-6 flex justify-between items-center text-white shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] to-[#1e293b] z-0"></div>
                <div class="relative z-10 flex flex-col justify-center">
                    <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase mb-1">Próximo en llamado:</p>
                    <div class="flex items-baseline space-x-4">
                        <span class="text-4xl font-black text-white tracking-tight" id="box-proximo-numero">{{ $turnoActual->tur_numero ?? ($turnosEnEspera->first()->tur_numero ?? '---') }}</span>
                        <span class="text-gray-400 text-xl font-medium">Sala de espera</span>
                    </div>
                </div>
                <div class="relative z-10 w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center">
                    <i class="fa-regular fa-calendar-check text-sena-orange text-4xl"></i>
                </div>
            </div>
        </div>

        <!-- Right Column: Media and Cards (Approx 62% width) -->
        <div class="w-[62%] flex flex-col gap-6 h-full">

            <!-- Video/Image Box -->
            <div
                class="flex-1 relative rounded-[2rem] overflow-hidden shadow-sm border border-gray-200 bg-black flex flex-col group">
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

                <!-- Bottom Solid Text Bar -->
                <div class="bg-sena-blue px-10 py-5 min-h-[5.5rem] flex items-center z-10 border-t border-white/5">
                    <h3 id="video-subtitle" class="text-xl font-medium text-gray-400 tracking-wide m-0 leading-none transition-all duration-500">
                        Conoce nuestras nuevas convocatorias de formación titulada 2026
                    </h3>
                </div>
            </div>

            <!-- Bottom Cards Row (Fixed Height) -->
            <div class="flex gap-6 h-36 shrink-0">
                <!-- Card 1 -->
                <div
                    class="flex-1 bg-white rounded-[2rem] p-6 flex items-center gap-6 shadow-sm border border-gray-200 hover:shadow-md transition">
                    <div class="w-16 h-16 rounded-[1.2rem] bg-[#e8f5e9] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-qrcode text-sena-500 text-3xl"></i>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 leading-none tracking-tight">Descarga nuestra
                            App</h3>
                        <p class="text-sm text-gray-500 font-medium leading-[1.25]">Gestiona tus turnos
                            y<br>certificados desde tu celular.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div
                    class="flex-1 bg-white rounded-[2rem] p-6 flex items-center gap-6 shadow-sm border border-gray-200 hover:shadow-md transition">
                    <div class="w-16 h-16 rounded-[1.2rem] bg-[#e8f5e9] flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-headset text-sena-500 text-3xl"></i>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 leading-none tracking-tight">¿Necesitas ayuda?
                        </h3>
                        <p class="text-sm text-gray-500 font-medium leading-[1.25]">Escanea el código QR en
                            los<br>módulos para asistencia.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal de Llamado (Turno que va a ser atendido) -->
    <div id="llamado-modal" class="fixed inset-0 z-50 flex items-center justify-center p-10 bg-[#10069FB3] backdrop-blur-md transition-all duration-500 opacity-0 pointer-events-none scale-110">
        <div class="bg-white w-full max-w-5xl rounded-[4rem] p-16 shadow-2xl flex flex-col items-center text-center space-y-12 border-8 border-sena-orange/20 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#FFB5001A] rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-[#FF671F1A] rounded-full blur-3xl"></div>
            
            <div class="relative">
                <div class="absolute inset-0 bg-sena-orange/20 rounded-full animate-ping"></div>
                <div class="w-24 h-24 bg-sena-orange rounded-3xl flex items-center justify-center text-white text-5xl shadow-lg relative z-10">
                    <i class="fa-solid fa-microphone-lines"></i>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-2xl font-black text-sena-orange uppercase tracking-[0.4em]">Llamando al turno</p>
                <h3 id="modal-turno-numero" class="text-[12rem] font-poppins font-black text-sena-blue tracking-tighter leading-none italic">
                    ---
                </h3>
            </div>

            <div class="flex items-center space-x-12 bg-gray-50 px-16 py-10 rounded-[3rem] border-2 border-gray-100 shadow-inner">
                <div class="text-left">
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest mb-2">Diríjase al:</p>
                    <span id="modal-modulo-numero" class="text-7xl font-poppins font-black text-gray-900 italic">Módulo --</span>
                </div>
                <div class="h-20 w-px bg-gray-200"></div>
                <img id="modal-ase-foto" src="{{ asset('images/foto de perfil.jpg') }}" class="w-32 h-32 rounded-[2.5rem] border-4 border-white shadow-xl object-cover">
            </div>

            <div class="flex items-center space-x-4 text-sena-blue animate-pulse">
                <div class="w-3 h-3 rounded-full bg-sena-blue"></div>
                <span class="text-xl font-bold uppercase tracking-widest">Atención Inmediata</span>
                <div class="w-3 h-3 rounded-full bg-sena-blue"></div>
            </div>
        </div>
    </div>

    <!-- Overlay Activar Sonido -->
    <div id="audio-activation-overlay" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#10069F] p-6">
        <div class="bg-white rounded-[3rem] p-12 text-center max-w-lg shadow-2xl space-y-8">
            <div class="w-24 h-24 bg-[#FFB50033] rounded-full flex items-center justify-center mx-auto">
                <i class="fa-solid fa-volume-high text-sena-orange text-4xl animate-bounce"></i>
            </div>
            <div class="space-y-4">
                <h4 class="text-3xl font-black text-gray-900 tracking-tight">Activar Sistema de Voz</h4>
                <p class="text-gray-500 font-medium">Por políticas del navegador, debemos activar el sonido manualmente para anunciar los turnos.</p>
            </div>
                <button onclick="enableAudio()" class="w-full py-6 rounded-2xl bg-sena-blue text-white font-black text-xl uppercase tracking-widest hover:bg-sena-orange transition-all shadow-xl active:scale-95 flex items-center justify-center space-x-4">
                    <i class="fa-solid fa-play"></i>
                    <span>ACTIVAR Y PROBAR SONIDO</span>
                </button>
        </div>
    </div>

    <!-- Footer Marquee Fixed Height -->
    <footer
        class="h-[4.5rem] flex shrink-0 relative z-20 text-white overflow-hidden shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
        <!-- Left Banner (Matches Left Column Width exactly + gaps) -->
        <!-- We use roughly the width of the left column + padding approx w-[38%] -->
        <div class="bg-sena-blue w-[40%] flex items-center pl-10 space-x-4 border-r border-sena-white/20">
            <i class="fa-solid fa-bullhorn text-2xl"></i>
            <span class="text-lg font-bold tracking-widest uppercase">Agencia pública de empleo</span>
        </div>

        <!-- Right Banner Marquee -->
        <div class="bg-sena-orange flex-1 flex items-center relative overflow-hidden">
            <div
                class="absolute flex items-center space-x-12 whitespace-nowrap animate-[marquee_25s_linear_infinite] pl-10 text-xl font-bold tracking-wide">
                <span>Noticias SENA: Abiertas las inscripciones para cursos cortos.</span>
                <span class="opacity-50">•</span>
                <span>Conoce el nuevo centro de innovación tecnológica.</span>
                <span class="opacity-50">•</span>
                <span>SENA: de clase mundial.</span>
            </div>
        </div>
    </footer>

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
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

    <!-- Scripts: Reloj, Video y Lógica de Notificaciones -->
    <script>
        // --- CONFIGURACIÓN Y ESTADO ---
        let audioEnabled = false;
        let lastTurnIds = @json($turnosEnEspera->pluck('tur_id'));
        let lastCurrentAtncId = @json($turnoActual->atnc_id ?? null);
        const pollingInterval = 3000; // 3 segundos para mayor respuesta

        // --- SISTEMA DE AUDIO ROBUSTO (WEB AUDIO API) ---
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        let audioCtx = null;

        function playDing() {
            if (!audioCtx) return;
            // Ding-Dong Effect (Two tones: E5 then C5)
            const playNote = (freq, startTime, duration) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                const filter = audioCtx.createBiquadFilter();
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, startTime);
                
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(1000, startTime);
                
                gain.gain.setValueAtTime(0, startTime);
                gain.gain.linearRampToValueAtTime(0.3, startTime + 0.1);
                gain.gain.exponentialRampToValueAtTime(0.01, startTime + duration);
                
                osc.connect(filter);
                filter.connect(gain);
                gain.connect(audioCtx.destination);
                
                osc.start(startTime);
                osc.stop(startTime + duration);
            };

            const now = audioCtx.currentTime;
            playNote(659.25, now, 1.5);      // E5 (Ding)
            playNote(523.25, now + 0.5, 2);  // C5 (Dong)
        }

        function playBell() {
            if (!audioCtx) return;
            // Harmonic Triple Chime (A Major Triad: A4, C#5, E5)
            const playSoftNote = (freq, start) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, start);
                gain.gain.setValueAtTime(0, start);
                gain.gain.linearRampToValueAtTime(0.15, start + 0.2);
                gain.gain.exponentialRampToValueAtTime(0.001, start + 3);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(start);
                osc.stop(start + 3);
            };

            const now = audioCtx.currentTime;
            playSoftNote(440.00, now); // A4
            playSoftNote(554.37, now); // C#5
            playSoftNote(659.25, now); // E5
        }

        // --- LÓGICA DE AUDIO (WEB SPEECH API) ---
        function enableAudio() {
            try {
                audioCtx = new AudioContext(); // Activar el contexto de audio
                audioEnabled = true;
                document.getElementById('audio-activation-overlay').style.display = 'none';
                
                // Prueba inmediata vinculada al clic
                playBell(); 
                
                const testMsg = new SpeechSynthesisUtterance("Sistema de audio activado");
                testMsg.lang = 'es-ES';
                window.speechSynthesis.speak(testMsg);

                console.log("Sistema de audio activado con Web Audio API.");
            } catch (e) {
                alert("Error al activar audio: " + e.message);
            }
        }

        // Iniciar polling inmediatamente al cargar la página
        setInterval(checkUpdates, pollingInterval);

        function anunciarTurno(numero, modulo) {
            if (!audioEnabled || !audioCtx) return;
            
            // Sonido de alerta generado por el navegador
            playDing();
            
            setTimeout(() => {
                const mensaje = new SpeechSynthesisUtterance(`Turno ${numero.replace('-', ' ')}, por favor dirigirse al módulo ${modulo}`);
                mensaje.lang = 'es-ES';
                mensaje.rate = 0.9;
                mensaje.pitch = 1;
                window.speechSynthesis.speak(mensaje);
            }, 500);
        }

        // --- POLLING Y ACTUALIZACIÓN ---
        async function checkUpdates() {
            try {
                const response = await fetch('{{ route("pantalla.api.data") }}');
                const data = await response.json();
                
                // 1. Detectar Cambios en la lista de espera (Detección profunda)
                const currentTurnIds = data.turnosEnEspera.map(t => t.tur_id);
                const listChanged = currentTurnIds.length !== lastTurnIds.length || 
                                   currentTurnIds.some((id, index) => id !== lastTurnIds[index]);
                
                if (listChanged) {
                    if (audioEnabled) playBell();
                    updateWaitingList(data.turnosEnEspera);
                    lastTurnIds = currentTurnIds;
                }

                // 2. Detectar Nuevo Turno Llamado (Frecuente)
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
            document.getElementById('modal-turno-numero').textContent = turno.tur_numero;
            document.getElementById('modal-modulo-numero').textContent = `Módulo ${String(turno.modulo).padStart(2, '0')}`;
            document.getElementById('modal-ase-foto').src = turno.ase_foto;

            // Mostrar con animación
            modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-110');
            modal.classList.add('opacity-100', 'scale-100');

            anunciarTurno(turno.tur_numero, turno.modulo);

            // Ocultar tras 10 segundos
            setTimeout(() => {
                modal.classList.add('opacity-0', 'pointer-events-none', 'scale-110');
                modal.classList.remove('opacity-100', 'scale-100');
            }, 10000);
        }

        function updateWaitingList(turnos) {
            const container = document.getElementById('contenedor-espera');
            if (!container) return;

            if (turnos.length === 0) {
                container.innerHTML = '<div class="p-10 text-center text-gray-500 text-lg font-medium">No hay turnos en espera</div>';
                return;
            }

            let html = '';
            turnos.forEach(t => {
                const isUrgent = t.tur_tipo === 'Victimas';
                const isPriority = t.tur_tipo === 'Prioritario';
                
                const sideColor = isUrgent ? 'bg-rose-500' : (isPriority ? 'bg-sena-orange' : 'bg-sena-blue');
                const badgeClass = isUrgent ? 'bg-rose-500 text-white' : (isPriority ? 'bg-sena-orange text-white' : 'bg-sena-yellow/20 text-sena-blue');
                const badgeLabel = isUrgent ? 'Urgente' : (isPriority ? 'Prioridad' : 'General');

                html += `
                    <div class="grid grid-cols-5 px-6 py-6 border-b border-gray-100 items-center hover:bg-gray-50 transition relative animate-fade-in" data-id="${t.tur_id}">
                        <div class="absolute left-0 top-2 bottom-2 w-1.5 ${sideColor} rounded-r-full"></div>
                        <div class="col-span-2 text-[2.75rem] font-black text-[#2e384d] tracking-tight ml-4">${t.tur_numero}</div>
                        <div class="col-span-3 text-lg font-semibold text-[#4a5568] leading-tight">
                            <span class="text-xs font-black uppercase tracking-widest px-2 py-1 rounded ${badgeClass} mb-2 inline-block">${badgeLabel}</span><br>
                            <span class="text-base font-medium text-gray-400">En espera</span>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function updateCurrentTurnBox(turno) {
            const container = document.getElementById('contenedor-atencion');
            const proximoDisplay = document.getElementById('box-proximo-numero');

            if (!turno) {
                container.innerHTML = '';
                return;
            }

            // Actualizar cuadro verde inferior
            if (proximoDisplay) proximoDisplay.textContent = turno.tur_numero;

            // Actualizar zona de atención activa
            const moduloFormatted = String(turno.modulo).padStart(2, '0');
            container.innerHTML = `
                <div class="grid grid-cols-5 px-6 py-6 border-b border-gray-100 items-center bg-[#f0fdf4] relative animate-pulse">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-sena-blue"></div>
                    <div class="col-span-2 text-[3rem] font-black text-sena-blue tracking-tight ml-4">${turno.tur_numero}</div>
                    <div class="col-span-3 flex items-center space-x-4">
                        <img src="${turno.ase_foto}" class="w-16 h-16 rounded-2xl border-2 border-white shadow-sm object-cover">
                        <div class="leading-tight">
                            <p class="text-xs font-black text-sena-orange uppercase tracking-[0.2em] mb-1">Pasar a:</p>
                            <span class="text-2xl font-black text-gray-900">Módulo ${moduloFormatted}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // --- LÓGICA EXISTENTE (RELOJ Y VIDEO) ---
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
        const videoContainer = document.getElementById('video-container');
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