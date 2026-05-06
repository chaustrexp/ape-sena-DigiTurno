<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $table = 'atencion';
    protected $primaryKey = 'atnc_id';
    public $timestamps = false;

    protected $fillable = [
        'atnc_hora_inicio', 'atnc_hora_fin', 'atnc_tipo', 'observaciones', 'ASESOR_ase_id', 'TURNO_tur_id'
    ];

    protected $casts = [
        'atnc_hora_inicio' => 'datetime',
        'atnc_hora_fin' => 'datetime',
    ];

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'ASESOR_ase_id', 'ase_id');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'TURNO_tur_id', 'tur_id');
    }
}
