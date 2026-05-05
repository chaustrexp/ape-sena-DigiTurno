<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Asesor;
use App\Models\Turno;
use App\Models\Atencion;
use App\Models\Persona;
use App\Models\Solicitante;

class DigiturnoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TÉCNICA 1: Partición de Equivalencia (Equivalence Partitioning)
     * Escenario: Registro de un nuevo turno en el Kiosco.
     */

    /** @test */
    public function kiosco_valid_turn_generation()
    {
        // Caso de Prueba A (Válido): Datos correctos
        $response = $this->post('/turno/solicitar', [
            'pers_doc' => '123456789',
            'pers_tipodoc' => 'CC',
            'pers_nombres' => 'Juan',
            'pers_apellidos' => 'Pérez',
            'tur_perfil' => 'General',
            'tur_tipo_atencion' => 'Normal',
            'tur_servicio' => 'Orientacion',
            'tur_telefono' => '3001234567'
        ]);

        $response->assertStatus(302); // Redirección exitosa (back)
        $this->assertDatabaseHas('persona', ['pers_doc' => '123456789']);
        $this->assertDatabaseHas('turno', ['tur_perfil' => 'General']);
    }

    /** @test */
    public function kiosco_invalid_turn_generation_missing_name()
    {
        // Caso de Prueba B (Inválido): Falta el nombre
        $response = $this->post('/turno/solicitar', [
            'pers_doc' => '123456789',
            'pers_tipodoc' => 'CC',
            'pers_apellidos' => 'Pérez',
            'tur_perfil' => 'General',
            'tur_tipo_atencion' => 'Normal',
            'tur_servicio' => 'Orientacion'
        ]);

        $response->assertSessionHasErrors(['pers_nombres']);
    }

    /**
     * TÉCNICA 2: Análisis de Valores Límite (Boundary Value Analysis)
     * Escenario: Longitud del nombre del ciudadano (max: 100).
     */

    /** @test */
    public function kiosco_name_length_limit_boundary()
    {
        // Caso Límite Superior (100 caracteres) - Válido
        $longName = str_repeat('A', 100);
        $response = $this->post('/turno/solicitar', [
            'pers_doc' => '123456789',
            'pers_tipodoc' => 'CC',
            'pers_nombres' => $longName,
            'pers_apellidos' => 'Prueba',
            'tur_perfil' => 'General',
            'tur_tipo_atencion' => 'Normal',
            'tur_servicio' => 'Orientacion'
        ]);
        $response->assertSessionHasNoErrors();

        // Caso Fuera de Rango (101 caracteres) - Inválido
        $tooLongName = str_repeat('A', 101);
        $response = $this->post('/turno/solicitar', [
            'pers_doc' => '987654321',
            'pers_tipodoc' => 'CC',
            'pers_nombres' => $tooLongName,
            'pers_apellidos' => 'Prueba',
            'tur_perfil' => 'General',
            'tur_tipo_atencion' => 'Normal',
            'tur_servicio' => 'Orientacion'
        ]);
        $response->assertSessionHasErrors(['pers_nombres']);
    }

    /**
     * TÉCNICA 3: Pruebas de Transición de Estados
     * Escenario: Impedir receso si hay atención activa.
     */

    /** @test */
    public function asesor_cannot_start_recess_with_active_attention()
    {
        // 1. Crear Asesor y Persona
        $persona = Persona::create([
            'pers_doc' => '101010',
            'pers_tipodoc' => 'CC',
            'pers_nombres' => 'Asesor',
            'pers_apellidos' => 'Pruebas'
        ]);
        
        $asesor = Asesor::create([
            'ase_id' => 10,
            'ase_correo' => 'asesor@test.com',
            'ase_password' => 'secret',
            'ase_tipo_asesor' => 'OT',
            'PERSONA_pers_doc' => '101010'
        ]);

        // Autenticar en sesión
        $this->withSession(['ase_id' => $asesor->ase_id]);

        // 2. Crear un turno y una atención activa (sin hora_fin)
        $solicitante = Solicitante::create(['PERSONA_pers_doc' => '101010', 'sol_tipo' => 'General']);
        $turno = Turno::create([
            'tur_hora_fecha' => now(),
            'tur_numero' => 'G-001',
            'tur_estado' => 'Atendiendo',
            'SOLICITANTE_sol_id' => $solicitante->sol_id
        ]);

        Atencion::create([
            'atnc_hora_inicio' => now(),
            'ASESOR_ase_id' => $asesor->ase_id,
            'TURNO_tur_id' => $turno->tur_id,
            'atnc_tipo' => 'General'
        ]);

        // 3. Intentar iniciar receso
        $response = $this->post('/asesor/receso/iniciar');

        // Verificar que se rechaza la transición
        $response->assertSessionHas('error', 'No puedes iniciar un receso mientras tienes una atención activa. Finaliza la atención primero.');
        $this->assertDatabaseMissing('pausas_asesores', ['ASESOR_ase_id' => $asesor->ase_id]);
    }

    /** @test */
    public function asesor_cannot_exceed_daily_recess_limit()
    {
        $persona = Persona::create(['pers_doc' => '202020', 'pers_tipodoc' => 'CC', 'pers_nombres' => 'A', 'pers_apellidos' => 'B']);
        $asesor = Asesor::create(['ase_id' => 11, 'ase_correo' => 'ase2@test.com', 'ase_password' => 'secret', 'ase_tipo_asesor' => 'OT', 'PERSONA_pers_doc' => '202020']);
        $this->withSession(['ase_id' => $asesor->ase_id]);

        // Crear 3 recesos previos hoy
        for ($i = 0; $i < 3; $i++) {
            \App\Models\PausaAsesor::create([
                'ASESOR_ase_id' => $asesor->ase_id,
                'hora_inicio' => now(),
                'hora_fin' => now()->addMinutes(15)
            ]);
        }

        // Intentar el 4to receso
        $response = $this->post('/asesor/receso/iniciar');
        $response->assertSessionHas('error', 'Has alcanzado el límite máximo de 3 recesos permitidos por día.');
    }

    /** @test */
    public function kiosco_document_length_validation()
    {
        // Caso: 5 dígitos (Error)
        $response = $this->post('/turno/solicitar', ['pers_doc' => '12345', 'pers_tipodoc' => 'CC', 'pers_nombres' => 'Test', 'pers_apellidos' => 'User', 'tur_perfil' => 'General', 'tur_tipo_atencion' => 'Normal', 'tur_servicio' => 'Orientacion']);
        $response->assertSessionHasErrors(['pers_doc']);

        // Caso: 6 dígitos (Válido)
        $response = $this->post('/turno/solicitar', ['pers_doc' => '123456', 'pers_tipodoc' => 'CC', 'pers_nombres' => 'Test', 'pers_apellidos' => 'User', 'tur_perfil' => 'General', 'tur_tipo_atencion' => 'Normal', 'tur_servicio' => 'Orientacion']);
        $response->assertSessionHasNoErrors();
    }
}
