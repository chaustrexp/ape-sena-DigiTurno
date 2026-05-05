@extends('layouts.asesor')

@section('title', 'Trámites y Servicios - APE Advisor')

@section('content')
<div class="mb-10">
    <h2 class="text-3xl font-black text-gray-900 leading-tight">Catálogo de Servicios</h2>
    <p class="text-gray-500 text-sm font-medium mt-1">Gestión de trámites disponibles para la atención ciudadana.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    @foreach([
        ['title' => 'Inscripción Hoja de Vida', 'desc' => 'Registro y actualización de perfiles en la plataforma APE.', 'icon' => 'fa-file-signature', 'color' => 'emerald'],
        ['title' => 'Orientación Laboral', 'desc' => 'Asesoria personalizada para la búsqueda efectiva de empleo.', 'icon' => 'fa-user-tie', 'color' => 'blue'],
        ['title' => 'Postulación a Vacantes', 'desc' => 'Apoyo en el proceso de aplicación a ofertas de trabajo.', 'icon' => 'fa-briefcase', 'color' => 'indigo'],
        ['title' => 'Certificaciones', 'desc' => 'Generación de certificados de inscripción y participación.', 'icon' => 'fa-certificate', 'color' => 'amber'],
        ['title' => 'Talleres APE', 'desc' => 'Inscripción a talleres de habilidades blandas y técnicas.', 'icon' => 'fa-users-gear', 'color' => 'rose'],
    ] as $serv)
    <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-500 group relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-{{ $serv['color'] ?? 'emerald' }}-50 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative z-10">
            <div class="w-16 h-16 bg-{{ $serv['color'] ?? 'emerald' }}-50 text-{{ $serv['color'] ?? 'emerald' }}-500 rounded-2xl flex items-center justify-center text-2xl mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <i class="fa-solid {{ $serv['icon'] }}"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-3">{{ $serv['title'] }}</h3>
            <p class="text-sm font-medium text-gray-400 leading-relaxed mb-8">{{ $serv['desc'] }}</p>
            
            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                <span id="badge-{{ Str::slug($serv['title']) }}" class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors">Activo</span>
                <button onclick="openConfigModal('{{ $serv['title'] }}', '{{ $serv['desc'] }}', '{{ $serv['color'] }}', '{{ Str::slug($serv['title']) }}')" class="text-sena-500 font-black text-[10px] uppercase tracking-widest hover:translate-x-1 transition-transform flex items-center">
                    Configurar <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal de Configuración -->
<div id="configModal" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeConfigModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 relative z-10 overflow-hidden" id="configModalContent">
            <!-- Header Modal -->
            <div id="modalHeader" class="p-8 border-b border-gray-50 bg-emerald-500 text-white relative">
                <button onclick="closeConfigModal()" class="absolute top-8 right-8 text-white/70 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-xl mb-4 shadow-inner">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h3 id="modalTitle" class="text-2xl font-black leading-tight">Configuración</h3>
                <p id="modalDesc" class="text-sm font-medium text-white/80 mt-1">Ajusta los parámetros de este trámite.</p>
            </div>
            
            <!-- Body Modal -->
            <div class="p-8 space-y-8 bg-gray-50/30">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Estado del Servicio</label>
                    <label class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 cursor-pointer shadow-sm hover:border-emerald-200 transition-colors">
                        <span class="text-sm font-bold text-gray-700">Trámite Activo y Disponible</span>
                        <input type="checkbox" id="modalStatus" checked class="w-12 h-6 bg-gray-200 rounded-full appearance-none checked:bg-emerald-500 transition-all relative after:content-[''] after:absolute after:w-5 after:h-5 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 checked:after:left-6 after:transition-all shadow-inner border border-gray-300 checked:border-emerald-600 outline-none">
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Tiempo Máximo (Min)</label>
                        <input type="number" value="15" class="w-full bg-white border border-gray-100 rounded-2xl px-5 py-3 text-sm font-black text-gray-700 outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Nivel Prioridad</label>
                        <select class="w-full bg-white border border-gray-100 rounded-2xl px-5 py-3 text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                            <option>Normal</option>
                            <option>Alta</option>
                            <option>Crítica</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-8 border-t border-gray-50 bg-white flex justify-end space-x-3">
                <button onclick="closeConfigModal()" class="px-6 py-3 rounded-2xl text-xs font-black text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-colors">Cancelar</button>
                <button onclick="saveConfigModal()" class="px-8 py-3 bg-gray-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl active:scale-95 flex items-center">
                    <i class="fa-solid fa-check mr-2"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentSlugId = null;

    function openConfigModal(title, desc, color, slug) {
        currentSlugId = slug;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent = desc;
        
        // Colores dinámicos del header según el item
        const header = document.getElementById('modalHeader');
        header.className = `p-8 border-b border-gray-50 text-white relative bg-${color}-500`;
        
        // Reflejar el estado actual del badge en el switch
        const badge = document.getElementById(`badge-${slug}`);
        const checkbox = document.getElementById('modalStatus');
        if (badge.textContent === 'Activo') {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }

        const modal = document.getElementById('configModal');
        const content = document.getElementById('configModalContent');
        
        modal.classList.remove('hidden');
        // Pequeño delay para que la transición CSS detone
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }

    function closeConfigModal() {
        const modal = document.getElementById('configModal');
        const content = document.getElementById('configModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // Equivale a duration-300
    }

    function saveConfigModal() {
        const checkbox = document.getElementById('modalStatus');
        const badge = document.getElementById(`badge-${currentSlugId}`);
        
        // Botón visual feedback
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Guardando';
        
        setTimeout(() => {
            if (checkbox.checked) {
                badge.textContent = 'Activo';
                badge.className = 'px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors';
            } else {
                badge.textContent = 'Inactivo';
                badge.className = 'px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors';
            }
            btn.innerHTML = originalHtml;
            closeConfigModal();
        }, 600);
    }
</script>
@endsection
