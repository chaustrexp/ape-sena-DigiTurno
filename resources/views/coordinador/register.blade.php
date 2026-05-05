<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Portal | Registro Coordinador</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], poppins: ['Poppins', 'sans-serif'] },
                    colors: { sena: { navy: '#000080', green: '#39A900', dark: '#001a33' } }
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
        .input-base { @apply w-full pl-12 pr-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-2xl text-slate-900 font-medium focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/5 outline-none transition-all placeholder:text-slate-300 text-sm; }
        .label-base { @apply text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block mb-1.5; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Video Background -->
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/B3b7T6-h8i4?autoplay=1&mute=1&loop=1&playlist=B3b7T6-h8i4&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1"
            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <div class="absolute inset-0 bg-sena-navy/60 backdrop-blur-[1px]"></div>
    </div>

    <div class="w-full max-w-2xl bg-white/90 backdrop-blur-lg rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.6)] border border-white/20 fade-up relative overflow-hidden">

        <!-- Top bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-sena-navy via-sena-green to-sena-navy"></div>

        <div class="p-8 lg:p-10">
            <!-- Header -->
            <div class="flex flex-col items-center mb-8">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SENA" class="h-20 mx-auto mb-3 drop-shadow-md">
                <h2 class="text-3xl font-poppins font-black text-sena-navy tracking-tighter">Registro Coordinador</h2>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-1 text-center">Portal de Gestión Administrativa</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl text-red-600 text-xs font-black">
                <i class="fa-solid fa-triangle-exclamation mr-2 text-lg"></i>
                <ul class="mt-1 space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('coordinador.register.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Sección Persona -->
                <div class="border-b border-slate-100 pb-1">
                    <p class="text-[10px] font-black text-sena-navy uppercase tracking-widest mb-4">
                        <i class="fa-solid fa-user-shield mr-1"></i> Datos Personales
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Tipo Documento</label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
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
                            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="number" name="pers_doc" value="{{ old('pers_doc') }}" placeholder="Ej: 1010101010"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Nombres</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" name="pers_nombres" value="{{ old('pers_nombres') }}" placeholder="Ej: María"
                                class="input-base" required>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Apellidos</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" name="pers_apellidos" value="{{ old('pers_apellidos') }}" placeholder="Ej: López Torres"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Teléfono</label>
                        <div class="relative">
                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="text" name="pers_telefono" value="{{ old('pers_telefono') }}" placeholder="Ej: 3001234567"
                                class="input-base">
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Fecha Nacimiento</label>
                        <div class="relative">
                            <i class="fa-solid fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="date" name="pers_fecha_nac" value="{{ old('pers_fecha_nac') }}"
                                class="input-base">
                        </div>
                    </div>
                </div>

                <!-- Sección Credenciales -->
                <div class="border-b border-slate-100 pb-1 pt-2">
                    <p class="text-[10px] font-black text-sena-navy uppercase tracking-widest mb-4">
                        <i class="fa-solid fa-key mr-1"></i> Credenciales de Acceso
                    </p>
                </div>

                <div>
                    <label class="label-base">Correo Institucional</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <input type="email" name="coor_correo" value="{{ old('coor_correo') }}" placeholder="nombre@sena.edu.co"
                            class="input-base" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-base">Contraseña</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="password" name="password" id="passInput" placeholder="Mínimo 6 caracteres"
                                class="input-base" required>
                            <button type="button" onclick="togglePass()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sena-navy transition-all">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="label-base">Confirmar Contraseña</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            <input type="password" name="password_confirmation" placeholder="Repetir contraseña"
                                class="input-base" required>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-sena-navy hover:bg-sena-dark text-white font-black py-4 rounded-2xl shadow-2xl shadow-sena-navy/40 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 uppercase tracking-[0.2em] text-xs mt-2">
                    <span>Crear Cuenta</span>
                    <i class="fa-solid fa-user-plus text-lg"></i>
                </button>
            </form>

            <div class="grid grid-cols-2 gap-4 pt-6">
                <a href="{{ route('coordinador.login') }}"
                    class="flex items-center justify-center gap-2 py-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Iniciar Sesión</span>
                </a>
                <a href="{{ url('/') }}"
                    class="flex items-center justify-center gap-2 py-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all text-[10px] font-black text-slate-600 uppercase tracking-widest">
                    <i class="fa-solid fa-house-user"></i>
                    <span>Ir al Kiosco</span>
                </a>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 text-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] leading-relaxed">
                    © {{ date('Y') }} Servicio Nacional de Aprendizaje SENA<br>
                    <span class="text-sena-navy/40">Coordinación Académica y de Emprendimiento</span>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('passInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
