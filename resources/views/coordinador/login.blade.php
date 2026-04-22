<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Portal Profesional | Coordinación</title>
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
                            navy: '#000080',
                            green: '#39A900',
                            dark: '#001a33'
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

        .glass-premium {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .fade-up { animation: fadeUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards; opacity: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased overflow-hidden min-h-screen flex flex-col">

    <!-- YouTube Video Background -->
    <div class="video-container">
        <iframe 
            src="https://www.youtube.com/embed/_MZRAUSIZtQ?autoplay=1&mute=1&loop=1&playlist=_MZRAUSIZtQ&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen>
        </iframe>
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-sena-navy/60 backdrop-blur-[1px]"></div>
    </div>

    <!-- Top Header mimicking Screenshot 3 -->
    <header class="h-16 bg-white flex items-center justify-between px-12 relative z-10 fade-up">
        <div class="flex items-center">
            <h1 class="text-sena-navy font-poppins font-black text-xl tracking-tighter uppercase">SENA Portal Profesional</h1>
        </div>
        <nav class="hidden md:flex items-center space-x-8">
            <a href="#" class="text-sena-navy font-bold text-xs uppercase tracking-widest border-b-2 border-sena-green pb-1">Soporte</a>
            <a href="#" class="text-slate-500 font-bold text-xs uppercase tracking-widest hover:text-sena-navy transition-all">Guía de Uso</a>
            <a href="#" class="text-slate-500 font-bold text-xs uppercase tracking-widest hover:text-sena-navy transition-all">Normativa</a>
            <a href="#" class="text-sena-navy font-black text-xs uppercase tracking-widest">Ayuda</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex overflow-hidden">
        
        <!-- Left Column: Branding Card -->
        <div class="hidden lg:flex lg:w-[50%] items-center justify-center p-12">
            <div class="glass-premium w-full max-w-[480px] p-12 rounded-[2.5rem] fade-up" style="animation-delay: 0.2s">
                
                <div class="flex justify-between items-start mb-10">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-building-user text-sena-navy text-2xl"></i>
                    </div>
                    <div class="flex flex-col space-y-2 items-end">
                        <span class="bg-sena-green text-white text-[10px] font-black px-3 py-1 rounded-full flex items-center gap-2">
                             <i class="fa-solid fa-shield-check text-[8px]"></i> Acceso Seguro
                        </span>
                        <span class="bg-slate-500/50 text-white text-[10px] font-black px-3 py-1 rounded-full flex items-center gap-2">
                             <i class="fa-solid fa-cloud text-[8px]"></i> Portal Activo
                        </span>
                    </div>
                </div>

                <h2 class="text-white text-4xl font-poppins font-black mb-6 leading-tight">Gestión del Talento Humano</h2>
                
                <p class="text-white/80 text-lg font-medium leading-relaxed mb-12">
                    Transformando vidas a través del empleo. Conectamos los sueños de millones de colombianos con las mejores oportunidades del mercado laboral.
                </p>

                <div class="pt-8 border-t border-white/20 flex items-center space-x-4">
                    <div class="flex -space-x-3">
                        @for($i=1;$i<=4;$i++)
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-200 overflow-hidden shadow-lg">
                            <img src="https://i.pravatar.cc/100?img={{$i+30}}" alt="User">
                        </div>
                        @endfor
                    </div>
                    <span class="text-white text-xs font-bold tracking-tight">Más de 500 asesores conectados hoy</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="w-full lg:w-[50%] bg-white flex flex-col justify-center px-10 sm:px-32 relative shadow-[-50px_0_100px_rgba(0,0,0,0.1)]">
            
            <div class="w-full max-w-[420px] mx-auto fade-up" style="animation-delay: 0.1s">
                
                <h2 class="text-5xl font-poppins font-black text-sena-navy mb-2 tracking-tighter">Login Coordinador</h2>
                <p class="text-slate-400 font-bold text-sm tracking-wide mb-12">Portal de Empleabilidad SENA</p>

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-5 mb-8 text-red-600 font-bold flex items-center rounded-r-xl">
                    <i class="fa-solid fa-circle-exclamation mr-4 text-lg"></i>
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('coordinador.login') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em] ml-1">Correo Electrónico</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-4 bg-white border border-slate-300 rounded-xl text-slate-900 font-medium focus:ring-4 focus:ring-sena-navy/5 focus:border-sena-navy outline-none transition-all placeholder:text-slate-400"
                                placeholder="nombre@sena.edu.co">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[11px] font-black text-slate-800 uppercase tracking-[0.2em]">Contraseña</label>
                            <a href="#" class="text-[11px] font-bold text-sena-navy hover:underline">¿Olvidó su contraseña?</a>
                        </div>
                        <div class="relative group">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="password" id="passInput" required
                                class="w-full pl-12 pr-12 py-4 bg-white border border-slate-300 rounded-xl text-slate-900 font-medium focus:ring-4 focus:ring-sena-navy/5 focus:border-sena-navy outline-none transition-all"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePass()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sena-navy transition-all">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center px-1">
                        <input type="checkbox" id="remember" class="w-4 h-4 rounded text-sena-navy focus:ring-sena-navy border-slate-300">
                        <label for="remember" class="ml-3 text-sm font-medium text-slate-500">Mantener sesión iniciada</label>
                    </div>

                    <button type="submit" class="w-full bg-sena-navy hover:bg-sena-dark text-white font-black py-5 rounded-xl shadow-2xl shadow-sena-navy/30 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-4 uppercase tracking-[0.15em] text-sm">
                        <span>Ingresar Ahora</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </button>

                    <div class="relative py-4 mt-8">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                        <div class="relative flex justify-center text-[10px]">
                            <span class="bg-white px-6 text-slate-400 font-black tracking-[0.25em] uppercase">Otros Accesos</span>
                        </div>
                    </div>

                    <a href="{{ route('asesor.login') }}" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-800 font-black py-4 rounded-xl transition-all flex items-center justify-center gap-3 text-xs uppercase tracking-widest leading-none">
                         <i class="fa-solid fa-user-gear text-sm"></i>
                         Acceso Asesor
                    </a>

                    <div class="text-center pt-4">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sena-green font-black hover:scale-105 transition-all text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Volver al Kiosco</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Global Footer -->
    <footer class="h-16 bg-slate-50 flex items-center justify-between px-12 relative z-10 border-t border-slate-200">
        <div class="flex items-center space-x-1">
            <span class="text-sena-navy font-poppins font-black text-xl tracking-tighter uppercase mr-4">SENA</span>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                © 2024 Servicio Nacional de Aprendizaje SENA. Todos los derechos reservados.
            </p>
        </div>
        <div class="flex items-center space-x-8 text-[10px] text-slate-500 font-black uppercase tracking-[0.2em]">
            <a href="#" class="hover:text-sena-navy transition-all">Privacidad</a>
            <a href="#" class="hover:text-sena-navy transition-all">Términos</a>
            <a href="#" class="hover:text-sena-navy transition-all">Contacto</a>
        </div>
    </footer>

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
