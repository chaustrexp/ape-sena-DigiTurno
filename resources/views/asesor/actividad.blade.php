@extends('layouts.asesor')

@section('title', 'Registro de Actividad - APE Advisor')

@section('content')
<div class="mb-10 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-black text-gray-900 leading-tight">Registro de Actividad</h2>
        <p class="text-gray-500 text-sm font-medium mt-1">Bitácora completa y detallada de tus atenciones realizadas.</p>
    </div>
    <div class="flex items-center space-x-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 italic text-[10px] font-black tracking-widest cursor-pointer group hover:bg-emerald-50 hover:border-emerald-200 transition-all" onclick="toggleRealTime()" id="realtimeToggle">
        <i class="fa-solid fa-clock-rotate-left text-gray-400 group-hover:text-emerald-500 transition-colors" id="rtIcon"></i>
        <span class="text-gray-400 group-hover:text-emerald-600 transition-colors" id="rtText">Historial en Tiempo Real: Inactivo</span>
    </div>
</div>

<div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
        <div class="flex items-center space-x-4">
            <div class="px-4 py-2 bg-sena-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest">Hoy</div>
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('d M, Y') }}</div>
        </div>
        <form id="filterForm" action="{{ route('asesor.actividad') }}" method="GET" class="flex items-center space-x-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar D.I. o Turno..." class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-sena-500 transition-all w-48 placeholder-gray-300">
            <select name="estado" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-700 outline-none cursor-pointer focus:ring-2 focus:ring-sena-500">
                <option value="">Todos los Estados</option>
                <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Atendido</option>
                <option value="proceso" {{ request('estado') == 'proceso' ? 'selected' : '' }}>En Proceso</option>
            </select>
            <button type="submit" class="h-10 px-4 flex items-center justify-center bg-sena-50 text-sena-600 font-bold border border-sena-100 rounded-xl hover:bg-sena-500 hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-filter text-xs mr-2"></i>
                <span class="text-[10px] uppercase tracking-widest">Filtrar</span>
            </button>
            <a href="{{ route('asesor.actividad', array_merge(request()->all(), ['export' => 'excel'])) }}" class="h-10 px-5 flex items-center justify-center bg-emerald-600 text-white font-bold border border-emerald-700 rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 group">
                <i class="fa-solid fa-file-excel text-xs mr-2 group-hover:-translate-y-0.5 transition-transform"></i>
                <span class="text-[10px] uppercase tracking-widest">Excel</span>
            </a>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                    <th class="px-10 py-6">Turno / ID</th>
                    <th class="px-10 py-6">Información del Ciudadano</th>
                    <th class="px-10 py-6">Intervalo de Atención</th>
                    <th class="px-10 py-6">Duración Estimada</th>
                    <th class="px-10 py-6 text-center">Estado del Proceso</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($atenciones as $atn)
                <tr class="hover:bg-gray-50/50 transition-colors group cursor-default">
                    <td class="px-10 py-8">
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-gray-900 group-hover:text-sena-500 transition-colors">{{ $atn->turno->tur_numero }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mt-1">Ticket #{{ str_pad($atn->atnc_id ?? rand(100, 999), 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>
                    <td class="px-10 py-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 font-black text-xs group-hover:bg-sena-50 group-hover:text-sena-500 transition-all uppercase">
                                {{ substr($atn->turno->solicitante->persona->pers_nombres ?? 'U', 0, 1) }}{{ substr($atn->turno->solicitante->persona->pers_apellidos ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-black text-gray-900 leading-tight">{{ $atn->turno->solicitante->persona->pers_nombres ?? 'Usuario' }} {{ $atn->turno->solicitante->persona->pers_apellidos ?? 'Anónimo' }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">D.I. {{ $atn->turno->solicitante->persona->pers_doc ?? '00000000' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-8">
                        <div class="flex flex-col space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <span class="text-xs font-bold text-gray-600 italic">Entrada: {{ is_string($atn->atnc_hora_inicio) ? date('h:i A', strtotime($atn->atnc_hora_inicio)) : $atn->atnc_hora_inicio->format('h:i A') }}</span>
                            </div>
                            @if($atn->atnc_hora_fin)
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                <span class="text-xs font-bold text-gray-600 italic">Salida: {{ is_string($atn->atnc_hora_fin) ? date('h:i A', strtotime($atn->atnc_hora_fin)) : $atn->atnc_hora_fin->format('h:i A') }}</span>
                            </div>
                            @else
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                                <span class="text-xs font-black text-blue-500 uppercase tracking-tighter">Atención Activa</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-10 py-8">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-gray-800">12 min 45s</span>
                            <div class="w-20 bg-gray-100 h-1 rounded-full mt-2 overflow-hidden">
                                <div class="bg-sena-500 h-full rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-8 text-center">
                        @if($atn->atnc_hora_fin)
                            <div class="inline-flex items-center space-x-2 bg-emerald-50 text-emerald-600 text-[10px] font-black px-5 py-2 rounded-xl border border-emerald-100 uppercase tracking-widest">
                                <i class="fa-solid fa-check-double"></i>
                                <span>Atendido</span>
                            </div>
                        @else
                            <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-600 text-[10px] font-black px-5 py-2 rounded-xl border border-blue-100 uppercase tracking-widest animate-pulse">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                <span>En Proceso</span>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex justify-center">
        @if($atenciones->hasMorePages())
        <button id="load-more-btn" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-sena-500 transition-all flex items-center space-x-2">
            <span>Cargar más registros</span>
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        @else
        <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">No hay más registros</span>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Configuración para Cargar Más Registros (Paginación AJAX)
    let currentPage = {{ $atenciones->currentPage() }};
    const lastPage = {{ $atenciones->lastPage() }};
    const loadBtn = document.getElementById('load-more-btn');

    if (loadBtn) {
        loadBtn.addEventListener('click', function() {
            if (currentPage >= lastPage) return;
            
            currentPage++;
            const url = new URL(window.location.href);
            url.searchParams.set('page', currentPage);
            
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i><span>Cargando...</span>';
            this.disabled = true;
            
            fetch(url)
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newRows = doc.querySelector('tbody').innerHTML;
                    
                    document.querySelector('tbody').insertAdjacentHTML('beforeend', newRows);
                    
                    if (currentPage >= lastPage) {
                        this.outerHTML = '<span class="text-[10px] font-black uppercase tracking-widest text-gray-300">No hay más registros</span>';
                    } else {
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    }
                });
        });
    }

    // Historial Tiempo Real
    let rtInterval = null;
    let isRealTime = false;

    function toggleRealTime() {
        isRealTime = !isRealTime;
        const toggle = document.getElementById('realtimeToggle');
        const icon = document.getElementById('rtIcon');
        const text = document.getElementById('rtText');

        if (isRealTime) {
            toggle.classList.add('bg-emerald-50', 'border-emerald-200');
            icon.classList.remove('fa-clock-rotate-left', 'text-gray-400');
            icon.classList.add('fa-spinner', 'fa-spin', 'text-emerald-500');
            text.classList.add('text-emerald-600');
            text.textContent = 'Actualizando en Vivo...';
            
            // Recargar cada 10 segundos preservando filtros
            rtInterval = setInterval(() => {
                const url = new URL(window.location.href);
                // Evitamos interferir si hay paginación manual
                fetch(url)
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTableBody = doc.querySelector('tbody').innerHTML;
                        document.querySelector('tbody').innerHTML = newTableBody;
                    });
            }, 5000);
        } else {
            toggle.classList.remove('bg-emerald-50', 'border-emerald-200');
            icon.classList.remove('fa-spinner', 'fa-spin', 'text-emerald-500');
            icon.classList.add('fa-clock-rotate-left', 'text-gray-400');
            text.classList.remove('text-emerald-600');
            text.textContent = 'Historial en Tiempo Real: Inactivo';
            clearInterval(rtInterval);
        }
    }
</script>
@endsection
