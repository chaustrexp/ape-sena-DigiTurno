<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Atencion;

class PantallaController extends Controller
{
    public function index()
    {
        // Turno que está siendo atendido actualmente (con hora_fin null)
        $atencionActual = Atencion::whereNull('atnc_hora_fin')
                                  ->with(['turno.persona', 'asesor'])
                                  ->latest('atnc_hora_inicio')
                                  ->first();

        $persona = $atencionActual && $atencionActual->turno->persona ? $atencionActual->turno->persona : null;
        $nombreCompleto = $persona ? "{$persona->pers_nombres} {$persona->pers_apellidos}" : 'Ciudadano';

        $turnoActual = $atencionActual ? (object)[
            'tur_numero' => $atencionActual->turno->tur_numero,
            'ciudadano' => $nombreCompleto,
            'modulo' => $atencionActual->ASESOR_ase_id,
            'ase_foto' => $atencionActual->asesor->ase_foto ?? 'images/foto de perfil.jpg'
        ] : null;

        // Turnos en espera (Ordenados por prioridad SENA: Víctima > Prioritario > General)
        $turnosEnEspera = Turno::whereDate('tur_hora_fecha', now()->today())
                                ->whereDoesntHave('atencion')
                                ->orderByRaw("CASE 
                                    WHEN tur_tipo = 'Victimas' THEN 1 
                                    WHEN tur_tipo = 'Prioritario' THEN 2 
                                    ELSE 3 END ASC")
                                ->orderBy('tur_id', 'asc')
                                ->get();

        return view('pantalla.index', compact('turnoActual', 'turnosEnEspera'));
    }

    public function getData()
    {
        // Turno que está siendo atendido actualmente
        $atencionActual = Atencion::whereNull('atnc_hora_fin')
                                  ->with(['turno.persona', 'asesor'])
                                  ->latest('atnc_hora_inicio')
                                  ->first();

        $persona = $atencionActual && $atencionActual->turno->persona ? $atencionActual->turno->persona : null;
        $nombreCompleto = $persona ? "{$persona->pers_nombres} {$persona->pers_apellidos}" : 'Ciudadano';

        $turnoActual = $atencionActual ? [
            'tur_id' => $atencionActual->turno->tur_id,
            'tur_numero' => $atencionActual->turno->tur_numero,
            'ciudadano' => $nombreCompleto,
            'modulo' => $atencionActual->ASESOR_ase_id,
            'ase_foto' => $atencionActual->asesor->ase_foto ? asset($atencionActual->asesor->ase_foto) : asset('images/foto de perfil.jpg'),
            'atnc_id' => $atencionActual->atnc_id
        ] : null;

        // Turnos en espera
        $turnosEnEspera = Turno::whereDate('tur_hora_fecha', now()->today())
                                ->whereDoesntHave('atencion')
                                ->orderByRaw("CASE 
                                    WHEN tur_tipo = 'Victimas' THEN 1 
                                    WHEN tur_tipo = 'Prioritario' THEN 2 
                                    ELSE 3 END ASC")
                                ->orderBy('tur_id', 'asc')
                                ->get()
                                ->map(function($t) {
                                    return [
                                        'tur_id' => $t->tur_id,
                                        'tur_numero' => $t->tur_numero,
                                        'tur_tipo' => $t->tur_tipo
                                    ];
                                });

        return response()->json([
            'turnoActual' => $turnoActual,
            'turnosEnEspera' => $turnosEnEspera
        ]);
    }
}
