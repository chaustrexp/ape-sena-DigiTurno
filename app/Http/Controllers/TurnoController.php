<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Solicitante;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TurnoController extends Controller
{
    public function index()
    {
        return view('kiosco.index');
    }

    public function store(Request $request)
    {
        // ── Validación base ──
        $request->validate([
            'pers_doc'         => 'required|numeric',
            'pers_tipodoc'     => 'required|in:CC,TI,CE',
            'pers_nombres'     => 'required|string|max:100',
            'pers_apellidos'   => 'required|string|max:100',
            'tur_perfil'       => 'required|in:General,Victima,Prioritario,Empresario',
            'tur_tipo_atencion'=> 'required|in:Normal,Especial',
            'tur_servicio'     => 'required|in:Orientacion,Formacion,Emprendimiento',
            'tur_telefono'     => 'nullable|string|max:20',
        ]);

        // ── Validación de longitud por tipo de documento (Normativa Colombiana) ──
        $doc     = (string) $request->pers_doc;
        $tipodoc = $request->pers_tipodoc;
        $docLen  = strlen($doc);

        if (in_array($tipodoc, ['CC', 'TI'])) {
            if ($docLen < 8 || $docLen > 10) {
                return back()
                    ->withInput()
                    ->withErrors(['pers_doc' => 'La Cédula de Ciudadanía y la Tarjeta de Identidad deben tener entre 8 y 10 dígitos.']);
            }
        } elseif ($tipodoc === 'CE') {
            if ($docLen < 6) {
                return back()
                    ->withInput()
                    ->withErrors(['pers_doc' => 'La Cédula de Extranjería debe tener mínimo 6 dígitos.']);
            }
        }

        return DB::transaction(function () use ($request) {

            // ── Regla de Negocio: Advertencia si el documento NO existe en la APE ──
            $advertenciaAPE = null;
            $existeEnAPE = Persona::where('pers_doc', $request->pers_doc)->exists();
            if (!$existeEnAPE) {
                $advertenciaAPE = '¡Bienvenido! Hemos generado su turno con éxito. Notamos que aún no cuenta con un registro formal en la Agencia Pública de Empleo; no se preocupe, el asesor que le atenderá le orientará para completar su inscripción y que pueda acceder a todos los servicios y beneficios del SENA.';
            }

            // 1. Guardar o actualizar Persona
            $persona = Persona::updateOrCreate(
                ['pers_doc' => $request->pers_doc],
                $request->only(['pers_tipodoc', 'pers_nombres', 'pers_apellidos', 'pers_fecha_nac'])
                + ['pers_telefono' => $request->tur_telefono ?? $request->pers_telefono ?? null]
            );

            // 2. Crear o recuperar Solicitante
            $solicitante = Solicitante::firstOrCreate([
                'PERSONA_pers_doc' => $persona->pers_doc,
            ], [
                'sol_tipo' => $request->tur_perfil,
            ]);

            // ── Regla de Negocio: Límite de un turno por persona según el periodo (día, semana, mes) ──
            $periodoReinicio = env('PERIODO_REINICIO_TURNOS', 'day'); // Valores: 'day', 'week', 'month'
            
            $fechaInicio = match($periodoReinicio) {
                'month' => now()->startOfMonth(),
                'week'  => now()->startOfWeek(),
                default => now()->startOfDay(),
            };

            $turnosExistentes = Turno::where('SOLICITANTE_sol_id', $solicitante->sol_id)
                ->where('tur_hora_fecha', '>=', $fechaInicio)
                ->exists();

            if ($turnosExistentes) {
                $mensajeError = match($periodoReinicio) {
                    'month' => 'Estos datos ya existen. Solo puedes solicitar un (1) turno por mes.',
                    'week'  => 'Estos datos ya existen. Solo puedes solicitar un (1) turno por semana.',
                    default => 'Estos datos ya existen. Solo puedes solicitar un (1) turno por día.'
                };
                return back()->with('error', $mensajeError);
            }

            // ── Perfilamiento: Prioridad Víctima > Empresario > Prioritario > General ──
            // Mapa de perfil → prefijo alfanumérico y tipo legacy (compatibilidad con 'atencion')
            $perfilMap = [
                'Victima'    => ['prefix' => 'V', 'tur_tipo' => 'Victimas'],
                'Empresario' => ['prefix' => 'E', 'tur_tipo' => 'Prioritario'],
                'Prioritario'=> ['prefix' => 'P', 'tur_tipo' => 'Prioritario'],
                'General'    => ['prefix' => 'G', 'tur_tipo' => 'General'],
            ];

            $perfil  = $request->tur_perfil;
            $mapping = $perfilMap[$perfil] ?? $perfilMap['General'];
            $prefix  = $mapping['prefix'];
            $turTipo = $mapping['tur_tipo'];

            // Generación de correlativo con bloqueo pesimista (lock FOR UPDATE)
            $count = Turno::whereDate('tur_hora_fecha', now()->toDateString())
                          ->where('tur_perfil', $perfil)
                          ->lockForUpdate()
                          ->count();

            $numero    = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $tur_numero = "{$prefix}-{$numero}";

            // 5. Crear Turno con los nuevos campos
            Turno::create([
                'tur_hora_fecha'    => now(),
                'tur_numero'        => $tur_numero,
                'tur_tipo'          => $turTipo,
                'tur_perfil'        => $perfil,
                'tur_tipo_atencion' => $request->tur_tipo_atencion,
                'tur_servicio'      => $request->tur_servicio,
                'tur_telefono'      => $request->tur_telefono,
                'SOLICITANTE_sol_id'=> $solicitante->sol_id,
            ]);

            // Retornar respuesta con éxito (y advertencia si aplica)
            $response = back()->with('success', "Turno solicitado con éxito: {$tur_numero}");
            if ($advertenciaAPE) {
                $response = $response->with('warning', $advertenciaAPE);
            }
            return $response;
        });
    }
}
