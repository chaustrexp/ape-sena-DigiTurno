@php
    $isPause = session('ase_estado') === 'Pausa';
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'APE Advisor - Digital Queue Control')</title>
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
                            50: '#f0f0ff', 
                            100: '#e1e1ff', 
                            500: '#10069F', 
                            600: '#0c047a' 
                        },
                        amber: { 50: '#fff8e1', 100: '#ffecb3', 500: '#FFB500', 600: '#e6a300' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar-item { transition: all 0.3s ease; }
        .active-glow { box-shadow: 0 0 15px rgba(16, 6, 159, 0.2); }
    </style>
    @yield('styles')
</head>
<body class="h-screen overflow-hidden flex bg-[#F0F2F5]">

    <!-- Sidebar -->
    <aside class="w-56 bg-white flex flex-col border-r border-gray-100 shrink-0 z-30">
        <div class="px-5 py-6 flex flex-col space-y-3">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.jpeg') }}" class="h-8 w-auto object-contain" alt="SENA Logo">
                <div class="h-6 w-px bg-gray-100 mx-1"></div>
                <div>
                    <h1 class="text-sm font-poppins font-black text-gray-900 tracking-tight leading-none uppercase">SENA APE</h1>
                    <p class="text-[8px] font-bold text-sena-blue uppercase tracking-wider mt-0.5 leading-none">Sistema de Gestión de Turnos</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('asesor.index') }}" class="sidebar-item flex items-center space-x-3 px-3 py-3 rounded-xl {{ Request::routeIs('asesor.index') && !$isPause ? 'bg-sena-50 text-sena-500 font-bold active-glow' : 'text-gray-400 hover:bg-gray-50' }}">
                <i class="fa-solid fa-house-chimney text-sm"></i>
                <span class="text-xs">Dashboard</span>
            </a>
            <a href="{{ route('asesor.actividad') }}" class="sidebar-item flex items-center space-x-3 px-3 py-3 rounded-xl {{ Request::routeIs('asesor.actividad') ? 'bg-sena-50 text-sena-500 font-bold active-glow' : 'text-gray-400 hover:bg-gray-50' }}">
                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                <span class="text-xs">Actividad Reciente</span>
            </a>
            <a href="{{ route('asesor.tramites') }}" class="sidebar-item flex items-center space-x-3 px-3 py-3 rounded-xl {{ Request::routeIs('asesor.tramites') ? 'bg-sena-50 text-sena-500 font-bold active-glow' : 'text-gray-400 hover:bg-gray-50' }}">
                <i class="fa-solid fa-file-invoice text-sm"></i>
                <span class="text-xs">Trámites</span>
            </a>
            <a href="{{ route('asesor.reportes') }}" class="sidebar-item flex items-center space-x-3 px-3 py-3 rounded-xl {{ Request::routeIs('asesor.reportes') ? 'bg-sena-50 text-sena-500 font-bold active-glow' : 'text-gray-400 hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-simple text-sm"></i>
                <span class="text-xs">Reportes</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-50 mt-auto">
            @if($isPause)
                <form action="{{ route('asesor.receso.finalizar') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center bg-sena-orange text-white font-black py-2.5 rounded-full shadow-lg shadow-sena-orange/30 hover:bg-sena-orange/90 transition-all hover:scale-105 active:scale-95 space-x-2 text-[10px] uppercase tracking-widest mb-4 px-2">
                        <i class="fa-solid fa-play text-xs"></i>
                        <span>Finalizar Receso</span>
                    </button>
                </form>
            @else
                <form action="{{ route('asesor.receso.iniciar') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center border-2 border-sena-orange text-sena-orange font-black py-2.5 rounded-full hover:bg-sena-orange/5 transition-all hover:scale-105 active:scale-95 space-x-2 text-[10px] uppercase tracking-widest mb-4 px-2">
                        <i class="fa-solid fa-pause text-xs"></i>
                        <span>Iniciar Receso</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('asesor.configuracion') }}" class="flex items-center space-x-3 px-3 py-2 rounded-xl text-gray-400 hover:text-gray-900 transition-colors mb-3">
                <i class="fa-solid fa-gear text-sm"></i>
                <span class="text-xs font-bold">Configuración</span>
            </a>

            <div class="flex flex-col bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-2 px-1">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset(session('ase_foto', $asesor->ase_foto ?? 'images/foto de perfil.jpg')) }}" class="w-8 h-8 rounded-full border-2 border-white shadow-sm object-cover" alt="Profile">
                        <div>
                            <p class="text-[10px] font-black text-gray-900 leading-tight">{{ session('ase_nombre', 'Asesor') }}</p>
                            <p class="text-[8px] font-bold text-sena-500 uppercase tracking-widest">{{ session('ase_tipo_asesor', 'General') }}</p>
                        </div>
                    </div>
                    <form action="{{ route('asesor.logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-rose-500 px-1 transition-colors" title="Cerrar Sesión">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
                <div class="px-1">
                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Módulo {{ $asesor->modulo ?? '04' }}</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 mt-4">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Ayuda</p>
                <a href="{{ route('manual.asesor') }}" class="inline-block mt-1 text-[10px] font-bold text-sena-500 hover:underline">Manual de usuario</a>
            </div>
        </div>
    </aside>

    <!-- Main Section -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Header -->
        <header class="h-14 px-6 flex items-center justify-between border-b border-gray-100 bg-white/80 backdrop-blur-md z-20 sticky top-0">
            <div class="flex items-center space-x-2">
                <span class="text-gray-400 font-medium text-xs">Agencia Pública de Empleo</span>
                <span class="text-gray-200">|</span>
                <span class="text-gray-400 font-medium text-xs">SENA Regional</span>
            </div>

            <div class="flex items-center space-x-5">
                <div class="flex items-center space-x-1.5 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">En Línea</span>
                </div>
                <div class="flex items-center space-x-3 relative">
                    <button id="notification-bell" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-full transition relative focus:outline-none">
                        <i class="fa-solid fa-bell text-sm"></i>
                        <span id="notification-indicator" class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full border border-white transition-opacity"></span>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="hidden absolute top-10 right-0 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform opacity-0 scale-95 transition-all duration-300 z-50">
                        <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Notificaciones</h4>
                            <span id="notification-badge" class="text-[8px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg uppercase tracking-widest">2 Nuevas</span>
                        </div>
                        <div class="max-h-[260px] overflow-y-auto">
                            <div class="p-4 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors relative group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-sena-blue rounded-r-lg group-hover:w-2 transition-all"></div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                        <i class="fa-solid fa-bullhorn text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-900">Actualización del Sistema</p>
                                        <p class="text-[9px] font-medium text-gray-500 mt-0.5 leading-relaxed">Se han implementado nuevas mejoras visuales.</p>
                                        <p class="text-[8px] font-black text-gray-400 mt-2 uppercase tracking-widest">Hace 5 min</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-gray-50 cursor-pointer transition-colors relative group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500 rounded-r-lg group-hover:w-2 transition-all"></div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-900">Recordatorio de Pausa</p>
                                        <p class="text-[9px] font-medium text-gray-500 mt-0.5 leading-relaxed">Tu próximo receso inicia en 15 minutos.</p>
                                        <p class="text-[8px] font-black text-gray-400 mt-2 uppercase tracking-widest">Hace 1 hora</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-t border-gray-50 text-center bg-gray-50/50">
                            <button id="mark-read-btn" class="w-full py-1.5 bg-white rounded-lg border border-gray-100 text-[8px] font-black text-gray-500 hover:text-emerald-500 hover:border-emerald-200 uppercase tracking-widest hover:shadow-sm transition-all">Marcar todas como leídas</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 bg-gray-50/30">
            <div class="max-w-[1400px] mx-auto">
                @yield('content')
            </div>
        </div>

        <!-- Footer status bar -->
        <footer class="h-8 bg-white border-t border-gray-100 px-6 flex items-center justify-between z-20 shrink-0">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isPause ? 'bg-sena-orange' : 'bg-sena-blue' }}"></span>
                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-[0.2em]">Estado: {{ $isPause ? 'Pausa' : 'Atendiendo' }}</span>
                </div>
                <div class="text-[8px] font-bold text-gray-400">Último ciudadano atendido: 10:42 AM</div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-1.5 text-gray-400">
                    <i class="fa-solid fa-wifi text-[9px]"></i>
                    <span class="text-[8px] font-bold">Sincronizado</span>
                </div>
                <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest">{{ $isPause ? 'v1.4.0-PAUSE' : 'v4.5.0-LIVE' }}</span>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bell = document.getElementById('notification-bell');
            const dropdown = document.getElementById('notification-dropdown');
            const indicator = document.getElementById('notification-indicator');
            const badge = document.getElementById('notification-badge');
            const markReadBtn = document.getElementById('mark-read-btn');

            if(bell && dropdown) {
                bell.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if(dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        setTimeout(() => {
                            dropdown.classList.remove('opacity-0', 'scale-95');
                        }, 10);
                    } else {
                        dropdown.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            dropdown.classList.add('hidden');
                        }, 300);
                    }
                });

                document.addEventListener('click', (e) => {
                    if(!dropdown.contains(e.target) && !dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            dropdown.classList.add('hidden');
                        }, 300);
                    }
                });

                if(markReadBtn) {
                    markReadBtn.addEventListener('click', () => {
                        if(indicator) indicator.classList.add('opacity-0');
                        if(badge) {
                            badge.textContent = '0 Nuevas';
                            badge.classList.replace('bg-emerald-50', 'bg-gray-100');
                            badge.classList.replace('text-emerald-600', 'text-gray-400');
                        }
                        markReadBtn.textContent = 'Sin notificaciones pendientes';
                        markReadBtn.disabled = true;
                    });
                }
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
