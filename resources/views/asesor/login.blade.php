<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Portal | Acceso Asesor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
                            50: '#F0F9FF', 
                            navy: '#000080',
                            green: '#39A900',
                            dark: '#003366'
                        } 
                    }
                }
            }
        }
    </script>
    <style>
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            overflow: hidden;
        }

        .video-container iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100vw;
            height: 100vh;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        @media (min-aspect-ratio: 16/9) {
            .video-container iframe { height: 56.25vw; }
        }
        @media (max-aspect-ratio: 16/9) {
            .video-container iframe { width: 177.78vh; }
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#F8FAFC] font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- YouTube Video Background (Immersive) -->
    <div class="video-container">
        <iframe 
            src="https://www.youtube.com/embed/_MZRAUSIZtQ?autoplay=1&mute=1&loop=1&playlist=_MZRAUSIZtQ&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen>
        </iframe>
        <!-- Darker Overlay for maximum contrast with Glassmorphism -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
    </div>

    <!-- Login Card (Glassmorphism) -->
    <div class="w-full max-w-md bg-white/90 backdrop-blur-md rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] p-8 lg:p-12 border border-white/20 fade-up relative overflow-hidden">
        
        <!-- Subtle Background Glow (Navy Theme) -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-sena-navy/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-sena-navy/5 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <!-- Branding -->
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SENA" class="h-20 mx-auto mb-4 drop-shadow-sm">
                <h2 class="text-3xl font-poppins font-black text-sena-navy tracking-tight">Acceso Asesores</h2>
                <p class="text-slate-500 font-medium text-sm mt-2 italic">Forjando el futuro del talento colombiano</p>
            </div>

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl text-red-600 text-sm font-bold flex items-center animate-shake">
                <i class="fa-solid fa-circle-exclamation mr-3 text-lg"></i>
                {{ session('error') }}
            </div>
            @endif

            <!-- Login Form -->
            <form action="{{ url('/asesor/login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sena-navy transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 font-semibold focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/10 outline-none transition-all placeholder:text-slate-400/60"
                            placeholder="ejemplo@sena.edu.co">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Contraseña</label>
                        <a href="#" class="text-[10px] font-black text-sena-navy hover:underline transition-colors uppercase tracking-tighter">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sena-navy transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border-2 border-slate-100 rounded-2xl text-slate-900 font-semibold focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/10 outline-none transition-all placeholder:text-slate-400/60"
                            placeholder="••••••••••••">
                    </div>
                </div>

                <div class="flex items-center space-x-2 px-1">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-slate-300 text-sena-navy focus:ring-sena-navy transition-all">
                    <label for="remember" class="text-xs font-bold text-slate-500 cursor-pointer">Recordar mi sesión</label>
                </div>

                <button type="submit" class="w-full bg-sena-navy hover:bg-sena-dark active:scale-[0.98] text-white font-black py-4 rounded-2xl shadow-xl shadow-sena-navy/30 hover:shadow-2xl hover:shadow-sena-navy/40 transition-all flex items-center justify-center gap-3 group mt-4">
                    <span class="tracking-widest uppercase">Ingresar</span>
                    <i class="fa-solid fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
                </button>

                <!-- Separator -->
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                    <div class="relative flex justify-center text-[10px]">
                        <span class="bg-white/0 px-4 text-slate-400 font-black tracking-[0.3em] uppercase backdrop-blur-sm">Alternativas</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('coordinador.login') }}" class="flex items-center justify-center gap-2 py-3 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all text-xs font-bold text-slate-700 shadow-sm">
                        <i class="fa-solid fa-user-shield text-sena-navy"></i>
                        <span>Coordinador</span>
                    </a>
                    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 py-3 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all text-xs font-bold text-slate-700 shadow-sm">
                        <i class="fa-solid fa-house text-sena-navy"></i>
                        <span>Inicio</span>
                    </a>
                </div>
            </form>

            <!-- Footer Compliance -->
            <div class="mt-10 text-center">
                <p class="text-[9px] text-slate-400 leading-relaxed font-bold uppercase tracking-widest">
                    Servicio Nacional de Aprendizaje SENA<br>
                    <span class="opacity-60">Dirección de Empleo, Trabajo y Emprendimiento</span>
                </p>
            </div>
        </div>
    </div>

    <style>
        .animate-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</body>
</html>
