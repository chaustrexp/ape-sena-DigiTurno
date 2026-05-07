<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA APE - Kiosco Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {theme: {extend: {fontFamily: { sans: ['Inter', 'sans-serif'], poppins: ['Poppins', 'sans-serif']}, colors: {sena: { 50: '#F0F5FF', 100: '#C6D4FF', 500: '#10069F', 600: '#000080', orange: '#FF671F', yellow: '#FFB500'}}}}}
    </script>
    <style>
        :root {
            --sena-navy: #000080;
            --sena-green: #39A900;
        }
        .bg-sena-navy { background-color: var(--sena-navy); }
        .text-sena-navy { color: var(--sena-navy); }
        .bg-sena-green { background-color: var(--sena-green); }
        .text-sena-green { color: var(--sena-green); }
        .border-sena-green { border-color: var(--sena-green); }

        body {
            background-color: #0f172a;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        .video-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; pointer-events: none; overflow: hidden;
        }
        .video-container iframe {
            width: 100vw; height: 56.25vw; min-height: 100vh; min-width: 177.77vh;
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        }
        .video-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,128,0.3), rgba(0,0,0,0.7));
            backdrop-filter: blur(2px);
        }
        .main-card {
            background-color: rgba(255,255,255,0.85);
            backdrop-filter: blur(25px);
            box-shadow: 0 50px 100px -20px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.5);
        }
        .step-content { display: none; width: 100%; }
        .step-content.active { display: flex; animation: fadeInScale 0.7s cubic-bezier(0.23,1,0.32,1) forwards; }
        @keyframes fadeInScale { from { opacity: 0; transform: scale(1.02); } to { opacity: 1; transform: scale(1); } }
        .progress-dot { @apply w-2.5 h-2.5 rounded-full bg-gray-200 transition-all duration-500; }
        .progress-dot.active { @apply bg-sena-500 w-8; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #10069F; border-radius: 10px; }
        .main-card { overscroll-behavior: contain; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative p-2 md:p-6 antialiased">

<!-- YouTube Video Background -->
<div class="video-container">
    <iframe 
        src="https://www.youtube.com/embed/_MZRAUSIZtQ?autoplay=1&mute=1&loop=1&playlist=_MZRAUSIZtQ&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1" 
        frameborder="0" 
        allow="autoplay; encrypted-media" 
        allowfullscreen>
    </iframe>
    <div class="video-overlay"></div>
</div>

<!-- Alerts -->
<div class="fixed top-8 left-1/2 transform -translate-x-1/2 z-[100] w-full max-w-2xl px-4 space-y-4">
    @if(session('success'))
    <div class="bg-emerald-500 text-white p-6 rounded-[2rem] shadow-2xl flex items-center space-x-4 animate-[bounce_1s_ease-in-out_1] border-4 border-white">
        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-2xl shrink-0"><i class="fa-solid fa-check"></i></div>
        <div><p class="font-black uppercase tracking-widest text-xs">¡Turno Generado exitosamente!</p><p class="text-lg font-poppins font-black">{{ session('success') }}</p></div>
    </div>
    @endif
    @if(session('error'))
    <div id="kiosco-error-alert" class="bg-rose-500 text-white p-4 rounded-2xl shadow-2xl flex items-center space-x-3 border-2 border-white">
        <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center text-lg shrink-0"><i class="fa-solid fa-xmark"></i></div>
        <div><p class="font-black uppercase tracking-widest text-xs">Atención</p><p class="text-sm font-bold">{{ session('error') }}</p></div>
    </div>
    <script>
        setTimeout(function() {
            var el = document.getElementById('kiosco-error-alert');
            if (el) {
                el.style.transition = 'opacity 0.5s ease-out';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 500);
            }
        }, 2000);
    </script>
    @endif
</div>

<div class="w-full max-w-4xl mx-auto main-card rounded-[2rem] flex flex-col relative shadow-2xl overflow-hidden bg-white/90 backdrop-blur-xl border border-white/20" style="height: 85vh; max-height: 720px;">

    <!-- Top Bar (Fixed) -->
    <header class="w-full px-5 py-2.5 flex items-center justify-between shrink-0 bg-white/50 border-b border-slate-100 z-30">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.jpeg') }}" class="w-6 h-6 object-contain rounded" alt="SENA Logo">
            <h1 class="text-base font-poppins font-black text-sena-navy tracking-tight uppercase">SENA Digital</h1>
        </div>
        <div class="flex items-center space-x-3">
            <div class="hidden sm:flex items-center space-x-1.5 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-200">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Activo</span>
            </div>
            <div class="flex items-center space-x-1.5 text-sena-navy">
                <i class="fa-solid fa-clock text-[10px]"></i>
                <span id="kiosco-clock" class="text-[10px] font-black tracking-widest">00:00 AM</span>
            </div>
        </div>
    </header>

    <!-- Content Area (Scrollable) -->
    <form id="kioskForm" action="{{ route('turnos.store') }}" method="POST" onsubmit="return validateForm();" class="flex-1 w-full overflow-y-auto overflow-x-hidden pt-4 pb-10 custom-scrollbar">
        @csrf

        <!-- STEP 1: BIENVENIDA -->
        <div id="step1" class="step-content active w-full flex-col items-center justify-center text-center px-5 md:px-8 py-3 md:py-5 space-y-4 md:space-y-6">
            <div class="space-y-2 max-w-2xl">
                <h1 id="welcomeTitle" class="text-2xl md:text-4xl lg:text-5xl font-black text-sena-navy tracking-tight leading-tight uppercase">Bienvenido al <br>Centro de Atención</h1>
                <p id="welcomeDescription" class="text-xs md:text-sm font-medium text-slate-500 max-w-xl mx-auto">Por favor toca el botón para iniciar tu proceso de asignación de turno.</p>
            </div>

            <button type="button" onclick="nextStep(2); playKey();" class="group relative px-8 md:px-10 py-3.5 md:py-5 bg-sena-navy rounded-2xl overflow-hidden hover:scale-105 active:scale-95 transition-all duration-500 shadow-xl mx-auto flex items-center gap-3">
                <div class="absolute inset-0 bg-gradient-to-r from-sena-navy to-slate-800 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <i class="fa-solid fa-hand-pointer text-white text-lg relative z-10"></i>
                <span id="startButton" class="relative text-white font-black text-base md:text-lg uppercase tracking-[0.2em]">Empezar Aquí</span>
            </button>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full max-w-4xl pt-2">
                <div class="bg-white/50 backdrop-blur-md p-3.5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center space-y-1.5">
                    <div class="w-8 h-8 bg-sena-green/10 text-sena-green rounded-lg flex items-center justify-center text-sm"><i class="fa-solid fa-gauge-high"></i></div>
                    <h4 class="text-sm font-black text-sena-navy">Atención Ágil</h4>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none">Mínima espera</p>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-3.5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center space-y-1.5">
                    <div class="w-8 h-8 bg-sena-green/10 text-sena-green rounded-lg flex items-center justify-center text-sm"><i class="fa-solid fa-list-check"></i></div>
                    <h4 class="text-sm font-black text-sena-navy">Trámites Claros</h4>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none">Transparencia</p>
                </div>
                <div class="bg-white/50 backdrop-blur-md p-3.5 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center space-y-1.5">
                    <div class="w-8 h-8 bg-sena-green/10 text-sena-green rounded-lg flex items-center justify-center text-sm"><i class="fa-solid fa-hands-holding-child"></i></div>
                    <h4 class="text-sm font-black text-sena-navy">Apoyo Humano</h4>
                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest leading-none">Personalizado</p>
                </div>
            </div>
        </div>

        <!-- STEP 2: TRATAMIENTO DE DATOS -->
        <div id="step2" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-4">
            <div class="text-center space-y-2">
                <h3 class="text-3xl font-poppins font-black text-[#1e293b] tracking-tighter leading-none">Tratamiento de Datos</h3>
                <p class="text-xs text-slate-500 font-medium">Para brindarte un servicio personalizado, requerimos procesar tu información institucional.</p>
                <div class="inline-block bg-sena-50 px-4 py-1.5 rounded-xl border border-sena-100">
                    <span class="text-[9px] font-black text-sena-500 uppercase tracking-[0.2em]">Aceptación de Términos Institucionales</span>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 w-full max-w-3xl">
                @php $newTerms = [
                    ['icon'=>'fa-shield-heart',    'title'=>'Privacidad',        'desc'=>'Datos protegidos bajo estándares internacionales.',    'color'=>'bg-sena-50 text-sena-500'],
                    ['icon'=>'fa-user-check',       'title'=>'Seguridad',         'desc'=>'Protocolos robustos contra acceso no autorizado.',     'color'=>'bg-sena-50 text-sena-500'],
                    ['icon'=>'fa-users-viewfinder', 'title'=>'Uso Personal',      'desc'=>'Información usada exclusivamente para trámites SENA.', 'color'=>'bg-blue-50 text-blue-500'],
                    ['icon'=>'fa-fingerprint',      'title'=>'Identidad Digital', 'desc'=>'Perfil digital para agilizar futuros accesos.',         'color'=>'bg-sena-50 text-sena-500'],
                ]; @endphp
                @foreach($newTerms as $t)
                <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center space-y-1.5 transition-all hover:shadow-md">
                    <div class="w-8 h-8 {{ $t['color'] }} rounded-lg flex items-center justify-center text-base"><i class="fa-solid {{ $t['icon'] }}"></i></div>
                    <h4 class="text-[11px] font-black text-slate-800">{{ $t['title'] }}</h4>
                    <p class="text-[8px] text-slate-400 font-bold leading-tight uppercase tracking-tight">{{ $t['desc'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="w-full max-w-xl bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <label class="flex items-center gap-3 cursor-pointer group" for="termsCheck">
                    <div class="relative shrink-0">
                        <input type="checkbox" id="termsCheck" onchange="toggleBtn(this)" class="peer appearance-none w-8 h-8 rounded-lg bg-slate-50 border-2 border-slate-200 checked:bg-sena-500 checked:border-sena-500 transition-all cursor-pointer">
                        <i class="fa-solid fa-check absolute inset-0 flex items-center justify-center text-white text-sm scale-0 peer-checked:scale-100 transition-transform pointer-events-none"></i>
                    </div>
                    <span class="text-sm font-black text-slate-700 leading-tight">Autorizo el tratamiento de mis datos personales según las políticas del SENA.</span>
                </label>
            </div>
            <div class="flex gap-3 w-full max-w-2xl">
                <button type="button" onclick="nextStep(1)" class="px-7 py-3.5 bg-white border border-gray-200 rounded-xl text-gray-400 font-black uppercase tracking-widest text-xs hover:bg-gray-50 active:scale-95 transition-all">Volver</button>
                <button type="button" id="nextBtn" onclick="nextStep(3)" disabled class="flex-1 py-3.5 rounded-xl bg-gray-200 text-gray-400 font-black flex items-center justify-center gap-2 cursor-not-allowed opacity-50 transition-all text-xs uppercase tracking-widest">
                    <span>ACEPTAR Y CONTINUAR</span><i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: PERFIL DE ATENCIÓN -->
        <div id="step3" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-4">
            <div class="text-center space-y-1.5">
                <h2 class="text-2xl font-poppins font-black text-[#1e293b] tracking-tight leading-tight">¿Cuál es su <span class="text-sena-500">categoría</span> de usuario?</h2>
                <p class="text-xs font-medium text-slate-500">Seleccione la opción que mejor describa su condición.</p>
            </div>
            @php $profiles = [
                ['id'=>'General',    'icon'=>'fa-user',      'title'=>'General',    'badge'=>'ATENCIÓN NORMAL',           'desc'=>'Sin condiciones especiales de prioridad.',                   'color'=>'bg-slate-100 text-slate-500',  'border'=>'border-gray-100'],
                ['id'=>'Prioritario','icon'=>'fa-wheelchair','title'=>'Prioritario','badge'=>'ADULTO MAYOR / DISCAPACIDAD','desc'=>'Atención preferencial para movilidad reducida.',             'color'=>'bg-sena-50 text-sena-500',     'border'=>'border-sena-200'],
                ['id'=>'Victima',    'icon'=>'fa-award',     'title'=>'Víctima',    'badge'=>'PRIORIDAD MÁXIMA',          'desc'=>'Atención inmediata bajo la ley de víctimas.',                'color'=>'bg-orange-50 text-orange-500', 'border'=>'border-orange-200'],
                ['id'=>'Empresario', 'icon'=>'fa-building',  'title'=>'Empresario', 'badge'=>'ALIANZAS Y EMPLEO',         'desc'=>'Empresas buscando servicios de formación o empleo.',         'color'=>'bg-blue-50 text-blue-500',     'border'=>'border-blue-200'],
            ]; @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 w-full">
                @foreach($profiles as $p)
                <div onclick="selectType('{{ $p['id'] }}')" class="bg-white p-4 rounded-2xl border {{ $p['border'] }} shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer group flex flex-col items-center text-center space-y-2">
                    <div class="w-10 h-10 {{ $p['color'] }} rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform"><i class="fa-solid {{ $p['icon'] }}"></i></div>
                    <h4 class="text-sm font-black text-slate-800">{{ $p['title'] }}</h4>
                    <div class="px-2 py-0.5 rounded-full {{ $p['color'] }}"><span class="text-[7px] font-black uppercase tracking-wider">{{ $p['badge'] }}</span></div>
                    <p class="text-[9px] font-medium text-slate-400 leading-relaxed">{{ $p['desc'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="w-full bg-slate-50 p-3 rounded-xl border border-gray-100 text-center">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Al seleccionar su categoría, el sistema le asignará un turno. Si necesita asistencia presione <span class="text-sena-500">Ayuda</span>.</p>
            </div>
        </div>

        <!-- STEP 3.5: SERVICIO Y TIPO DE ATENCIÓN -->
        <div id="step3_5" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-5">
            <div class="text-center space-y-2 max-w-3xl mx-auto">
                <h2 class="text-3xl font-poppins font-black text-[#1e293b] tracking-tighter leading-none">DETALLES DE LA <span class="text-sena-500">VISITA</span></h2>
                <p class="text-xs text-slate-400 font-medium">Debe seleccionar <strong>ambas opciones</strong> para poder continuar.</p>
            </div>

            <div id="step35-error" class="hidden w-full max-w-4xl bg-rose-50 border-2 border-rose-200 rounded-xl p-3 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-base shrink-0"></i>
                <p id="step35-error-msg" class="text-xs font-bold text-rose-600"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-sena-500 uppercase tracking-[0.3em]">Tipo de Servicio</span>
                        <span id="service-required" class="text-rose-500 text-[10px] font-black hidden">✕ Obligatorio</span>
                        <span id="service-ok" class="text-green-500 text-[10px] font-black hidden">✓ Seleccionado</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2.5" id="service-container">
                        @foreach(['Orientacion'=>'Orientación Laboral','Formacion'=>'Formación Profesional','Emprendimiento'=>'Emprendimiento'] as $val=>$label)
                        <button type="button" onclick="setService('{{ $val }}', this); playKey();" class="service-btn w-full p-4 text-left rounded-xl border-2 transition-all flex items-center justify-between group border-gray-100 text-slate-500">
                            <span class="text-xs font-black uppercase tracking-widest">{{ $label }}</span>
                            <i class="fa-solid fa-circle-check text-sena-500 opacity-0 transition-opacity"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-sena-500 uppercase tracking-[0.3em]">Tipo de Atención</span>
                        <span id="attention-required" class="text-rose-500 text-[10px] font-black hidden">✕ Obligatorio</span>
                        <span id="attention-ok" class="text-green-500 text-[10px] font-black hidden">✓ Seleccionado</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2.5" id="attention-container">
                        @foreach(['Normal'=>'Atención Normal','Especial'=>'Trámite Especial / Radicación'] as $val=>$label)
                        <button type="button" onclick="setAttentionType('{{ $val }}', this); playKey();" class="attention-btn w-full p-4 text-left rounded-xl border-2 transition-all flex items-center justify-between group border-gray-100 text-slate-500">
                            <span class="text-xs font-black uppercase tracking-widest">{{ $label }}</span>
                            <i class="fa-solid fa-circle-check text-sena-500 opacity-0 transition-opacity"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="button" onclick="validateStep3_5();" class="px-12 py-4 bg-sena-500 text-white font-black rounded-full shadow-xl hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-widest text-sm">
                Continuar <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </div>

        <!-- STEP 4: IDENTIDAD -->
        <div id="step4" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-3">
            <div class="text-center space-y-1">
                <h3 class="text-3xl font-poppins font-black text-[#1e293b] tracking-tighter leading-none">Ingrese su <span class="text-sena-500">Documento</span></h3>
                <p class="text-xs text-slate-500 font-medium">Seleccione el tipo e ingrese su número de identificación.</p>
            </div>
            <div class="flex gap-2 w-full max-w-xl">
                <button type="button" onclick="setDocType('CC', this); playKey();" id="docTabCC" class="doc-tab-btn flex-1 py-3 rounded-xl border-2 font-black text-xs uppercase tracking-widest transition-all border-sena-500 bg-sena-50 text-sena-500 shadow-md">Cédula</button>
                <button type="button" onclick="setDocType('CE', this); playKey();" id="docTabCE" class="doc-tab-btn flex-1 py-3 rounded-xl border-2 font-black text-xs uppercase tracking-widest transition-all border-gray-100 text-slate-400 hover:border-gray-200">Extranjería</button>
                <button type="button" onclick="setDocType('TI', this); playKey();" id="docTabTI" class="doc-tab-btn flex-1 py-3 rounded-xl border-2 font-black text-xs uppercase tracking-widest transition-all border-gray-100 text-slate-400 hover:border-gray-200">T. Identidad</button>
            </div>
            <div class="w-full max-w-xl bg-white border-4 border-gray-100 rounded-2xl px-6 py-4 flex flex-col items-center shadow-inner">
                <span id="docDisplay" class="text-4xl font-black text-[#1e293b] tracking-widest flex-1 text-center truncate w-full">_ _ _ _ _ _ _ _ _ _</span>
                <span id="docHint" class="text-[10px] font-bold text-slate-400 mt-1 tracking-widest uppercase">Cédula: entre 8 y 10 dígitos</span>
            </div>
            <div class="grid grid-cols-3 gap-2 w-full max-w-xl">
                @for($i=1;$i<=9;$i++)
                <button type="button" onclick="pressNum('{{ $i }}'); playKey();" class="h-11 bg-slate-50 hover:bg-white border-2 border-transparent hover:border-sena-100 rounded-xl flex items-center justify-center text-xl font-black text-slate-700 shadow-sm hover:shadow-md transition-all active:scale-95">{{ $i }}</button>
                @endfor
                <button type="button" onclick="clearNum(); playKey();" class="h-11 bg-rose-50 hover:bg-rose-100 rounded-xl flex items-center justify-center text-base text-rose-500 shadow-sm transition-all active:scale-95"><i class="fa-solid fa-trash-can"></i></button>
                <button type="button" onclick="pressNum('0'); playKey();" class="h-11 bg-slate-50 hover:bg-white border-2 border-transparent hover:border-sena-100 rounded-xl flex items-center justify-center text-xl font-black text-slate-700 shadow-sm hover:shadow-md transition-all active:scale-95">0</button>
                <button type="button" onclick="backspace(); playKey();" class="h-11 bg-slate-50 hover:bg-white rounded-xl flex items-center justify-center text-base text-slate-400 shadow-sm transition-all active:scale-95"><i class="fa-solid fa-delete-left"></i></button>
            </div>
            <button type="button" onclick="validateDoc(); playKey();" class="w-full max-w-xl py-4 bg-sena-orange text-white font-black text-base rounded-2xl shadow-xl hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest">
                <span>CONTINUAR</span><i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

        <!-- STEP 5: CONTACTO -->
        <div id="step5" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-3">
            <div class="text-center space-y-1">
                <h3 class="text-2xl font-poppins font-black text-[#1e293b] tracking-tight">Datos de <span class="text-sena-500">Contacto</span></h3>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Ingrese su número celular para el turno</p>
            </div>
            <div class="w-full max-w-xl bg-white border-4 border-gray-100 rounded-2xl px-5 py-3.5 flex items-center shadow-inner">
                <span class="text-2xl font-black text-sena-500 opacity-50 mr-3 shrink-0">+57</span>
                <span id="phoneDisplay" class="text-3xl font-black text-[#1e293b] tracking-widest flex-1 text-center">300 000 0000</span>
                <div class="w-0.5 h-8 bg-sena-500 animate-pulse rounded-full ml-2 shrink-0"></div>
            </div>
            <div class="grid grid-cols-3 gap-2 w-full max-w-xl">
                @for($i=1;$i<=9;$i++)
                <button type="button" onclick="pressPhone('{{ $i }}'); playKey();" class="h-12 bg-white hover:bg-slate-50 border-2 border-transparent hover:border-sena-100 rounded-xl flex items-center justify-center text-xl font-black text-slate-700 shadow-sm hover:shadow-md transition-all active:scale-95">{{ $i }}</button>
                @endfor
                <button type="button" onclick="phoneNumber=''; updatePhoneDisplay(); playKey();" class="h-12 bg-white rounded-xl flex items-center justify-center text-base text-slate-300 shadow-sm hover:text-rose-500 transition-colors"><i class="fa-solid fa-rotate-right"></i></button>
                <button type="button" onclick="pressPhone('0'); playKey();" class="h-12 bg-white hover:bg-slate-50 border-2 border-transparent hover:border-sena-100 rounded-xl flex items-center justify-center text-xl font-black text-slate-700 shadow-sm hover:shadow-md transition-all active:scale-95">0</button>
                <button type="button" onclick="backspacePhone(); playKey();" class="h-12 bg-white rounded-xl flex items-center justify-center text-base text-slate-300 shadow-sm hover:text-amber-500 transition-colors"><i class="fa-solid fa-delete-left"></i></button>
            </div>
            <button type="button" onclick="validatePhoneStep(); playKey();" class="w-full max-w-xl py-4 bg-sena-orange text-white font-black text-base rounded-2xl shadow-xl hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest">
                <span>CONTINUAR</span><i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <!-- STEP 6: CANAL DE ENTREGA -->
        <div id="step6" class="step-content w-full flex-col items-center justify-start p-4 md:p-7 space-y-4">
            <div class="text-center space-y-1">
                <h3 class="text-2xl font-poppins font-black text-[#1e293b] tracking-tight">Canal de <span class="text-sena-500">Entrega</span></h3>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest">¿Por qué medio desea recibir su turno?</p>
            </div>
            <div class="grid grid-cols-4 gap-2.5 w-full">
                @php $methods = [['id'=>'SMS','icon'=>'fa-comment-sms','title'=>'SMS','fab'=>false],['id'=>'WhatsApp','icon'=>'fa-whatsapp','title'=>'WhatsApp','fab'=>true],['id'=>'Email','icon'=>'fa-envelope','title'=>'Email','fab'=>false],['id'=>'QR','icon'=>'fa-qrcode','title'=>'Código QR','fab'=>false]]; @endphp
                @foreach($methods as $m)
                <button type="button" onclick="selectChannel('{{ $m['id'] }}', this); playKey();" class="receive-card bg-white p-3.5 rounded-xl border-2 border-gray-100 transition-all duration-300 flex flex-col items-center gap-2 hover:border-sena-500 hover:shadow-md active:scale-95">
                    <div class="w-10 h-10 bg-gray-50 text-gray-400 rounded-lg flex items-center justify-center text-xl transition-colors">
                        <i class="{{ $m['fab'] ? 'fa-brands' : 'fa-solid' }} {{ $m['icon'] }}"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 uppercase tracking-widest">{{ $m['title'] }}</span>
                </button>
                @endforeach
            </div>
            <div id="channel-panel" class="w-full hidden">
                <div id="panel-WhatsApp" class="channel-content hidden bg-green-50 border border-green-200 rounded-xl p-4 text-center space-y-2">
                    <i class="fa-brands fa-whatsapp text-3xl text-green-500"></i>
                    <p class="text-xs font-bold text-slate-600">Se enviará la confirmación al número <span id="wa-number" class="text-green-600 font-black"></span></p>
                </div>
                <div id="panel-Email" class="channel-content hidden bg-blue-50 border border-blue-200 rounded-xl p-4 space-y-2">
                    <p class="text-xs font-bold text-slate-600 text-center">Ingrese su correo electrónico</p>
                    <input type="email" id="email-input" name="pers_email" placeholder="ejemplo@correo.com" class="w-full bg-white border-2 border-blue-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-sena-500 transition-all text-center">
                </div>
                <div id="panel-QR" class="channel-content hidden bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col items-center space-y-2">
                    <p class="text-xs font-bold text-slate-600">Escanee este código QR con su celular</p>
                    <div id="qr-container" class="w-32 h-32 bg-white rounded-xl flex items-center justify-center border-2 border-slate-200 shadow-inner">
                        <img id="qr-image" src="" alt="QR" class="w-full h-full rounded-xl hidden">
                        <i class="fa-solid fa-qrcode text-4xl text-slate-300" id="qr-placeholder"></i>
                    </div>
                </div>
                <div id="panel-SMS" class="channel-content hidden bg-slate-50 border border-slate-200 rounded-xl p-4 text-center space-y-1">
                    <i class="fa-solid fa-comment-sms text-2xl text-sena-500"></i>
                    <p class="text-xs font-bold text-slate-600">Se enviará un SMS al número <span id="sms-number" class="text-sena-500 font-black"></span></p>
                </div>
            </div>
            <div class="flex gap-2.5 w-full">
                <button type="button" onclick="nextStep(5); playKey();" class="px-6 py-3 bg-white border border-gray-200 rounded-xl text-slate-400 font-black uppercase tracking-widest text-[10px] hover:bg-gray-50 active:scale-95 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> VOLVER
                </button>
                <button type="submit" onclick="playKey();" class="flex-1 py-3 bg-gradient-to-r from-sena-500 to-sena-orange text-white font-black text-xs rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <span>GENERAR TURNO FINAL</span><i class="fa-solid fa-ticket"></i>
                </button>
            </div>
        </div>

        <!-- CAMPOS OCULTOS -->
        <input type="hidden" name="pers_tipodoc"       id="hidden_pers_tipodoc"       value="{{ old('pers_tipodoc','CC') }}">
        <input type="hidden" name="pers_doc"           id="hidden_pers_doc"           value="{{ old('pers_doc') }}">
        <input type="hidden" name="pers_nombres"       value="Usuario">
        <input type="hidden" name="pers_apellidos"     value="Kiosco">
        <input type="hidden" name="pers_telefono"      id="hidden_pers_telefono"      value="{{ old('pers_telefono') }}">
        <input type="hidden" name="pers_email"         id="hidden_pers_email"         value="">
        <input type="hidden" name="sol_tipo"           value="Externo">
        <input type="hidden" name="tur_perfil"         id="hidden_tur_perfil"         value="{{ old('tur_perfil','General') }}">
        <input type="hidden" name="tur_tipo"           id="hidden_tur_tipo"           value="{{ old('tur_tipo','General') }}">
        <input type="hidden" name="tur_servicio"       id="hidden_tur_servicio"       value="{{ old('tur_servicio') }}">
        <input type="hidden" name="tur_tipo_atencion"  id="hidden_tur_tipo_atencion"  value="{{ old('tur_tipo_atencion') }}">
        <input type="hidden" name="receive_method"     id="hidden_receive_method"     value="{{ old('receive_method','SMS') }}">
    </form>

    <!-- Footer (Fixed Bottom) -->
    <footer class="w-full px-5 py-2.5 flex flex-col sm:flex-row items-center justify-between border-t border-slate-100 shrink-0 bg-slate-50/80 backdrop-blur-sm z-30 space-y-2 sm:space-y-0">
        <div class="flex items-center space-x-5">
            <a href="{{ route('pantalla.index') }}" class="flex items-center space-x-1.5 text-[9px] font-black text-slate-400 hover:text-sena-navy transition-all uppercase tracking-widest">
                <i class="fa-solid fa-display"></i><span class="hidden lg:inline">Pantalla</span>
            </a>
            <a href="{{ route('asesor.login') }}" class="flex items-center space-x-1.5 text-[9px] font-black text-slate-400 hover:text-sena-navy transition-all uppercase tracking-widest">
                <i class="fa-solid fa-headset"></i><span class="hidden lg:inline">Asesor</span>
            </a>
            <a href="{{ route('coordinador.login') }}" class="flex items-center space-x-1.5 text-[9px] font-black text-slate-400 hover:text-sena-navy transition-all uppercase tracking-widest">
                <i class="fa-solid fa-user-tie"></i><span class="hidden lg:inline">Coordinador</span>
            </a>
        </div>
        <div class="flex items-center space-x-5">
            <button type="button" onclick="openConsultarModal(); playKey();" class="text-[9px] font-black text-sena-navy hover:text-sena-500 transition-all uppercase tracking-widest flex items-center gap-1">
                <i class="fa-solid fa-magnifying-glass text-[8px]"></i> CONSULTAR TURNO
            </button>
            <button type="button" onclick="showHelp(); playKey();" class="text-[9px] font-black text-slate-400 hover:text-sena-navy transition-all uppercase tracking-widest">Ayuda</button>
            <button type="button" onclick="showEmergency(); playKey();" class="text-[9px] font-black text-rose-500 hover:text-rose-600 transition-all uppercase tracking-widest">Emergencia</button>
        </div>
    </footer>
</div>

<!-- MODAL DE ÉXITO -->
@if(session('success'))
@php $turnoNumero = str_replace('Turno solicitado con éxito: ', '', session('success')); @endphp
<div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden flex flex-col sm:flex-row border border-white/20">

        {{-- Panel izquierdo azul --}}
        <div class="sm:w-2/5 bg-sena-500 p-6 flex flex-col items-center justify-center text-center space-y-3 relative overflow-hidden">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-sena-orange/20 rounded-full blur-3xl"></div>
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-xl relative z-10">
                <i class="fa-solid fa-check text-2xl text-sena-500"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-poppins font-black text-white tracking-tight italic">¡Completado!</h3>
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mt-1">Transacción Exitosa</p>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="sm:w-3/5 p-5 flex flex-col items-center space-y-3">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Su turno asignado es</p>

            {{-- Número de turno --}}
            <div class="bg-slate-50 border-2 border-slate-100 rounded-xl px-6 py-3 w-full text-center shadow-inner">
                <div class="text-[2.8rem] font-poppins font-black text-sena-500 tracking-tight leading-none whitespace-nowrap">{{ $turnoNumero }}</div>
            </div>

            {{-- Advertencia APE --}}
            @if(session('warning'))
            <div class="w-full bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-blue-500 text-sm mt-0.5 shrink-0"></i>
                <div class="text-left">
                    <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest leading-none mb-0.5">Información de Usuario</p>
                    <p class="text-[10px] font-bold text-blue-800 leading-tight">{{ session('warning') }}</p>
                </div>
            </div>
            @endif

            {{-- QR --}}
            <div class="flex flex-col items-center space-y-1">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Escanee para guardar su turno</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode('SENA APE - Turno: '.$turnoNumero) }}"
                     alt="QR {{ $turnoNumero }}"
                     class="w-24 h-24 rounded-xl border-2 border-slate-100 shadow-sm">
            </div>

            {{-- Instrucción --}}
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase">Retire su tiquete e ingrese a la sala de espera.</p>
                <div class="flex items-center justify-center gap-1.5 text-sena-500 mt-0.5">
                    <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                    <span class="text-[9px] font-black uppercase tracking-widest">Será llamado pronto · Cierre en 8s</span>
                </div>
            </div>

            <button onclick="closeModal()" class="w-full py-3 rounded-xl bg-sena-orange text-white font-black text-xs uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                FINALIZAR <i class="fa-solid fa-chevron-right ml-1"></i>
            </button>
        </div>
    </div>
</div>
<script>
function playSuccessNotification() {
    initKioscoAudio();
    if (audioCtxKiosco.state === 'suspended') audioCtxKiosco.resume();
    const now = audioCtxKiosco.currentTime;
    const osc = audioCtxKiosco.createOscillator(); const gain = audioCtxKiosco.createGain();
    osc.frequency.setValueAtTime(523.25, now); osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.5);
    gain.gain.setValueAtTime(0.1, now); gain.gain.exponentialRampToValueAtTime(0.01, now + 1);
    osc.connect(gain); gain.connect(audioCtxKiosco.destination); osc.start(); osc.stop(now + 1);
}
function closeModal() {
    const modal = document.getElementById('successModal');
    if (modal) { modal.style.opacity='0'; modal.style.transition='opacity 0.5s ease-out'; setTimeout(()=>modal.remove(),500); }
}
setTimeout(()=>{ playSuccessNotification(); setTimeout(closeModal,10000); },300);
</script>
@endif

<!-- MODAL DE ERROR -->
@if($errors->any() || session('error'))
<div id="errorModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-2xl p-7 shadow-2xl flex flex-col items-center text-center space-y-4 border border-gray-100 relative">
        <button type="button" onclick="cerrarErrorModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 text-xl transition"><i class="fa-solid fa-xmark"></i></button>
        <div class="w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center relative">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-rose-500 relative z-10"></i>
        </div>
        <div class="space-y-1">
            <h3 class="text-xl font-poppins font-black text-gray-900 tracking-tight leading-none">¡Atención!</h3>
            <p class="text-sm font-bold text-gray-500">@if(session('error')){{ session('error') }}@else Por favor verifica los datos ingresados:@endif</p>
        </div>
        @if($errors->any())
        <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-4 w-full text-left overflow-y-auto max-h-32">
            <ul class="list-disc list-inside text-rose-600 font-medium space-y-1 text-xs">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif
        <button type="button" onclick="cerrarErrorModal()" class="w-full py-3 rounded-xl bg-gray-900 text-white font-black text-sm uppercase tracking-widest hover:bg-black transition-all shadow-lg active:scale-95">INTENTAR DE NUEVO</button>
    </div>
</div>
<script>
    function cerrarErrorModal() {
        const m = document.getElementById('errorModal');
        if (m) { m.style.transition='opacity 0.4s'; m.style.opacity='0'; setTimeout(()=>m.remove(),400); }
    }
    // Auto-cierre en 2 segundos
    setTimeout(cerrarErrorModal, 2000);
</script>
@endif

<!-- MODAL CONSULTAR TURNO -->
<div id="consultarModal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md transition-all duration-300 opacity-0">
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="consultarModalContent">
        <!-- Header -->
        <div class="bg-sena-navy p-6 flex items-center justify-between relative">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-poppins font-black text-xl uppercase tracking-tight">Consultar Mi Turno</h3>
                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">Válido para hoy</p>
                </div>
            </div>
            <button onclick="closeConsultarModal()" class="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-8 space-y-6">
            <div class="flex gap-2">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-id-card absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="number" id="consultarDoc" placeholder="Ingrese su número de documento" 
                           class="w-full pl-12 pr-4 py-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-lg font-bold text-slate-700 focus:outline-none focus:border-sena-500 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                </div>
                <button onclick="consultarTurno()" class="px-8 bg-sena-navy text-white font-black rounded-2xl shadow-lg hover:bg-slate-800 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-search"></i>
                    <span>Buscar</span>
                </button>
            </div>

            <!-- Result Area -->
            <div id="consultarResult" class="min-h-[200px] flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 text-4xl">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <p class="text-slate-400 font-medium max-w-xs mx-auto">Ingrese su documento para consultar el estado de su turno</p>
            </div>
        </div>
    </div>
</div>

<script>
// Logic for Consultation
function openConsultarModal() {
    const modal = document.getElementById('consultarModal');
    const content = document.getElementById('consultarModalContent');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.add('opacity-100');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
    document.getElementById('consultarDoc').focus();
}

function closeConsultarModal() {
    const modal = document.getElementById('consultarModal');
    const content = document.getElementById('consultarModalContent');
    modal.classList.remove('opacity-100');
    content.classList.add('scale-95');
    content.classList.remove('scale-100');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // Reset state
        document.getElementById('consultarDoc').value = '';
        document.getElementById('consultarResult').innerHTML = `
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 text-4xl">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <p class="text-slate-400 font-medium max-w-xs mx-auto">Ingrese su documento para consultar el estado de su turno</p>
        `;
    }, 300);
}

async function consultarTurno() {
    const doc = document.getElementById('consultarDoc').value;
    const resultDiv = document.getElementById('consultarResult');
    
    if (!doc || doc.length < 5) {
        alert("Por favor ingrese un documento válido");
        return;
    }

    resultDiv.innerHTML = `<div class="flex flex-col items-center space-y-4">
        <div class="w-12 h-12 border-4 border-sena-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-slate-500 font-bold animate-pulse">Buscando su turno...</p>
    </div>`;

    try {
        const response = await fetch(`/api/turno/consultar/${doc}`);
        const data = await response.json();

        if (data.success) {
            const t = data.data;
            let statusColor = 'bg-amber-100 text-amber-600';
            if (t.estado === 'Atendiendo') statusColor = 'bg-emerald-100 text-emerald-600';
            if (t.estado === 'Finalizado' || t.estado === 'Ausente') statusColor = 'bg-slate-100 text-slate-600';

            resultDiv.innerHTML = `
                <div class="w-full space-y-4 animate-[fadeInScale_0.4s_ease-out]">
                    <div class="text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">CIUDADANO</p>
                        <h4 class="text-xl font-black text-slate-800">${t.ciudadano}</h4>
                        <p class="text-[10px] font-bold text-slate-400 mt-1">Hoy ${new Date().toLocaleDateString()}</p>
                    </div>
                    
                    <div class="bg-amber-50/50 border-2 border-amber-100 rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-sena-navy rounded-xl flex items-center justify-center text-white font-black text-xs">
                                    ${t.numero.split('-')[0]}
                                </div>
                                <div class="text-left">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">TURNO</p>
                                    <h5 class="text-xl font-black text-slate-800">${t.numero}</h5>
                                </div>
                            </div>
                            <div class="px-4 py-1.5 rounded-full ${statusColor} flex items-center gap-2">
                                <i class="fa-solid fa-clock text-[10px]"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">${t.estado}</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-2xl text-left border border-amber-100/50">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">PERFIL</p>
                                <p class="text-sm font-black text-slate-700">${t.perfil}</p>
                            </div>
                            <div class="bg-white p-3 rounded-2xl text-left border border-amber-100/50">
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">SERVICIO</p>
                                <p class="text-sm font-black text-slate-700">${t.servicio}</p>
                            </div>
                        </div>
                        
                        <div class="mt-3 bg-white/50 py-2 px-4 rounded-xl inline-block">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">GENERADO</p>
                            <p class="text-[11px] font-bold text-slate-600">${t.hora}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="flex flex-col items-center space-y-4 animate-in fade-in duration-500">
                    <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 text-4xl">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-lg font-black text-slate-800 uppercase italic">No encontrado</h4>
                        <p class="text-sm text-slate-500 font-medium px-10">${data.message}</p>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        resultDiv.innerHTML = `
            <div class="text-rose-500 font-bold p-4 bg-rose-50 rounded-2xl">
                Error de conexión. Por favor intente nuevamente.
            </div>
        `;
    }
}
</script>

<!-- MODAL INFORMATIVO -->
<div id="kioscoInfoModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 bg-slate-900/80 backdrop-blur-md transition-all duration-300 opacity-0">
    <div class="bg-white w-full max-w-2xl rounded-[4rem] p-12 shadow-2xl flex flex-col items-center text-center space-y-8 border border-white/20 relative scale-90 transition-transform duration-300" id="infoModalContent">
        <button type="button" onclick="closeKioscoModal()" class="absolute top-10 right-10 text-slate-300 hover:text-slate-900 text-3xl transition"><i class="fa-solid fa-xmark"></i></button>
        <div id="infoIconContainer" class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center shadow-inner"><i id="infoIcon" class="fa-solid fa-circle-info text-5xl text-slate-400"></i></div>
        <div class="space-y-4">
            <h3 id="infoTitle" class="text-5xl font-poppins font-black text-slate-900 tracking-tighter leading-none italic uppercase">TITULO</h3>
            <p id="infoSubtitle" class="text-sm font-black text-sena-500 uppercase tracking-[0.2em] mb-4">SUBTÍTULO</p>
            <div id="infoBody" class="text-slate-500 font-medium leading-relaxed px-6"></div>
        </div>
        <button type="button" onclick="closeKioscoModal()" class="w-full py-8 rounded-[2.5rem] bg-slate-900 text-white font-black text-xl uppercase tracking-widest hover:bg-black transition-all shadow-2xl active:scale-95">ENTENDIDO</button>
    </div>
</div>

<script>
let docNumber = "{{ old('pers_doc','') }}";
let phoneNumber = "{{ old('pers_telefono','') }}";
let audioCtxKiosco = null;

function initKioscoAudio() { if (!audioCtxKiosco) audioCtxKiosco = new (window.AudioContext || window.webkitAudioContext)(); }
function playKey() {
    initKioscoAudio();
    if (audioCtxKiosco.state === 'suspended') audioCtxKiosco.resume();
    const osc = audioCtxKiosco.createOscillator(); const gain = audioCtxKiosco.createGain();
    osc.type = 'sine'; osc.frequency.setValueAtTime(600, audioCtxKiosco.currentTime);
    osc.frequency.exponentialRampToValueAtTime(100, audioCtxKiosco.currentTime + 0.1);
    gain.gain.setValueAtTime(0.1, audioCtxKiosco.currentTime); gain.gain.exponentialRampToValueAtTime(0.01, audioCtxKiosco.currentTime + 0.1);
    osc.connect(gain); gain.connect(audioCtxKiosco.destination); osc.start(); osc.stop(audioCtxKiosco.currentTime + 0.1);
}
function nextStep(step) {
    initKioscoAudio();
    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
    setTimeout(() => { document.getElementById('step' + step).classList.add('active'); }, 10);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
function toggleBtn(checkbox) {
    const btn = document.getElementById('nextBtn');
    if (checkbox.checked) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-200','text-gray-400','cursor-not-allowed','opacity-50');
        btn.classList.add('bg-sena-500','text-white','cursor-pointer','opacity-100');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-200','text-gray-400','cursor-not-allowed','opacity-50');
        btn.classList.remove('bg-sena-500','text-white','cursor-pointer','opacity-100');
    }
}
function selectType(type) {
    document.getElementById('hidden_tur_perfil').value = type;
    document.getElementById('hidden_tur_tipo').value = type;
    nextStep('3_5');
}
function setService(val, btn) {
    document.getElementById('hidden_tur_servicio').value = val;
    document.querySelectorAll('.service-btn').forEach(b => { b.classList.remove('border-sena-500','bg-sena-50','text-sena-500'); b.querySelector('i').classList.add('opacity-0'); });
    btn.classList.add('border-sena-500','bg-sena-50','text-sena-500'); btn.querySelector('i').classList.remove('opacity-0');
    // feedback indicador
    document.getElementById('service-required').classList.add('hidden');
    document.getElementById('service-ok').classList.remove('hidden');
    document.getElementById('service-container').classList.remove('ring-2','ring-rose-300','rounded-2xl');
}
function setAttentionType(val, btn) {
    document.getElementById('hidden_tur_tipo_atencion').value = val;
    document.querySelectorAll('.attention-btn').forEach(b => { b.classList.remove('border-sena-500','bg-sena-50','text-sena-500'); b.querySelector('i').classList.add('opacity-0'); });
    btn.classList.add('border-sena-500','bg-sena-50','text-sena-500'); btn.querySelector('i').classList.remove('opacity-0');
    // feedback indicador
    document.getElementById('attention-required').classList.add('hidden');
    document.getElementById('attention-ok').classList.remove('hidden');
    document.getElementById('attention-container').classList.remove('ring-2','ring-rose-300','rounded-2xl');
}
function validateStep3_5() {
    const servicio  = document.getElementById('hidden_tur_servicio').value;
    const atencion  = document.getElementById('hidden_tur_tipo_atencion').value;
    const errBanner = document.getElementById('step35-error');
    const errMsg    = document.getElementById('step35-error-msg');
    let errors = [];

    if (!servicio) {
        errors.push('Tipo de Servicio');
        document.getElementById('service-required').classList.remove('hidden');
        document.getElementById('service-ok').classList.add('hidden');
        document.getElementById('service-container').classList.add('ring-2','ring-rose-300','rounded-2xl');
    }
    if (!atencion) {
        errors.push('Tipo de Atención');
        document.getElementById('attention-required').classList.remove('hidden');
        document.getElementById('attention-ok').classList.add('hidden');
        document.getElementById('attention-container').classList.add('ring-2','ring-rose-300','rounded-2xl');
    }

    if (errors.length > 0) {
        errMsg.textContent = 'Por favor seleccione: ' + errors.join(' y ') + '.';
        errBanner.classList.remove('hidden');
        errBanner.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    errBanner.classList.add('hidden');
    nextStep(4);
}
let currentDocType = 'CC';
const docRules = {
    CC: { min: 8, max: 10, hint: 'Cédula: entre 8 y 10 dígitos' },
    TI: { min: 8, max: 10, hint: 'Tarjeta de Identidad: entre 8 y 10 dígitos' },
    CE: { min: 6, max: 15, hint: 'Cédula Extranjería: mínimo 6 dígitos' },
};
function setDocType(type, btn) {
    currentDocType = type;
    document.getElementById('hidden_pers_tipodoc').value = type;
    document.querySelectorAll('.doc-tab-btn').forEach(b => { b.classList.remove('border-sena-500','bg-sena-50','text-sena-500','shadow-md'); b.classList.add('border-gray-100','text-slate-400'); });
    btn.classList.add('border-sena-500','bg-sena-50','text-sena-500','shadow-md'); btn.classList.remove('border-gray-100','text-slate-400');
    docNumber = ''; updateDocDisplay();
    document.getElementById('docHint').textContent = docRules[type].hint;
}
function pressNum(n) { if (docNumber.length < docRules[currentDocType].max) docNumber += n; updateDocDisplay(); }
function backspace() { docNumber = docNumber.slice(0,-1); updateDocDisplay(); }
function clearNum() { docNumber = ""; updateDocDisplay(); }
function updateDocDisplay() {
    const d = document.getElementById('docDisplay');
    const rule = docRules[currentDocType];
    d.innerText = docNumber || "_ _ _ _ _ _ _ _ _ _";
    d.style.color = docNumber ? "#1e293b" : "#cbd5e1";
    document.getElementById('hidden_pers_doc').value = docNumber;
    // Feedback visual: rojo si no cumple el mínimo, verde si está en rango
    const hint = document.getElementById('docHint');
    if (docNumber.length > 0 && docNumber.length < rule.min) {
        hint.style.color = '#ef4444';
    } else if (docNumber.length >= rule.min) {
        hint.style.color = '#16a34a';
    } else {
        hint.style.color = '';
    }
}
function validateDoc() {
    const rule = docRules[currentDocType];
    if (docNumber.length < rule.min) {
        alert(`Documento muy corto. ${rule.hint}`);
        return;
    }
    nextStep(5);
}
function pressPhone(n) { if (phoneNumber.length < 10) phoneNumber += n; updatePhoneDisplay(); }
function backspacePhone() { phoneNumber = phoneNumber.slice(0,-1); updatePhoneDisplay(); }
function updatePhoneDisplay() {
    const d = document.getElementById('phoneDisplay');
    d.innerText = phoneNumber.replace(/(\d{3})(\d{3})(\d{4})/, "$1 $2 $3") || "300 000 0000";
    d.style.color = phoneNumber ? "#1e293b" : "#cbd5e1";
    document.getElementById('hidden_pers_telefono').value = phoneNumber;
}
function validatePhoneStep() {
    if (!phoneNumber || phoneNumber.length < 10) {
        alert("Primero ingrese su número de teléfono (10 dígitos)");
        return;
    }
    nextStep(6);
}
function selectChannel(method, btn) {
    document.getElementById('hidden_receive_method').value = method;
    document.querySelectorAll('.receive-card').forEach(c => { c.classList.remove('border-sena-500','bg-sena-50'); c.querySelector('div').classList.remove('bg-sena-500','text-white'); c.querySelector('div').classList.add('bg-gray-50','text-gray-400'); });
    btn.classList.add('border-sena-500','bg-sena-50'); btn.querySelector('div').classList.add('bg-sena-500','text-white'); btn.querySelector('div').classList.remove('bg-gray-50','text-gray-400');
    document.getElementById('channel-panel').classList.remove('hidden');
    document.querySelectorAll('.channel-content').forEach(p => p.classList.add('hidden'));
    const panel = document.getElementById('panel-' + method); if (panel) panel.classList.remove('hidden');
    const phone = document.getElementById('hidden_pers_telefono').value || phoneNumber;
    const doc   = document.getElementById('hidden_pers_doc').value || docNumber;
    if (method === 'WhatsApp') document.getElementById('wa-number').textContent = '+57 ' + phone;
    if (method === 'SMS') document.getElementById('sms-number').textContent = '+57 ' + phone;
    if (method === 'QR') {
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent('SENA APE - Doc: ' + doc + ' | Tel: ' + phone);
        const img = document.getElementById('qr-image'); const placeholder = document.getElementById('qr-placeholder');
        img.src = qrUrl; img.classList.remove('hidden'); placeholder.classList.add('hidden');
    }
}

let currentLang = 'ES';
const translations = {
    ES: { welcomeTitle: 'Bienvenido al <br><span class="text-sena-500">Centro de Atención</span>', welcomeDescription: 'Por favor toca el botón para iniciar tu proceso', startButton: 'Empezar Aquí', helpTitle: 'GUÍA DE USO', helpSubtitle: '¿CÓMO SOLICITAR TU TURNO?', helpBody: `<div class="space-y-4 text-left"><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">1</div><p>Toca <b>Empezar</b> y acepta los términos.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">2</div><p>Selecciona tu <b>Categoría</b> de atención.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">3</div><p>Ingresa tu <b>Documento</b> y <b>Teléfono</b>.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">4</div><p>¡Retira tu <b>Ticket</b> y espera el llamado!</p></div></div>` },
    EN: { welcomeTitle: 'Welcome to the <br><span class="text-sena-500">Service Center</span>', welcomeDescription: 'Please touch the button to start your process', startButton: 'Start Here', helpTitle: 'USER GUIDE', helpSubtitle: 'HOW TO REQUEST YOUR TURN?', helpBody: `<div class="space-y-4 text-left"><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">1</div><p>Tap <b>Start</b> and accept the terms.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">2</div><p>Select your service <b>Category</b>.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">3</div><p>Enter your <b>ID</b> and <b>Phone</b>.</p></div><div class="flex items-center space-x-4"><div class="w-8 h-8 bg-sena-500 text-white rounded-full flex items-center justify-center font-bold">4</div><p>Collect your <b>Ticket</b> and wait for your call!</p></div></div>` }
};
function toggleLanguage() {
    currentLang = currentLang === 'ES' ? 'EN' : 'ES';
    document.getElementById('langLabel').innerText = currentLang === 'ES' ? 'ES / EN' : 'EN / ES';
    document.getElementById('welcomeTitle').innerHTML = translations[currentLang].welcomeTitle;
    document.getElementById('welcomeDescription').innerText = translations[currentLang].welcomeDescription;
    document.getElementById('startButton').innerText = translations[currentLang].startButton;
    document.getElementById('helpLabel').innerText = currentLang === 'ES' ? 'AYUDA' : 'HELP';
}
function showKioscoModal(title, subtitle, body, icon='fa-circle-info') {
    const modal = document.getElementById('kioscoInfoModal'); const content = document.getElementById('infoModalContent');
    document.getElementById('infoTitle').innerText = title; document.getElementById('infoSubtitle').innerText = subtitle;
    document.getElementById('infoBody').innerHTML = body; document.getElementById('infoIcon').className = `fa-solid ${icon} text-5xl text-slate-400`;
    modal.classList.remove('hidden'); modal.classList.add('flex');
    setTimeout(() => { modal.classList.add('opacity-100'); content.classList.remove('scale-90'); content.classList.add('scale-100'); }, 10);
}
function closeKioscoModal() {
    const modal = document.getElementById('kioscoInfoModal'); const content = document.getElementById('infoModalContent');
    modal.classList.remove('opacity-100'); content.classList.add('scale-90'); content.classList.remove('scale-100');
    setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
}
function showHelp() { const data = translations[currentLang]; showKioscoModal(data.helpTitle, data.helpSubtitle, data.helpBody, 'fa-hand-pointer'); }
function showPortalInfo(name) {
    const body = `<div class="flex flex-col items-center space-y-6"><p class="text-slate-500">Puedes acceder al ${name} escaneando este código QR con tu dispositivo móvil.</p><div class="w-48 h-48 bg-slate-100 rounded-3xl flex items-center justify-center border-4 border-slate-50 shadow-inner"><i class="fa-solid fa-qrcode text-8xl text-slate-300"></i></div><div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">SENA DIGITAL • CONECTIVIDAD</div></div>`;
    showKioscoModal('ACCESO MÓVIL', name, body, 'fa-mobile-screen-button');
}
function showEmergency() {
    const body = `<div class="flex flex-col items-center space-y-6 text-center">
        <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center animate-pulse">
            <i class="fa-solid fa-phone-volume text-4xl text-rose-500"></i>
        </div>
        <p class="text-slate-600 font-medium">Si tiene una emergencia médica o de seguridad, por favor diríjase de inmediato a la recepción o comuníquese con el personal de seguridad más cercano.</p>
        <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100 w-full">
            <p class="text-rose-600 font-black text-2xl tracking-tighter">EXT. 1234</p>
            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest">Línea de Atención Inmediata</p>
        </div>
    </div>`;
    showKioscoModal('BOTÓN DE EMERGENCIA', 'ASISTENCIA INMEDIATA', body, 'fa-triangle-exclamation');
}
function updateClock() { const el = document.getElementById('kiosco-clock'); if (!el) return; const now = new Date(); el.textContent = now.toLocaleTimeString('en-US', { hour:'2-digit', minute:'2-digit', hour12:true }); }
setInterval(updateClock, 1000); updateClock();
function validateForm() {
    document.getElementById('hidden_pers_doc').value = docNumber;
    document.getElementById('hidden_pers_telefono').value = phoneNumber;
    const method = document.getElementById('hidden_receive_method').value;
    if (method === 'Email') {
        const emailVal = document.getElementById('email-input').value.trim();
        if (!emailVal || !emailVal.includes('@')) { alert("Por favor ingrese un correo electrónico válido."); return false; }
        document.getElementById('hidden_pers_email').value = emailVal;
    }
    if (!docNumber || docNumber.length < 5) { alert("Por favor ingrese un número de documento válido."); nextStep(4); return false; }
    return true;
}
window.onload = () => { updateDocDisplay(); updatePhoneDisplay(); };
</script>
</body>
</html>
