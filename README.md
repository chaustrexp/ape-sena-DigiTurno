# Digiturno SENA APE - Sistema de Gestión de Turnos Digital

![Logo SENA](public/images/Logo.png)

## 🚀 Descripción del Proyecto
Digiturno SENA APE es una solución moderna y eficiente para la gestión de turnos en la Agencia Pública de Empleo del SENA. Este sistema transforma la experiencia del usuario a través de un kiosco digital intuitivo, permitiendo una solicitud de turnos 100% ecológica y sin papel.

## ✨ Características Principales
- **Kiosco Premium**: Interfaz minimalista con estética Apple-style, optimizada para pantallas táctiles y con micro-animaciones fluidas.
- **Flujo en 6 Pasos**:
    1. Bienvenida con logo animado.
    2. Aceptación de términos con tarjetas informativas.
    3. Selección de perfil de atención (General, Prioritario, Especial).
    4. Validación de Identidad con teclado numérico "Tactile Pro".
    5. Registro de Contacto móvil.
    6. Selección de Canal de Recepción (SMS, WhatsApp, Email, QR).
- **Escalabilidad Técnica**: Base de datos optimizada con `BIGINT` para soportar cualquier longitud de documento de identidad.
- **Eco-Friendly**: Proceso totalmente digital que elimina la necesidad de impresión física.

## 🛠️ Tecnologías Utilizadas
- **Backend**: Laravel 11.x / PHP 8.2
- **Frontend**: Tailwind CSS, Font Awesome 6.4
- **Base de Datos**: MySQL / MariaDB (Ape Sena)
- **Tipografía**: 'Outfit' (Google Fonts)

## 📦 Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/chaustrexp/Proyecto-digiturno.git
   ```
2. Instalar dependencias:
   ```bash
   composer install
   npm install
   ```
3. Configurar el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configurar la base de datos en `.env` (Nombre: `ape sena`) y ejecutar migraciones:
   ```bash
   php artisan migrate
   ```
5. Servir la aplicación:
   ```bash
   php artisan serve
   ```

## 📸 Demo Visual
El kiosco cuenta con un diseño neo-glass translúcido sobre el fondo institucional `fondo.jpg`, logrando un equilibrio perfecto entre marca corporativa y modernidad digital.

---
© 2026 Agencia Pública de Empleo - SENA
