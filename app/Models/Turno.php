<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turno';
    protected $primaryKey = 'tur_id';
    public $timestamps = false;

    protected $fillable = [
        'tur_estado',
        'tur_hora_fecha',
        'tur_hora_llamado',
        'tur_numero',
        'tur_tipo',
        'tur_perfil',
        'tur_tipo_atencion',
        'tur_servicio',
        'tur_telefono',
        'SOLICITANTE_sol_id',
    ];

    protected $casts = [
        'tur_hora_fecha'    => 'datetime',
        'tur_hora_llamado'  => 'datetime',
    ];

    // ── CU-01: Ciclo de vida del turno ──────────────────────────────────────

    /**
     * Tiempo de espera en minutos: desde que se generó el turno hasta que fue llamado.
     * Requiere tur_hora_llamado (migración 2026_04_20_000001).
     */
    public function getTiempoEsperaAttribute(): ?int
    {
        if (!$this->tur_hora_fecha || !$this->tur_hora_llamado) {
            return null;
        }
        return (int) $this->tur_hora_fecha->diffInMinutes($this->tur_hora_llamado);
    }

    /**
     * Tiempo de atención en minutos: desde que inició la atención hasta que finalizó.
     * Requiere la relación atencion cargada con atnc_hora_fin.
     */
    public function getTiempoAtencionAttribute(): ?int
    {
        $atnc = $this->relationLoaded('atencion') ? $this->atencion : null;
        if (!$atnc || !$atnc->atnc_hora_inicio || !$atnc->atnc_hora_fin) {
            return null;
        }
        return (int) $atnc->atnc_hora_inicio->diffInMinutes($atnc->atnc_hora_fin);
    }

    // ── Relaciones ───────────────────────────────────────────────────────────

    public function solicitante()
    {
        return $this->belongsTo(Solicitante::class, 'SOLICITANTE_sol_id', 'sol_id');
    }

    public function atencion()
    {
        return $this->hasOne(Atencion::class, 'TURNO_tur_id', 'tur_id');
    }

    public function persona()
    {
        return $this->hasOneThrough(
            Persona::class,
            Solicitante::class,
            'sol_id', // Foreign key on solicitante table
            'pers_doc', // Foreign key on persona table
            'SOLICITANTE_sol_id', // Local key on turno table
            'PERSONA_pers_doc' // Local key on solicitante table
        );
    }
}
