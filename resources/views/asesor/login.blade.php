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
<body class="bg-[#F8FAFC] font-sans antialiased overflow-hidden min-h-screen">

    <!-- YouTube Video Background -->
    <div class="video-container">
        <iframe 
            src="https://www.youtube.com/embed/_MZRAUSIZtQ?autoplay=1&mute=1&loop=1&playlist=_MZRAUSIZtQ&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen>
        </iframe>
        <!-- Overlay for readability -->
        <div class="absolute inset-0 bg-sena-navy/40 backdrop-blur-[1px]"></div>
    </div>

    <div class="flex min-h-screen">
        
        <!-- Left Section: Content matching the screenshot -->
        <div class="hidden lg:flex lg:w-[60%] relative flex-col justify-between p-16 overflow-hidden">
            
            <!-- Top Logo -->
            <div class="relative z-10 flex items-center space-x-3 fade-up">
                <div class="bg-sena-green p-2 rounded-lg">
                    <i class="fa-solid fa-graduation-cap text-white text-2xl"></i>
                </div>
                <span class="text-white text-2xl font-poppins font-bold">SENA Portal</span>
            </div>

            <!-- Main Title & Subtitle -->
            <div class="relative z-10 max-w-2xl fade-up" style="animation-delay: 0.2s">
                <h1 class="text-white text-6xl font-poppins font-extrabold leading-[1.1] mb-8">
                    Transformando el futuro profesional de Colombia.
                </h1>
                <p class="text-white/80 text-xl font-medium leading-relaxed max-w-lg">
                    Accede a la red de formación más grande del país y gestiona tu carrera con herramientas de vanguardia.
                </p>
            </div>

            <!-- Bottom Quality Sello -->
            <div class="relative z-10 fade-up" style="animation-delay: 0.4s">
                <p class="text-sena-green font-black text-sm tracking-widest uppercase mb-1">Calidad Certificada</p>
                <p class="text-white/70 text-base">Excelencia en formación integral</p>
            </div>
            
        </div>

        <!-- Right Section: Pure Login Focus (Solid White) -->
        <div class="w-full lg:w-[40%] bg-white flex flex-col justify-center px-10 sm:px-20 py-12 relative shadow-[-20px_0_50px_rgba(0,0,0,0.1)]">
            
            <div class="w-full max-w-[400px] mx-auto fade-up">
                
                <h2 class="text-4xl font-poppins font-black text-sena-navy mb-2">Bienvenido</h2>
                <p class="text-slate-500 font-medium mb-10">Ingresa tus credenciales para acceder al portal.</p>

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 text-red-600 text-sm font-bold flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-3"></i>
                    {{ session('error') }}
                </div>
                @endif

                <!-- Login Form -->
                <form action="{{ url('/asesor/login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Correo o Documento</label>
                        <div class="relative group">
                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sena-navy transition-colors"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-4 bg-slate-100 border-transparent border-2 rounded-xl text-slate-900 font-medium focus:bg-white focus:border-sena-navy outline-none transition-all placeholder:text-slate-400"
                                placeholder="Ej: 1020304050">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-sm font-bold text-slate-700">Contraseña</label>
                            <a href="#" class="text-xs font-bold text-sena-navy hover:underline">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="relative group">
                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sena-navy transition-colors"></i>
                            <input type="password" name="password" required
                                class="w-full pl-12 pr-4 py-4 bg-slate-100 border-transparent border-2 rounded-xl text-slate-900 font-medium focus:bg-white focus:border-sena-navy outline-none transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-sena-navy active:bg-sena-dark text-white font-bold py-4 rounded-xl shadow-lg shadow-sena-navy/20 transition-all hover:translate-y-[-2px] flex items-center justify-center gap-3">
                        <span>Ingresar</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-white px-4 text-slate-400 font-black tracking-widest uppercase">Otras Opciones</span>
                        </div>
                    </div>

                    <a href="{{ route('coordinador.login') }}" class="w-full border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-900 font-bold py-4 rounded-xl transition-all flex items-center justify-center gap-3 mb-4">
                        <i class="fa-solid fa-lock-open text-sm"></i>
                        <span>Acceso Coordinador</span>
                    </a>

                    <div class="text-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-500 font-bold hover:text-sena-navy transition-all text-sm">
                            <i class="fa-solid fa-house"></i>
                            <span>Volver al Inicio</span>
                        </a>
                    </div>
                </form>

                <!-- Footer Text -->
                <div class="mt-20 text-center">
                    <p class="text-[10px] text-slate-400 leading-relaxed font-medium">
                        © 2024 Servicio Nacional de Aprendizaje SENA.<br>
                        Institución Pública de Educación Técnica y Tecnológica.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
