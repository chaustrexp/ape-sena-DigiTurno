# 📋 Resumen General del Proyecto
## DigiTurno APE — Sistema Digital de Turnos SENA
### Fecha: 5 de Mayo de 2026

---

## 1. Descripción del Proyecto

**DigiTurno APE** es un sistema web desarrollado en **Laravel 12** para la **Agencia Pública de Empleo (APE) del SENA**. Su propósito es gestionar de forma digital la asignación, monitoreo y atención al ciudadano mediante un sistema de turnos en tiempo real, aplicando un enfoque diferencial que prioriza a las poblaciones más vulnerables.

El sistema reemplaza el proceso manual de entrega de tiquetes físicos por una solución completamente digital, accesible desde un kiosco táctil, con pantalla pública de llamados, panel de gestión para asesores y dashboard gerencial para coordinadores.

---

## 2. Ficha Técnica

| Campo | Detalle |
|-------|---------|
| **Nombre del sistema** | DigiTurno APE — SENA |
| **Repositorio** | https://github.com/SaraKerrigan2001/digiturno |
| **Framework** | Laravel 12 |
| **Lenguaje** | PHP 8.2 |
| **Frontend** | Tailwind CSS 4.0 · Font Awesome 6.4 |
| **Base de datos** | MySQL 8.0 / MariaDB 10.4 |
| **Arquitectura** | MVC + Repository Pattern |
| **Autenticación** | Sesiones PHP nativas (sin Laravel Auth) |
| **Tiempo real** | Polling asíncrono cada 3 segundos (Fetch API) |
| **Audio** | Web Audio API + Web Speech API |

---

## 3. Módulos del Sistema

El sistema está compuesto por **4 módulos principales**, cada uno con su propia interfaz y lógica de negocio.

---

### 3.1 Kiosco Digital

**URL:** `/`

Es la interfaz pública de autoatención. El ciudadano interactúa con ella sin necesidad de asistencia. Está diseñada para pantallas táctiles y sigue un flujo guiado de 7 pasos:

| Paso | Nombre | Descripción |
|------|--------|-------------|
| 1 | Bienvenida | Pantalla inicial con botón "Empezar Aquí" |
| 2 | Tratamiento de Datos | Aceptación de política de privacidad del SENA |
| 3 | Perfil de Atención | Selección de categoría: General, Prioritario, Víctima o Empresario |
| 4 | Detalles de la Visita | Tipo de servicio (Orientación, Formación, Emprendimiento) y tipo de atención |
| 5 | Documento | Teclado numérico táctil para ingresar número de identificación |
| 6 | Contacto | Número de celular para notificación del turno |
| 7 | Canal de Entrega | Selección de medio: SMS, WhatsApp, Email o Código QR |

Al completar el flujo, el sistema genera un turno con número correlativo del día según el perfil:

| Perfil | Prefijo | Ejemplo |
|--------|---------|---------|
| General | `G` | G-001 |
| Prioritario | `P` | P-001 |
| Víctima | `V` | V-001 |
| Empresario | `E` | E-001 |

**Características técnicas:**
- Validación de longitud de documento según normativa colombiana (CC/TI: 8-10 dígitos, CE: mínimo 6)
- Límite de un turno por persona por período (configurable: día, semana o mes)
- Advertencia automática si el ciudadano no tiene registro previo en la APE
- Bloqueo pesimista (`lockForUpdate`) para evitar números de turno duplicados en alta concurrencia
- Modal de éxito con código QR del turno generado
- Sonido de confirmación al generar el turno

---

### 3.2 Pantalla Pública

**URL:** `/pantalla`

Display en tiempo real para la sala de espera. Se proyecta en un monitor o televisor visible para todos los ciudadanos.

**Componentes de la pantalla:**

- **Panel izquierdo (38%):** Lista de turnos en espera con columnas Turno y Módulo/Profesional
- **Panel derecho (62%):** Video institucional de YouTube con información del SENA
- **Barra inferior negra:** Muestra el siguiente turno en cola con botón de activación de sonido

**Funcionamiento en tiempo real:**
- Polling automático cada 3 segundos al endpoint `/api/pantalla/data`
- Cuando un asesor llama un turno, aparece un **modal de llamado** con:
  - Número del turno en tamaño grande
  - Número de módulo al que debe dirigirse
  - Foto del asesor
  - Anuncio de voz: *"Turno G 001, por favor diríjase al módulo 4"*
- El modal se cierra automáticamente después de 12 segundos

**Sistema de sonido:**
- Chime musical (acorde Do-Mi-Sol) antes del anuncio de voz
- Botón 🔊 visible para activar el audio (requerido por política de navegadores)
- Compatible con Chrome, Edge y Firefox

**Columna Módulo / Profesional:**
- Turnos en espera: muestra "— En cola"
- Turno en atención: muestra foto del asesor + número de módulo + nombre completo

---

### 3.3 Panel del Asesor

**URL:** `/asesor`

Panel privado para los asesores de atención al ciudadano. Requiere autenticación con correo y contraseña.

**Secciones disponibles:**

| Sección | URL | Descripción |
|---------|-----|-------------|
| Dashboard | `/asesor` | Turno activo, cola de espera, estadísticas del día |
| Actividad | `/asesor/actividad` | Historial de atenciones con filtros y exportación Excel |
| Trámites | `/asesor/tramites` | Información de trámites disponibles |
| Reportes | `/asesor/reportes` | Estadísticas por período con gráficos |
| Configuración | `/asesor/configuracion` | Edición de datos personales |
| Manual | `/manual/asesor` | Guía de uso del sistema |

**Flujo de atención:**

```
Sin turno activo
      ↓
[Llamar Siguiente Turno]
      ↓
Turno asignado — aparece en tarjeta azul
      ↓
[Finalizar Atención] o [Ciudadano Ausente]
      ↓
Sin turno activo — listo para el siguiente
```

**Características del dashboard:**
- Tarjeta azul con número de turno, nombre del ciudadano, documento y cronómetro
- Módulo y tipo de asesor visibles (OT / OV / AT)
- Selector de módulo con flechas ◀ ▶ (rango 1-20)
- Cola de espera filtrada según el tipo de asesor
- Estadísticas: atendidos hoy, tiempo promedio, capacidad del puesto
- Gráfico de atenciones por hora

**Sistema de recesos (CU-03):**
- Máximo 3 recesos por día
- No se puede iniciar receso con atención activa
- Cronómetro de pausa persistente con localStorage
- El módulo queda bloqueado para recibir turnos durante el receso

---

### 3.4 Panel del Coordinador

**URL:** `/coordinador`

Dashboard gerencial para el coordinador de la sede. Requiere autenticación con correo institucional y contraseña.

**Secciones disponibles:**

| Sección | URL | Descripción |
|---------|-----|-------------|
| Dashboard | `/coordinador` | KPIs en tiempo real, gráficos, estado de asesores |
| Reportes | `/coordinador/reportes` | Análisis por período con distribución de tipos |
| Gestión Módulos | `/coordinador/modulos` | CRUD completo de asesores |
| Supervisión | `/coordinador/supervision` | Monitoreo de módulos 15 y 19 |
| Configuración | `/coordinador/configuracion` | Ajustes del sistema |
| Manual | `/manual/coordinador` | Guía de uso |

**KPIs del dashboard:**
- Turnos generados hoy
- Turnos en espera
- Turnos en atención
- Turnos finalizados
- Turnos ausentes
- Tiempo medio de espera
- Satisfacción (4.8/5)

**Alertas automáticas:**
- Turnos en espera con más de 15 minutos
- Cola activa sin asesores atendiendo
- Alto volumen (más de 15 turnos en la última hora)

**Gestión de Asesores:**
- Crear nuevo asesor con datos personales y credenciales
- Selección de tipo: OT, OV o AT
- Editar datos y contraseña
- Eliminar asesor

**Exportación a Excel:**
- Reporte global con todos los turnos del día
- Reporte individual por asesor con estadísticas de atención

---

## 4. Perfiles de Atención

El sistema implementa un **enfoque diferencial** con 4 perfiles de ciudadanos, cada uno con su propia prioridad de atención:

| Perfil | Prefijo | Prioridad | Color | Descripción |
|--------|---------|-----------|-------|-------------|
| **Víctima** | `V` | 1 — Máxima | 🔴 Rojo | Atención inmediata bajo la Ley de Víctimas |
| **Empresario** | `E` | 2 — Alta | 🟣 Morado | Empresas buscando servicios de formación o empleo |
| **Prioritario** | `P` | 3 — Media | 🟠 Naranja | Adulto mayor, discapacidad, movilidad reducida |
| **General** | `G` | 4 — Normal | 🔵 Azul | Ciudadano sin condición especial de prioridad |

---

## 5. Tipos de Asesor

Cada asesor tiene asignado un tipo que determina qué perfiles de ciudadanos puede atender:

| Tipo | Nombre | Perfiles que atiende | Prioridad interna |
|------|--------|----------------------|-------------------|
| `OT` | Orientador Técnico | General · Prioritario | Prioritario 3:1 General |
| `OV` | Orientador de Víctimas | Víctima · Empresario | Empresario → Víctima |
| `AT` | Asesor Total | Los 4 perfiles | Víctima → Empresario → Prioritario → General |

> El tipo **AT (Asesor Total)** fue agregado en la jornada del 5 de mayo de 2026 para permitir que un asesor atienda todos los perfiles sin restricción.

---

## 6. Base de Datos

### Tablas del sistema

| Tabla | Descripción | Registros clave |
|-------|-------------|-----------------|
| `persona` | Datos personales de todos los usuarios | `pers_doc` (PK), `pers_nombres`, `pers_tipodoc` |
| `solicitante` | Perfil del ciudadano que solicita turno | `sol_id`, `sol_tipo`, `PERSONA_pers_doc` |
| `turno` | Cada turno generado en el kiosco | `tur_numero`, `tur_perfil`, `tur_estado`, `tur_hora_fecha` |
| `atencion` | Registro de cada atención | `atnc_hora_inicio`, `atnc_hora_fin`, `ASESOR_ase_id`, `TURNO_tur_id` |
| `asesor` | Asesores con credenciales | `ase_correo`, `ase_password`, `ase_tipo_asesor` |
| `coordinador` | Coordinadores con credenciales | `coor_correo`, `coor_password` |
| `pausas_asesor` | Recesos de asesores | `hora_inicio`, `hora_fin`, `duracion` |
| `configuracion_sistema` | Parámetros configurables | `clave`, `valor` |

### Diagrama de relaciones

```
persona ──< solicitante ──< turno >── atencion >── asesor
   │                                                  │
   └──< coordinador                           pausas_asesor
```

### Estados del turno

```
[Espera] → [Atendiendo] → [Finalizado]
                       → [Ausente]
```

---

## 7. Autenticación

El sistema usa **sesiones PHP nativas** con dos flujos independientes:

### Asesor
- **Login:** correo electrónico + contraseña
- **Sesión:** `ase_id`, `ase_tipo_asesor`, `ase_nombre`, `ase_foto`, `ase_email`
- **Registro:** crea Persona + Asesor, inicia sesión automáticamente

### Coordinador
- **Login:** correo institucional + contraseña
- **Sesión:** `coordinador_id`, `coordinador_nombre`
- **Registro:** crea Persona + Coordinador, inicia sesión automáticamente

### Credenciales de prueba

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Asesor | `asesor@sena.edu.co` | `asesor123` |
| Coordinador | `coordinador@sena.edu.co` | `sena2026` |

---

## 8. Control de Concurrencia

Para garantizar que dos asesores no llamen al mismo turno simultáneamente, el sistema implementa **Pessimistic Locking** a nivel de base de datos:

```php
$turno = Turno::where('tur_estado', 'Espera')
               ->lockForUpdate()   // SELECT ... FOR UPDATE
               ->first();
```

Adicionalmente, el tipo `OT` aplica una **relación 3:1** (3 Prioritarios por cada 1 General) usando caché para el contador, garantizando equidad en la atención.

---

## 9. Cambios Realizados el 5 de Mayo de 2026

### 9.1 Correcciones de Bugs

| # | Bug corregido | Impacto |
|---|---------------|---------|
| 1 | `now()->today()` no es método válido en Carbon | Error en filtros de fecha del asesor y coordinador |
| 2 | Asesor creado con tipo `'Asesor'` inválido en ENUM | Impedía el login del asesor recién creado |
| 3 | Botón "Llamar Siguiente" visible con atención activa | Riesgo de crear dos atenciones simultáneas |
| 4 | Barra inferior no se limpiaba al finalizar turno | Mostraba número obsoleto en pantalla pública |
| 5 | Columna Módulo/Profesional vacía en pantalla | No se enviaba `asesor_nombre` desde la API |
| 6 | Sonido no funcionaba por política de autoplay | Navegadores modernos bloquean AudioContext sin interacción |

### 9.2 Nuevas Funcionalidades

| Funcionalidad | Descripción |
|---------------|-------------|
| **Registro de Asesor** | Formulario en `/asesor/register` para crear cuenta con datos personales |
| **Registro de Coordinador** | Formulario en `/coordinador/register` para crear cuenta |
| **Selector de Módulo** | Botones ◀ ▶ en el panel del asesor para cambiar número de módulo (1-20) |
| **Módulo/Profesional en Pantalla** | Columna que muestra foto, módulo y nombre del asesor asignado |
| **Tipo AT — Asesor Total** | Nuevo tipo que atiende los 4 perfiles con prioridad completa |
| **Sonido mejorado** | Botón 🔊 de activación + chime Do-Mi-Sol + voz con módulo |

### 9.3 Ajustes Visuales

Todos los módulos fueron reducidos en tamaño para mejorar la visualización en pantallas estándar:

| Módulo | Cambio principal |
|--------|-----------------|
| Kiosco | Contenedor de 850px → 720px, textos y botones reducidos en todos los pasos |
| Pantalla | Turnos en lista reducidos, barra inferior más compacta |
| Panel Asesor | Sidebar de 288px → 224px, header de 96px → 56px, tarjetas más compactas |
| Panel Coordinador | Sidebar de 256px → 208px, header más delgado |

### 9.4 Limpieza de Archivos

| Archivo eliminado | Razón |
|-------------------|-------|
| `app/Models/User.php` | Modelo Laravel por defecto, no usado en el proyecto |
| `database/factories/UserFactory.php` | Factory del modelo eliminado |
| `resources/views/docs/casos_prueba_pdf.blade.php` | Vista sin referencia en controladores |

---

## 10. Rutas Completas del Sistema

### Rutas Públicas

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/` | Kiosco — pantalla de inicio |
| `GET` | `/kiosco` | Alias del kiosco |
| `GET` | `/solicitar` | Alias del kiosco |
| `POST` | `/turno/solicitar` | Generar nuevo turno |
| `GET` | `/pantalla` | Pantalla pública de turnos |
| `GET` | `/api/pantalla/data` | JSON con turno actual y cola |
| `GET` | `/api/turno/consultar/{doc}` | Consultar turno por documento |

### Rutas del Asesor

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/asesor/login` | Formulario de login |
| `POST` | `/asesor/login` | Autenticar asesor |
| `POST` | `/asesor/logout` | Cerrar sesión |
| `GET` | `/asesor/register` | Formulario de registro |
| `POST` | `/asesor/register` | Crear cuenta de asesor |
| `GET` | `/asesor` | Dashboard principal |
| `GET` | `/asesor/actividad` | Historial de atenciones |
| `GET` | `/asesor/tramites` | Información de trámites |
| `GET` | `/asesor/reportes` | Reportes estadísticos |
| `GET` | `/asesor/configuracion` | Configuración personal |
| `POST` | `/asesor/llamar` | Llamar siguiente turno |
| `POST` | `/asesor/finalizar/{id}` | Finalizar atención |
| `POST` | `/asesor/ausente/{id}` | Marcar ciudadano ausente |
| `POST` | `/asesor/receso/iniciar` | Iniciar receso |
| `POST` | `/asesor/receso/finalizar` | Finalizar receso |
| `GET` | `/manual/asesor` | Manual de usuario |

### Rutas del Coordinador

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/coordinador/login` | Formulario de login |
| `POST` | `/coordinador/login` | Autenticar coordinador |
| `POST` | `/coordinador/logout` | Cerrar sesión |
| `GET` | `/coordinador/register` | Formulario de registro |
| `POST` | `/coordinador/register` | Crear cuenta de coordinador |
| `GET` | `/coordinador` | Dashboard gerencial |
| `GET` | `/coordinador/reportes` | Reportes globales |
| `GET` | `/coordinador/modulos` | Gestión de asesores |
| `POST` | `/coordinador/modulos/store` | Crear nuevo asesor |
| `POST` | `/coordinador/modulos/update/{id}` | Editar asesor |
| `POST` | `/coordinador/modulos/delete/{id}` | Eliminar asesor |
| `GET` | `/coordinador/supervision` | Supervisión de piso |
| `GET` | `/coordinador/export` | Exportar reporte Excel |
| `GET` | `/coordinador/configuracion` | Configuración del sistema |
| `GET` | `/manual/coordinador` | Manual de usuario |

---

## 11. Instalación y Configuración

### Requisitos del sistema

| Componente | Versión mínima |
|------------|----------------|
| PHP | 8.2+ |
| Laravel | 12.x |
| MySQL / MariaDB | 8.0 / 10.4+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |

### Pasos de instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/SaraKerrigan2001/digiturno.git
cd digiturno

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Importar base de datos
mysql -u root -p apesena < database/db_dumps/ape-sena-utf8.sql

# 5. Iniciar servidor
php artisan serve
```

### Variables de entorno importantes

```env
APP_NAME="DigiTurno APE SENA"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apesena
DB_USERNAME=root
DB_PASSWORD=

PERIODO_REINICIO_TURNOS=day   # day | week | month
```

### Despliegue en pantalla física (kiosco/TV)

Para abrir la pantalla pública en modo kiosco con audio automático:

```bat
start chrome --kiosk --autoplay-policy=no-user-gesture-required http://localhost:8000/pantalla
```

---

## 12. Pendientes y Recomendaciones

| # | Descripción | Prioridad |
|---|-------------|-----------|
| 1 | Agregar campo `ase_tipo_asesor` al formulario de edición de asesor | Media |
| 2 | Persistir el módulo seleccionado en la BD (`ase_modulo`) | Media |
| 3 | Reemplazar `checkAuth()` manual por middleware de Laravel | Media |
| 4 | Implementar WebSockets (Laravel Echo) para reemplazar el polling | Baja |
| 5 | Notificación real por WhatsApp/SMS al generar turno | Baja |
| 6 | Agregar paginación a la cola de espera del asesor | Baja |

---

## 13. Estado del Repositorio

| Campo | Valor |
|-------|-------|
| **Repositorio** | https://github.com/SaraKerrigan2001/digiturno |
| **Rama principal** | `main` |
| **Último commit** | `5f7919e` — docs: agregar documento de cambios |
| **Total archivos** | 44 archivos modificados en la jornada |
| **Líneas agregadas** | 3,899 |
| **Líneas eliminadas** | 2,190 |

---

*Documento generado el 5 de Mayo de 2026*
*DigiTurno APE — Servicio Nacional de Aprendizaje SENA*
*Dirección de Empleo, Trabajo y Emprendimiento*
