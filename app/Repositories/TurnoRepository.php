<?php

namespace App\Repositories;

use App\Models\Turno;
use App\Models\Atencion;
use App\Models\Asesor;
use App\Models\PausaAsesor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TurnoRepository
{
    /**
     * Obtiene los turnos en espera para la pantalla pública.
     * Orden de prioridad: Víctima (V) → Empresario (E) → Prioritario (P) → General (G) + FIFO.
     */
    public function getWaitingForPublicScreen()
    {
        return Turno::whereDate('tur_hora_fecha', now()->toDateString())
            ->where('tur_estado', 'Espera')
            ->orderByRaw("CASE
                WHEN tur_perfil = 'Victima'     THEN 1
                WHEN tur_perfil = 'Empresario'  THEN 2
                WHEN tur_perfil = 'Prioritario' THEN 3
                ELSE 4 END ASC")
            ->orderBy('tur_id', 'asc')
            ->get();
    }

    /**
     * Obtiene estadísticas de espera para el Dashboard del Coordinador.
     */
    public function getWaitingStats()
    {
        return Turno::whereDate('tur_hora_fecha', now()->toDateString())
            ->where('tur_estado', 'Espera')
            ->selectRaw('tur_tipo, count(*) as count')
            ->groupBy('tur_tipo')
            ->pluck('count', 'tur_tipo')
            ->toArray();
    }

    /**
     * Obtiene turnos en espera según el perfil del asesor (CU-02).
     * Orientador Técnico (OT): General + Prioritario
     * Orientador de Víctimas (OV): Victima + Empresario
     */
    public function getWaitingForAsesor($tipoAsesor)
    {
        $query = Turno::whereDate('tur_hora_fecha', now()->toDateString())
                      ->where('tur_estado', 'Espera');

        if ($tipoAsesor === 'OV') {
            // Orientador de Víctimas: Empresario primero, luego Víctima
            return $query->whereIn('tur_perfil', ['Victima', 'Empresario'])
                         ->orderByRaw("CASE WHEN tur_perfil = 'Empresario' THEN 1 ELSE 2 END ASC")
                         ->orderBy('tur_hora_fecha', 'asc')
                         ->get();
        } else {
            // Orientador Técnico (OT): Prioritario vs General (3:1)
            // Para la vista de espera, simplemente los mostramos priorizando Prioritario
            return $query->whereIn('tur_perfil', ['Prioritario', 'General'])
                         ->orderByRaw("CASE WHEN tur_perfil = 'Prioritario' THEN 1 ELSE 2 END ASC")
                         ->orderBy('tur_hora_fecha', 'asc')
                         ->get();
        }
    }

    /**
     * Lógica transaccional para llamar al siguiente turno en la fila (CU-02).
     * Orientador Técnico (OT): Prioritario → General (FIFO)
     * Orientador de Víctimas (OV): Victima → Empresario (FIFO)
     * Registra tur_hora_llamado para calcular tiempo de espera real.
     */
    public function callNextTurn(Asesor $asesor)
    {
        return DB::transaction(function () use ($asesor) {
            $tipoAsesor = $asesor->ase_tipo_asesor ?? 'OT';
            $ase_id     = $asesor->ase_id;

            // Verificar si el asesor tiene una pausa activa
            $pausaActiva = PausaAsesor::where('ASESOR_ase_id', $ase_id)
                                      ->whereNull('hora_fin')
                                      ->exists();
            if ($pausaActiva) {
                return null;
            }

            $query = Turno::whereDate('tur_hora_fecha', now()->toDateString())
                          ->where('tur_estado', 'Espera');

            if ($tipoAsesor === 'OV') {
                // Orientador de Víctimas (Role 1): Empresario → Victima
                $turno = $query->whereIn('tur_perfil', ['Victima', 'Empresario'])
                               ->orderByRaw("CASE WHEN tur_perfil = 'Empresario' THEN 1 ELSE 2 END ASC")
                               ->orderBy('tur_hora_fecha', 'asc')
                               ->lockForUpdate()
                               ->first();
            } else {
                // Orientador Técnico (Role 2): Prioritario → General (Relación 3:1)
                $count = Cache::get('prioritario_counter', 0);
                
                if ($count < 3) {
                    // Intentar obtener un Prioritario
                    $turno = $query->clone()
                                   ->where('tur_perfil', 'Prioritario')
                                   ->orderBy('tur_hora_fecha', 'asc')
                                   ->lockForUpdate()
                                   ->first();
                    
                    if ($turno) {
                        Cache::put('prioritario_counter', $count + 1, now()->addDay());
                    } else {
                        // Si no hay Prioritario, tomar un General y reiniciar contador
                        $turno = $query->clone()
                                       ->where('tur_perfil', 'General')
                                       ->orderBy('tur_hora_fecha', 'asc')
                                       ->lockForUpdate()
                                       ->first();
                        Cache::put('prioritario_counter', 0, now()->addDay());
                    }
                } else {
                    // Se alcanzó el límite de 3 prioritarios, intentar obtener un General
                    $turno = $query->clone()
                                   ->where('tur_perfil', 'General')
                                   ->orderBy('tur_hora_fecha', 'asc')
                                   ->lockForUpdate()
                                   ->first();
                    
                    if ($turno) {
                        Cache::put('prioritario_counter', 0, now()->addDay());
                    } else {
                        // Si no hay General, tomar un Prioritario de todos modos si existe
                        $turno = $query->clone()
                                       ->where('tur_perfil', 'Prioritario')
                                       ->orderBy('tur_hora_fecha', 'asc')
                                       ->lockForUpdate()
                                       ->first();
                        Cache::put('prioritario_counter', 0, now()->addDay()); 
                    }
                }
            }

            if (!$turno) return null;

            // Actualizar estado a 'Atendiendo' y registrar hora de llamado
            $turno->update([
                'tur_estado' => 'Atendiendo',
                'tur_hora_llamado' => now()
            ]);

            // Mapear perfil al enum de atencion
            $tipoAtencion = match ($turno->tur_perfil) {
                'Victima'                    => 'Victimas',
                'Empresario', 'Prioritario'  => 'Prioritaria',
                default                      => 'General',
            };

            $atencion = Atencion::create([
                'atnc_hora_inicio' => now(),
                'ASESOR_ase_id'    => $ase_id,
                'TURNO_tur_id'     => $turno->tur_id,
                'atnc_tipo'        => $tipoAtencion,
            ]);

            return $atencion->load('turno');
        });
    }

    /**
     * Inicia un receso para el asesor (CU-03).
     * Bloquea si hay una atención activa sin finalizar.
     *
     * @return PausaAsesor|string  Devuelve la pausa creada o un mensaje de error.
     */
    public function iniciarReceso(Asesor $asesor)
    {
        $ase_id = $asesor->ase_id;

        // ── Bloqueo: no se puede pausar si hay atención activa ──
        $atencionActiva = Atencion::where('ASESOR_ase_id', $ase_id)
                                  ->whereNull('atnc_hora_fin')
                                  ->exists();
        if ($atencionActiva) {
            return 'No puedes iniciar un receso mientras tienes una atención activa. Finaliza la atención primero.';
        }

        // ── Bloqueo: evitar doble pausa activa ──
        $pausaActiva = PausaAsesor::where('ASESOR_ase_id', $ase_id)
                                   ->whereNull('hora_fin')
                                   ->exists();
        if ($pausaActiva) {
            return 'Ya tienes un receso activo en curso.';
        }

        return PausaAsesor::create([
            'ASESOR_ase_id' => $ase_id,
            'hora_inicio'   => now(),
        ]);
    }

    /**
     * Finaliza el receso activo del asesor (CU-03).
     * Calcula la duración en minutos.
     *
     * @return PausaAsesor|string
     */
    public function finalizarReceso(Asesor $asesor)
    {
        $pausa = PausaAsesor::where('ASESOR_ase_id', $asesor->ase_id)
                             ->whereNull('hora_fin')
                             ->latest('hora_inicio')
                             ->first();

        if (!$pausa) {
            return 'No tienes un receso activo para finalizar.';
        }

        $fin      = now();
        $duracion = (int) $pausa->hora_inicio->diffInMinutes($fin);

        $pausa->update([
            'hora_fin' => $fin,
            'duracion' => $duracion,
        ]);

        return $pausa;
    }

    /**
     * Obtiene el turno que está siendo atendido actualmente a nivel global.
     */
    public function getCurrentAttention()
    {
        return Atencion::whereNull('atnc_hora_fin')
                       ->with(['turno.solicitante.persona', 'asesor'])
                       ->latest('atnc_hora_inicio')
                       ->first();
    }

    /**
     * Obtiene la atención activa para un asesor específico.
     */
    public function getActiveAttentionForAsesor($ase_id)
    {
        return Atencion::where('ASESOR_ase_id', $ase_id)
                       ->whereNull('atnc_hora_fin')
                       ->with('turno.solicitante.persona')
                       ->first();
    }

    /**
     * CU-01 / CU-04: Calcula tiempos medios del ciclo de vida del turno.
     * - tiempo_espera_medio:  promedio de (tur_hora_llamado - tur_hora_fecha) en minutos
     * - tiempo_atencion_medio: promedio de (atnc_hora_fin - atnc_hora_inicio) en minutos
     *
     * @param  string|null $fecha  Fecha en formato Y-m-d (default: hoy)
     * @return array{tiempo_espera_medio: float, tiempo_atencion_medio: float}
     */
    public function getTiemposMedios(?string $fecha = null): array
    {
        $fecha = $fecha ?? now()->toDateString();

        // Tiempo medio de espera (tur_hora_llamado - tur_hora_fecha)
        $espera = Turno::whereDate('tur_hora_fecha', $fecha)
            ->whereNotNull('tur_hora_llamado')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, tur_hora_fecha, tur_hora_llamado)) as promedio')
            ->value('promedio');

        // Tiempo medio de atención (atnc_hora_fin - atnc_hora_inicio)
        $atencion = Atencion::whereDate('atnc_hora_inicio', $fecha)
            ->whereNotNull('atnc_hora_fin')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, atnc_hora_inicio, atnc_hora_fin)) as promedio')
            ->value('promedio');

        return [
            'tiempo_espera_medio'   => round((float) $espera, 1),
            'tiempo_atencion_medio' => round((float) $atencion, 1),
        ];
    }

    /**
     * CU-04: Cuenta emprendedores atendidos en la semana SOLO por módulos 15 y 19.
     *
     * @param  string $inicioSemana  Y-m-d
     * @param  string $finSemana     Y-m-d
     * @return int
     */
    public function getEmprendedoresModulosVigilancia(string $inicioSemana, string $finSemana): int
    {
        return Turno::whereBetween('tur_hora_fecha', [
                        $inicioSemana . ' 00:00:00',
                        $finSemana    . ' 23:59:59',
                    ])
                    ->where('tur_servicio', 'Emprendimiento')
                    ->whereHas('atencion', function ($q) {
                        $q->whereIn('ASESOR_ase_id', [15, 19]);
                    })
                    ->count();
    }
}
