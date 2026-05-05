# 📄 Documento de Cambios y Ajustes
## Sistema DigiTurno APE — SENA
### Fecha: 5 de Mayo de 2026

---

## 1. Información General

| Campo | Detalle |
|-------|---------|
| **Proyecto** | DigiTurno APE — Sistema Digital de Turnos SENA |
| **Repositorio** | https://github.com/SaraKerrigan2001/digiturno |
| **Framework** | Laravel 12 · PHP 8.2 |
| **Base de Datos** | MySQL / MariaDB |
| **Fecha de cambios** | 5 de Mayo de 2026 |
| **Total de archivos modificados** | 44 archivos |

---

## 2. Resumen Ejecutivo

Durante la jornada de hoy se realizaron ajustes integrales al sistema DigiTurno, abarcando los módulos de **Kiosco**, **Pantalla Pública**, **Panel del Asesor**, **Panel del Coordinador** y la capa de **Autenticación**. Los cambios se enfocaron en tres áreas principales:

1. **Reducción de tamaños visuales** — El kiosco y los dashboards se veían demasiado grandes en pantalla. Se redujeron fuentes, paddings y dimensiones de componentes en todos los módulos.
2. **Corrección de lógica de negocio** — Se corrigió el flujo de atención del asesor, la creación de asesores desde el coordinador y la asignación de turnos por tipo de asesor.
3. **Nuevas funcionalidades** — Registro de asesor y coordinador, selector de módulo, columna Módulo/Profesional en pantalla, tipo de asesor AT (Asesor Total) y sistema de sonido corregido.

---

## 3. Cambios por Módulo

---

### 3.1 Kiosco Digital (`resources/views/kiosco/index.blade.php`)

#### Problema
El kiosco se veía demasiado grande en pantalla. Los textos, botones y tarjetas ocupaban demasiado espacio vertical, obligando al usuario a hacer scroll en cada paso.

#### Cambios Realizados

| Elemento | Antes | Después |
|----------|-------|---------|
| Contenedor principal | `max-w-5xl · 90vh · 850px` | `max-w-4xl · 85vh · 720px` |
| Header — logo | `w-8 h-8` | `w-6 h-6` |
| Header — título | `text-xl` | `text-base` |
| Step 1 — título bienvenida | `text-6xl` | `text-5xl` |
| Step 1 — botón Empezar | `py-7` | `py-3.5` |
| Step 2 — título Tratamiento | `text-5xl` | `text-3xl` |
| Step 3 — tarjetas perfil | `p-5 · w-12 h-12` | `p-4 · w-10 h-10` |
| Step 3.5 — título Detalles | `text-5xl` | `text-3xl` |
| Step 3.5 — botones servicio | `p-6` | `p-4` |
| Step 4 — display documento | `text-6xl` | `text-4xl` |
| Step 4 — teclas numpad | `h-14` | `h-11` |
| Step 5 — display teléfono | `text-4xl` | `text-3xl` |
| Step 5 — teclas numpad | `h-16` | `h-12` |
| Step 6 — tarjetas canal | `p-5 · w-12 h-12` | `p-3.5 · w-10 h-10` |
| Footer | `py-4 · text-[10px]` | `py-2.5 · text-[9px]` |

#### Resultado
El kiosco ahora cabe completamente en pantalla sin scroll en cada paso, manteniendo la legibilidad y usabilidad táctil.

---

### 3.2 Pantalla Pública (`resources/views/pantalla/index.blade.php`)

#### Problemas Identificados
1. La columna **MÓDULO / PROFESIONAL** aparecía vacía — no mostraba el nombre del asesor ni el módulo asignado.
2. El **sonido de llamada** no funcionaba — el `AudioContext` se creaba pero el navegador lo suspendía por política de autoplay.
3. La **barra inferior negra** ("Siguiente en turno") mantenía el número del último turno atendido incluso después de que el asesor lo finalizara.
4. Los turnos en la lista eran demasiado grandes.

#### Cambios Realizados

**A. Columna Módulo / Profesional**

Se rediseñó cada fila de la lista de turnos con un layout de 5 columnas:
- Columnas 1-2: Icono de color + número de turno + estado
- Columnas 3-5: Módulo asignado + nombre del asesor (cuando está atendiendo) o "— En cola" (cuando espera)

El turno en atención ahora muestra:
- Foto del asesor
- Número de módulo (ej: `Módulo 04`)
- Nombre completo del asesor

**B. API actualizada** (`app/Http/Controllers/Api/ApiController.php`)

Se agregó el campo `asesor_nombre` a la respuesta JSON de la pantalla:

```json
{
  "turnoActual": {
    "tur_numero": "G-001",
    "modulo": 4,
    "asesor_nombre": "Juan Carlos Pérez",
    "ase_foto": "...",
    "tur_tipo": "General",
    "tur_perfil": "General"
  }
}
```

**C. Sistema de Sonido Corregido**

El problema raíz era que el `AudioContext` se creaba al cargar la página, pero los navegadores modernos lo suspenden hasta que el usuario interactúa. La solución implementada:

- Se agregó un **botón 🔊 visible** en la barra inferior para activar el audio manualmente
- Al hacer clic en el botón, el ícono cambia a 🔊 amarillo confirmando que el audio está activo
- El primer clic en cualquier parte de la página también activa el audio automáticamente
- Al cargar, se intenta activar automáticamente (funciona en Chrome con flags de kiosco)
- El chime mejorado usa un acorde ascendente Do-Mi-Sol con fade natural

**D. Barra inferior — Limpiar al finalizar turno**

Cuando el asesor finaliza un turno y no hay otro en atención, la función `updateCurrentTurnBox(null)` ahora:
1. Limpia la tarjeta de atención
2. Busca el primer turno en la lista de espera y actualiza el número en la barra
3. Si no hay turnos en espera, muestra `---`

**E. Reducción de tamaños en la lista**

| Elemento | Antes | Después |
|----------|-------|---------|
| Icono de turno | `w-16 h-16 / w-20 h-20` | `w-10 h-10 / w-12 h-12` |
| Número de turno | `text-3xl / text-5xl` | `text-lg / text-2xl` |
| Barra inferior — número | `text-4xl / text-6xl` | `text-2xl / text-4xl` |

---

### 3.3 Panel del Asesor (`resources/views/asesor/panel.blade.php`)

#### Problemas Identificados
1. El dashboard se veía muy grande — tarjetas con padding excesivo y textos enormes.
2. El botón **"Llamar Siguiente"** aparecía mientras había una atención activa, lo que podría crear dos atenciones simultáneas.
3. No se mostraba el **Módulo/Profesionalidad** del asesor en la tarjeta de atención.
4. No había forma de cambiar el número de módulo desde el panel.

#### Cambios Realizados

**A. Reducción de tamaños**

| Elemento | Antes | Después |
|----------|-------|---------|
| Grid gap | `gap-10` | `gap-5` |
| Tarjeta azul | `rounded-[3rem] p-10` | `rounded-2xl p-6` |
| Número de turno | `text-7xl` | `text-4xl` |
| Nombre ciudadano | `text-3xl` | `text-xl` |
| Stats cards | `p-8 rounded-[2.5rem]` | `p-5 rounded-2xl` |
| Números en stats | `text-4xl` | `text-3xl` |
| Estado inactivo | `p-16 rounded-[3rem]` | `p-8 rounded-2xl` |
| Gráfico | `h-64` | `h-40` |

**B. Corrección de lógica de botones**

Cuando hay una atención activa, los botones disponibles son:
- ✅ **Finalizar Atención** — marca el turno como Finalizado
- 🚫 **Ciudadano Ausente** — marca el turno como Ausente

El botón "Llamar Siguiente" **solo aparece** cuando no hay atención activa. Esto evita que un asesor llame dos turnos simultáneamente.

**C. Módulo / Profesionalidad**

Debajo del número de turno en la tarjeta azul se muestra:
```
Módulo  [04]  ·  Orientador Técnico
```

Los valores posibles para el tipo de asesor son:
- `OT` → "Orientador Técnico"
- `OV` → "Orientador Víctimas"
- `AT` → "Asesor Total"

**D. Selector de Módulo con Flechas**

Se agregó un control en la esquina superior derecha de la tarjeta azul:

```
Módulo  ◀  04  ▶
```

- Rango: 1 a 20
- Los botones ◀ y ▶ incrementan o decrementan el número
- El número se actualiza visualmente en tiempo real

---

### 3.4 Layouts — Asesor y Coordinador

#### Problema
Los sidebars y headers eran demasiado anchos y altos, reduciendo el espacio disponible para el contenido principal.

#### Cambios en Layout Asesor (`resources/views/layouts/asesor.blade.php`)

| Elemento | Antes | Después |
|----------|-------|---------|
| Ancho sidebar | `w-72` | `w-56` |
| Padding sidebar | `px-8 py-10` | `px-5 py-6` |
| Logo | `h-12` | `h-8` |
| Título SENA APE | `text-xl` | `text-sm` |
| Items de navegación | `px-5 py-4` | `px-3 py-3` |
| Iconos nav | `text-lg` | `text-sm` |
| Header altura | `h-24 px-10` | `h-14 px-6` |
| Botones receso | `py-4` | `py-2.5` |
| Padding contenido | `p-10` | `p-6` |
| Footer | `h-10` | `h-8` |

#### Cambios en Layout Coordinador (`resources/views/layouts/coordinador.blade.php`)

| Elemento | Antes | Después |
|----------|-------|---------|
| Header padding | `px-8 py-4` | `px-6 py-2.5` |
| Logo | `h-10` | `h-7` |
| Ancho sidebar | `w-64` | `w-52` |
| Items de navegación | `p-3 · w-8 h-8` | `p-2.5 · w-7 h-7` |
| Textos nav | `text-sm` | `text-xs` |
| Padding contenido | `p-6 md:p-8` | `p-4 md:p-6` |
| Avatar usuario | `w-10 h-10` | `w-8 h-8` |

---

### 3.5 Autenticación — Registro de Asesor y Coordinador

#### Problema
No existía un flujo de registro. Los asesores y coordinadores solo podían ser creados manualmente en la base de datos o por el coordinador desde el panel.

#### Nuevas Vistas Creadas

**Registro Asesor** (`resources/views/asesor/register.blade.php`)
- Mismo estilo visual que el login del asesor (fondo claro, video de YouTube)
- Campos: Tipo documento, Nro. documento, Nombres, Apellidos, Teléfono, Fecha nacimiento
- Campos de asesor: Nro. contrato, Tipo asesor (OT/OV), Correo, Contraseña, Confirmar contraseña
- Al registrarse, inicia sesión automáticamente y redirige al panel

**Registro Coordinador** (`resources/views/coordinador/register.blade.php`)
- Mismo estilo visual que el login del coordinador (fondo oscuro)
- Campos: Tipo documento, Nro. documento, Nombres, Apellidos, Teléfono, Fecha nacimiento
- Campos de credenciales: Correo institucional, Contraseña, Confirmar contraseña
- Al registrarse, inicia sesión automáticamente y redirige al dashboard

#### Nuevas Rutas Agregadas (`routes/web.php`)

```
GET  /asesor/register       → Formulario de registro asesor
POST /asesor/register       → Procesar registro asesor

GET  /coordinador/register  → Formulario de registro coordinador
POST /coordinador/register  → Procesar registro coordinador
```

#### Métodos Agregados a los Controladores

**`AsesorController::showRegister()`** — Muestra el formulario
**`AsesorController::register()`** — Valida, crea Persona + Asesor, inicia sesión

**`CoordinadorController::showRegister()`** — Muestra el formulario
**`CoordinadorController::register()`** — Valida, crea Persona + Coordinador, inicia sesión

---

### 3.6 Gestión de Asesores desde el Coordinador

#### Problema 1 — Credenciales incorrectas al iniciar sesión
El método `storeAsesor()` guardaba `ase_tipo_asesor = 'Asesor'` (texto libre), pero la columna en la BD es un `ENUM('OT','OV')`. MySQL rechazaba el valor y el asesor quedaba con tipo vacío, impidiendo el login.

#### Solución
Se corrigió el controlador para guardar el valor correcto del ENUM y se agregó validación:

```php
'ase_tipo_asesor' => 'required|in:OT,OV,AT',
```

#### Problema 2 — Faltaba el campo tipo de asesor en el formulario
El modal "Nuevo Asesor" no tenía selector de tipo, por lo que siempre enviaba el valor por defecto incorrecto.

#### Solución
Se agregaron 3 opciones de radio button visuales al modal:

| Opción | Descripción | Perfiles |
|--------|-------------|---------|
| **OT** — Orientador Técnico | Ícono azul | General · Prioritario |
| **OV** — Orientador de Víctimas | Ícono naranja | Víctima · Empresario |
| **AT** — Asesor Total | Ícono verde ⭐ | Los 4 perfiles |

---

### 3.7 Nuevo Tipo de Asesor: AT (Asesor Total)

#### Problema
Al crear un asesor nuevo, el sistema solo permitía OT u OV, cada uno atendiendo solo 2 de los 4 perfiles. Se solicitó que un asesor pudiera atender los 4 tipos de atención.

#### Cambios Realizados

**A. Migración de base de datos**

Se creó la migración `2026_05_05_000001_add_at_to_ase_tipo_asesor.php`:

```sql
ALTER TABLE asesor 
MODIFY ase_tipo_asesor ENUM('OT','OV','AT') NOT NULL DEFAULT 'OT';
```

La migración fue ejecutada exitosamente.

**B. TurnoRepository — Nueva lógica para AT**

```php
} elseif ($tipoAsesor === 'AT') {
    // Asesor Total: atiende todos los perfiles con prioridad completa
    $turno = $query->whereIn('tur_perfil', ['Victima', 'Empresario', 'Prioritario', 'General'])
                   ->orderByRaw("CASE
                       WHEN tur_perfil = 'Victima'     THEN 1
                       WHEN tur_perfil = 'Empresario'  THEN 2
                       WHEN tur_perfil = 'Prioritario' THEN 3
                       ELSE 4 END ASC")
                   ->orderBy('tur_hora_fecha', 'asc')
                   ->lockForUpdate()
                   ->first();
}
```

**C. Orden de prioridad para AT**

| Prioridad | Perfil |
|-----------|--------|
| 1 | Víctima |
| 2 | Empresario |
| 3 | Prioritario |
| 4 | General |

---

### 3.8 Limpieza de Archivos

Se eliminaron archivos que no tenían uso en el proyecto:

| Archivo eliminado | Razón |
|-------------------|-------|
| `app/Models/User.php` | Modelo Laravel por defecto, no usado en el proyecto |
| `database/factories/UserFactory.php` | Factory del modelo User eliminado |
| `resources/views/docs/casos_prueba_pdf.blade.php` | Vista sin referencia en ningún controlador |

---

## 4. Correcciones de Bugs

| # | Bug | Archivo | Corrección |
|---|-----|---------|------------|
| 1 | `now()->today()` no es un método válido | `AsesorController.php` | Cambiado a `today()` |
| 2 | `now()->today()` en dashboard coordinador | `CoordinadorController.php` | Cambiado a `today()` (2 ocurrencias) |
| 3 | Asesor creado con `ase_tipo_asesor = 'Asesor'` (inválido) | `CoordinadorController.php` | Corregido a usar el valor del formulario |
| 4 | Botón "Llamar Siguiente" visible con atención activa | `panel.blade.php` | Eliminado del bloque `@if($atencion)` |
| 5 | Barra inferior mantiene número al finalizar turno | `pantalla/index.blade.php` | `updateCurrentTurnBox(null)` ahora limpia el número |
| 6 | Columna Módulo/Profesional vacía en pantalla | `ApiController.php` | Se agregó `asesor_nombre` a la respuesta JSON |
| 7 | Sonido no funciona por política de autoplay | `pantalla/index.blade.php` | AudioContext creado solo tras interacción del usuario |

---

## 5. Nuevas Funcionalidades

### 5.1 Registro de Asesor
- **URL:** `/asesor/register`
- **Descripción:** Formulario completo para que un asesor cree su cuenta con datos personales y credenciales
- **Flujo:** Persona → Asesor → Sesión automática → Redirige al panel

### 5.2 Registro de Coordinador
- **URL:** `/coordinador/register`
- **Descripción:** Formulario para que un coordinador cree su cuenta
- **Flujo:** Persona → Coordinador → Sesión automática → Redirige al dashboard

### 5.3 Selector de Módulo en Panel Asesor
- **Ubicación:** Esquina superior derecha de la tarjeta azul de atención
- **Funcionamiento:** Botones ◀ ▶ para cambiar el número de módulo (1-20)
- **Visualización:** El número se actualiza en tiempo real en la tarjeta y en el badge

### 5.4 Módulo/Profesional en Pantalla Pública
- **Descripción:** Cada fila de turno ahora muestra en la columna derecha el módulo y nombre del asesor asignado
- **Estado en espera:** Muestra "— En cola"
- **Estado atendiendo:** Muestra foto + número de módulo + nombre del asesor

### 5.5 Tipo de Asesor AT (Asesor Total)
- **Descripción:** Nuevo tipo de asesor que atiende los 4 perfiles de ciudadanos
- **Prioridad:** Víctima → Empresario → Prioritario → General
- **Migración:** ENUM ampliado de `('OT','OV')` a `('OT','OV','AT')`

### 5.6 Sistema de Sonido Mejorado
- **Botón de activación:** Ícono 🔊 en la barra inferior de la pantalla
- **Chime:** Acorde ascendente Do-Mi-Sol con fade natural
- **Voz:** *"Turno G 001, por favor diríjase al módulo 4"*
- **Compatibilidad:** Funciona en Chrome, Edge y Firefox tras interacción del usuario

---

## 6. Archivos Modificados

### Controladores
| Archivo | Cambios |
|---------|---------|
| `AsesorController.php` | Corrección `today()`, métodos `showRegister()` y `register()`, tipo AT en panel |
| `CoordinadorController.php` | Corrección `today()`, métodos `showRegister()` y `register()`, validación tipo asesor |
| `ApiController.php` | Agregar `asesor_nombre`, `tur_tipo`, `tur_perfil` a respuesta de pantalla |
| `PantallaController.php` | Actualización de relaciones con asesor |
| `TurnoController.php` | Ajustes menores |

### Repositorio
| Archivo | Cambios |
|---------|---------|
| `TurnoRepository.php` | Agregar lógica para tipo `AT` en `getWaitingForAsesor()` y `callNextTurn()` |

### Vistas
| Archivo | Cambios |
|---------|---------|
| `kiosco/index.blade.php` | Reducción completa de tamaños en todos los pasos |
| `pantalla/index.blade.php` | Módulo/Profesional, sonido corregido, barra inferior, tamaños reducidos |
| `asesor/panel.blade.php` | Reducción tamaños, lógica botones, selector módulo, tipo AT |
| `asesor/register.blade.php` | **NUEVO** — Vista de registro de asesor |
| `coordinador/register.blade.php` | **NUEVO** — Vista de registro de coordinador |
| `coordinador/modulos.blade.php` | Selector tipo asesor OT/OV/AT con radio buttons |
| `layouts/asesor.blade.php` | Reducción sidebar y header |
| `layouts/coordinador.blade.php` | Reducción sidebar y header |

### Base de Datos
| Archivo | Cambios |
|---------|---------|
| `2026_05_05_000001_add_at_to_ase_tipo_asesor.php` | **NUEVA** — Amplía ENUM a `('OT','OV','AT')` |

### Rutas
| Archivo | Cambios |
|---------|---------|
| `routes/web.php` | Agregar rutas de registro para asesor y coordinador |

---

## 7. Estado del Repositorio

```
Commit: 6df9b75
Branch: main
Remote: https://github.com/SaraKerrigan2001/digiturno
Archivos cambiados: 44
Inserciones: 3,899 líneas
Eliminaciones: 2,190 líneas
```

---

## 8. Pendientes / Recomendaciones

| # | Descripción | Prioridad |
|---|-------------|-----------|
| 1 | Agregar `ase_tipo_asesor` al formulario de edición de asesor en el coordinador | Media |
| 2 | Implementar notificación real por WhatsApp/SMS al generar turno | Baja |
| 3 | Agregar autenticación con middleware en lugar de `checkAuth()` manual | Media |
| 4 | Implementar WebSockets (Laravel Echo) para reemplazar el polling de 3 segundos | Baja |
| 5 | Agregar campo `ase_modulo` en la tabla `asesor` para persistir el módulo seleccionado | Media |

---

*Documento generado el 5 de Mayo de 2026 — DigiTurno APE SENA*
