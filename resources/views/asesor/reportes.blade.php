@extends('layouts.asesor')

@section('title', 'Reportes de Desempeño - APE Advisor')

@section('content')
<div class="mb-10 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-gray-900 leading-tight">Analítica de Atención</h2>
        <p class="text-gray-500 text-sm font-medium mt-1">Resumen estadístico de tu productividad y calidad de servicio.</p>
    </div>
    <div class="flex items-center space-x-4">
        <button onclick="downloadPDF()" class="bg-sena-500 text-white font-black px-8 py-3 rounded-2xl text-[10px] uppercase tracking-widest shadow-xl shadow-sena-500/20 hover:scale-105 active:scale-95 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Descargar PDF Pro</span>
        </button>
        <form action="{{ route('asesor.reportes') }}" method="GET" id="filterForm">
            <select name="periodo" onchange="document.getElementById('filterForm').submit()" class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 text-xs font-black text-gray-700 uppercase tracking-widest outline-none cursor-pointer hover:bg-gray-50 transition-all">
                <option value="today" {{ ($periodo ?? '') == 'today' ? 'selected' : '' }}>Hoy</option>
                <option value="7d" {{ ($periodo ?? '') == '7d' ? 'selected' : '' }}>Últimos 7 días</option>
                <option value="month" {{ ($periodo ?? '') == 'month' ? 'selected' : '' }}>Este Mes</option>
                <option value="year" {{ ($periodo ?? '') == 'year' ? 'selected' : '' }}>Año Actual</option>
            </select>
        </form>
    </div>
</div>

<div id="report-content" class="space-y-8">
    <!-- Fila 1: KPIs Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Atendidos</p>
            <h4 class="text-4xl font-black text-gray-900">{{ $metas['diaria_actual'] }}</h4>
            <div class="mt-4 flex items-center text-emerald-500 space-x-1">
                <i class="fa-solid fa-caret-up text-xs"></i>
                <span class="text-[10px] font-black tracking-widest">+8.2% vs ayer</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">T. Promedio</p>
            <h4 class="text-4xl font-black text-gray-900">{{ $metas['atencion_actual'] }} <span class="text-sm">min</span></h4>
            <div class="mt-4 flex items-center text-amber-500 space-x-1">
                <i class="fa-solid fa-caret-down text-xs"></i>
                <span class="text-[10px] font-black tracking-widest">-1.1% mejora</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Calificación</p>
            <h4 class="text-4xl font-black text-gray-900">{{ $metas['calificacion'] }}</h4>
            <div class="mt-4 flex items-center text-emerald-500 space-x-1">
                <i class="fa-solid fa-star text-xs"></i>
                <span class="text-[10px] font-black tracking-widest">Nivel Excelente</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Meta Diaria</p>
            <h4 class="text-4xl font-black text-gray-900">{{ round(($metas['diaria_actual']/$metas['diaria_meta'])*100) }}%</h4>
            <div class="mt-4 flex items-center text-blue-500 space-x-1">
                <span class="text-[10px] font-black tracking-widest">{{ $metas['diaria_actual'] }} / {{ $metas['diaria_meta'] }} tickets</span>
            </div>
        </div>
    </div>

    <!-- Fila 2: Gráficos de Distribución y Flujo -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 text-center">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Distribución por Tipo</h4>
            <div class="h-64 flex items-center justify-center">
                <canvas id="typeDistributionChart"></canvas>
            </div>
        </div>
        <div class="xl:col-span-2 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Flujo Semanal de Ciudadanos</h4>
            <div class="h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Fila 3: Objetivos vs Real y Top Trámites -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Comparativa de Metas (Tiempo)</h4>
            <div class="space-y-8">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">Tiempo de Atención (Meta: 12 min)</span>
                        <span class="text-sm font-black text-emerald-500">{{ $metas['atencion_actual'] }} min</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(100, (12/max(1, $metas['atencion_actual']))*100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">Capacidad Diaria (Meta: 50 turns)</span>
                        <span class="text-sm font-black text-blue-500">{{ $metas['diaria_actual'] }} turns</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ ($metas['diaria_actual']/$metas['diaria_meta'])*100 }}%"></div>
                    </div>
                </div>
                <div class="p-5 bg-sena-50 rounded-2xl border border-sena-100">
                    <p class="text-xs font-bold text-sena-700 leading-relaxed italic">"Estás superando tu meta de tiempo en un 15%. Mantén este ritmo para mejorar tu KPI de volumen."</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Top Trámites Realizados</h4>
            <div class="space-y-4">
                @foreach($topTramites as $tramite)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full {{ $tramite['color'] }}"></div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $tramite['nombre'] }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-xs font-black text-gray-400">{{ $tramite['count'] }} tickets</span>
                        <div class="w-24 bg-gray-50 h-1.5 rounded-full overflow-hidden">
                            <div class="h-full {{ $tramite['color'] }}" style="width: {{ ($tramite['count']/85)*100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Fila 4: Mapa de Calor y Feedback -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Mapa de Calor de Productividad</h4>
            <div class="grid grid-cols-12 gap-2 text-center text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                <div>8am</div><div>9am</div><div>10am</div><div>11am</div><div>12pm</div><div>1pm</div><div>2pm</div><div>3pm</div><div>4pm</div><div>5pm</div><div>6pm</div><div>7pm</div>
            </div>
            <div class="grid grid-rows-6 gap-2">
                @for($l=0; $l<6; $l++)
                <div class="flex items-center space-x-2">
                    <span class="w-8 text-[9px] text-gray-400 font-black uppercase">{{ ['L','M','Mi','J','V','S'][$l] }}</span>
                    <div class="flex-1 grid grid-cols-12 gap-1.5 h-6">
                        @for($h=0; $h<12; $h++)
                            @php $rand = rand(10, 90); @endphp
                            <div class="group relative rounded-md transition-all hover:scale-110 cursor-pointer" 
                                 style="background-color: rgba(57, 169, 0, {{ $rand/100 }});"
                                 title="Prod: {{ $rand }}%">
                            </div>
                        @endfor
                    </div>
                </div>
                @endfor
            </div>
            <div class="mt-6 flex items-center justify-end space-x-4">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Baja</span>
                <div class="flex space-x-1">
                    <div class="w-4 h-4 rounded bg-sena-50"></div>
                    <div class="w-4 h-4 rounded bg-sena-200"></div>
                    <div class="w-4 h-4 rounded bg-sena-500"></div>
                    <div class="w-4 h-4 rounded bg-sena-700"></div>
                </div>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alta Especialización</span>
            </div>
        </div>
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 flex flex-col">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 text-center">Calificaciones Recientes</h4>
            <div class="flex-1 space-y-5">
                @foreach($feedback as $fb)
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-sena-200 transition-all cursor-default group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-sena-500 flex items-center justify-center text-white text-[10px] font-black">{{ substr($fb['user'], 0, 1) }}</div>
                            <span class="text-xs font-black text-gray-900">{{ $fb['user'] }}</span>
                        </div>
                        <div class="flex text-amber-400 text-[10px]">
                            @for($i=0; $i<$fb['stars']; $i++) <i class="fa-solid fa-star"></i> @endfor
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">"{{ $fb['comentario'] }}"</p>
                    <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-2">{{ $fb['time'] }}</p>
                </div>
                @endforeach
            </div>
            <button onclick="document.getElementById('commentsModal').classList.remove('hidden')" class="mt-6 w-full py-3 bg-gray-900 text-white rounded-2xl text-[9px] font-black uppercase tracking-widest hover:bg-black transition-all">Ver todos los comentarios</button>
        </div>
    </div>

    <!-- Modal Ver Todos Los Comentarios -->
    <div id="commentsModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm transition-all">
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] p-8 shadow-2xl relative">
            <button onclick="document.getElementById('commentsModal').classList.add('hidden')" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-500 hover:text-rose-500 rounded-full transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-wider">Mis Calificaciones</h3>
            <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-2">
                @foreach($feedback as $fb)
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-sena-500 flex items-center justify-center text-white text-[10px] font-black">{{ substr($fb['user'], 0, 1) }}</div>
                            <span class="text-xs font-black text-gray-900">{{ $fb['user'] }}</span>
                        </div>
                        <div class="flex text-amber-400 text-[10px]">
                            @for($i=0; $i<$fb['stars']; $i++) <i class="fa-solid fa-star"></i> @endfor
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">"{{ $fb['comentario'] }}"</p>
                    <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-2">{{ $fb['time'] }}</p>
                </div>
                @endforeach
                <!-- Comentarios extra simulados -->
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-sena-500 flex items-center justify-center text-white text-[10px] font-black">D</div>
                            <span class="text-xs font-black text-gray-900">Diego P.</span>
                        </div>
                        <div class="flex text-amber-400 text-[10px]">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">"El asesor resolvió muy rápido mi certificado, gran actitud."</p>
                    <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-2">Ayer</p>
                </div>
            </div>
            <!-- Alert temporal -->
            <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-start space-x-4">
                <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                <div>
                    <p class="text-[11px] font-black text-blue-900 leading-tight">Aviso de Fase de Pruebas</p>
                    <p class="text-[10px] text-blue-700 mt-1 font-medium leading-relaxed">Estos datos son visuales. Para guardar tus propios comentarios en vivo, el administrador debe habilitar la base de datos real de encuestas ciudadanas.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila 5: Registro Detallado (Tabla) -->
    <!-- Fila 5: Registro Detallado (Tabla) -->
    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 mt-8">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-sm font-black text-gray-900 tracking-wide uppercase">Registro Detallado de Tus Atenciones</h4>
            <span class="px-4 py-1.5 bg-gray-50 text-[10px] font-black text-gray-400 rounded-full border border-gray-100">Registro en tiempo real</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Turno ID</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Hora Inicio</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Categoría</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Duración</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($turnos as $atn)
                    @php
                        $duracion = $atn->atnc_hora_fin ? $atn->atnc_hora_inicio->diffInMinutes($atn->atnc_hora_fin) . ' min' : '0 min';
                        $status = $atn->atnc_hora_fin ? 'Completado' : 'En proceso';
                    @endphp
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="py-5">
                            <span class="text-xs font-black text-gray-900 py-1.5 px-3 bg-gray-100 rounded-lg">{{ $atn->turno->tur_numero ?? 'N/A' }}</span>
                        </td>
                        <td class="py-5 text-xs font-bold text-gray-600">{{ is_string($atn->atnc_hora_inicio) ? $atn->atnc_hora_inicio : $atn->atnc_hora_inicio->format('h:i A') }}</td>
                        <td class="py-5">
                            <span class="text-[10px] font-black uppercase tracking-widest py-1 px-3 rounded-full {{ $atn->atnc_tipo == 'General' ? 'text-emerald-600 bg-emerald-50' : (in_array($atn->atnc_tipo, ['Prioritario', 'Prioritaria']) ? 'text-amber-600 bg-amber-50' : 'text-blue-600 bg-blue-50') }}">
                                {{ $atn->atnc_tipo }}
                            </span>
                        </td>
                        <td class="py-5 text-xs font-black text-gray-900">{{ $duracion }}</td>
                        <td class="py-5">
                            <div class="flex items-center space-x-2 {{ $status == 'Completado' ? 'text-emerald-500' : 'text-amber-500' }} text-left">
                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">{{ $status }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-5 text-center text-xs text-gray-400 font-bold uppercase tracking-widest">No hay atenciones registradas hoy.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-6 border-t border-gray-50">
            {{ $turnos->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function downloadPDF() {
        const reportArea = document.getElementById('report-content');
        const btn = event.currentTarget || event.target.closest('button');
        const originalText = btn ? btn.innerHTML : '';
        
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin text-lg"></i><span>Generando...</span>';
            btn.disabled = true;
        }

        const mainEl = document.querySelector('main');
        if(mainEl) {
            mainEl.style.overflow = 'visible';
            mainEl.style.height = 'auto';
        }

        const opt = {
            margin:       [10, 5, 10, 5],
            filename:     'Reporte_Asesor_APE.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, scrollY: 0 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(reportArea).save().then(() => {
            if(mainEl) {
                mainEl.style.overflow = '';
                mainEl.style.height = '';
            }
            if (btn) {
                btn.innerHTML = originalText || '<i class="fa-solid fa-file-pdf text-lg"></i><span>Descargar PDF Pro</span>';
                btn.disabled = false;
            }
        }).catch(err => {
            console.error('Error generating PDF:', err);
            if(mainEl) {
                mainEl.style.overflow = '';
                mainEl.style.height = '';
            }
            if (btn) {
                btn.innerHTML = originalText || '<i class="fa-solid fa-file-pdf text-lg"></i><span>Descargar PDF Pro</span>';
                btn.disabled = false;
            }
        });
    }

    // Gráfico de Distribución (Pie)
    const ctxType = document.getElementById('typeDistributionChart').getContext('2d');
    new Chart(ctxType, {
        type: 'doughnut',
        data: {
            labels: ['General', 'Prioritario', 'Víctimas'],
            datasets: [{
                data: [{{ $distribucionTipos['General'] }}, {{ $distribucionTipos['Prioritario'] }}, {{ $distribucionTipos['Víctimas'] }}],
                backgroundColor: ['#39A900', '#F6AD55', '#3182CE'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9, weight: 'bold' } } } }
        }
    });

    // Gráfico de Flujo Semanal
    const ctxFlow = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctxFlow, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            datasets: [{
                label: 'Atenciones',
                data: [32, 45, 38, 52, 48, 30],
                borderColor: '#39A900',
                backgroundColor: 'rgba(57, 169, 0, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2,
                pointBorderColor: '#39A900'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
</script>
@endsection
