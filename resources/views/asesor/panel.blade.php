@extends('layouts.asesor')

@section('title', 'Dashboard - SENA APE')

@section('content')
@php
    $isPause = session('ase_estado') === 'Pausa';
@endphp

@if(!$isPause)
    <!-- Attendance Dashboard (Active) -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        
        <!-- Main Attendance Card -->
        <div class="xl:col-span-2 space-y-5">
            @if($atencion)
            <div class="bg-sena-blue rounded-2xl p-6 shadow-xl shadow-sena-blue/20 relative overflow-hidden group">
                <i class="fa-solid fa-id-card absolute -bottom-6 -right-6 text-[120px] text-white/5 transform rotate-12 transition-transform group-hover:rotate-0 duration-700"></i>

                <div class="flex justify-between items-start relative z-10">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-white/80 uppercase tracking-[0.3em]">Atendiendo Ahora</p>
                        <h2 class="text-4xl font-black text-white tracking-tighter">{{ $atencion->turno->tur_numero }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[8px] font-black text-white/60 uppercase tracking-widest">Módulo</span>
                            <span class="bg-white/20 text-white text-[9px] font-black px-2 py-0.5 rounded-lg" id="modulo-badge">{{ sprintf('%02d', $asesor->ase_id ?? '01') }}</span>
                            <span class="text-white/40 text-[9px]">·</span>
                            <span class="text-[8px] font-black text-white/70 uppercase tracking-widest">{{ $asesor->ase_tipo_asesor === 'OV' ? 'Orientador Víctimas' : ($asesor->ase_tipo_asesor === 'AT' ? 'Asesor Total' : 'Orientador Técnico') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <div class="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/30">
                            <p class="text-[10px] font-black text-white tracking-widest" id="atencion-timer" data-start="{{ \Carbon\Carbon::parse($atencion->atnc_hora_inicio)->timestamp }}">00:00:00</p>
                        </div>
                        <div class="flex items-center gap-1 bg-white/10 rounded-xl px-2 py-1 border border-white/20">
                            <span class="text-[8px] font-black text-white/60 uppercase mr-1">Módulo</span>
                            <button type="button" onclick="cambiarModulo(-1)" class="w-6 h-6 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all active:scale-90">
                                <i class="fa-solid fa-chevron-left text-white text-[9px]"></i>
                            </button>
                            <span id="modulo-display" class="text-sm font-black text-white w-8 text-center">{{ sprintf('%02d', $asesor->ase_id ?? '01') }}</span>
                            <button type="button" onclick="cambiarModulo(1)" class="w-6 h-6 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all active:scale-90">
                                <i class="fa-solid fa-chevron-right text-white text-[9px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 relative z-10 flex justify-between items-end">
                    <div>
                        <p class="text-[10px] font-black text-white/80 uppercase tracking-[0.2em] mb-2">Ciudadano</p>
                        <h3 class="text-xl font-black text-white leading-tight">
                            {{ $atencion->turno->solicitante?->persona?->pers_nombres ?? 'Ciudadano' }} 
                            {{ $atencion->turno->solicitante?->persona?->pers_apellidos ?? 'No Registrado' }}
                        </h3>
                        <p class="text-sm font-bold text-white/70 mt-1">
                            {{ $atencion->turno->solicitante?->persona?->pers_tipodoc ?? 'DOC' }} 
                            {{ $atencion->turno->solicitante?->persona?->pers_doc ?? '—' }}
                        </p>
                    </div>
                    <button onclick="toggleEditModal(true)" class="bg-white/10 hover:bg-white/20 p-3 rounded-xl border border-white/20 transition-all group active:scale-95" title="Editar datos del ciudadano">
                        <i class="fa-solid fa-user-pen text-white text-xl"></i>
                    </button>
                </div>

                {{-- Mientras hay atención activa: solo Finalizar y Ausente --}}
                <div class="grid grid-cols-2 gap-3 mt-6 relative z-10">
                    <form action="{{ route('asesor.finalizar', $atencion->atnc_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-white text-sena-blue font-extrabold py-3.5 rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center space-x-2 shadow-lg active:scale-95">
                            <i class="fa-solid fa-circle-check text-sm"></i>
                            <span class="uppercase tracking-widest text-xs">Finalizar Atención</span>
                        </button>
                    </form>
                    <form action="{{ route('asesor.ausente', $atencion->atnc_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-rose-500 text-white font-extrabold py-3.5 rounded-xl hover:bg-rose-600 transition-all flex items-center justify-center space-x-2 shadow-lg active:scale-95">
                            <i class="fa-solid fa-user-slash text-sm"></i>
                            <span class="uppercase tracking-widest text-xs">Ciudadano Ausente</span>
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Estado Inactivo / Esperando Turno -->
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user-clock text-3xl text-gray-300"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 italic">Esperando Ciudadano...</h3>
                    <p class="text-sm font-medium text-gray-400 mt-2">Actualmente no tienes ninguna atención activa.</p>
                </div>
                <form id="autoCallForm" action="{{ route('asesor.llamar') }}" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="hidden" name="ase_id" value="{{ $asesor->ase_id }}">
                    <button type="submit" id="autoCallBtn" class="w-full bg-sena-blue text-white font-black py-4 rounded-xl shadow-lg hover:bg-sena-blue/90 hover:-translate-y-0.5 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        Llamar Siguiente Turno
                    </button>
                </form>
            </div>
            @endif

            <!-- Secondary Stats Row -->
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col items-center group hover:shadow-lg transition-all duration-300">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-base mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Atendidos hoy</p>
                    <h4 class="text-3xl font-black text-gray-900">24</h4>
                    <span class="text-[10px] font-black text-emerald-500 mt-2 tracking-widest">+12%</span>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col items-center group hover:shadow-lg transition-all duration-300">
                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-base mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tiempo atención</p>
                    <h4 class="text-3xl font-black text-gray-900">14 <span class="text-sm">min</span></h4>
                    <span class="text-[10px] font-black text-gray-400 mt-2 tracking-widest uppercase">Promedio</span>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex flex-col justify-between group hover:shadow-lg transition-all duration-300">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-left px-2">Estado del Puesto</h5>
                    <div class="w-full mt-4 bg-gray-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-sena-orange h-full rounded-full w-[75%] transition-all duration-1000 group-hover:w-[85%]"></div>
                    </div>
                    <div class="flex justify-between items-end mt-4">
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Capacidad</span>
                        <span class="text-lg font-black text-gray-900">75%</span>
                    </div>
                    <div class="mt-4 bg-[#ECFDF5] text-emerald-600 px-4 py-2 rounded-xl text-center">
                        <p class="text-[8px] font-black uppercase tracking-[0.2em] mb-0.5">Rendimiento</p>
                        <p class="text-xs font-black uppercase">Excelente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Service Details) -->
        <div class="space-y-10">
            <div class="bg-white h-full rounded-[3rem] p-10 shadow-sm border border-gray-100">
                <div class="flex items-center space-x-2 mb-5 pb-4 border-b border-gray-50">
                    <i class="fa-solid fa-user-tag text-sena-blue text-sm"></i>
                    <h4 class="text-sm font-black text-gray-900 tracking-wide uppercase">Detalles del Servicio</h4>
                </div>

                <div class="space-y-10">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100/50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Trámite solicitado</p>
                        <h5 class="text-sm font-black text-gray-800 leading-snug">
                            {{ $atencion->turno->tramite ?? 'Consultoría de Empleo y Formalización' }}
                        </h5>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100/50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Prioridad</p>
                        <div class="flex items-center space-x-2 {{ ($atencion && $atencion->turno->tur_tipo != 'General') ? 'text-sena-orange' : 'text-sena-blue' }}">
                            <i class="fa-solid fa-circle-check"></i>
                            <span class="text-xs font-black uppercase tracking-widest">{{ $atencion->turno->tur_tipo ?? 'Normal' }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100/50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hora de llegada</p>
                        <h5 class="text-lg font-black text-gray-800 flex items-center space-x-3">
                            <span>{{ \Carbon\Carbon::parse($atencion->turno->tur_hora_fecha ?? now())->format('h:i A') }}</span>
                            <span id="arrival-relative-time" class="text-[9px] font-black bg-sena-blue/10 text-sena-blue px-3 py-1 rounded-full uppercase tracking-tighter" data-arrival="{{ \Carbon\Carbon::parse($atencion->turno->tur_hora_fecha ?? now())->timestamp }}">
                                Calculando...
                            </span>
                        </h5>
                    </div>

                    @if($atencion)
                    {{-- Botón ausente eliminado aquí — ya está en la tarjeta principal --}}
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Lists -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 h-full">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-clock-rotate-left text-gray-400"></i>
                        <h4 class="text-sm font-black text-gray-900 tracking-wide uppercase">Últimos Turnos</h4>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @foreach([['num'=>'NIT-044', 'name'=>'Ana María Restrepo', 'time'=>'9:15 AM'],['num'=>'NIT-043', 'name'=>'Carlos Mario Úsuga', 'time'=>'8:58 AM'],['num'=>'NIT-042', 'name'=>'Elena Beltrán', 'time'=>'8:30 AM'],['num'=>'NIT-041', 'name'=>'Pedro Duarte', 'time'=>'8:12 AM']] as $t)
                    <div class="flex items-center justify-between group cursor-pointer hover:bg-gray-50 p-2 -m-2 rounded-2xl transition-all">
                        <div class="flex items-center space-x-4">
                            <div class="w-2 h-2 rounded-full bg-gray-200 group-hover:bg-sena-blue transition-colors"></div>
                            <div>
                                <p class="text-xs font-black text-gray-900">{{ $t['num'] }}</p>
                                <p class="text-[10px] font-bold text-gray-400 mt-0.5">{{ $t['name'] }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $t['time'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 h-full">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-sm font-black text-gray-900 tracking-wide uppercase">Atendidos por Hora</h4>
                </div>
                <div class="h-64">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@else
    
    <!-- Pause Mode Design (Ultra-Premium Glassmorphism) -->
    <div class="relative w-full h-[80vh] min-h-[600px] flex items-center justify-center">
        <!-- Fondos decorativos para el Glassmorphism -->
        <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-amber-400 rounded-full blur-[120px] opacity-30 -translate-y-1/2"></div>
        <div class="absolute bottom-1/4 right-1/4 w-[30rem] h-[30rem] bg-orange-500 rounded-full blur-[150px] opacity-20"></div>
        
        <div class="bg-white/60 backdrop-blur-3xl p-8 sm:p-12 rounded-[3.5rem] shadow-2xl border border-white w-full max-w-5xl relative z-10 overflow-hidden flex flex-col md:flex-row gap-8 lg:gap-12">
            
            <!-- Left Side: Status & Action -->
            <div class="flex-1 flex flex-col justify-center text-center md:text-left">
                <div class="w-20 h-20 bg-sena-orange rounded-[1.5rem] flex items-center justify-center text-white text-4xl shadow-lg shadow-sena-orange/30 mb-8 relative border-2 border-white mx-auto md:mx-0">
                    <i class="fa-solid fa-mug-hot relative z-10"></i>
                    <div class="absolute inset-0 bg-white/20 rounded-[1.5rem] animate-pulse"></div>
                </div>
                
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">Atención en Pausa</h2>
                <p class="text-sm lg:text-base font-medium text-gray-500 leading-relaxed mb-10 max-w-sm mx-auto md:mx-0">Actualmente te encuentras en tiempo de descanso programado. La asignación de turnos está temporalmente detenida.</p>
                
                <form action="{{ route('asesor.receso.finalizar') }}" method="POST" class="w-full md:w-max mx-auto md:mx-0">
                    @csrf
                    <button type="submit" id="btn-resume-work" class="bg-gradient-to-r from-sena-blue to-sena-orange hover:from-sena-blue/90 hover:to-sena-orange/90 text-white font-black py-5 px-10 rounded-2xl transition-all shadow-xl shadow-sena-blue/30 transform hover:-translate-y-1 active:scale-95 inline-flex items-center justify-center space-x-4 w-full relative overflow-hidden group">
                        <span class="absolute inset-0 w-full h-full bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <i class="fa-solid fa-play text-lg relative z-10"></i>
                        <span class="text-xs uppercase tracking-[0.2em] relative z-10">Finalizar Receso y Reanudar</span>
                    </button>
                </form>
            </div>

            <!-- Right Side: Timer & Stats -->
            <div class="flex-1">
                <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] p-8 lg:p-10 shadow-sm border border-white flex flex-col items-center justify-center text-center h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-sena-yellow rounded-full blur-[50px] opacity-10"></div>
                    
                    <p class="text-[10px] font-black text-sena-orange uppercase tracking-[0.4em] mb-8 bg-sena-yellow/10 px-4 py-1.5 rounded-xl border border-sena-yellow/20">Tiempo Transcurrido</p>
                    
                    <div class="flex items-center justify-center space-x-3 sm:space-x-6 w-full" id="pause-timer-display">
                        <div class="flex flex-col items-center w-20 sm:w-24">
                            <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl border border-gray-100 flex items-center justify-center shadow-inner mb-3 transform hover:scale-105 transition-transform">
                                <span class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tighter" id="pause-hours">00</span>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Horas</span>
                        </div>
                        <span class="text-3xl sm:text-4xl font-black text-sena-orange pb-8 animate-pulse opacity-50">:</span>
                        <div class="flex flex-col items-center w-20 sm:w-24">
                            <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl border border-gray-100 flex items-center justify-center shadow-inner mb-3 transform hover:scale-105 transition-transform">
                                <span class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tighter" id="pause-minutes">00</span>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Minutos</span>
                        </div>
                        <span class="text-3xl sm:text-4xl font-black text-sena-orange pb-8 animate-pulse opacity-50">:</span>
                        <div class="flex flex-col items-center w-20 sm:w-24">
                            <div class="w-full aspect-square bg-gradient-to-br from-gray-50 to-white rounded-2xl sm:rounded-3xl border border-gray-100 flex items-center justify-center shadow-inner mb-3 transform hover:scale-105 transition-transform">
                                <span class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tighter" id="pause-seconds">00</span>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Segundos</span>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent my-10"></div>

                    <div class="flex justify-between items-center w-full px-2 sm:px-4">
                        <div class="text-left flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Atendidos Hoy</p>
                                <p class="text-xl font-black text-gray-900">12</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center space-x-3 flex-row-reverse">
                            <div class="w-8 h-8 bg-sena-blue/10 rounded-lg ml-3 items-center justify-center text-sena-blue hidden sm:flex"><i class="fa-solid fa-arrow-trend-up"></i></div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Impacto</p>
                                <p class="text-xl font-black text-sena-blue uppercase">+15%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endif

@if($atencion)
    <!-- Modal de Edición de Ciudadano -->
    <div id="editPersonaModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" onclick="toggleEditModal(false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="bg-white px-8 pt-10 pb-8 sm:p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-sena-50 rounded-2xl flex items-center justify-center text-sena-blue">
                                <i class="fa-solid fa-user-gear text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 leading-none">Editar Ciudadano</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1.5">Corregir información del perfil</p>
                            </div>
                        </div>
                        <button onclick="toggleEditModal(false)" class="text-gray-300 hover:text-gray-500 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('asesor.persona.update', $atencion->turno->solicitante?->persona?->pers_doc ?? '0') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipo Doc</label>
                                <select name="pers_tipodoc" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-sena-blue/20 focus:border-sena-blue outline-none transition-all">
                                    <option value="CC" {{ ($atencion->turno->solicitante?->persona?->pers_tipodoc ?? '') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                    <option value="TI" {{ ($atencion->turno->solicitante?->persona?->pers_tipodoc ?? '') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                    <option value="CE" {{ ($atencion->turno->solicitante?->persona?->pers_tipodoc ?? '') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                    <option value="PEP" {{ ($atencion->turno->solicitante?->persona?->pers_tipodoc ?? '') == 'PEP' ? 'selected' : '' }}>PEP</option>
                                </select>
                            </div>
                            <div class="space-y-1.5 opacity-60">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Documento</label>
                                <input type="text" value="{{ $atencion->turno->solicitante?->persona?->pers_doc ?? '—' }}" disabled class="w-full bg-gray-100 border border-gray-100 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombres</label>
                            <input type="text" name="pers_nombres" value="{{ $atencion->turno->solicitante?->persona?->pers_nombres ?? '' }}" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-sena-blue/20 focus:border-sena-blue outline-none transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Apellidos</label>
                            <input type="text" name="pers_apellidos" value="{{ $atencion->turno->solicitante?->persona?->pers_apellidos ?? '' }}" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-sena-blue/20 focus:border-sena-blue outline-none transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Teléfono / Celular</label>
                            <input type="text" name="pers_telefono" value="{{ $atencion->turno->solicitante?->persona?->pers_telefono ?? '' }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-sena-blue/20 focus:border-sena-blue outline-none transition-all" placeholder="Ej: 3001234567">
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <button type="button" onclick="toggleEditModal(false)" class="flex-1 bg-gray-50 text-gray-500 font-black py-4 rounded-2xl hover:bg-gray-100 transition-all text-xs uppercase tracking-widest">
                                Cancelar
                            </button>
                            <button type="submit" class="flex-1 bg-sena-blue text-white font-black py-4 rounded-2xl hover:bg-sena-blue/90 transition-all text-xs uppercase tracking-widest shadow-lg shadow-sena-blue/20">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    // Cronómetro de atención
    const timerElement = document.getElementById('atencion-timer');
    if (timerElement && timerElement.dataset.start) {
        const startTime = parseInt(timerElement.dataset.start);
        
        setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const diff = now - startTime;
            
            const h = Math.floor(diff / 3600).toString().padStart(2, '0');
            const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const s = (diff % 60).toString().padStart(2, '0');
            
            timerElement.textContent = `${h}:${m}:${s}`;
        }, 1000);
    }

    // Cronómetro de pausa (Persistente con localStorage)
    const pauseDisplay = document.getElementById('pause-timer-display');
    if (pauseDisplay) {
        let pauseStartTime = localStorage.getItem('ape_pause_start');
        if (!pauseStartTime) {
            pauseStartTime = Date.now();
            localStorage.setItem('ape_pause_start', pauseStartTime);
        }

        const hEl = document.getElementById('pause-hours');
        const mEl = document.getElementById('pause-minutes');
        const sEl = document.getElementById('pause-seconds');

        setInterval(() => {
            const diff = Math.floor((Date.now() - parseInt(pauseStartTime)) / 1000);
            const h = Math.floor(diff / 3600).toString().padStart(2, '0');
            const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const s = (diff % 60).toString().padStart(2, '0');
            
            hEl.textContent = h;
            mEl.textContent = m;
            sEl.textContent = s;
        }, 1000);
    }

    // Actualización de tiempo relativo de llegada
    const arrivalElement = document.getElementById('arrival-relative-time');
    if (arrivalElement) {
        const arrivalTimestamp = parseInt(arrivalElement.dataset.arrival);
        
        function updateArrivalRelativeTime() {
            const now = Math.floor(Date.now() / 1000);
            const diffInSeconds = now - arrivalTimestamp;
            const diffInMinutes = Math.floor(diffInSeconds / 60);
            
            if (diffInMinutes < 1) {
                arrivalElement.textContent = 'Hace un momento';
            } else if (diffInMinutes < 60) {
                arrivalElement.textContent = `Hace ${diffInMinutes} min`;
            } else {
                const hours = Math.floor(diffInMinutes / 60);
                arrivalElement.textContent = `Hace ${hours}h ${diffInMinutes % 60}min`;
            }
        }
        
        updateArrivalRelativeTime();
        setInterval(updateArrivalRelativeTime, 60000); // Actualizar cada minuto
    }

    const btnResume = document.getElementById('btn-resume-work');
    if(btnResume) {
        btnResume.addEventListener('click', () => {
            localStorage.removeItem('ape_pause_start');
        });
    }


    // --- SELECTOR DE MÓDULO ---
    let moduloActual = parseInt('{{ $asesor->ase_id ?? 1 }}') || 1;
    const moduloMin = 1;
    const moduloMax = 20;

    function cambiarModulo(delta) {
        moduloActual = Math.min(moduloMax, Math.max(moduloMin, moduloActual + delta));
        const display = document.getElementById('modulo-display');
        const badge = document.getElementById('modulo-badge');
        const formatted = String(moduloActual).padStart(2, '0');
        if (display) display.textContent = formatted;
        if (badge) badge.textContent = formatted;
        // Guardar en sesión via fetch silencioso (opcional)
        fetch('/asesor/modulo/' + moduloActual, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' } }).catch(() => {});
    }
    function toggleEditModal(show) {
        const modal = document.getElementById('editPersonaModal');
        if (modal) {
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    }

    if (document.getElementById('mainChart')) {
        const ctx = document.getElementById('mainChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00'],
                datasets: [{
                    label: 'Atenciones',
                    data: [2, 5, 4, 8, 3, 5],
                    borderColor: '#10069F',
                    backgroundColor: 'rgba(16, 6, 159, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { display: false }
                }
            }
        });
    }

    // SINCRONIZACIÓN ENTRE ASESORES (Auto-refresco de cola)
    // Refresca la página cada 20 segundos para mantener la lista de espera sincronizada
    // Solo si el modal de edición no está abierto para no interrumpir al usuario
    setInterval(() => {
        const modal = document.getElementById('editPersonaModal');
        const isModalOpen = modal && !modal.classList.contains('hidden');
        
        if (!isModalOpen) {
            console.log('Sincronizando estado de turnos...');
            window.location.reload();
        }
    }, 20000); // 20 segundos

    // AUTO-LLAMADO AUTOMÁTICO (Nueva Función Solicitada)
    // Si no hay atención activa Y hay turnos en espera Y no estamos en pausa
    @if(!$atencion && count($turnosEnEspera) > 0 && !$isPause)
        const autoForm = document.getElementById('autoCallForm');
        const autoBtn = document.getElementById('autoCallBtn');
        if (autoForm && autoBtn) {
            let timer = 3;
            const autoInterval = setInterval(() => {
                autoBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> LLAMANDO EN ${timer}...`;
                timer--;
                if (timer < 0) {
                    clearInterval(autoInterval);
                    autoForm.submit();
                }
            }, 1000);
        }
    @endif
</script>
@endsection




