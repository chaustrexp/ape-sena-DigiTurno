@extends('layouts.coordinador')

@section('title', 'Supervisión de Piso — DigiTurno APE SENA')

@section('content')

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-wide">Supervisión de Piso</h2>
        <p class="text-[11px] font-semibold text-gray-400 mt-0.5">CU-04 · Monitoreo en tiempo real · Módulos 15 y 19</p>
    </div>
    <div class="flex items-center space-x-3">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Hora:</span>
        <span id="reloj" class="text-sm font-black text-sena-blue">--:--:--</span>
        <a href="{{ route('coordinador.supervision') }}" class="bg-sena-blue/10 hover:bg-sena-blue/20 text-sena-blue px-4 py-2 rounded-full text-[11px] font-bold transition flex items-center space-x-2">
            <i class="fa-solid fa-rotate-right"></i>
            <span>Actualizar</span>
        </a>
    </div>
</div>

<!-- KPIs Ciclo de Vida (CU-01 / CU-04) -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-5 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-blue-50 text-sena-blue rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Tiempo Medio de Espera</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">
                {{ $tiemposMedios['tiempo_espera_medio'] > 0 ? $tiemposMedios['tiempo_espera_medio'] . ' min' : '—' }}
            </h3>
            <p class="text-[10px] font-bold mt-1 {{ $tiemposMedios['tiempo_espera_medio'] > 20 ? 'text-red-500' : 'text-green-500' }}">
                {{ $tiemposMedios['tiempo_espera_medio'] > 20 ? '⚠ Supera límite (20 min)' : '✓ Dentro del límite' }}
            </p>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-stopwatch"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Tiempo Medio de Atención</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">
                {{ $tiemposMedios['tiempo_atencion_medio'] > 0 ? $tiemposMedios['tiempo_atencion_medio'] . ' min' : '—' }}
            </h3>
            <p class="text-[10px] font-bold text-gray-400 mt-1">Promedio hoy</p>
        </div>
    </div>
</div>

<!-- ── SECCIÓN 1: Estado de Módulos ── -->
<p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
    <i class="fa-solid fa-display mr-2"></i>Estado de Módulos en Tiempo Real
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    @foreach($modulosVigilancia as $modId)
    @php
        $mod = $estadoModulos[$modId];
        $estado = $mod['estado'];
        $colorBorder = match($estado) {
            'Atendiendo'  => 'border-l-4 border-l-green-500',
            'Pausa'       => 'border-l-4 border-l-yellow-500',
            'Libre'       => 'border-l-4 border-l-sena-blue',
            default       => 'border-l-4 border-l-gray-200',
        };
        $badgeBg = match($estado) {
            'Atendiendo'  => 'bg-green-50 text-green-600',
            'Pausa'       => 'bg-yellow-50 text-yellow-600',
            'Libre'       => 'bg-blue-50 text-sena-blue',
            default       => 'bg-gray-50 text-gray-400',
        };
        $dotColor = match($estado) {
            'Atendiendo'  => 'bg-green-500',
            'Pausa'       => 'bg-yellow-500',
            'Libre'       => 'bg-sena-blue',
            default       => 'bg-gray-300',
        };
    @endphp
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.04)] border border-gray-100 {{ $colorBorder }} p-6 hover:shadow-md transition-all duration-300">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-sena-50 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="text-2xl font-black text-sena-blue">{{ $modId }}</span>
                </div>
                <div>
                    <h4 class="text-sm font-black text-gray-900">{{ $mod['nombre'] }}</h4>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Módulo de Vigilancia</p>
                </div>
            </div>
            <span class="flex items-center space-x-1.5 text-[10px] font-black px-3 py-1.5 rounded-full {{ $badgeBg }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} {{ $estado === 'Atendiendo' ? 'animate-pulse' : '' }}"></span>
                <span>{{ $estado }}</span>
            </span>
        </div>

        <div class="flex items-center space-x-6 mb-4">
            <div class="text-center">
                <p class="text-2xl font-black text-gray-900">{{ $mod['atencionesDia'] }}</p>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Atend. Hoy</p>
            </div>
            @if($mod['pausaActiva'])
            <div class="text-center">
                <p class="text-2xl font-black text-yellow-500">☕</p>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Receso desde {{ \Carbon\Carbon::parse($mod['pausaActiva']->hora_inicio)->format('H:i') }}</p>
            </div>
            @endif
        </div>

        @if($mod['atencionActiva'])
        @php
            $persona = $mod['atencionActiva']->turno->solicitante->persona ?? null;
        @endphp
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 flex items-center justify-between">
            <div>
                <p class="text-base font-black text-sena-blue">{{ $mod['atencionActiva']->turno->tur_numero ?? '—' }}</p>
                <p class="text-[10px] text-gray-500 mt-0.5">
                    {{ $persona ? $persona->pers_nombres . ' ' . $persona->pers_apellidos : 'Ciudadano en atención' }}
                    · Inició {{ \Carbon\Carbon::parse($mod['atencionActiva']->atnc_hora_inicio)->format('H:i') }}
                </p>
            </div>
            <i class="fa-solid fa-message text-sena-blue/20 text-2xl"></i>
        </div>
        @endif
    </div>
    @endforeach
</div>

<!-- ── SECCIÓN 2: Meta + Alertas ── -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- Meta Emprendedores -->
    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meta Semanal — Emprendedores</h3>
                <p class="text-xs font-bold text-gray-600 mt-1">Semana {{ now()->startOfWeek()->format('d/m') }} – {{ now()->endOfWeek()->format('d/m/Y') }}</p>
            </div>
            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-rocket text-sm"></i>
            </div>
        </div>

        <div class="flex items-end justify-between mb-3">
            <div>
                <span class="text-4xl font-black text-gray-900">{{ $emprendedoresSemana }}</span>
                <span class="text-sm font-bold text-gray-400 ml-1">/ {{ $metaEmprendedores }}</span>
            </div>
            <span class="text-sm font-black text-gray-500">{{ $porcentajeMeta }}%</span>
        </div>

        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden mb-3">
            <div class="h-full rounded-full transition-all duration-1000"
                 style="width: {{ $porcentajeMeta }}%; background: linear-gradient(90deg, #10069F, #3b82f6);">
            </div>
        </div>

        @if($porcentajeMeta >= 100)
            <p class="text-[11px] font-bold text-green-600 text-center"><i class="fa-solid fa-check-circle mr-1"></i>¡Meta cumplida esta semana!</p>
        @elseif($porcentajeMeta >= 75)
            <p class="text-[11px] font-bold text-yellow-600 text-center"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Faltan {{ $metaEmprendedores - $emprendedoresSemana }} para completar la meta</p>
        @else
            <p class="text-[11px] text-gray-400 text-center">Faltan {{ $metaEmprendedores - $emprendedoresSemana }} emprendedores para alcanzar la meta</p>
        @endif

        <div class="mt-4 pt-4 border-t border-gray-50">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Servicio: Emprendimiento</p>
            <p class="text-[11px] text-gray-400 leading-relaxed">Los turnos con servicio "Emprendimiento" cuentan hacia esta meta. Módulos 15 y 19 son los responsables.</p>
        </div>
    </div>

    <!-- Alertas Espera >20 min -->
    <div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 {{ $turnosEspera20->count() > 0 ? 'border-l-4 border-l-red-500' : '' }}">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-[10px] font-black {{ $turnosEspera20->count() > 0 ? 'text-red-500' : 'text-gray-400' }} uppercase tracking-widest">Alertas de Espera &gt; 20 min</h3>
                <p class="text-xs font-bold text-gray-600 mt-1">{{ $turnosEspera20->count() }} turno(s) con espera crítica</p>
            </div>
            <div class="w-10 h-10 {{ $turnosEspera20->count() > 0 ? 'bg-red-50 text-red-500 animate-bounce' : 'bg-green-50 text-green-500' }} rounded-xl flex items-center justify-center">
                <i class="fa-solid {{ $turnosEspera20->count() > 0 ? 'fa-bell' : 'fa-check' }} text-sm"></i>
            </div>
        </div>

        <div class="space-y-3 max-h-52 overflow-y-auto pr-1">
            @forelse($turnosEspera20 as $t)
            @php $per = $t->solicitante->persona ?? null; @endphp
            <div class="flex items-center justify-between p-3 bg-red-50 border border-red-100 rounded-xl">
                <div>
                    <p class="text-sm font-black text-red-600">{{ $t->tur_numero }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">
                        {{ $per ? $per->pers_nombres . ' ' . $per->pers_apellidos : 'Ciudadano' }}
                        — {{ $t->tur_perfil }} | {{ $t->tur_servicio }}
                    </p>
                </div>
                <span class="text-[11px] font-black text-red-600 bg-red-100 px-3 py-1 rounded-full">{{ $t->minutos_espera }} min</span>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="w-14 h-14 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-check text-green-500 text-xl"></i>
                </div>
                <p class="text-xs font-black text-gray-900 uppercase tracking-wide">Todo en orden</p>
                <p class="text-[10px] text-gray-400 mt-1">Todos los turnos en espera son &lt; 20 minutos</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ── SECCIÓN 3: Rotación Bimestral ── -->
<p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
    <i class="fa-solid fa-rotate mr-2"></i>Indicador de Rotación Bimestral del Personal
</p>
<div class="bg-white p-6 rounded-2xl shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-sm font-black text-gray-900">Personal — Vigencia de Contrato</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Se marca "Rotación requerida" cuando la vigencia es ≤ 60 días</p>
        </div>
        @php $requierenRotacion = $asesoresRotacion->where('requiere_rotacion', true)->count(); @endphp
        @if($requierenRotacion > 0)
            <span class="text-[11px] font-black text-red-500 bg-red-50 border border-red-100 px-4 py-2 rounded-full">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $requierenRotacion }} requiere(n) rotación
            </span>
        @else
            <span class="text-[11px] font-black text-green-600 bg-green-50 border border-green-100 px-4 py-2 rounded-full">
                <i class="fa-solid fa-check mr-1"></i>Sin rotaciones pendientes
            </span>
        @endif
    </div>

    <div class="divide-y divide-gray-50">
        @forelse($asesoresRotacion as $ase)
        @php
            $dias = $ase['dias_restantes'];
            $pillBg = $dias > 90 ? 'bg-green-50 text-green-600' : ($dias > 30 ? 'bg-yellow-50 text-yellow-600' : 'bg-red-50 text-red-500');
        @endphp
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-sena-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user text-sena-blue text-xs"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $ase['nombre'] }}</p>
                    <p class="text-[10px] text-gray-400">Vigencia: {{ $ase['vigencia'] }} · ID: {{ $ase['ase_id'] }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-[11px] font-black px-3 py-1 rounded-full {{ $pillBg }}">
                    {{ $dias >= 0 ? $dias . ' días' : 'Vencido' }}
                </span>
                @if($ase['requiere_rotacion'])
                    <span class="text-[10px] font-black text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-lg uppercase tracking-wider">Rotar</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-10">
            <p class="text-sm text-gray-400">No hay asesores con vigencia registrada.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Auto-refresh note -->
<p class="text-center text-[10px] text-gray-400 pb-6">
    Actualización automática cada 60 seg &nbsp;·&nbsp; <a href="{{ route('coordinador.supervision') }}" class="text-sena-blue font-bold hover:underline">Actualizar ahora</a>
</p>

<!-- MODAL DE ALERTA CRÍTICA (ESPERA > 20 MIN) -->
<div id="criticoModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-6 bg-red-900/40 backdrop-blur-sm transition-all duration-500">
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(220,38,38,0.3)] border-4 border-red-500 overflow-hidden animate-in zoom-in duration-300">
        <div class="bg-red-500 p-8 flex flex-col items-center text-center text-white relative">
            <div class="absolute top-4 right-4">
                <button onclick="cerrarAlertModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition shadow-sm"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <i class="fa-solid fa-bell text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black uppercase tracking-tighter italic">Alerta de Espera Crítica</h3>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-80 mt-1">Supervisión en tiempo real — CU-04</p>
        </div>
        <div class="p-8 space-y-6">
            <div class="space-y-3">
                <p class="text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Turnos sin llamar por > 20 minutos:</p>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($turnosEspera20 as $t)
                    <div class="flex items-center justify-between p-4 bg-red-50 border border-red-100 rounded-2xl">
                        <div class="flex items-center space-x-3">
                            <span class="w-10 h-10 bg-red-500 text-white rounded-xl flex items-center justify-center font-black text-xs shadow-sm">{{ $t->tur_numero }}</span>
                            <div>
                                <p class="text-sm font-black text-gray-800">{{ $t->solicitante->persona->pers_nombres ?? 'Ciudadano' }}</p>
                                <p class="text-[9px] font-bold text-red-500 uppercase tracking-widest">{{ $t->tur_perfil }} · {{ $t->tur_servicio }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-black text-red-600 bg-white border border-red-100 px-3 py-1 rounded-full">{{ $t->minutos_espera }}m</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col space-y-3">
                <button onclick="cerrarAlertModal()" class="w-full py-5 bg-gray-900 text-white font-black rounded-2xl shadow-xl hover:bg-black transition-all active:scale-95 uppercase tracking-widest text-xs">Entendido, atender ahora</button>
                <div class="flex items-center justify-center space-x-2 text-[9px] font-bold text-gray-300 uppercase tracking-widest">
                    <i class="fa-solid fa-clock"></i>
                    <span>Siguiente verificación en 60 segundos</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    
    function playAlertSound() {
        const now = audioCtx.currentTime;
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        
        osc.type = 'triangle';
        osc.frequency.setValueAtTime(440, now);
        osc.frequency.exponentialRampToValueAtTime(880, now + 0.1);
        osc.frequency.exponentialRampToValueAtTime(440, now + 0.2);
        
        gain.gain.setValueAtTime(0.2, now);
        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.8);
        
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        
        osc.start(now);
        osc.stop(now + 0.8);
    }

    function toggleAlertModal(show) {
        const modal = document.getElementById('criticoModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            try { playAlertSound(); } catch(e) { console.log("Audio interaction required"); }
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function cerrarAlertModal() {
        toggleAlertModal(false);
    }

    function actualizarReloj() {
        const ahora = new Date();
        document.getElementById('reloj').textContent =
            ahora.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }

    window.onload = () => {
        actualizarReloj();
        setInterval(actualizarReloj, 1000);
        
        // Disparar alerta si hay turnos críticos (>0)
        @if($turnosEspera20->count() > 0)
            setTimeout(() => toggleAlertModal(true), 500);
        @endif

        // Auto-refresh cada 60s
        setTimeout(() => location.reload(), 60000);
    };
</script>
@endsection
