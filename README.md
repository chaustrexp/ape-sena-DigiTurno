# 🚦 DigiTurno APE — SENA

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-SaraKerrigan2001/digiturno-181717?style=for-the-badge&logo=github&logoColor=white)

Sistema Digital de Turnos desarrollado para la **Agencia Pública de Empleo (APE) del SENA**. Gestiona la asignación, monitoreo y atención al ciudadano en tiempo real con enfoque diferencial por perfiles de vulnerabilidad.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Arquitectura](#-arquitectura)
- [Módulos del Sistema](#-módulos-del-sistema)
- [Perfiles de Atención](#-perfiles-de-atención)
- [Tipos de Asesor](#-tipos-de-asesor)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Base de Datos](#-base-de-datos)
- [Rutas del Sistema](#-rutas-del-sistema)
- [Credenciales de Prueba](#-credenciales-de-prueba)
- [Control de Concurrencia](#-control-de-concurrencia)
- [Estructura de Carpetas](#-estructura-de-carpetas)

---

## ✨ Características

| Módulo | Descripción |
|--------|-------------|
| 🖥️ **Kiosco Digital** | Interfaz táctil para que el ciudadano solicite su turno sin asistencia |
| 📺 **Pantalla Pública** | Display en tiempo real con llamadas por voz (Web Speech API) |
| 👤 **Panel Asesor** | Gestión de atenciones con cronómetro, recesos y cola filtrada por perfil |
| 🛡️ **Panel Coordinador** | Dashboard gerencial con KPIs, reportes, gestión de asesores y supervisión |
| 🔐 **Autenticación** | Sistema de sesiones independiente para Asesor y Coordinador |
| 📊 **Reportes** | Exportación a Excel con estadísticas de atención por asesor y global |

---

## 🏗 Arquitectura

El sistema sigue el patrón **MVC + Repository** de Laravel:

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AsesorController.php        # Login, panel, atención, recesos
│   │   ├── CoordinadorController.php   # Dashboard, reportes, gestión asesores
│   │   ├── TurnoController.php         # Kiosco — solicitud de turno
│   │   ├── PantallaController.php      # Pantalla pública
│   │   └── Api/ApiController.php       # Endpoints JSON para polling
│   └── Middleware/
│       ├── AuthAsesor.php
│       └── AuthCoordinador.php
├── Models/
│   ├── Asesor.php
│   ├── Atencion.php
│   ├── Turno.php
│   ├── Persona.php
│   ├── Solicitante.php
│   ├── Coordinador.php
│   ├── PausaAsesor.php
│   └── ConfiguracionSistema.php
└── Repositories/
    └── TurnoRepository.php             # Lógica de negocio de turnos
```

---

## 📦 Módulos del Sistema

### 🖥️ Kiosco Digital (`/`)
Interfaz de autoatención para el ciudadano. Flujo de 6 pasos:

1. **Bienvenida** — Pantalla inicial con botón "Empezar Aquí"
2. **Tratamiento de Datos** — Aceptación de política de privacidad
3. **Perfil de Atención** — Selección de categoría (General, Prioritario, Víctima, Empresario)
4. **Detalles de la Visita** — Tipo de servicio y tipo de atención
5. **Documento** — Teclado numérico táctil para ingresar cédula/TI/CE
6. **Contacto** — Número de celular para notificación
7. **Canal de Entrega** — SMS, WhatsApp, Email o QR

Al finalizar se genera el turno con número correlativo del día (ej: `G-001`, `P-003`, `V-001`, `E-002`).

---

### 📺 Pantalla Pública (`/pantalla`)
Display en tiempo real para la sala de espera:

- **Lista de turnos en espera** con columna Turno y Módulo/Profesional
- **Turno en atención** con foto del asesor, número de módulo y nombre
- **Barra inferior** con siguiente en turno y botón de activación de sonido
- **Modal de llamado** con número grande, módulo y foto del asesor
- **Voz automática**: *"Turno G 001, por favor diríjase al módulo 4"*
- **Polling cada 3 segundos** sin recargar la página
- **Video institucional** de YouTube en el panel derecho

---

### 👤 Panel Asesor (`/asesor`)
Panel de gestión de atenciones:

- **Dashboard** con turno activo, cronómetro, cola de espera filtrada por tipo
- **Selector de módulo** con flechas ◀ ▶ para cambiar número de módulo
- **Módulo/Profesionalidad** visible en la tarjeta de atención activa
- **Botones de acción**: Finalizar Atención / Ciudadano Ausente
- **Recesos** con límite de 3 por día y bloqueo si hay atención activa
- **Actividad** — historial paginado con exportación a Excel
- **Reportes** — estadísticas por período con gráficos
- **Configuración** — edición de datos personales

---

### 🛡️ Panel Coordinador (`/coordinador`)
Dashboard gerencial completo:

- **KPIs en tiempo real**: turnos hoy, en espera, en atención, finalizados, ausentes
- **Gráfico de flujo** por hora con ajuste de zona horaria
- **Estado de asesores** con foto, módulo y estado actual
- **Alertas automáticas**: espera > 15 min, cola sin asesores, alta demanda
- **Gestión de Módulos** — crear, editar y eliminar asesores
- **Supervisión de Piso** — monitoreo de módulos 15 y 19, meta semanal de emprendedores
- **Reportes globales** con exportación a Excel
- **Configuración del sistema**

---

## 👥 Perfiles de Atención

| Perfil | Prefijo | Prioridad | Color |
|--------|---------|-----------|-------|
| **Víctima** | `V` | 1 — Máxima | 🔴 Rojo |
| **Empresario** | `E` | 2 — Alta | 🟣 Morado |
| **Prioritario** | `P` | 3 — Media (adulto mayor / discapacidad) | 🟠 Naranja |
| **General** | `G` | 4 — Normal | 🔵 Azul |

---

## 🧑‍💼 Tipos de Asesor

| Tipo | Nombre | Perfiles que atiende |
|------|--------|----------------------|
| `OT` | Orientador Técnico | General · Prioritario |
| `OV` | Orientador de Víctimas | Víctima · Empresario |
| `AT` | Asesor Total | General · Prioritario · Víctima · Empresario |

> El tipo `AT` atiende los 4 perfiles con la prioridad completa: Víctima → Empresario → Prioritario → General.

---

## ⚙️ Requisitos

| Componente | Versión mínima |
|------------|----------------|
| PHP | 8.2+ |
| Laravel | 12.x |
| MySQL / MariaDB | 8.0 / 10.4+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/SaraKerrigan2001/digiturno.git
cd digiturno
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias Node

```bash
npm install
```

### 4. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con las credenciales de la base de datos:

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

### 5. Importar la base de datos

```bash
# Opción A — Importar el dump completo (recomendado)
mysql -u root -p apesena < database/db_dumps/ape-sena-utf8.sql

# Opción B — Ejecutar migraciones desde cero
php artisan migrate
```

### 6. Compilar assets (opcional en desarrollo)

```bash
npm run build
```

### 7. Iniciar el servidor

```bash
php artisan serve
```

El sistema estará disponible en `http://127.0.0.1:8000`

---

## 🗄️ Base de Datos

### Tablas principales

| Tabla | Descripción |
|-------|-------------|
| `persona` | Datos personales de ciudadanos, asesores y coordinadores |
| `solicitante` | Perfil del ciudadano que solicita el turno |
| `turno` | Registro de cada turno generado en el kiosco |
| `atencion` | Registro de cada atención (asesor ↔ turno) |
| `asesor` | Asesores del sistema con credenciales de acceso |
| `coordinador` | Coordinadores con acceso al panel gerencial |
| `pausas_asesor` | Registro de recesos con duración calculada |
| `configuracion_sistema` | Parámetros configurables del sistema |

### Diagrama simplificado

```
persona ──< solicitante ──< turno >── atencion >── asesor
                                                      │
                                               pausas_asesor
```

### Campos clave del turno

| Campo | Descripción |
|-------|-------------|
| `tur_numero` | Número visible (ej: `G-001`) |
| `tur_perfil` | `General` / `Prioritario` / `Victima` / `Empresario` |
| `tur_tipo` | `General` / `Prioritario` / `Victimas` |
| `tur_estado` | `Espera` / `Atendiendo` / `Finalizado` / `Ausente` |
| `tur_servicio` | `Orientacion` / `Formacion` / `Emprendimiento` |
| `tur_hora_fecha` | Timestamp de creación |
| `tur_hora_llamado` | Timestamp cuando el asesor llamó el turno |

---

## 🛣️ Rutas del Sistema

### Públicas

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/` | Kiosco — pantalla de inicio |
| `POST` | `/turno/solicitar` | Generar nuevo turno |
| `GET` | `/pantalla` | Pantalla pública de turnos |
| `GET` | `/api/pantalla/data` | JSON con turno actual y cola |
| `GET` | `/api/turno/consultar/{doc}` | Consultar turno por documento |

### Asesor

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/asesor/login` | Formulario de login |
| `POST` | `/asesor/login` | Autenticar asesor |
| `GET` | `/asesor/register` | Formulario de registro |
| `POST` | `/asesor/register` | Crear cuenta de asesor |
| `GET` | `/asesor` | Dashboard principal |
| `POST` | `/asesor/llamar` | Llamar siguiente turno |
| `POST` | `/asesor/finalizar/{id}` | Finalizar atención |
| `POST` | `/asesor/ausente/{id}` | Marcar ciudadano ausente |
| `POST` | `/asesor/receso/iniciar` | Iniciar receso |
| `POST` | `/asesor/receso/finalizar` | Finalizar receso |

### Coordinador

| Método | URL | Descripción |
|--------|-----|-------------|
| `GET` | `/coordinador/login` | Formulario de login |
| `POST` | `/coordinador/login` | Autenticar coordinador |
| `GET` | `/coordinador/register` | Formulario de registro |
| `GET` | `/coordinador` | Dashboard gerencial |
| `GET` | `/coordinador/reportes` | Reportes globales |
| `GET` | `/coordinador/modulos` | Gestión de asesores |
| `POST` | `/coordinador/modulos/store` | Crear nuevo asesor |
| `POST` | `/coordinador/modulos/update/{id}` | Editar asesor |
| `POST` | `/coordinador/modulos/delete/{id}` | Eliminar asesor |
| `GET` | `/coordinador/supervision` | Supervisión de piso |
| `GET` | `/coordinador/export` | Exportar reporte Excel |

---

## 🔑 Credenciales de Prueba

### Asesor
| Campo | Valor |
|-------|-------|
| Correo | `asesor@sena.edu.co` |
| Contraseña | `asesor123` |

### Coordinador
| Campo | Valor |
|-------|-------|
| Correo | `coordinador@sena.edu.co` |
| Contraseña | `sena2026` |

> Las credenciales se insertan automáticamente al ejecutar la migración `2026_04_29_000002_add_credentials_to_coordinador_table.php`.

---

## 🔒 Control de Concurrencia

El sistema implementa **Pessimistic Locking** (`SELECT ... FOR UPDATE`) en la asignación de turnos para garantizar que dos asesores no llamen al mismo turno simultáneamente:

```php
$turno = Turno::where('tur_estado', 'Espera')
               ->lockForUpdate()
               ->first();
```

Adicionalmente, el tipo `OT` aplica una **relación 3:1** (3 Prioritarios por cada 1 General) usando caché para el contador.

---

## 📁 Estructura de Carpetas

```
digiturno/
├── app/
│   ├── Http/Controllers/       # Controladores MVC
│   ├── Models/                 # Modelos Eloquent
│   ├── Repositories/           # Lógica de negocio (TurnoRepository)
│   └── Providers/
├── database/
│   ├── db_dumps/               # Dump completo de la BD
│   ├── migrations/             # Migraciones incrementales
│   └── sql/                    # Scripts SQL auxiliares
├── resources/
│   └── views/
│       ├── asesor/             # Vistas del panel asesor
│       ├── coordinador/        # Vistas del panel coordinador
│       ├── kiosco/             # Vista del kiosco público
│       ├── layouts/            # Layouts maestros
│       └── pantalla/           # Vista de pantalla pública
├── routes/
│   └── web.php                 # Todas las rutas del sistema
├── public/
│   └── images/                 # Imágenes estáticas (logo, avatares)
└── scripts/
    ├── abrir-pantalla.bat      # Abre la pantalla en modo kiosco
    └── configurar-audio.bat    # Configura audio para Chrome kiosco
```

---

## 🖥️ Scripts de Kiosco

Para despliegue en pantallas físicas (TV/monitor de sala de espera):

**`scripts/abrir-pantalla.bat`** — Abre Chrome en modo kiosco con autoplay de audio:
```bat
start chrome --kiosk --autoplay-policy=no-user-gesture-required http://localhost:8000/pantalla
```

**`scripts/configurar-audio.bat`** — Configura Chrome para permitir audio automático sin interacción del usuario.

---

## 🧪 Tests

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test tests/Feature/DigiturnoTest.php
```

---

## 📝 Variables de Entorno Importantes

| Variable | Descripción | Valores |
|----------|-------------|---------|
| `PERIODO_REINICIO_TURNOS` | Frecuencia de reinicio del correlativo | `day` / `week` / `month` |
| `DB_DATABASE` | Nombre de la base de datos | `apesena` |
| `APP_URL` | URL base del sistema | `http://localhost:8000` |

---

## 🤝 Contribución

1. Fork del repositorio
2. Crear rama: `git checkout -b feature/nueva-funcionalidad`
3. Commit: `git commit -m "feat: descripción del cambio"`
4. Push: `git push origin feature/nueva-funcionalidad`
5. Abrir Pull Request

---

## 📄 Licencia

Uso exclusivo institucional — **Servicio Nacional de Aprendizaje SENA**  
Dirección de Empleo, Trabajo y Emprendimiento · © 2026

---

> **Repositorio:** [github.com/SaraKerrigan2001/digiturno](https://github.com/SaraKerrigan2001/digiturno)
