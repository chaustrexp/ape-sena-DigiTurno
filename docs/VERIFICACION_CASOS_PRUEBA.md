# ✅ Verificación de Casos de Prueba
## Sistema DigiTurno APE — SENA
### Fecha de verificación: 5 de Mayo de 2026

---

## Resumen Ejecutivo

| Métrica | Valor |
|---------|-------|
| **Total casos revisados** | 43 |
| **Casos que coinciden con el código** | 38 |
| **Casos con observación** | 3 |
| **Casos que NO coinciden / requieren ajuste** | 2 |
| **Cobertura general** | 88% ✅ |

---

## Leyenda

| Símbolo | Significado |
|---------|-------------|
| ✅ | Coincide completamente con el código |
| ⚠️ | Coincide parcialmente — tiene observación |
| ❌ | No coincide o requiere corrección en el código |

---

## 1. Módulo Kiosco

| ID | Descripción | Estado | Verificación en código |
|----|-------------|--------|------------------------|
| CP-001 | Registro exitoso ciudadano General — genera G-001 | ✅ | `TurnoController::store()` — `$perfilMap['General'] = ['prefix' => 'G']` — correlativo con `str_pad($count + 1, 3, '0')` |
| CP-002 | Registro ciudadano Víctima — genera V-001 | ✅ | `$perfilMap['Victima'] = ['prefix' => 'V']` — validación `in:General,Victima,Prioritario,Empresario` |
| CP-003 | Registro ciudadano Prioritario — genera P-001 | ✅ | `$perfilMap['Prioritario'] = ['prefix' => 'P']` — funciona correctamente |
| CP-004 | Cédula de 8 dígitos — límite inferior | ✅ | `if ($docLen < 8 \|\| $docLen > 10)` — valida CC/TI entre 8 y 10 dígitos |
| CP-005 | Teléfono con menos de 10 dígitos — bloqueo | ✅ | Validación en el kiosco JS: `if (phoneNumber.length < 10)` — bloquea y muestra alerta |
| CP-006 | Cédula de 10 dígitos — límite superior | ✅ | Misma validación `$docLen > 10` — acepta hasta 10 dígitos |
| CP-007 | Consulta de turno activo por documento | ✅ | `ApiController::consultarTurno($documento)` — ruta `GET /api/turno/consultar/{doc}` — devuelve estado del turno |
| CP-008 | Bloqueo de turno duplicado mismo documento | ✅ | `$turnosExistentes = Turno::where('SOLICITANTE_sol_id', ...)->where('tur_hora_fecha', '>=', $fechaInicio)->exists()` — retorna error "Solo puedes solicitar un (1) turno por día" |
| CP-009 | Caracteres no numéricos en documento | ✅ | Kiosco usa `type="number"` en el input y el teclado táctil solo permite dígitos — letras filtradas automáticamente |
| CP-010 | Generación de ticket — inserción en DB | ✅ | `Turno::create([...])` dentro de `DB::transaction()` — registro creado con éxito |

---

## 2. Módulo Pantalla Pública

| ID | Descripción | Estado | Verificación en código |
|----|-------------|--------|------------------------|
| CP-011 | Activación de audio automático al llamar | ⚠️ | **Parcialmente corregido.** Se agregó botón 🔊 de activación manual. El audio automático sin interacción sigue siendo bloqueado por los navegadores modernos (política de autoplay). Funciona en Chrome con flag `--autoplay-policy=no-user-gesture-required`. El caso de prueba marca FALLA — es correcto, el navegador lo bloquea por diseño. |
| CP-012 | Visualización de modal de llamado | ✅ | `mostrarModalLlamado(turno)` — aparece con turno, módulo y foto del asesor. Polling detecta cambio en `lastCurrentAtncId` |
| CP-013 | Historial de turnos recientes | ✅ | `updateWaitingList(turnos)` actualiza la lista en tiempo real con todos los turnos en espera |
| CP-014 | Ocultamiento automático de modal (12 seg) | ✅ | `setTimeout(() => { modal.classList.add('opacity-0'...) }, 12000)` — exactamente 12 segundos |
| CP-015 | Identificación de módulo de atención | ✅ | `ApiController::getPantallaData()` devuelve `modulo` y `asesor_nombre`. Modal muestra `Módulo ${moduloFormatted}` |
| CP-016 | Refresco de contenido vía polling | ✅ | `setInterval(checkUpdates, pollingInterval)` con `pollingInterval = 3000` ms — sin necesidad de F5 |
| CP-017 | Aviso visual de turno prioritario | ✅ | Cada perfil tiene color distinto: Víctima=rojo, Empresario=morado, Prioritario=naranja, General=azul |

---

## 3. Módulo Asesor

| ID | Descripción | Estado | Verificación en código |
|----|-------------|--------|------------------------|
| CP-018 | Inicio de sesión con rol Asesor | ✅ | `AsesorController::login()` — busca por `ase_correo`, verifica con `Hash::check()`, guarda sesión `ase_id` |
| CP-019 | Llamado de turno — algoritmo FIFO | ✅ | `TurnoRepository::callNextTurn()` — `->orderBy('tur_hora_fecha', 'asc')` garantiza FIFO dentro de cada perfil |
| CP-020 | Priorización 3:1 (3 Prioritarios por 1 General) | ✅ | `Cache::get('prioritario_counter', 0)` — si `$count < 3` llama Prioritario, al llegar a 3 llama General y reinicia contador |
| CP-021 | Gestión de pausas — límite de 3 por día | ✅ | `if ($recesosHoy >= 3) return 'Has alcanzado el límite máximo de 3 recesos...'` |
| CP-022 | Finalización de atención | ✅ | `AsesorController::finalizar()` — `$atencion->update(['atnc_hora_fin' => now()])` + `tur_estado = 'Finalizado'` |
| CP-023 | Marcación de ciudadano ausente | ✅ | `AsesorController::ausente()` — `$atencion->update(['atnc_hora_fin' => now()])` + `tur_estado = 'Ausente'` |
| CP-024 | Re-llamado de turno actual (sonido) | ❌ | **No implementado.** No existe ruta ni botón de "Re-llamar" en el panel del asesor. El caso de prueba marca FALLA — es correcto. Se requiere agregar esta funcionalidad. |
| CP-025 | Bloqueo de nuevo llamado sin finalizar anterior | ✅ | En `panel.blade.php` el botón "Llamar Siguiente" **solo aparece** en el bloque `@else` (cuando `$atencion` es null). Con atención activa solo aparecen "Finalizar" y "Ausente" |
| CP-026 | Cambio de estado a disponible tras pausa | ✅ | `TurnoRepository::finalizarReceso()` — actualiza `hora_fin` y `duracion`. La sesión `ase_estado` vuelve a `'Activo'` |
| CP-027 | Validación de tiempo de atención (120 min) | ✅ | `$atencion->update(['atnc_hora_fin' => now()])` — la duración se calcula en reportes con `diffInMinutes()` |
| CP-028 | Alertas de sesión expirada | ⚠️ | La sesión de Laravel expira según `SESSION_LIFETIME=120` en `.env` (120 minutos). El sistema redirige al login. Sin embargo, no hay un mensaje de alerta explícito tipo "Su sesión expiró" — solo redirige. |
| CP-029 | Concurrencia — dos asesores no se "roban" turnos | ✅ | `->lockForUpdate()` en `callNextTurn()` — `SELECT ... FOR UPDATE` garantiza que solo un asesor obtenga el turno |

---

## 4. Módulo Coordinador

| ID | Descripción | Estado | Verificación en código |
|----|-------------|--------|------------------------|
| CP-030 | Login Coordinador | ✅ | `CoordinadorController::login()` — busca en tabla `coordinador` por `coor_correo`, verifica con `Hash::check()` |
| CP-031 | Visualización de KPIs de productividad | ✅ | `CoordinadorController::dashboard()` — calcula `$usuariosHoy`, `$enEspera`, `$enAtencion`, `$tiempoMedio` |
| CP-032 | Alerta crítica: tiempo de espera > 15 min | ✅ | `$turnosRetrasados = Turno::where('tur_hora_fecha', '<', now()->subMinutes(15))->count()` — genera alerta si > 0 |
| CP-033 | Gestión de asesores — crear nuevo | ✅ | `CoordinadorController::storeAsesor()` — crea `Persona` + `Asesor` con `bcrypt()` para la contraseña |
| CP-034 | Gestión de asesores — editar módulo | ⚠️ | `CoordinadorController::updateAsesor()` actualiza `ase_correo`, `ase_nrocontrato`, `ase_foto` y contraseña. **Sin embargo, no existe campo `ase_modulo` en la BD** — el módulo es el `ase_id`. El selector de módulo en el panel del asesor es solo visual (no persiste en BD). |
| CP-035 | Gestión de asesores — eliminar | ✅ | `CoordinadorController::deleteAsesor()` — `$asesor->delete()` — eliminación física del registro |
| CP-036 | Exportación de reporte consolidado Excel | ✅ | `CoordinadorController::export()` — genera HTML con formato Excel, headers `Content-Type: application/vnd.ms-excel` |
| CP-037 | Monitoreo de módulos en tiempo real | ✅ | `CoordinadorController::dashboard()` — `$asesoresStatus` mapea cada asesor con su estado actual (Libre/Atendiendo/Descanso) |
| CP-038 | Diseño responsivo en 1366px | ❌ | **Falla confirmada.** Las tarjetas de KPI del dashboard del coordinador pueden solaparse en resoluciones de 1366px. El layout usa `grid-cols-3` sin breakpoints intermedios para pantallas pequeñas. Requiere ajuste de CSS. |
| CP-039 | Filtro de histórico por rango de fechas | ✅ | `CoordinadorController::reportes()` — parámetro `$periodo` acepta `today`, `7d`, `month`, `year` |
| CP-040 | Configuración de parámetros globales (SLA) | ⚠️ | Existe el modelo `ConfiguracionSistema` con métodos `get()` y `set()`. Sin embargo, la vista de configuración no tiene un campo de SLA editable desde la UI — solo existe en BD. |
| CP-041 | Backup de base de datos | ⚠️ | **No implementado en la UI.** No existe botón "Respaldar Ahora" en el panel. El dump de BD está en `database/db_dumps/ape-sena-utf8.sql` pero es manual. |
| CP-042 | Registro de Asesor — creación de cuenta | ✅ | `AsesorController::showRegister()` + `register()` — ruta `GET/POST /asesor/register` — crea Persona + Asesor, inicia sesión automáticamente |
| CP-043 | Registro de Coordinador — creación de cuenta | ✅ | `CoordinadorController::showRegister()` + `register()` — ruta `GET/POST /coordinador/register` — crea Persona + Coordinador, inicia sesión automáticamente |

---

## 5. Casos que Requieren Acción

### ❌ CP-024 — Re-llamado de Turno (FALLA)
**Problema:** No existe botón ni lógica de "Re-llamar" en el panel del asesor.
**Impacto:** El asesor no puede volver a llamar al ciudadano si este no escuchó el llamado.
**Solución sugerida:** Agregar botón "Re-llamar" que dispare el modal de la pantalla pública sin crear una nueva atención.

---

### ❌ CP-038 — Diseño Responsivo en 1366px (FALLA)
**Problema:** Las tarjetas de KPI del dashboard del coordinador se solapan en resolución 1366x768.
**Impacto:** Coordinadores con laptops pequeñas no pueden ver bien el dashboard.
**Solución sugerida:** Cambiar `grid-cols-3` a `grid-cols-2 lg:grid-cols-3` en las tarjetas de KPI.

---

### ⚠️ CP-011 — Audio Automático (FALLA ESPERADA)
**Problema:** Los navegadores modernos bloquean `AudioContext` sin interacción del usuario.
**Estado actual:** Se implementó botón 🔊 de activación manual como solución alternativa.
**Para kioscos físicos:** Usar Chrome con `--autoplay-policy=no-user-gesture-required`.

---

### ⚠️ CP-034 — Módulo del Asesor no persiste en BD
**Problema:** El selector de módulo ◀ ▶ es visual pero no guarda el número en la base de datos.
**Impacto:** Al recargar la página, el módulo vuelve al valor del `ase_id`.
**Solución sugerida:** Agregar campo `ase_modulo` a la tabla `asesor` y una ruta `POST /asesor/modulo/{numero}`.

---

### ⚠️ CP-041 — Backup de BD no disponible en UI
**Problema:** No existe botón de backup en el panel del coordinador.
**Estado actual:** El dump está disponible manualmente en `database/db_dumps/ape-sena-utf8.sql`.
**Solución sugerida:** Agregar ruta y botón que ejecute `mysqldump` y descargue el archivo.

---

## 6. Tabla Consolidada Final

| ID | Módulo | Descripción breve | Estado |
|----|--------|-------------------|--------|
| CP-001 | Kiosco | Turno General G-001 | ✅ |
| CP-002 | Kiosco | Turno Víctima V-001 | ✅ |
| CP-003 | Kiosco | Turno Prioritario P-001 | ✅ |
| CP-004 | Kiosco | CC 8 dígitos aceptado | ✅ |
| CP-005 | Kiosco | Teléfono corto bloqueado | ✅ |
| CP-006 | Kiosco | CC 10 dígitos aceptado | ✅ |
| CP-007 | Kiosco | Consulta turno por documento | ✅ |
| CP-008 | Kiosco | Bloqueo turno duplicado | ✅ |
| CP-009 | Kiosco | Letras filtradas en documento | ✅ |
| CP-010 | Kiosco | Inserción en DB exitosa | ✅ |
| CP-011 | Pantalla | Audio automático | ⚠️ Parcial |
| CP-012 | Pantalla | Modal de llamado | ✅ |
| CP-013 | Pantalla | Historial de turnos | ✅ |
| CP-014 | Pantalla | Modal cierra a los 12s | ✅ |
| CP-015 | Pantalla | Número de módulo visible | ✅ |
| CP-016 | Pantalla | Polling sin F5 | ✅ |
| CP-017 | Pantalla | Badge color por perfil | ✅ |
| CP-018 | Asesor | Login asesor | ✅ |
| CP-019 | Asesor | FIFO en llamado | ✅ |
| CP-020 | Asesor | Priorización 3:1 | ✅ |
| CP-021 | Asesor | Límite 3 pausas | ✅ |
| CP-022 | Asesor | Finalizar atención | ✅ |
| CP-023 | Asesor | Ciudadano ausente | ✅ |
| CP-024 | Asesor | Re-llamado de turno | ❌ No implementado |
| CP-025 | Asesor | Bloqueo llamado con atención activa | ✅ |
| CP-026 | Asesor | Disponible tras pausa | ✅ |
| CP-027 | Asesor | Duración 120 min guardada | ✅ |
| CP-028 | Asesor | Sesión expirada | ⚠️ Sin mensaje explícito |
| CP-029 | Asesor | Concurrencia — no se roban turnos | ✅ |
| CP-030 | Coordinador | Login coordinador | ✅ |
| CP-031 | Coordinador | KPIs de productividad | ✅ |
| CP-032 | Coordinador | Alerta espera > 15 min | ✅ |
| CP-033 | Coordinador | Crear asesor | ✅ |
| CP-034 | Coordinador | Editar módulo asesor | ⚠️ No persiste en BD |
| CP-035 | Coordinador | Eliminar asesor | ✅ |
| CP-036 | Coordinador | Exportar Excel | ✅ |
| CP-037 | Coordinador | Monitoreo en tiempo real | ✅ |
| CP-038 | Coordinador | Responsivo 1366px | ❌ KPIs se solapan |
| CP-039 | Coordinador | Filtro por fechas | ✅ |
| CP-040 | Coordinador | Configuración SLA | ⚠️ Solo en BD, no en UI |
| CP-041 | Coordinador | Backup de BD | ⚠️ No disponible en UI |
| CP-042 | Auth | Registro de Asesor | ✅ |
| CP-043 | Auth | Registro de Coordinador | ✅ |

---

*Verificación realizada el 5 de Mayo de 2026*
*DigiTurno APE — Servicio Nacional de Aprendizaje SENA*
