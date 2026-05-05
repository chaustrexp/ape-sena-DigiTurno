<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SENA APE - Sistema de Gestión de Turnos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            500: '#10069F', 
                            600: '#0c047a', 
                            50: '#f0f0ff' 
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f4f6f8; font-family: 'Inter', sans-serif; }
    </style>
    @yield('styles')
</head>
<body class="h-screen overflow-hidden flex flex-col">

    <!-- Top Header -->
    <header class="bg-white px-6 py-2.5 flex items-center justify-between border-b border-gray-100 shrink-0 z-20">
        <!-- Logo -->
        <div class="flex items-center space-x-3 w-1/4">
            <img src="{{ asset('images/logo.jpeg') }}" class="h-7 w-auto object-contain" alt="SENA Logo">
            <div class="h-5 w-px bg-gray-100"></div>
            <div>
                <h1 class="text-sm font-poppins font-bold text-gray-900 leading-tight">SENA APE</h1>
                <p class="text-[8px] font-bold text-sena-blue tracking-wider">Sistema de Gestión de Turnos</p>
            </div>
        </div>

        <!-- Time & Search -->
        <div class="flex items-center justify-center space-x-8 w-2/4">
            <div class="flex flex-col border-r border-gray-200 pr-8">
                <span class="text-[8px] font-semibold text-gray-500 uppercase tracking-widest">Sesión Actual</span>
                <span class="text-[10px] font-bold text-gray-900" id="header-datetime">Cargando...</span>
            </div>
            <div class="relative w-64">
                <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="globalSearchInput" placeholder="Buscar módulos o asesores..." class="w-full bg-gray-50 border border-gray-100 rounded-full py-2 pl-9 pr-3 text-[10px] focus:ring-2 focus:ring-sena-blue outline-none text-gray-700 font-medium transition-all">
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3 w-1/4">
            <a href="{{ route('coordinador.export') }}" class="bg-sena-blue hover:bg-sena-blue/90 text-white px-4 py-2 rounded-full text-[10px] font-bold transition flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-download text-xs"></i>
                <span>Exportar</span>
            </a>
            <div class="flex items-center space-x-2 border-l border-gray-200 pl-3 relative">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-900">{{ session('coordinador_nombre', 'Coordinador') }}</p>
                    <p class="text-[8px] font-semibold text-sena-blue">Coordinador</p>
                </div>
                <div class="relative">
                    <button onclick="document.getElementById('coord-user-menu').classList.toggle('hidden')" 
                            class="w-8 h-8 rounded-full border-2 border-gray-200 hover:border-sena-blue/30 transition overflow-hidden focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(session('coordinador_nombre', 'C')) }}&background=f0f0ff&color=10069F&bold=true" class="w-full h-full object-cover" alt="Profile">
                    </button>
                    <div id="coord-user-menu" class="hidden absolute right-0 top-10 bg-white border border-gray-100 rounded-2xl shadow-2xl w-48 z-50 overflow-hidden py-1.5">
                        <div class="px-3 py-2 border-b border-gray-50">
                            <p class="text-[10px] font-black text-gray-900">{{ session('coordinador_nombre', 'Coordinador') }}</p>
                            <p class="text-[8px] text-gray-400 font-medium mt-0.5">Coordinador APE SENA</p>
                        </div>
                        <a href="{{ route('coordinador.configuracion') }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-50 transition">
                            <i class="fa-solid fa-gear text-gray-400 w-3 text-center text-xs"></i>
                            <span class="text-[10px] font-bold text-gray-600">Configuración</span>
                        </a>
                        <form action="{{ route('coordinador.logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 hover:bg-red-50 transition text-left">
                                <i class="fa-solid fa-right-from-bracket text-red-400 w-3 text-center text-xs"></i>
                                <span class="text-[10px] font-bold text-red-500">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-52 bg-white border-r border-gray-100 hidden lg:flex flex-col shrink-0 z-10 shadow-sm">
            <nav class="flex-1 p-4 space-y-1">
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-3 px-2">Principal</p>
                
                <a href="{{ route('coordinador.dashboard') }}" class="flex items-center space-x-2.5 p-2.5 rounded-xl {{ Request::routeIs('coordinador.dashboard') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-7 h-7 rounded-lg {{ Request::routeIs('coordinador.dashboard') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-house text-xs"></i>
                    </div>
                    <span class="text-xs">Dashboard</span>
                </a>

                <a href="{{ route('coordinador.reportes') }}" class="flex items-center space-x-2.5 p-2.5 rounded-xl {{ Request::routeIs('coordinador.reportes') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-7 h-7 rounded-lg {{ Request::routeIs('coordinador.reportes') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                    </div>
                    <span class="text-xs">Reportes</span>
                </a>

                <a href="{{ route('coordinador.modulos') }}" class="flex items-center space-x-2.5 p-2.5 rounded-xl {{ Request::routeIs('coordinador.modulos') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-7 h-7 rounded-lg {{ Request::routeIs('coordinador.modulos') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-grip text-xs"></i>
                    </div>
                    <span class="text-xs">Gestión Módulos</span>
                </a>

                <a href="{{ route('coordinador.supervision') }}" class="flex items-center space-x-2.5 p-2.5 rounded-xl {{ Request::routeIs('coordinador.supervision') ? 'bg-sena-orange/10 text-sena-orange font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-7 h-7 rounded-lg {{ Request::routeIs('coordinador.supervision') ? 'bg-white shadow-sm text-sena-orange' : 'bg-orange-50 text-orange-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-sena-orange' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </div>
                    <span class="text-xs">Supervisión Piso</span>
                </a>

                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-6 mb-3 px-2">Configuración</p>

                <a href="{{ route('coordinador.configuracion') }}" class="flex items-center space-x-2.5 p-2.5 rounded-xl {{ Request::routeIs('coordinador.configuracion') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-7 h-7 rounded-lg {{ Request::routeIs('coordinador.configuracion') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-gear text-xs"></i>
                    </div>
                    <span class="text-xs">Ajustes</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-50 space-y-2">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-wider mb-1">Ayuda</p>
                    <a href="{{ route('manual.coordinador') }}" class="inline-block mt-1 text-[10px] font-bold text-sena-blue hover:underline">Manual de usuario</a>
                </div>
                <form action="{{ route('coordinador.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 font-black py-2 rounded-xl text-[9px] uppercase tracking-widest transition">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f6f8]">
            <div class="max-w-[1500px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function updateHeaderClock() {
            const now = new Date();
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            const dateStr = now.toLocaleDateString('es-CO', options);
            const timeStr = now.toLocaleTimeString('es-CO', { hour12: false });
            const clockEl = document.getElementById('header-datetime');
            if (clockEl) clockEl.textContent = dateStr + ' | ' + timeStr;
        }
        setInterval(updateHeaderClock, 1000);
        updateHeaderClock();

        // GLOBAL SEARCH LAUNCHER
        const searchInput = document.getElementById('globalSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                const items = document.querySelectorAll('.searchable-item');
                items.forEach(item => {
                    const searchData = item.getAttribute('data-search') || '';
                    if (searchData.toLowerCase().includes(term)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
