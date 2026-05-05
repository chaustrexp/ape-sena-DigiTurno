@extends('layouts.coordinador')

@section('title', 'Gestión de Módulos - SENA APE')

@section('content')

{{-- Toasts --}}
@if(session('success'))
<div id="toast-success" class="fixed top-6 right-6 z-[9999] bg-white border border-sena-blue/20 rounded-2xl px-5 py-4 shadow-2xl flex items-center space-x-3 min-w-[280px]">
    <div class="w-9 h-9 bg-sena-blue/10 rounded-xl flex items-center justify-center text-sena-blue"><i class="fa-solid fa-circle-check"></i></div>
    <p class="text-sm font-bold text-gray-800">{{ session('success') }}</p>
    <button onclick="document.getElementById('toast-success').remove()" class="ml-auto text-gray-300 hover:text-gray-500"><i class="fa-solid fa-xmark"></i></button>
</div>
@endif
@if(session('error'))
<div id="toast-error" class="fixed top-6 right-6 z-[9999] bg-white border border-red-100 rounded-2xl px-5 py-4 shadow-2xl flex items-center space-x-3 min-w-[280px]">
    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center text-red-500"><i class="fa-solid fa-circle-xmark"></i></div>
    <p class="text-sm font-bold text-gray-800">{{ session('error') }}</p>
    <button onclick="document.getElementById('toast-error').remove()" class="ml-auto text-gray-300 hover:text-gray-500"><i class="fa-solid fa-xmark"></i></button>
</div>
@endif

{{-- Header --}}
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black text-gray-900 leading-tight">Gestión de Personal</h1>
        <p class="text-gray-500 text-sm font-medium mt-1">Administra los asesores y sus accesos al sistema.</p>
    </div>
    <button onclick="openModal('modal-create')" class="bg-sena-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black shadow-lg hover:bg-sena-600 transition flex items-center space-x-2 uppercase tracking-widest">
        <i class="fa-solid fa-plus text-xs"></i>
        <span>Nuevo Asesor</span>
    </button>
</div>

{{-- Stats bar --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-sena-50 rounded-xl flex items-center justify-center text-sena-500"><i class="fa-solid fa-users"></i></div>
        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Asesores</p><p class="text-xl font-black text-gray-900">{{ count($asesores) }}</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-sena-blue/10 rounded-xl flex items-center justify-center text-sena-blue"><i class="fa-solid fa-circle-dot"></i></div>
        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Activos</p><p class="text-xl font-black text-gray-900">{{ count($asesores) }}</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center space-x-3">
        <div class="w-10 h-10 bg-sena-orange/10 rounded-xl flex items-center justify-center text-sena-orange"><i class="fa-solid fa-grid-2"></i></div>
        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Módulos</p><p class="text-xl font-black text-gray-900">{{ count($asesores) }}/10</p></div>
    </div>
</div>

{{-- Advisors Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($asesores as $ase)
    <div data-search="{{ $ase->persona->pers_nombres ?? '' }} {{ $ase->persona->pers_apellidos ?? '' }} modulo {{ sprintf('%02d', $ase->ase_id) }} {{ $ase->ase_correo }} {{ $ase->persona->pers_doc ?? '' }}" class="searchable-item bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
        
        {{-- Header --}}
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center space-x-3">
                <img src="{{ asset($ase->ase_foto ?? 'images/foto de perfil.jpg') }}" 
                     class="w-12 h-12 rounded-2xl border-2 border-sena-100 object-cover group-hover:border-sena-300 transition">
                <div>
                    <h3 class="text-sm font-black text-gray-900 leading-snug">{{ $ase->persona->pers_nombres ?? 'N/A' }} {{ $ase->persona->pers_apellidos ?? '' }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Módulo {{ sprintf('%02d', $ase->ase_id) }}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[9px] font-black bg-sena-blue/10 text-sena-blue border border-sena-blue/20 tracking-widest uppercase">Activo</span>
        </div>

        {{-- Info --}}
        <div class="space-y-2 flex-1 mb-5">
            <div class="flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-3 py-2.5 rounded-xl">
                <i class="fa-solid fa-envelope text-gray-300 w-4 text-center"></i>
                <span class="font-medium truncate">{{ $ase->ase_correo }}</span>
            </div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-3 py-2.5 rounded-xl">
                <i class="fa-solid fa-id-card text-gray-300 w-4 text-center"></i>
                <span class="font-medium">Doc: {{ $ase->persona->pers_doc ?? 'N/A' }}</span>
            </div>
            @if($ase->ase_nrocontrato)
            <div class="flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-3 py-2.5 rounded-xl">
                <i class="fa-solid fa-file-contract text-gray-300 w-4 text-center"></i>
                <span class="font-medium">Contrato: {{ $ase->ase_nrocontrato }}</span>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex space-x-2 pt-4 border-t border-gray-50">
            <button onclick="openEditModal({{ json_encode($ase) }}, {{ json_encode($ase->persona) }})" 
                    class="flex-1 bg-gray-50 hover:bg-sena-50 border border-gray-100 hover:border-sena-100 text-gray-600 hover:text-sena-600 py-2.5 rounded-xl text-[10px] font-black transition uppercase tracking-widest">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i> Editar
            </button>
            <button onclick="openDeleteModal({{ $ase->ase_id }}, '{{ $ase->persona->pers_nombres ?? 'Asesor' }} {{ $ase->persona->pers_apellidos ?? '' }}')"
                    class="flex-1 bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 py-2.5 rounded-xl text-[10px] font-black transition uppercase tracking-widest">
                <i class="fa-solid fa-trash-can mr-1.5"></i> Eliminar
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-24">
        <i class="fa-solid fa-users-slash text-gray-200 text-5xl mb-4"></i>
        <p class="font-black text-gray-400 uppercase tracking-widest text-sm">Sin Asesores Registrados</p>
        <p class="text-xs text-gray-400 mt-1">Haga clic en "Nuevo Asesor" para comenzar.</p>
    </div>
    @endforelse
</div>

{{-- ==================== MODAL: CREAR ASESOR ==================== --}}
<div id="modal-create" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-create')"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="bg-sena-500 p-7 rounded-t-[2.5rem] flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-black text-white">Registrar Nuevo Asesor</h2>
                <p class="text-sena-100 text-xs font-medium mt-0.5">Completa todos los campos requeridos</p>
            </div>
            <button onclick="closeModal('modal-create')" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('coordinador.asesores.store') }}" method="POST" class="p-8 space-y-8">
            @csrf
            
            <!-- Section 1: Personal Data -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 mb-4 border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-address-card text-sena-500 text-xs"></i>
                    <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.15em]">Datos Personales del Asesor</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipo de Documento *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-id-card-clip text-xs"></i>
                            </div>
                            <select name="pers_tipodoc" required class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all appearance-none">
                                <option value="CC" {{ old('pers_tipodoc') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                <option value="CE" {{ old('pers_tipodoc') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                <option value="TI" {{ old('pers_tipodoc') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                <option value="PAS" {{ old('pers_tipodoc') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-300">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Número de Identificación *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-hashtag text-xs"></i>
                            </div>
                            <input type="text" name="pers_doc" required value="{{ old('pers_doc') }}" placeholder="Ej: 1012345678" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all @error('pers_doc') border-red-500 @enderror">
                        </div>
                        @error('pers_doc') <p class="text-[9px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombres *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-signature text-xs"></i>
                            </div>
                            <input type="text" name="pers_nombres" required value="{{ old('pers_nombres') }}" placeholder="Nombre completo" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Apellidos *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-font text-xs"></i>
                            </div>
                            <input type="text" name="pers_apellidos" required value="{{ old('pers_apellidos') }}" placeholder="Apellidos completos" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                        </div>
                    </div>

                    <div class="col-span-full space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Teléfono Móvil</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </div>
                            <input type="text" name="pers_telefono" value="{{ old('pers_telefono') }}" placeholder="300 000 0000" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Account & System -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 mb-4 border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-laptop-code text-sena-500 text-xs"></i>
                    <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.15em]">Configuración de Cuenta</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-full space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-at text-xs"></i>
                            </div>
                            <input type="email" name="ase_correo" required value="{{ old('ase_correo') }}" placeholder="usuario@sena.edu.co" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all @error('ase_correo') border-red-500 @enderror">
                        </div>
                        @error('ase_correo') <p class="text-[9px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Contraseña de Acceso *</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" name="ase_password" required placeholder="Mínimo 6 caracteres" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all @error('ase_password') border-red-500 @enderror">
                        </div>
                        @error('ase_password') <p class="text-[9px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo de Asesor --}}
                    <div class="col-span-full space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipo de Asesor *</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="ase_tipo_asesor" value="OT" class="peer sr-only" required>
                                <div class="flex items-start gap-3 p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-sena-500 peer-checked:bg-sena-50 transition-all hover:border-gray-300">
                                    <div class="w-8 h-8 rounded-xl bg-sena-blue/10 text-sena-blue flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="fa-solid fa-users-gear text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-800">OT — Orientador Técnico</p>
                                        <p class="text-[9px] text-gray-400 font-medium mt-0.5">Atiende: <span class="font-bold text-gray-600">General · Prioritario</span></p>
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="ase_tipo_asesor" value="OV" class="peer sr-only">
                                <div class="flex items-start gap-3 p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-sena-orange peer-checked:bg-orange-50 transition-all hover:border-gray-300">
                                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-sena-orange flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="fa-solid fa-award text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-800">OV — Orientador Víctimas</p>
                                        <p class="text-[9px] text-gray-400 font-medium mt-0.5">Atiende: <span class="font-bold text-gray-600">Víctima · Empresario</span></p>
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="ase_tipo_asesor" value="AT" class="peer sr-only">
                                <div class="flex items-start gap-3 p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all hover:border-gray-300">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="fa-solid fa-star text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-800">AT — Asesor Total</p>
                                        <p class="text-[9px] text-gray-400 font-medium mt-0.5">Atiende: <span class="font-bold text-emerald-600">Los 4 perfiles</span></p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        {{-- Leyenda de perfiles --}}
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            @foreach([['General','bg-blue-100 text-blue-700','fa-user'],['Prioritario','bg-amber-100 text-amber-700','fa-wheelchair'],['Víctima','bg-rose-100 text-rose-700','fa-award'],['Empresario','bg-purple-100 text-purple-700','fa-building']] as [$label,$cls,$icon])
                            <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl {{ $cls }}">
                                <i class="fa-solid {{ $icon }} text-[9px]"></i>
                                <span class="text-[9px] font-black uppercase tracking-wide">{{ $label }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">N° de Contrato / Ficha</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-file-contract text-xs"></i>
                            </div>
                            <input type="text" name="ase_nrocontrato" value="{{ old('ase_nrocontrato') }}" placeholder="CONT-2026..." class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                        </div>
                    </div>

                    <!-- Photo Path (Hidden or simple input for now as per previous logic) -->
                    <div class="col-span-full space-y-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ruta de Foto Perfil (Opcional)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                                <i class="fa-solid fa-image text-xs"></i>
                            </div>
                            <input type="text" name="ase_foto" value="images/foto de perfil.jpg" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex space-x-4 pt-6">
                <button type="button" onclick="closeModal('modal-create')" class="flex-1 bg-white border-2 border-gray-100 text-gray-400 font-black py-4 rounded-2xl text-[11px] uppercase tracking-[0.2em] hover:bg-gray-50 hover:text-gray-600 transition-all">
                    Descartar
                </button>
                <button type="submit" class="flex-1 bg-sena-500 text-white font-black py-4 rounded-2xl text-[11px] uppercase tracking-[0.2em] hover:bg-sena-600 transition-all shadow-xl shadow-sena-500/20 active:scale-95 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Registrar Asesor</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL: EDITAR ASESOR ==================== --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-edit')"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="bg-sena-500 p-7 rounded-t-[2.5rem] flex items-center justify-between sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-black text-white">Editar Asesor</h2>
                <div class="flex items-center space-x-3 mt-1">
                    <img id="edit-preview-photo" src="{{ asset('images/foto de perfil.jpg') }}" class="w-8 h-8 rounded-lg border border-white/30 object-cover">
                    <p class="text-sena-100 text-xs font-medium" id="edit-modal-subtitle">Modifica los datos del asesor</p>
                </div>
            </div>
            <button onclick="closeModal('modal-edit')" class="w-9 h-9 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-8 space-y-8">
            @csrf
            
            <!-- Profile Photo Section -->
            <div class="flex flex-col md:flex-row items-center gap-8 bg-gray-50/50 p-6 rounded-[2.5rem] border border-gray-100 shadow-inner">
                <div class="relative group">
                    <img id="edit-preview-photo-large" src="{{ asset('images/foto de perfil.jpg') }}" class="w-28 h-28 rounded-[2rem] border-4 border-white shadow-2xl object-cover transition-transform group-hover:scale-105 duration-500">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-sena-500 text-white rounded-xl flex items-center justify-center shadow-lg border-2 border-white">
                        <i class="fa-solid fa-camera text-[10px]"></i>
                    </div>
                </div>
                <div class="flex-1 space-y-3 w-full">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Ruta de Foto de Perfil</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-link text-xs"></i>
                        </div>
                        <input type="text" name="ase_foto" id="edit-ase_foto" 
                               oninput="document.getElementById('edit-preview-photo-large').src = this.value.startsWith('http') ? this.value : '/' + this.value; document.getElementById('edit-preview-photo').src = this.value.startsWith('http') ? this.value : '/' + this.value" 
                               class="w-full bg-white border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all shadow-sm"
                               placeholder="images/foto de perfil.jpg">
                    </div>
                </div>
            </div>

            <!-- Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- Personal Information Header -->
                <div class="col-span-full flex items-center space-x-3 mb-2 border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-user-tie text-sena-500 text-xs"></i>
                    <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.15em]">Información Personal</h3>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombres Completos</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-signature text-xs"></i>
                        </div>
                        <input type="text" name="pers_nombres" id="edit-pers_nombres" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Apellidos</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-font text-xs"></i>
                        </div>
                        <input type="text" name="pers_apellidos" id="edit-pers_apellidos" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Teléfono de Contacto</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-phone-volume text-xs"></i>
                        </div>
                        <input type="text" name="pers_telefono" id="edit-pers_telefono" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                </div>

                <!-- System Information Header -->
                <div class="col-span-full flex items-center space-x-3 mt-4 mb-2 border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-shield-halved text-sena-500 text-xs"></i>
                    <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.15em]">Credenciales & Sistema</h3>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Correo Institucional</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </div>
                        <input type="email" name="ase_correo" id="edit-ase_correo" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">N° de Contrato</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-file-contract text-xs"></i>
                        </div>
                        <input type="text" name="ase_nrocontrato" id="edit-ase_nrocontrato" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Actualizar Contraseña</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-sena-500 transition-colors">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                        <input type="password" name="ase_password" placeholder="••••••••" class="w-full bg-gray-50/50 border border-gray-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-gray-700 focus:outline-none focus:ring-4 focus:ring-sena-500/10 focus:border-sena-500 transition-all">
                    </div>
                    <p class="text-[9px] text-gray-400 font-bold ml-1">Dejar en blanco para conservar la actual</p>
                </div>
            </div>

            <div class="flex space-x-4 pt-6">
                <button type="button" onclick="closeModal('modal-edit')" class="flex-1 bg-white border-2 border-gray-100 text-gray-400 font-black py-4 rounded-2xl text-[11px] uppercase tracking-[0.2em] hover:bg-gray-50 hover:text-gray-600 transition-all">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-sena-500 text-white font-black py-4 rounded-2xl text-[11px] uppercase tracking-[0.2em] hover:bg-sena-600 transition-all shadow-xl shadow-sena-500/20 active:scale-95 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL: ELIMINAR ==================== --}}
<div id="modal-delete" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('modal-delete')"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 text-2xl mx-auto mb-5">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h2 class="text-lg font-black text-gray-900 mb-2">¿Eliminar Asesor?</h2>
        <p class="text-sm text-gray-500 font-medium mb-6">Estás por eliminar a <strong id="delete-asesor-name" class="text-gray-900"></strong>. Esta acción no se puede deshacer.</p>
        <form id="form-delete" method="POST">
            @csrf
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('modal-delete')" class="flex-1 bg-gray-50 border border-gray-200 text-gray-600 font-black py-3.5 rounded-2xl text-[11px] uppercase tracking-widest hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-black py-3.5 rounded-2xl text-[11px] uppercase tracking-widest transition shadow-lg shadow-red-500/20">
                    Sí, Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openEditModal(asesor, persona) {
        document.getElementById('edit-pers_nombres').value = persona?.pers_nombres ?? '';
        document.getElementById('edit-pers_apellidos').value = persona?.pers_apellidos ?? '';
        document.getElementById('edit-pers_telefono').value = persona?.pers_telefono ?? '';
        document.getElementById('edit-ase_correo').value = asesor.ase_correo ?? '';
        document.getElementById('edit-ase_nrocontrato').value = asesor.ase_nrocontrato ?? '';
        document.getElementById('edit-ase_foto').value = asesor.ase_foto ?? 'images/foto de perfil.jpg';
        const photoPath = asesor.ase_foto ?? 'images/foto de perfil.jpg';
        document.getElementById('edit-preview-photo').src = `/${photoPath}`;
        document.getElementById('edit-preview-photo-large').src = `/${photoPath}`;
        document.getElementById('edit-modal-subtitle').textContent = 'Editando: ' + (persona?.pers_nombres ?? 'Asesor') + ' ' + (persona?.pers_apellidos ?? '');
        document.getElementById('form-edit').action = `/coordinador/modulos/update/${asesor.ase_id}`;
        openModal('modal-edit');
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete-asesor-name').textContent = name;
        document.getElementById('form-delete').action = `/coordinador/modulos/delete/${id}`;
        openModal('modal-delete');
    }

    // Auto-dismiss toasts after 4s
    setTimeout(() => {
        const ts = document.getElementById('toast-success');
        const te = document.getElementById('toast-error');
        if (ts) ts.remove();
        if (te) te.remove();
    }, 4000);

    // Auto-open modal if there are validation errors
    @if ($errors->any())
        openModal('modal-create');
    @endif
</script>
@endsection
