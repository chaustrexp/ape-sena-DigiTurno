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
    <header class="bg-white px-8 py-4 flex items-center justify-between border-b border-gray-100 shrink-0 z-20">
        <!-- Logo -->
        <div class="flex items-center space-x-4 w-1/4">
            <img src="{{ asset('images/logo.jpeg') }}" class="h-10 w-auto object-contain" alt="SENA Logo">
            <div class="h-6 w-px bg-gray-100"></div>
            <div>
                <h1 class="text-lg font-poppins font-bold text-gray-900 leading-tight">SENA APE</h1>
                <p class="text-[10px] font-bold text-sena-blue tracking-wider">Sistema de Gestión de Turnos</p>
            </div>
        </div>

        <!-- Time & Search -->
        <div class="flex items-center justify-center space-x-12 w-2/4">
            <div class="flex flex-col border-r border-gray-200 pr-12">
                <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Sesión Actual</span>
                <span class="text-xs font-bold text-gray-900" id="header-datetime">Cargando...</span>
            </div>
            <div class="relative w-80">
                <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="globalSearchInput" placeholder="Buscar módulos o asesores..." class="w-full bg-gray-50 border border-gray-100 rounded-full py-2.5 pl-10 pr-4 text-xs focus:ring-2 focus:ring-sena-blue outline-none text-gray-700 font-medium transition-all">
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4 w-1/4">
            <a href="{{ route('coordinador.export') }}" class="bg-sena-blue hover:bg-sena-blue/90 text-white px-5 py-2.5 rounded-full text-[11px] font-bold transition flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-download"></i>
                <span>Exportar</span>
            </a>
            <!-- User + Logout -->
            <div class="flex items-center space-x-3 border-l border-gray-200 pl-4 relative" x-data="{ open: false }">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-900">{{ session('coordinador_nombre', 'Coordinador') }}</p>
                    <p class="text-[10px] font-semibold text-sena-blue">Coordinador</p>
                </div>
                <div class="relative">
                    <button onclick="document.getElementById('coord-user-menu').classList.toggle('hidden')" 
                            class="w-10 h-10 rounded-full border-2 border-gray-200 hover:border-sena-blue/30 transition overflow-hidden focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(session('coordinador_nombre', 'C')) }}&background=f0f0ff&color=10069F&bold=true" class="w-full h-full object-cover" alt="Profile">
                    </button>
                    <!-- Dropdown -->
                    <div id="coord-user-menu" class="hidden absolute right-0 top-12 bg-white border border-gray-100 rounded-2xl shadow-2xl w-52 z-50 overflow-hidden py-2">
                        <div class="px-4 py-3 border-b border-gray-50">
                            <p class="text-xs font-black text-gray-900">{{ session('coordinador_nombre', 'Coordinador') }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Coordinador APE SENA</p>
                        </div>
                        <a href="{{ route('coordinador.configuracion') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition">
                            <i class="fa-solid fa-gear text-gray-400 w-4 text-center"></i>
                            <span class="text-xs font-bold text-gray-600">Configuración</span>
                        </a>
                        <form action="{{ route('coordinador.logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition text-left">
                                <i class="fa-solid fa-right-from-bracket text-red-400 w-4 text-center"></i>
                                <span class="text-xs font-bold text-red-500">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-100 hidden lg:flex flex-col shrink-0 z-10 shadow-sm">
            <nav class="flex-1 p-6 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 px-3">Principal</p>
                
                <a href="{{ route('coordinador.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl {{ Request::routeIs('coordinador.dashboard') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-8 h-8 rounded-lg {{ Request::routeIs('coordinador.dashboard') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-house text-sm"></i>
                    </div>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('coordinador.reportes') }}" class="flex items-center space-x-3 p-3 rounded-xl {{ Request::routeIs('coordinador.reportes') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-8 h-8 rounded-lg {{ Request::routeIs('coordinador.reportes') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <span class="text-sm">Reportes</span>
                </a>

                <a href="{{ route('coordinador.modulos') }}" class="flex items-center space-x-3 p-3 rounded-xl {{ Request::routeIs('coordinador.modulos') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-8 h-8 rounded-lg {{ Request::routeIs('coordinador.modulos') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-grip text-sm"></i>
                    </div>
                    <span class="text-sm">Gestión Módulos</span>
                </a>

                <a href="{{ route('coordinador.supervision') }}" class="flex items-center space-x-3 p-3 rounded-xl {{ Request::routeIs('coordinador.supervision') ? 'bg-sena-orange/10 text-sena-orange font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-8 h-8 rounded-lg {{ Request::routeIs('coordinador.supervision') ? 'bg-white shadow-sm text-sena-orange' : 'bg-orange-50 text-orange-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-sena-orange' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </div>
                    <span class="text-sm">Supervisión Piso</span>
                </a>

                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-8 mb-4 px-3">Configuración</p>

                <a href="{{ route('coordinador.configuracion') }}" class="flex items-center space-x-3 p-3 rounded-xl {{ Request::routeIs('coordinador.configuracion') ? 'bg-sena-50 text-sena-blue font-bold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }} group transition">
                    <div class="w-8 h-8 rounded-lg {{ Request::routeIs('coordinador.configuracion') ? 'bg-white shadow-sm text-sena-blue' : 'bg-gray-50 text-gray-400 group-hover:bg-white group-hover:shadow-sm group-hover:text-gray-600' }} flex items-center justify-center transition">
                        <i class="fa-solid fa-gear text-sm"></i>
                    </div>
                    <span class="text-sm">Ajustes</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-6 border-t border-gray-50 space-y-3">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Ayuda</p>
                    <p class="text-[11px] text-gray-600 leading-relaxed">¿Necesitas ayuda con el sistema?</p>
                    <a href="{{ route('manual.coordinador') }}" class="inline-block mt-3 text-[11px] font-bold text-sena-blue hover:underline">Manual de usuario</a>
                </div>
                <form action="{{ route('coordinador.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 font-black py-3 rounded-2xl text-[11px] uppercase tracking-widest transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#f4f6f8]">
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
