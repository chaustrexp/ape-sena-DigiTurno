@extends('layouts.coordinador')

@section('title', 'Reporte Global de Sede - SENA APE')

@section('content')
<div class="mb-10 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-gray-900 leading-tight">Analítica Global de Servicio</h2>
        <p class="text-gray-500 text-sm font-medium mt-1">Resumen estadístico del rendimiento general de la sede.</p>
    </div>
    <div class="flex items-center space-x-4">
        <button onclick="downloadPDF()" class="bg-sena-blue text-white font-black px-8 py-3 rounded-2xl text-[10px] uppercase tracking-widest shadow-xl shadow-sena-blue/20 hover:scale-105 active:scale-95 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-file-pdf"></i>
            <span>Descargar PDF Pro</span>
        </button>
        <select class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 text-xs font-black text-gray-700 uppercase tracking-widest outline-none">
            <option>Últimos 7 días</option>
            <option>Este Mes</option>
            <option>Año Actual</option>
        </select>
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
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">T. Promedio Sede</p>
            <h4 class="text-4xl font-black text-gray-900">{{ $metas['atencion_actual'] }} <span class="text-sm">min</span></h4>
            <div class="mt-4 flex items-center text-amber-500 space-x-1">
                <i class="fa-solid fa-caret-down text-xs"></i>
                <span class="text-[10px] font-black tracking-widest">-1.1% mejora</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Calificación Global</p>
            <h4 class="text-4xl font-black text-gray-900">{{ $metas['calificacion'] }}</h4>
            <div class="mt-4 flex items-center text-emerald-500 space-x-1">
                <i class="fa-solid fa-star text-xs"></i>
                <span class="text-[10px] font-black tracking-widest">Nivel Excelente</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Meta Sede Hoy</p>
            <h4 class="text-4xl font-black text-gray-900">{{ round(($metas['diaria_actual']/$metas['diaria_meta'])*100) }}%</h4>
            <div class="mt-4 flex items-center text-blue-500 space-x-1">
                <span class="text-[10px] font-black tracking-widest">{{ $metas['diaria_actual'] }} / {{ $metas['diaria_meta'] }} tickets</span>
            </div>
        </div>
    </div>

    <!-- Fila 2: Gráficos de Distribución y Flujo -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 text-center">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Distribución por Perfil</h4>
            <div class="h-64 flex items-center justify-center">
                <canvas id="typeDistributionChart"></canvas>
            </div>
        </div>
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 text-center">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Distribución por Estado</h4>
            <div class="h-64 flex items-center justify-center">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Flujo Semanal de Sede</h4>
            <div class="h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Fila 3: Objetivos vs Real y Top Trámites -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Comparativa Global (Tiempo)</h4>
            <div class="space-y-8">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">Tiempo General (Meta: 12 min)</span>
                        <span class="text-sm font-black text-emerald-500">{{ $metas['atencion_actual'] }} min</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-sena-blue h-full rounded-full" style="width: {{ min(100, (12/max(1, $metas['atencion_actual']))*100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">Capacidad Diaria (Meta: 200 turns)</span>
                        <span class="text-sm font-black text-blue-500">{{ $metas['diaria_actual'] }} turns</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ ($metas['diaria_actual']/$metas['diaria_meta'])*100 }}%"></div>
                    </div>
                </div>
                <div class="p-5 bg-sena-blue/10 rounded-2xl border border-sena-blue/20">
                    <p class="text-xs font-bold text-sena-blue leading-relaxed italic">"La sede está procesando los turnos un 15% más rápido del promedio regional. Excelente gestión de colas."</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Top Trámites Sede</h4>
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
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Mapa de Calor Global</h4>
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
                                 style="background-color: rgba(16, 6, 159, {{ $rand/100 }});"
                                 title="Aforo: {{ $rand }}%">
                            </div>
                        @endfor
                    </div>
                </div>
                @endfor
            </div>
            <div class="mt-6 flex items-center justify-end space-x-4">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Aforo Bajo</span>
                <div class="flex space-x-1">
                    <div class="w-4 h-4 rounded bg-sena-blue/10"></div>
                    <div class="w-4 h-4 rounded bg-sena-blue/30"></div>
                    <div class="w-4 h-4 rounded bg-sena-blue/60"></div>
                    <div class="w-4 h-4 rounded bg-sena-blue"></div>
                </div>
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Aforo Alto</span>
            </div>
        </div>
        <div class="xl:col-span-1 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 flex flex-col">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 text-center">Calificaciones Globales</h4>
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
            <h3 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-wider">Historial Detallado de Calificaciones</h3>
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
                            <div class="w-8 h-8 rounded-full bg-sena-500 flex items-center justify-center text-white text-[10px] font-black">L</div>
                            <span class="text-xs font-black text-gray-900">Luis M.</span>
                        </div>
                        <div class="flex text-amber-400 text-[10px]">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">"Todo muy bien explicado, gracias por su paciencia."</p>
                    <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-2">Ayer</p>
                </div>
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-sena-500 flex items-center justify-center text-white text-[10px] font-black">C</div>
                            <span class="text-xs font-black text-gray-900">Camila T.</span>
                        </div>
                        <div class="flex text-amber-400 text-[10px]">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">"Puntuales y muy eficientes en el registro."</p>
                    <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-2">Ayer</p>
                </div>
            </div>
            <!-- Alert temporal -->
            <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-start space-x-4">
                <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                <div>
                    <p class="text-[11px] font-black text-blue-900 leading-tight">Aviso de Fase de Pruebas</p>
                    <p class="text-[10px] text-blue-700 mt-1 font-medium leading-relaxed">Estos son datos de ejemplo visuales. Aún debemos implementar la base de datos real para habilitar envíos de encuestas por parte de los ciudadanos.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila 5: Registro Detallado de Sede -->
    <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 mt-8">
        <div class="flex items-center justify-between mb-8">
            <h4 class="text-sm font-black text-gray-900 tracking-wide uppercase">Registro Exhaustivo de Turnos</h4>
            <span class="px-4 py-1.5 bg-gray-50 text-[10px] font-black text-gray-400 rounded-full border border-gray-100">Vista global de la sede</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Turno</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Solicitante</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Tipo</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Fecha / Hora</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Asesor Asignado</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($turnos as $t)
                    <tr class="group hover:bg-gray-50/50 transition-colors">
                        <td class="py-5">
                            <span class="text-xs font-black text-sena-blue py-1.5 px-3 bg-sena-blue/10 rounded-lg">#{{ $t->tur_numero }}</span>
                        </td>
                        <td class="py-5">
                            <div class="text-xs font-bold text-gray-900">{{ $t->solicitante->persona->pers_nombres ?? 'Indeterminado' }} {{ $t->solicitante->persona->pers_apellidos ?? '' }}</div>
                            <div class="text-[10px] text-gray-400 font-bold mt-0.5">ID: {{ $t->solicitante->persona->pers_doc ?? 'N/A' }}</div>
                        </td>
                        <td class="py-5">
                            <span class="text-[10px] font-black uppercase tracking-widest py-1 px-3 rounded-full {{ $t->tur_tipo == 'General' ? 'text-emerald-600 bg-emerald-50' : (in_array($t->tur_tipo, ['Prioritario', 'Prioritaria']) ? 'text-amber-600 bg-amber-50' : 'text-blue-600 bg-blue-50') }}">
                                {{ $t->tur_tipo }}
                            </span>
                        </td>
                        <td class="py-5 text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($t->tur_hora_fecha)->format('h:i A') }}</td>
                        <td class="py-5">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-user-tie text-gray-300 text-[10px]"></i>
                                <span class="text-[11px] font-bold text-gray-700">
                                    {{ $t->atencion && $t->atencion->asesor ? ($t->atencion->asesor->persona->pers_nombres ?? 'Módulo '.$t->atencion->ASESOR_ase_id) : 'Pendiente' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-5 text-center">
                            @if($t->tur_estado == 'Finalizado')
                                <div class="inline-flex items-center space-x-2 text-emerald-500 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-full">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">FINALIZADO</span>
                                </div>
                            @elseif($t->tur_estado == 'Atendiendo')
                                <div class="inline-flex items-center space-x-2 text-blue-500 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-full">
                                    <i class="fa-solid fa-user-clock text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">ATENDIENDO</span>
                                </div>
                            @elseif($t->tur_estado == 'Ausente')
                                <div class="inline-flex items-center space-x-2 text-rose-500 bg-rose-50 border border-rose-100 px-3 py-1.5 rounded-full">
                                    <i class="fa-solid fa-user-slash text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">AUSENTE</span>
                                </div>
                            @else
                                <div class="inline-flex items-center space-x-2 text-amber-500 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-full">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">EN ESPERA</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-5 text-center text-xs text-gray-400 font-bold uppercase tracking-widest">No hay turnos para mostrar hoy.</td></tr>
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
            filename:     'Reporte_Global_APE.pdf',
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
            labels: ['General', 'Prioritario', 'Víctimas', 'Empresarios'],
            datasets: [{
                data: [{{ $distribucionTipos['General'] }}, {{ $distribucionTipos['Prioritario'] }}, {{ $distribucionTipos['Víctimas'] }}, {{ $distribucionTipos['Empresarios'] }}],
                backgroundColor: ['#10069F', '#FF671F', '#FFB500', '#39A900'],
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

    // Gráfico de Distribución por Estado
    const ctxStatus = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Espera', 'Atendiendo', 'Finalizado', 'Ausente'],
            datasets: [{
                data: [{{ $distribucionEstados['Espera'] }}, {{ $distribucionEstados['Atendiendo'] }}, {{ $distribucionEstados['Finalizado'] }}, {{ $distribucionEstados['Ausente'] }}],
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
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
                label: 'Atenciones Sedes',
                data: [132, 145, 138, 152, 148, 130],
                borderColor: '#10069F',
                backgroundColor: 'rgba(16, 6, 159, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2,
                pointBorderColor: '#10069F'
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
