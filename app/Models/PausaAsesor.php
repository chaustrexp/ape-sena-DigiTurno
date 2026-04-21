<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PausaAsesor extends Model
{
    protected $table = 'pausas_asesor';

    protected $fillable = [
        'ASESOR_ase_id',
        'hora_inicio',
        'hora_fin',
        'duracion',
    ];

    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fin'    => 'datetime',
    ];

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'ASESOR_ase_id', 'ase_id');
    }
}
