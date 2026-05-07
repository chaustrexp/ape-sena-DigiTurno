<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TurnoRepository;
use App\Models\Turno;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    protected $turnoRepo;

    public function __construct(TurnoRepository $turnoRepo)
    {
        $this->turnoRepo = $turnoRepo;
    }

    /**
     * Obtiene estadísticas de turnos en espera para el Coordinador.
     */
    public function getCoordinatorStats()
    {
        // Solo coordinadores autenticados si se desea reforzar
        if (!session()->has('coordinador_id')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $stats = $this->turnoRepo->getWaitingStats();

        return response()->json([
            'success'     => true,
            'timestamp'   => now()->format('H:i:s'),
            'data' => [
                'General'     => $stats['General'] ?? 0,
                'Prioritario' => $stats['Prioritario'] ?? 0,
                'Victimas'    => $stats['Victimas'] ?? 0,
                'Total'       => array_sum($stats)
            ]
        ]);
    }

    /**
     * Obtiene los datos de la pantalla de turnos (Turno Actual y Espera).
     */
    public function getPantallaData()
    {
        $atencionActual = $this->turnoRepo->getCurrentAttention();

        $turnoActual = null;
        if ($atencionActual) {
            $asesor = $atencionActual->asesor;
            $nombreAsesor = $asesor?->persona
                ? ($asesor->persona->pers_nombres . ' ' . $asesor->persona->pers_apellidos)
                : 'Asesor';

            $turnoActual = [
                'tur_id'       => $atencionActual->turno->tur_id,
                'tur_numero'   => $atencionActual->turno->tur_numero,
                'tur_tipo'     => $atencionActual->turno->tur_tipo ?? 'General',
                'tur_perfil'   => $atencionActual->turno->tur_perfil ?? 'General',
                'modulo'       => $atencionActual->ASESOR_ase_id,
                'asesor_nombre'=> $nombreAsesor,
                'ase_foto'     => $asesor?->ase_foto
                    ? asset($asesor->ase_foto)
                    : asset('images/foto de perfil.jpg'),
                'atnc_id'      => $atencionActual->atnc_id,
                'ciudadano'    => $atencionActual->turno->solicitante?->persona?->pers_nombres ?? 'Ciudadano',
            ];
        }

        $perfilAsesorMap = [
            'General'    => ['OT', 'AT'],
            'Prioritario'=> ['OT', 'AT'],
            'Victima'    => ['OV', 'AT'],
            'Victimas'   => ['OV', 'AT'],
            'Empresario' => ['OV', 'AT'],
        ];

        // Asesores disponibles (sin atención activa) agrupados por tipo
        $asesoresPorTipo = \App\Models\Asesor::with('persona')
            ->whereDoesntHave('atenciones', function($q) {
                $q->whereNull('atnc_hora_fin');
            })
            ->get()
            ->groupBy('ase_tipo_asesor');

        $turnosEnEspera = Turno::whereDate('tur_hora_fecha', now()->toDateString())
                                ->where('tur_estado', 'Espera')
                                ->orderByRaw("CASE
                                    WHEN tur_perfil = 'Victima'     THEN 1
                                    WHEN tur_perfil = 'Empresario'  THEN 2
                                    WHEN tur_perfil = 'Prioritario' THEN 3
                                    ELSE 4 END ASC")
                                ->orderBy('tur_id', 'asc')
                                ->get()
                                ->map(function($t) use ($perfilAsesorMap, $asesoresPorTipo) {
                                    $perfil = $t->tur_perfil ?? $t->tur_tipo ?? 'General';
                                    $tiposAsesor = $perfilAsesorMap[$perfil] ?? ['OT', 'AT'];
                                    $asesorSugerido = null;
                                    foreach ($tiposAsesor as $tipo) {
                                        if (isset($asesoresPorTipo[$tipo]) && $asesoresPorTipo[$tipo]->isNotEmpty()) {
                                            $asesorSugerido = $asesoresPorTipo[$tipo]->first();
                                            break;
                                        }
                                    }
                                    return [
                                        'tur_id'               => $t->tur_id,
                                        'tur_numero'           => $t->tur_numero,
                                        'tur_tipo'             => $t->tur_tipo,
                                        'tur_perfil'           => $t->tur_perfil ?? $t->tur_tipo,
                                        'modulo_sugerido'      => $asesorSugerido?->ase_id,
                                        'asesor_nombre_sugerido' => $asesorSugerido?->persona
                                            ? ($asesorSugerido->persona->pers_nombres . ' ' . $asesorSugerido->persona->pers_apellidos)
                                            : null,
                                    ];
                                });

        return response()->json([
            'success'        => true,
            'timestamp'      => now()->format('H:i:s'),
            'turnoActual'    => $turnoActual,
            'turnosEnEspera' => $turnosEnEspera
        ]);
    }

    /**
     * Consulta el turno más reciente de un ciudadano para el día actual.
     */
    public function consultarTurno($documento)
    {
        $turno = Turno::whereHas('solicitante.persona', function($query) use ($documento) {
            $query->where('pers_doc', $documento);
        })
        ->whereDate('tur_hora_fecha', now()->toDateString())
        ->latest('tur_id')
        ->first();

        if (!$turno) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron turnos activos para este documento hoy.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'numero'    => $turno->tur_numero,
                'perfil'    => $turno->tur_perfil,
                'estado'    => $turno->tur_estado ?? 'Espera',
                'servicio'  => $turno->tur_servicio,
                'hora'      => $turno->tur_hora_fecha->format('d/m/Y h:i A'),
                'ciudadano' => ($turno->solicitante?->persona?->pers_nombres ?? 'Usuario') . ' ' . ($turno->solicitante?->persona?->pers_apellidos ?? 'Kiosco')
            ]
        ]);
    }
}
