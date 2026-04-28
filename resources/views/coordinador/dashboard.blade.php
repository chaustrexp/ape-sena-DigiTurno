@extends('layouts.coordinador')

@section('title', 'SENA APE - Dashboard Coordinador')

@section('content')
<!-- KPIs Row -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- KPI 1: Tiempo Medio -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Tiempo Medio Espera</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">{{ $tiempoMedio }}m</h3>
            <p class="text-[10px] font-bold text-emerald-500 mt-1">Eficiente <span class="text-gray-400 font-medium">promedio</span></p>
        </div>
    </div>

    <!-- KPI 2: Atendiendo -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-sena-blue/10 text-sena-blue rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-user-headset"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">En Atención</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">{{ sprintf('%02d', $enAtencion) }}</h3>
            <p class="text-[10px] font-bold text-sena-blue mt-1">Activos <span class="text-gray-400 font-medium">ahora</span></p>
        </div>
    </div>

    <!-- KPI 3: Finalizados -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-check-double"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Finalizados</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">{{ $finalizados }}</h3>
            <p class="text-[10px] font-bold text-emerald-500 mt-1">Hoy <span class="text-gray-400 font-medium">completados</span></p>
        </div>
    </div>

    <!-- KPI 4: Ausentes -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-user-slash"></i>
        </div>
        <div class="text-right">
            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">Ausentes</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">{{ $ausentes }}</h3>
            <p class="text-[10px] font-bold text-rose-500 mt-1">Hoy <span class="text-gray-400 font-medium">llamados</span></p>
        </div>
    </div>
</div>

<!-- Queue Waiting Status Row (New Real-time Section) -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Waiting General -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">En Espera: General</span>
            <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center text-xs">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <h3 id="count-general" class="text-4xl font-black text-slate-800 leading-none">{{ $enEspera }}</h3>
            <span class="text-[10px] font-bold text-slate-300 uppercase">Ciudadanos</span>
        </div>
    </div>

    <!-- Waiting Prioritario -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[10px] font-black text-sena-orange uppercase tracking-widest">En Espera: Prioritario</span>
            <div class="w-8 h-8 bg-orange-50 text-sena-orange rounded-lg flex items-center justify-center text-xs">
                <i class="fa-solid fa-person-rays"></i>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <h3 id="count-prioritario" class="text-4xl font-black text-slate-800 leading-none">0</h3>
            <span class="text-[10px] font-bold text-slate-300 uppercase">Prioritarios</span>
        </div>
    </div>

    <!-- Waiting Victimas -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col border-l-4 border-l-rose-500">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">En Espera: Víctimas</span>
            <div class="w-8 h-8 bg-rose-50 text-rose-500 rounded-lg flex items-center justify-center text-xs">
                <i class="fa-solid fa-handshake-angle"></i>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <h3 id="count-victimas" class="text-4xl font-black text-slate-800 leading-none">0</h3>
            <span class="text-[10px] font-bold text-slate-300 uppercase">Población</span>
        </div>
    </div>
</div>

<!-- Main Layout Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
    
    <!-- Left Column (Grid spans 2/3) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Module Monitor Container -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-chart-simple text-sena-blue text-lg"></i>
                    <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Monitor de Módulos (Torre de Control)</h2>
                </div>
                <div class="bg-sena-blue/10 text-sena-blue px-3 py-1 rounded-full text-[10px] font-black flex items-center tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-sena-blue mr-2 animate-pulse"></span> {{ $enAtencion }} Atendiendo
                </div>
            </div>

            <!-- Modules Grid -->
            <div class="grid grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($asesoresStatus as $ase)
                @php
                    $estado = strtoupper($ase['estado']);
                    $color = $estado == 'ATENDIENDO' ? '#10069F' : ($estado == 'DESCANSO' ? '#FF671F' : '#6b7280');
                    $timeLabel = $estado == 'ATENDIENDO' ? 'Sesión Actual' : ($estado == 'DESCANSO' ? 'Tiempo Descanso' : 'Tiempo Inactivo');
                    
                    // Tiempo transcurrido si está atendiendo
                    $timeText = '00:00 min';
                    if ($ase['atencion']) {
                        $diff = $ase['atencion']->atnc_hora_inicio->diff(now());
                        $timeText = $diff->format('%I:%S') . ' min';
                    } else if ($estado == 'DESCANSO') {
                        $timeText = '15:00 min'; // Mock para descanso
                    }

                    $icon = $estado == 'ATENDIENDO' ? 'fa-message' : ($estado == 'DESCANSO' ? 'fa-mug-hot' : 'fa-stopwatch');
                @endphp
                <div data-search="{{ explode(' ', $ase['nombre'])[0] }} modulo {{ sprintf('%02d', $ase['modulo']) }} {{ $estado }}" class="searchable-item border border-gray-100 rounded-2xl p-4 flex flex-col justify-between hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 min-h-[120px] bg-white group hover:-translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden shrink-0 border border-gray-100 group-hover:border-sena-100">
                                <img src="{{ asset($ase['ase_foto'] ?? 'images/foto de perfil.jpg') }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-gray-900 leading-none">{{ explode(' ', $ase['nombre'])[0] }}</h4>
                                <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase">Módulo {{ sprintf('%02d', $ase['modulo']) }}</p>
                            </div>
                        </div>
                        <span class="text-white text-[8px] font-black px-2 py-0.5 rounded shadow-sm tracking-widest" style="background-color: {{ $color }}">{{ $estado }}</span>
                    </div>
                    <div class="flex justify-between items-end mt-auto">
                        <div>
                            <p class="text-[9px] text-gray-400 font-black mb-0.5 tracking-wide uppercase">{{ $timeLabel }}</p>
                            <p class="text-xs font-black" style="color: {{ $color }}">{{ $timeText }}</p>
                        </div>
                        <i class="fa-solid {{ $icon }} text-gray-100 text-lg group-hover:text-sena-blue/10 group-hover:scale-110 transition-all"></i>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Geographic Map Container -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100">
            <div class="flex items-center space-x-3 mb-6">
                <i class="fa-solid fa-map-location-dot text-sena-blue text-lg"></i>
                <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Distribución Geográfica de Sede</h2>
            </div>
            <div class="relative h-64 border-2 border-dashed border-gray-100 rounded-3xl flex items-center justify-center bg-gray-50/50 overflow-hidden">
                <!-- Abstract Map Graphic -->
                <div class="relative w-full h-full flex items-center justify-center opacity-10">
                   <i class="fa-solid fa-map text-[200px]"></i>
                </div>

                <!-- Pins Dynamically Mapped to Advisors -->
                @foreach($asesoresStatus as $index => $ase)
                @php
                    // Distribución visual estática para el layout del mapa abstracto
                    $positions = [
                        ['left' => '20%', 'top' => '35%'],
                        ['left' => '45%', 'top' => '60%'],
                        ['left' => '68%', 'bottom' => '25%'],
                        ['left' => '75%', 'top' => '35%'],
                    ];
                    $pos = $positions[$index % count($positions)];
                    
                    $colorClass = 'bg-gray-400';
                    $bgClass = 'bg-gray-100';
                    if(strtoupper($ase['estado']) == 'ATENDIENDO') {
                        $colorClass = 'bg-sena-blue';
                        $bgClass = 'bg-sena-blue/10';
                    } else if (strtoupper($ase['estado']) == 'DESCANSO') {
                        $colorClass = 'bg-sena-orange';
                        $bgClass = 'bg-sena-orange/10';
                    }
                @endphp
                <div class="absolute flex flex-col items-center justify-center group cursor-pointer" style="left: {{ $pos['left'] ?? 'auto' }}; top: {{ $pos['top'] ?? 'auto' }}; bottom: {{ $pos['bottom'] ?? 'auto' }}; z-index: 10;">
                    <div class="w-10 h-10 {{ $bgClass }} rounded-full flex items-center justify-center shadow-md border-2 border-white group-hover:scale-110 transition relative">
                        @if(strtoupper($ase['estado']) == 'ATENDIENDO')
                            <div class="absolute inset-0 {{ $colorClass }} rounded-full animate-ping opacity-20"></div>
                        @endif
                        <div class="w-3 h-3 {{ $colorClass }} rounded-full"></div>
                    </div>
                    <span class="text-[9px] font-black text-gray-700 mt-2 bg-white px-2 py-1 rounded shadow-sm border border-gray-100">Mod {{ sprintf('%02d', $ase['modulo']) }}</span>
                </div>
                @endforeach

                <!-- Legend -->
                <div class="absolute bottom-4 right-4 bg-white p-4 rounded-2xl shadow-xl border border-gray-50 flex flex-col space-y-2 z-10 min-w-[120px]">
                    <span class="text-[9px] font-black text-gray-900 border-b border-gray-50 pb-2 uppercase tracking-widest">Leyenda</span>
                    <div class="flex items-center space-x-2"><span class="w-2 h-2 rounded-full bg-sena-blue"></span><span class="text-[9px] font-bold text-gray-600">Activos</span></div>
                    <div class="flex items-center space-x-2"><span class="w-2 h-2 rounded-full bg-sena-orange"></span><span class="text-[9px] font-bold text-gray-600">Alerta</span></div>
                    <div class="flex items-center space-x-2"><span class="w-2 h-2 rounded-full bg-gray-300"></span><span class="text-[9px] font-bold text-gray-600">Mantenimiento</span></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column (Grid spans 1/3) -->
    <div class="lg:col-span-1 space-y-8 flex flex-col">
        
        <!-- Flow Per Hour Char Container -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 h-64">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">FLUJO POR HORA</h3>
                <i class="fa-solid fa-chart-column text-gray-200"></i>
            </div>
            <div class="h-40">
                <canvas id="flowChart"></canvas>
            </div>
        </div>

        <!-- Document Types Container -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 h-64">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">TIPOS DE DOCUMENTO</h3>
                <i class="fa-solid fa-chart-pie text-gray-200"></i>
            </div>
            
            <div class="flex items-center h-40">
                <div class="w-1/2 h-full relative">
                    <canvas id="docChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-black text-gray-900 leading-none">
                            @php
                                $total = array_sum($docData) ?: 1;
                                $ccCount = $docData['CC'] ?? 0;
                                echo round(($ccCount / $total) * 100) . '%';
                            @endphp
                        </span>
                        <span class="text-[8px] font-bold text-gray-400 mt-1 uppercase tracking-widest">CC</span>
                    </div>
                </div>
                <div class="w-1/2 pl-6 flex flex-col space-y-4">
                    @foreach($docData as $type => $count)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full" style="background-color: {{ $type == 'CC' ? '#10069F' : ($type == 'NIT' ? '#3b82f6' : '#d1d5db') }}"></div>
                            <span class="text-[10px] font-bold text-gray-600">{{ $type }}</span>
                        </div>
                        <span class="text-[10px] font-black text-gray-900">{{ $count }}</span>
                    </div>
                    @endforeach
                    @if(empty($docData))
                    <p class="text-[10px] text-gray-400 font-bold italic">Sin datos hoy</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alerts Container -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-[0_2px_10px_-3px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col flex-1 min-h-[300px]">
            <div class="flex justify-between items-start mb-6">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">ALERTAS RECIENTES</h3>
                @if(count($alertas) > 0)
                <span class="bg-red-500 text-white w-5 h-5 rounded-lg flex items-center justify-center text-[10px] font-black shadow-sm mr-2 animate-bounce">{{ count($alertas) }}</span>
                @endif
            </div>

            <div class="space-y-4 flex-1 mb-6 overflow-y-auto pr-2">
                @forelse($alertas as $alerta)
                @php
                    $isCritica = isset($alerta['tipo']) && $alerta['tipo'] == 'critica';
                    $bgClass = $isCritica ? 'bg-red-50' : 'bg-blue-50';
                    $borderClass = $isCritica ? 'border-red-100' : 'border-blue-100';
                    $textClass = $isCritica ? 'text-red-500' : 'text-blue-500';
                    $hoverBorderClass = $isCritica ? 'hover:border-red-100' : 'hover:border-blue-100';
                    $hoverBgClass = $isCritica ? 'hover:bg-red-50/30' : 'hover:bg-blue-50/30';
                    $iconClass = $isCritica ? 'fa-clock-rotate-left' : 'fa-circle-info';
                @endphp
                <div class="flex items-start space-x-3 group cursor-pointer {{ $hoverBgClass }} transition p-2 -ml-2 rounded-2xl border border-transparent {{ $hoverBorderClass }}">
                    <div class="w-9 h-9 rounded-xl {{ $bgClass }} flex justify-center items-center shrink-0 border {{ $borderClass }} {{ $textClass }}">
                        <i class="fa-solid {{ $iconClass }} text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[11px] font-black text-gray-900 mb-0.5 uppercase tracking-wide">{{ $alerta['msg'] }}</h4>
                        <!--<p class="text-[10px] font-medium text-gray-500 mb-1 leading-tight">Detalle disponible próximamente.</p>-->
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-1">{{ $alerta['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class="fa-solid fa-bell-slash text-gray-200 text-3xl mb-3"></i>
                    <p class="text-[10px] font-black uppercase text-gray-400">Sin Alertas Recientes</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-auto border-t border-gray-50 pt-5 text-center">
                <a href="#" onclick="document.getElementById('notificationsModal').classList.remove('hidden'); return false;" class="text-[10px] font-black text-sena-blue hover:text-sena-blue/80 hover:underline uppercase tracking-widest">Ver Todas las Notificaciones</a>
            </div>
        </div>

        <!-- Footer version info -->
        <div class="flex flex-col items-end pt-2 opacity-50 pr-4 mt-auto">
            <div class="flex items-center space-x-2 mb-1">
                <div class="w-5 h-4 bg-gray-400 rounded-sm"></div>
                <span class="text-[9px] font-black text-gray-700 tracking-wider">SENA APE 2026</span>
            </div>
            <span class="text-[8px] font-bold text-gray-500 text-right">Sistema de Gestión de Turnos<br>v4.9.0-estable</span>
        </div>

    </div>
</div>

<!-- Modal Historial Notificaciones -->
<div id="notificationsModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm transition-all">
    <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-8 shadow-2xl relative flex flex-col max-h-[80vh]">
        <button onclick="document.getElementById('notificationsModal').classList.add('hidden')" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-500 hover:text-rose-500 rounded-full transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <h3 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-wider">Historial de Notificaciones</h3>
        
        <div class="space-y-4 overflow-y-auto pr-2 flex-1">
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fa-solid fa-bell-slash text-gray-300 text-2xl"></i>
                </div>
                <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-1">El historial está vacío</h4>
                <p class="text-[10px] text-gray-400 font-medium">No hay notificaciones antiguas guardadas en el sistema.</p>
            </div>
            
            <!-- Alert temporal -->
            <div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-start space-x-4">
                <i class="fa-solid fa-check-circle text-emerald-500 mt-1"></i>
                <div>
                    <p class="text-[11px] font-black text-emerald-900 leading-tight">Sistema en Tiempo Real Activo</p>
                    <p class="text-[10px] text-emerald-700 mt-1 font-medium leading-relaxed">Las alertas que ves en el Dashboard ahora son calculadas en vivo inspeccionando los turnos en la base de datos.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js Default styling
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9ca3af';

    // FLOW PER HOUR (Bar Chart)
    const flowCtx = document.getElementById('flowChart').getContext('2d');
    new Chart(flowCtx, {
        type: 'bar',
        data: {
            labels: @json($flowLabels),
            datasets: [{
                data: @json($flowValues), 
                backgroundColor: '#10069F',
                borderRadius: 4,
                borderSkipped: false,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { size: 9 }, maxRotation: 0, padding: 5, autoSkip: false }
                },
                y: { display: false, beginAtZero: true }
            }
        }
    });

    // DOCUMENT TYPES (Doughnut Chart)
    const docCtx = document.getElementById('docChart').getContext('2d');
    new Chart(docCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($docData)),
            datasets: [{
                data: @json(array_values($docData)),
                backgroundColor: ['#10069F', '#3b82f6', '#94a3b8', '#cbd5e1', '#f1f5f9'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '82%', 
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            layout: { padding: 5 }
        }
    });

    // --- LÓGICA DE MONITOREO AUTOMÁTICO (POLLING) ---
    async function updateDashboardStats() {
        try {
            console.log('Actualizando estadísticas del dashboard...');
            const response = await fetch("{{ route('coordinador.api.stats') }}");
            const result = await response.json();

            if (result.success) {
                // Actualizar los IDs del DOM con animación suave
                updateValueWithAnimation('count-general', result.data.General);
                updateValueWithAnimation('count-prioritario', result.data.Prioritario);
                updateValueWithAnimation('count-victimas', result.data.Victimas);
                
                console.log(`Última actualización: ${result.timestamp}`);
            }
        } catch (error) {
            console.error('Error al sincronizar estadísticas:', error);
        }
    }

    function updateValueWithAnimation(id, newValue) {
        const el = document.getElementById(id);
        if (!el) return;
        
        const currentValue = parseInt(el.innerText);
        if (currentValue !== newValue) {
            el.classList.add('animate-pulse', 'text-sena-blue');
            setTimeout(() => {
                el.innerText = newValue;
                el.classList.remove('animate-pulse', 'text-sena-blue');
            }, 500);
        }
    }

    // Ejecutar inmediatamente al cargar y luego cada 5 minutos (300,000 ms)
    updateDashboardStats();
    setInterval(updateDashboardStats, 300000); 

</script>
@endsection
