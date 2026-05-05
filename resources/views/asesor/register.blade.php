<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Portal | Registro Asesor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], poppins: ['Poppins', 'sans-serif'] },
                    colors: { sena: { 50: '#F0F9FF', navy: '#000080', green: '#39A900', dark: '#003366' } }
                }
            }
        }
    </script>
    <style>
        .video-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -10; overflow: hidden; }
        .video-container iframe { position: absolute; top: 50%; left: 50%; width: 100vw; height: 100vh; transform: translate(-50%, -50%); pointer-events: none; }
        @media (min-aspect-ratio: 16/9) { .video-container iframe { height: 56.25vw; } }
        @media (max-aspect-ratio: 16/9) { .video-container iframe { width: 177.78vh; } }
        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .input-base { @apply w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 font-semibold focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/10 outline-none transition-all placeholder:text-slate-400/60 text-sm; }
        .label-base { @apply text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-1.5; }
    </style>
</head>
<body class="bg-[#F8FAFC] font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Video Background -->
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/_MZRAUSIZtQ?autoplay=1&mute=1&loop=1&playlist=_MZRAUSIZtQ&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1"
            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
    </div>

    <div class="w-full max-w-2xl bg-white/90 backdrop-blur-md rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] border border-white/20 fade-up relative overflow-hidden">

        <!-- Top bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-sena-navy via-sena-green to-sena-navy"></div>

        <div class="p-8 lg:p-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SENA" class="h-16 mx-auto mb-3 drop-shadow-sm">
                <h2 class="text-3xl font-poppins font-black text-sena-navy tracking-tight">Registro de Asesor</h2>
                <p class="text-slate-500 font-medium text-sm mt-1">Completa los datos para crear tu cuenta</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl text-red-600 text-sm font-bold">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>
                <ul class="mt-1 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('asesor.register.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Sección Persona -->
                <div class="border-b border-slate-100 pb-1">
                    <p class="text-[10px] font-black text-sena-navy uppercase tracking-widest mb-4">
                        <i class="fa-solid fa-user mr-1"></i> Datos Personales
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Tipo Documento</label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <select name="pers_tipodoc" class="input-base" required>
                                <option value="">Seleccionar</option>
                                <option value="CC" {{ old('pers_tipodoc')=='CC'?'selected':'' }}>Cédula (CC)</option>
                                <option value="TI" {{ old('pers_tipodoc')=='TI'?'selected':'' }}>Tarjeta Identidad (TI)</option>
                                <option value="CE" {{ old('pers_tipodoc')=='CE'?'selected':'' }}>Cédula Extranjería (CE)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Nro. Documento</label>
                        <div class="relative">
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="number" name="pers_doc" value="{{ old('pers_doc') }}" placeholder="Ej: 1010101010"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Nombres</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="pers_nombres" value="{{ old('pers_nombres') }}" placeholder="Ej: Juan Carlos"
                                class="input-base" required>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Apellidos</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="pers_apellidos" value="{{ old('pers_apellidos') }}" placeholder="Ej: Pérez Gómez"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Teléfono</label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="pers_telefono" value="{{ old('pers_telefono') }}" placeholder="Ej: 3001234567"
                                class="input-base">
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Fecha Nacimiento</label>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="date" name="pers_fecha_nac" value="{{ old('pers_fecha_nac') }}"
                                class="input-base">
                        </div>
                    </div>
                </div>

                <!-- Sección Asesor -->
                <div class="border-b border-slate-100 pb-1 pt-2">
                    <p class="text-[10px] font-black text-sena-navy uppercase tracking-widest mb-4">
                        <i class="fa-solid fa-headset mr-1"></i> Datos del Asesor
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Nro. Contrato</label>
                        <div class="relative">
                            <i class="fa-solid fa-file-contract absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="ase_nrocontrato" value="{{ old('ase_nrocontrato') }}" placeholder="CONT-2026-001"
                                class="input-base" required>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Tipo Asesor</label>
                        <div class="relative">
                            <i class="fa-solid fa-users-gear absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <select name="ase_tipo_asesor" class="input-base" required>
                                <option value="">Seleccionar</option>
                                <option value="OT" {{ old('ase_tipo_asesor')=='OT'?'selected':'' }}>OT - Orientador Técnico</option>
                                <option value="OV" {{ old('ase_tipo_asesor')=='OV'?'selected':'' }}>OV - Orientador de Víctimas</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label-base">Correo Electrónico</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" name="ase_correo" value="{{ old('ase_correo') }}" placeholder="correo@sena.edu.co"
                            class="input-base" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Contraseña</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="password" placeholder="Mínimo 6 caracteres"
                                class="input-base" required>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Confirmar Contraseña</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" name="password_confirmation" placeholder="Repetir contraseña"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-sena-navy hover:bg-sena-dark active:scale-[0.98] text-white font-black py-4 rounded-2xl shadow-xl shadow-sena-navy/30 hover:shadow-2xl transition-all flex items-center justify-center gap-3 group mt-2">
                    <span class="tracking-widest uppercase text-sm">Crear Cuenta</span>
                    <i class="fa-solid fa-user-plus group-hover:scale-110 transition-transform"></i>
                </button>
            </form>

            <div class="relative py-4">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-[10px]">
                    <span class="bg-white px-4 text-slate-400 font-black tracking-[0.3em] uppercase">Alternativas</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('asesor.login') }}" class="flex items-center justify-center gap-2 py-3 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all text-xs font-bold text-slate-700 shadow-sm">
                    <i class="fa-solid fa-arrow-left text-sena-navy"></i>
                    <span>Iniciar Sesión</span>
                </a>
                <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 py-3 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all text-xs font-bold text-slate-700 shadow-sm">
                    <i class="fa-solid fa-house text-sena-navy"></i>
                    <span>Inicio</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-[9px] text-slate-400 leading-relaxed font-bold uppercase tracking-widest">
                    Servicio Nacional de Aprendizaje SENA<br>
                    <span class="opacity-60">Dirección de Empleo, Trabajo y Emprendimiento</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
