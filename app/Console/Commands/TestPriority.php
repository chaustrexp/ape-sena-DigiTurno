<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Turno;
use App\Models\Atencion;
use App\Models\Asesor;
use App\Repositories\TurnoRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TestPriority extends Command
{
    protected $signature = 'test:priority';
    protected $description = 'Verifica la lógica de prioridad 3:1 y roles';

    public function handle()
    {
        $this->info("Iniciando prueba de prioridad...");

        // Limpiar datos previos de hoy para la prueba
        Atencion::whereHas('turno', function($q) {
            $q->whereDate('tur_hora_fecha', now()->toDateString());
        })->delete();
        Turno::whereDate('tur_hora_fecha', now()->toDateString())->delete();
        Cache::forget('prioritario_counter');

        $this->info("1. Generando 100 turnos de prueba...");
        
        // Crear una persona y solicitante base para las pruebas
        $persona = \App\Models\Persona::firstOrCreate(
            ['pers_doc' => '10203040'],
            ['pers_tipodoc' => 'CC', 'pers_nombres' => 'Ciudadano', 'pers_apellidos' => 'Prueba']
        );
        $solicitante = \App\Models\Solicitante::firstOrCreate(
            ['PERSONA_pers_doc' => $persona->pers_doc],
            ['sol_id' => 1]
        );

        // Generar mezcla de turnos para Role 2 (General/Prioritario)
        for ($i = 1; $i <= 20; $i++) {
            Turno::create([
                'tur_numero' => "G-$i",
                'tur_perfil' => 'General',
                'tur_estado' => 'Espera',
                'tur_hora_fecha' => now()->subMinutes(100 - $i),
                'SOLICITANTE_sol_id' => $solicitante->sol_id,
            ]);
            Turno::create([
                'tur_numero' => "P-$i",
                'tur_perfil' => 'Prioritario',
                'tur_estado' => 'Espera',
                'tur_hora_fecha' => now()->subMinutes(100 - $i),
                'SOLICITANTE_sol_id' => $solicitante->sol_id,
            ]);
        }

        // Generar mezcla para Role 1 (Víctimas/Empresarios)
        for ($i = 1; $i <= 10; $i++) {
            Turno::create([
                'tur_numero' => "V-$i",
                'tur_perfil' => 'Victima',
                'tur_estado' => 'Espera',
                'tur_hora_fecha' => now()->subMinutes(50 - $i),
                'SOLICITANTE_sol_id' => $solicitante->sol_id,
            ]);
            Turno::create([
                'tur_numero' => "E-$i",
                'tur_perfil' => 'Empresario',
                'tur_estado' => 'Espera',
                'tur_hora_fecha' => now()->subMinutes(50 - $i),
                'SOLICITANTE_sol_id' => $solicitante->sol_id,
            ]);
        }

        $repo = new TurnoRepository();
        
        // Simular Asesor Role 2 (OT)
        $asesorOT = Asesor::where('ase_tipo_asesor', 'OT')->first() ?? Asesor::create(['ase_tipo_asesor' => 'OT', 'ase_correo' => 'test_ot@example.com', 'PERSONA_pers_doc' => '1']);
        
        $this->info("\n2. Probando Relación 3:1 (Role 2)...");
        $results = [];
        for ($i = 1; $i <= 8; $i++) {
            $atencion = $repo->callNextTurn($asesorOT);
            $perfil = $atencion->turno->tur_perfil;
            $results[] = $perfil;
            $counter = Cache::get('prioritario_counter', 0);
            $this->line("Llamada $i: $perfil (#{$atencion->turno->tur_numero}) [Counter: $counter]");
        }

        // Esperamos: P, P, P, G, P, P, P, G
        $expected = ['Prioritario', 'Prioritario', 'Prioritario', 'General', 'Prioritario', 'Prioritario', 'Prioritario', 'General'];
        if ($results === $expected) {
            $this->info("✅ Lógica 3:1 CORRECTA");
        } else {
            $this->error("❌ Lógica 3:1 FALLIDA");
            $this->line("Esperado: " . implode(', ', $expected));
            $this->line("Obtenido: " . implode(', ', $results));
        }

        // Simular Asesor Role 1 (OV)
        $asesorOV = Asesor::where('ase_tipo_asesor', 'OV')->first() ?? Asesor::create(['ase_tipo_asesor' => 'OV', 'ase_correo' => 'test_ov@example.com', 'PERSONA_pers_doc' => '2']);
        
        $this->info("\n3. Probando Prioridad Empresario > Victima (Role 1)...");
        $results = [];
        for ($i = 1; $i <= 4; $i++) {
            $atencion = $repo->callNextTurn($asesorOV);
            $perfil = $atencion->turno->tur_perfil;
            $results[] = $perfil;
            $this->line("Llamada $i: $perfil (#{$atencion->turno->tur_numero})");
        }

        // Esperamos: Empresario, Empresario, ... (hasta que se acaben)
        if ($results[0] === 'Empresario' && $results[1] === 'Empresario') {
            $this->info("✅ Prioridad Rol 1 CORRECTA");
        } else {
            $this->error("❌ Prioridad Rol 1 FALLIDA");
        }

        $this->info("\nPruebas finalizadas.");
    }
}
