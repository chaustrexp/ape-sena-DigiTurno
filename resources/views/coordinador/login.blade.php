<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENA Portal Profesional | Coordinación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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
            .video-container iframe {
                height: 56.25vw;
            }
        }

        @media (max-aspect-ratio: 16/9) {
            .video-container iframe {
                width: 177.78vh;
            }
        }

        .glass-premium {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .fade-up {
            animation: fadeUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- YouTube Video Background (Actualizado) -->
    <div class="video-container">
        <iframe
            src="https://www.youtube.com/embed/B3b7T6-h8i4?autoplay=1&mute=1&loop=1&playlist=B3b7T6-h8i4&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1"
            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
        </iframe>
        <!-- Dark Overlay (INTACTO) -->
        <div class="absolute inset-0 bg-sena-navy/60 backdrop-blur-[1px]"></div>
    </div>

    <!-- Dashboard Login Card (Advanced Glassmorphism) -->
    <div
        class="w-full max-w-xl bg-white/90 backdrop-blur-lg rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.6)] border border-white/20 fade-up relative overflow-hidden">

        <!-- Top Indicator Bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-sena-navy via-sena-green to-sena-navy"></div>

        <div class="p-8 lg:p-14">
            <!-- Admin Header -->
            <div class="flex flex-col items-center mb-10">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SENA" class="h-24 mx-auto mb-4 drop-shadow-md">
                <h2 class="text-4xl font-poppins font-black text-sena-navy tracking-tighter">Acceso Coordinación</h2>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-2 text-center">Portal de
                    Gestión Administrativa</p>
            </div>

            @if(session('error'))
                <div
                    class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-xl text-red-600 text-xs font-black flex items-center uppercase tracking-wide">
                    <i class="fa-solid fa-triangle-exclamation mr-3 text-lg"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('coordinador.login') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Credencial
                            Institucional</label>
                        <div class="relative group">
                            <i
                                class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-sena-navy transition-colors"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-12 pr-4 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-slate-900 font-medium focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/5 outline-none transition-all placeholder:text-slate-300"
                                placeholder="nombre@sena.edu.co">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Contraseña de
                                Dashboard</label>
                            <a href="#"
                                class="text-[10px] font-black text-sena-navy hover:underline uppercase tracking-tighter">¿Olvidó
                                su clave?</a>
                        </div>
                        <div class="relative group">
                            <i
                                class="fa-solid fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-sena-navy transition-colors"></i>
                            <input type="password" name="password" id="passInput" required
                                class="w-full pl-12 pr-12 py-4 bg-slate-50/50 border border-slate-200 rounded-2xl text-slate-900 font-medium focus:bg-white focus:border-sena-navy focus:ring-4 focus:ring-sena-navy/5 outline-none transition-all"
                                placeholder="••••••••••••">
                            <button type="button" onclick="togglePass()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sena-navy transition-all">
                                <i class="fa-solid fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember"
                            class="w-4 h-4 rounded text-sena-navy focus:ring-sena-navy border-slate-300">
                        <label for="remember" class="ml-3 text-xs font-bold text-slate-500">Mantener sesión
                            iniciada</label>
                    </div>
                </div>

                <!-- Admin Button -->
                <button type="submit"
                    class="w-full bg-sena-navy hover:bg-sena-dark text-white font-black py-5 rounded-2xl shadow-2xl shadow-sena-navy/40 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 uppercase tracking-[0.2em] text-xs">
                    <span>Ingresar al Dashboard</span>
                    <i class="fa-solid fa-arrow-right-to-bracket text-lg"></i>
                </button>

                <!-- Navigation Links -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <a href="{{ route('asesor.login') }}"
                        class="flex items-center justify-center gap-2 py-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Acceso Asesor</span>
                    </a>
                    <a href="{{ url('/') }}"
                        class="flex items-center justify-center gap-2 py-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-all text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <i class="fa-solid fa-house-user"></i>
                        <span>Ir al Kiosco</span>
                    </a>
                </div>
            </form>

            <!-- Institutional Footer -->
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">


                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] leading-relaxed">
                    © 2026 Servicio Nacional de Aprendizaje SENA<br>
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