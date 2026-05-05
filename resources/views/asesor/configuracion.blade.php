@extends('layouts.asesor')

@section('title', 'Configuración de Módulo - APE Advisor')

@section('content')
<div class="relative max-w-5xl mx-auto mt-2">
    <!-- Fondos decorativos para el Glassmorphism -->
    <div class="absolute -top-10 -left-10 w-96 h-96 bg-sena-400 rounded-full blur-[100px] opacity-20"></div>
    <div class="absolute -bottom-10 -right-10 w-96 h-96 bg-emerald-300 rounded-full blur-[100px] opacity-20"></div>
    
    <!-- Contenedor Principal Glass -->
    <div class="bg-white/70 backdrop-blur-2xl p-2 rounded-3xl shadow-xl border border-white relative z-10 overflow-hidden">
        
        <!-- Header del Contenedor -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-8 rounded-[1.5rem] text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-sena-500 rounded-full blur-[80px] opacity-30 -translate-y-1/2 translate-x-1/3"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black tracking-tight mb-1 sm:mb-2">Ajustes del Entorno</h2>
                    <p class="text-gray-400 font-medium text-xs sm:text-sm">Personaliza tu módulo de atención, notificaciones y perfil de usuario.</p>
                </div>
                <div class="hidden sm:flex w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl items-center justify-center border border-white/20 text-xl shadow-inner shrink-0 sm:ml-4">
                    <i class="fa-solid fa-sliders text-sena-400"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-4 sm:p-6">
            
            <!-- Columna Izquierda: Perfil (4 columnas) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 text-center hover:shadow-xl transition-all duration-300 relative group overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-24 bg-gray-50 group-hover:bg-sena-50 transition-colors duration-500"></div>
                    <div class="relative z-10 pt-4">
                        <div class="relative inline-block">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($asesor->persona->pers_nombres ?? 'Carlos Ruiz') }}&background=39A900&color=fff&size=128&bold=true" class="w-24 h-24 rounded-2xl border-4 border-white mx-auto shadow-md transform group-hover:scale-105 transition-transform duration-300" alt="Profile">
                            <span class="absolute bottom-2 -right-2 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full shadow-sm"></span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 leading-tight mt-4 mb-1">{{ $asesor->persona->pers_nombres ?? 'Carlos Ruiz' }}</h3>
                        <p class="text-[10px] font-black text-sena-500 uppercase tracking-widest bg-sena-50 px-3 py-1 rounded-lg inline-block">Asesor Módulo {{ $asesor->modulo ?? '04' }}</p>
                        
                        <div class="mt-6 pt-6 border-t border-gray-50">
                            <button class="w-full bg-gray-900 text-white font-black py-3 rounded-xl text-[10px] uppercase tracking-widest hover:bg-black transition-colors flex items-center justify-center space-x-2 shadow-lg">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Cambiar Fotografía</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-sena-500 to-emerald-600 rounded-[1.5rem] p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-white rounded-full blur-[40px] opacity-20"></div>
                    <i class="fa-solid fa-headset text-2xl mb-3 opacity-80"></i>
                    <h4 class="text-xs font-black uppercase tracking-widest mb-2">Soporte Técnico</h4>
                    <p class="text-[10px] font-medium text-white/80 leading-relaxed mb-4">¿Problemas con el módulo? Genera un ticket.</p>
                    <button class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white font-black py-2.5 rounded-lg text-[9px] uppercase tracking-widest transition-colors">
                        Solicitar Ayuda
                    </button>
                </div>
            </div>

            <!-- Columna Derecha: Formulario (8 columnas) -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[1.5rem] p-6 sm:p-8 shadow-sm border border-gray-100 h-full flex flex-col justify-between">
                    
                    <div class="space-y-8">
                        <!-- Sección 1 -->
                        <div>
                            <div class="flex items-center space-x-3 mb-5">
                                <div class="w-7 h-7 rounded-full bg-sena-50 flex items-center justify-center text-sena-500">
                                    <i class="fa-solid fa-location-crosshairs text-[10px]"></i>
                                </div>
                                <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Ubicación del Puesto</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="group">
                                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1 group-hover:text-sena-500 transition-colors">Número de Módulo</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-hashtag text-[9px]"></i>
                                        </div>
                                        <input type="text" value="{{ $asesor->modulo ?? '04' }}" class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-black text-gray-700 outline-none focus:ring-2 focus:ring-sena-500 transition-all shadow-inner" readonly>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 pl-1 group-hover:text-sena-500 transition-colors">Sede Asignada</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-building text-[9px]"></i>
                                        </div>
                                        <input type="text" value="Sede Central Antioquia" class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-12 pr-4 py-3 text-sm font-black text-gray-700 outline-none transition-all shadow-inner truncate" readonly title="Sede Central Antioquia">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-px bg-gray-50"></div>

                        <!-- Sección 2 -->
                        <div>
                            <div class="flex items-center space-x-3 mb-5">
                                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">
                                    <i class="fa-solid fa-bell-concierge text-[10px]"></i>
                                </div>
                                <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Alertas y Notificaciones</h4>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-100 hover:border-sena-200 cursor-pointer shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 group-hover:bg-sena-400 transition-colors"></div>
                                    <div class="flex items-center space-x-4 ml-2">
                                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-sena-500 transition-colors shrink-0">
                                            <i class="fa-solid fa-volume-high text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs sm:text-sm font-black text-gray-900 leading-tight">Sonidos del sistema</span>
                                            <span class="block text-[9px] sm:text-[10px] font-medium text-gray-400 mt-0.5 sm:mt-1 leading-snug pr-2">Al recibir turnos nuevos o cambios</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" id="setting-sound" checked class="w-12 h-6 bg-gray-200 rounded-full appearance-none checked:bg-emerald-500 transition-all relative after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-1 after:left-1 checked:after:left-7 after:shadow-sm after:transition-all outline-none shrink-0">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-100 hover:border-sena-200 cursor-pointer shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 group-hover:bg-sena-400 transition-colors"></div>
                                    <div class="flex items-center space-x-4 ml-2">
                                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-sena-500 transition-colors shrink-0">
                                            <i class="fa-solid fa-message text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs sm:text-sm font-black text-gray-900 leading-tight">Push Notification</span>
                                            <span class="block text-[9px] sm:text-[10px] font-medium text-gray-400 mt-0.5 sm:mt-1 leading-snug pr-2">Alertas fuera del navegador web</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" id="setting-push" class="w-12 h-6 bg-gray-200 rounded-full appearance-none checked:bg-emerald-500 transition-all relative after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-1 after:left-1 checked:after:left-7 after:shadow-sm after:transition-all outline-none shrink-0">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="pt-6 mt-8 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <button type="button" id="btn-reset-settings" class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-rose-500 transition-colors underline decoration-dotted underline-offset-4 w-full sm:w-auto text-center">Valores Iniciales</button>
                        <button type="button" id="btn-save-settings" class="bg-gray-900 text-white px-8 py-3.5 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg hover:bg-sena-600 active:scale-95 transition-all w-full sm:w-auto flex items-center justify-center space-x-2 relative group overflow-hidden">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-sena-500 to-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                            <i class="fa-solid fa-check relative z-10"></i>
                            <span class="relative z-10 w-full text-center">Guardar Configuración</span>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const soundCheck = document.getElementById('setting-sound');
        const pushCheck = document.getElementById('setting-push');

        // Cargar preferencias guardadas en el navegador
        if(localStorage.getItem('ape_sound_alerts') !== null) {
            soundCheck.checked = localStorage.getItem('ape_sound_alerts') === 'true';
        }
        if(localStorage.getItem('ape_push_alerts') !== null) {
            pushCheck.checked = localStorage.getItem('ape_push_alerts') === 'true';
        }

        document.getElementById('btn-save-settings').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;
            
            // Guardar localmente
            localStorage.setItem('ape_sound_alerts', soundCheck.checked);
            localStorage.setItem('ape_push_alerts', pushCheck.checked);

            // Permisos de navegador reales para push si se activa
            if(pushCheck.checked && ("Notification" in window) && Notification.permission !== 'granted') {
                Notification.requestPermission();
            }

            // Animación de guardado
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sm"></i> <span>Guardando...</span>';
            
            setTimeout(() => {
                btn.classList.remove('bg-gray-900', 'hover:bg-black');
                btn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
                btn.innerHTML = '<i class="fa-solid fa-check text-sm"></i> <span>¡Preferencias Guardadas!</span>';
                
                setTimeout(() => {
                    btn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
                    btn.classList.add('bg-gray-900', 'hover:bg-black');
                    btn.innerHTML = originalHtml;
                }, 2500);
            }, 800);
        });

        document.getElementById('btn-reset-settings').addEventListener('click', function() {
            if(confirm('¿Estás seguro de restablecer todas las preferencias a su estado original?')) {
                localStorage.removeItem('ape_sound_alerts');
                localStorage.removeItem('ape_push_alerts');
                soundCheck.checked = true; // Por defecto
                pushCheck.checked = false; // Por defecto
            }
        });
    });
</script>
@endsection
