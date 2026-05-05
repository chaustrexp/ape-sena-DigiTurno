@extends('layouts.asesor')

@section('title', 'Manual del Asesor - SENA APE')

@section('content')
<div id="manual-content" class="bg-gray-50/30">
    <div class="mb-12 print:hidden italic">
        <h1 class="text-4xl font-black text-gray-900 leading-tight">Guía Operativa del Asesor</h1>
        <p class="text-gray-400 text-lg font-bold mt-2 uppercase tracking-widest">Agencia Pública de Empleo - SENA</p>
    </div>

    <!-- Contenido -->
    <div class="space-y-12">
        <!-- Section 1: Atención -->
        <div class="bg-white p-12 rounded-[3.5rem] shadow-sm border border-gray-100 print:shadow-none print:border-sena-500 print:border-2">
            <div class="flex items-center space-x-5 mb-8">
                <div class="w-14 h-14 bg-sena-50 text-sena-500 rounded-[1.5rem] flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">1. Gestión de la Atención</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="space-y-4">
                    <div class="text-xs font-black text-emerald-500 bg-emerald-50 py-2 rounded-full uppercase tracking-widest">Llamar</div>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">Use **"Llamar Siguiente"** para asignar el turno prioritario o general en espera.</p>
                </div>
                <div class="space-y-4">
                    <div class="text-xs font-black text-blue-500 bg-blue-50 py-2 rounded-full uppercase tracking-widest">Atender</div>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">Visualice los datos del ciudadano y controle el tiempo con el cronómetro.</p>
                </div>
                <div class="space-y-4">
                    <div class="text-xs font-black text-red-500 bg-red-50 py-2 rounded-full uppercase tracking-widest">Cerrar</div>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">Presione **"Finalizar"** al terminar para quedar disponible de nuevo.</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Estados -->
        <div class="bg-white p-12 rounded-[3.5rem] shadow-sm border border-gray-100 print:shadow-none print:border-sena-500 print:border-2">
            <div class="flex items-center space-x-5 mb-8">
                <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-[1.5rem] flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-pause"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">2. Pausas y Recesos</h3>
            </div>
            <p class="text-gray-600 font-bold mb-6 italic leading-relaxed">
                Es fundamental marcar sus pausas para que el sistema no le asigne ciudadanos mientras no está en su puesto.
            </p>
            <div class="bg-amber-50 p-8 rounded-[2rem] border border-amber-100 flex items-start space-x-4">
                <i class="fa-solid fa-circle-exclamation text-amber-500 mt-1"></i>
                <p class="text-xs text-amber-800 font-bold leading-relaxed uppercase tracking-wide">
                    Al activar el modo pausa, su estado en el monitor del coordinador cambiará a "Receso" y el tiempo de sesión se detendrá.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mt-12 flex justify-end print:hidden">
    <button id="download-pdf-asesor" class="bg-gray-900 text-white px-10 py-5 rounded-[2rem] font-black uppercase tracking-widest flex items-center space-x-4 hover:bg-black transition-all shadow-2xl active:scale-95">
        <i class="fa-solid fa-download"></i>
        <span>Guardar Manual PDF</span>
    </button>
</div>

<style>
    /* Estilos específicos para la generación del PDF */
    .pdf-mode {
        width: 800px !important;
        padding: 40px !important;
        background: white !important;
        color: #1a202c !important;
    }
    .pdf-mode .rounded-\[3\.5rem\] {
        border-radius: 1.5rem !important;
        border: 2px solid #f3f4f6 !important;
    }
    .pdf-mode h1 { font-size: 32px !important; }
    .pdf-mode h3 { font-size: 20px !important; }
    .pdf-mode p { font-size: 14px !important; }
</style>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('download-pdf-asesor').addEventListener('click', function() {
        const reportArea = document.getElementById('manual-content');
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i><span>PROCESANDO...</span>';
        btn.disabled = true;

        const mainEl = document.querySelector('main');
        if(mainEl) {
            mainEl.style.overflow = 'visible';
            mainEl.style.height = 'auto';
        }

        const opt = {
            margin:       [15, 10, 15, 10],
            filename:     'Manual_Asesor_APE.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, scrollY: 0 },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(reportArea).save().then(() => {
            if(mainEl) {
                mainEl.style.overflow = '';
                mainEl.style.height = '';
            }
            btn.innerHTML = originalText || '<i class="fa-solid fa-download"></i><span>Guardar Manual PDF</span>';
            btn.disabled = false;
        }).catch(err => {
            console.error('Error generating PDF:', err);
            if(mainEl) {
                mainEl.style.overflow = '';
                mainEl.style.height = '';
            }
            btn.innerHTML = originalText || '<i class="fa-solid fa-download"></i><span>Guardar Manual PDF</span>';
            btn.disabled = false;
        });
    });
</script>
@endsection
@endsection
