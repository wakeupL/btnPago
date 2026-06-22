<p align="center">
  <img src="public/imgs/LOGO_3.png" alt="BtnPago Logo" width="200"/>
</p>

<h1 align="center">BtnPago — Sistema de Gestión de Pagos WebpayPlus</h1>

<p align="center">
  Plataforma de administración de botones de pago integrada con <strong>Transbank WebpayPlus</strong>, desarrollada en Chile para el mercado local.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12"/>
  <img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=flat-square&logo=livewire&logoColor=white" alt="Livewire 3"/>
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind 4"/>
  <img src="https://img.shields.io/badge/Vite-6-646CFF?style=flat-square&logo=vite&logoColor=white" alt="Vite 6"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+"/>
  <img src="https://img.shields.io/badge/SQLite-ready-003B57?style=flat-square&logo=sqlite&logoColor=white" alt="SQLite"/>
</p>

---

## Descripción

**BtnPago** es una herramienta de administración que permite generar y gestionar botones de pago WebpayPlus (Transbank) de forma centralizada. Nació como solución interna para una empresa de comercialización de materiales eléctricos, donde los clientes necesitaban pagar facturas, guías de despacho o adelantar cheques de forma digital.

El operador genera el botón desde el panel, el cliente paga desde su dispositivo a través de WebpayPlus, y el sistema registra automáticamente el resultado, envía un comprobante por email y genera un PDF descargable.

---

## Características

- **Generación de botones de pago** con monto y número de documento
- **URL corta única** para compartir el link de pago al cliente (8 caracteres, token anti-colisión con índice UNIQUE en BD)
- **Dashboard con KPIs** en tiempo real: activos, pagados, rechazados, monto recaudado, actividad diaria
- **Listados con búsqueda en vivo** (Livewire): botones activos, pagados y rechazados
- **Comprobante PDF** descargable tras pago exitoso (DomPDF)
- **Notificación por email** automática al completar un pago
- **Panel de configuración**: cambio de logo de la aplicación sin tocar código
- **Credenciales Transbank por `.env`**: integración/producción sin recompilar
- **Regeneración de botón**: renueva el token WebpayPlus sin perder el registro
- **Soporte SQLite y MySQL**

---

## Stack tecnológico

| Capa | Tecnología | Versión |
|---|---|---|
| Framework PHP | Laravel | 12.x |
| Componentes reactivos | Livewire | 3.x |
| CSS utilitario | Tailwind CSS | 4.x |
| Bundler | Vite | 6.x |
| JS interactivo | Alpine.js | 3.x |
| PDF | barryvdh/laravel-dompdf | 3.x |
| Pagos | Transbank WebpayPlus SDK | 2.x |
| Testing | Pest | 3.x |
| PHP mínimo | PHP | 8.2+ |
| Base de datos | SQLite / MySQL | — |

---

## Historial de versiones

### v2.0 — Actualización mayor (2026)

Migración completa del stack y mejoras de arquitectura:

**Stack actualizado**
- Laravel 9 → **12** (nueva estructura: `bootstrap/app.php` fluente, `bootstrap/providers.php`, eliminados Kernels legacy)
- Livewire 2 → **3** (componentes en `App\Livewire`, `wire:model.live`, `wire:confirm`)
- Tailwind CSS 3 → **4** (`@tailwindcss/vite` plugin nativo, `@import "tailwindcss"`)
- Vite 4 → **6** (ESM modules, `laravel-vite-plugin ^1.0`)
- `barryvdh/laravel-dompdf` 2 → **3** (soporte Laravel 12)
- Pest 1 → **3**

**Mejoras de código**
- Modelos con `$fillable`, `$casts`, constantes de estado y scopes Eloquent (`activos()`, `pagados()`, `rechazados()`)
- Consultas SQL optimizadas: filtrado por scope en BD en lugar de Blade, eager loading `with(['user', 'confirmacion'])`
- Helper global `chilePesos()` para formateo de montos en CLP (evita redeclaración en Blade)
- Helper `appLogo()` con fallback al logo por defecto

**Nuevas funcionalidades**
- Dashboard real con Livewire (antes era estático): KPIs, últimos pagos, actividad diaria, selector de período
- Credenciales Transbank movidas a `.env` con `config/transbank.php` — configurable sin tocar código
- Panel de Configuración: upload dinámico de logo (PNG/JPG/SVG/WebP, máx. 2 MB)
- Índice UNIQUE en `boton_pagos.corta_token` — garantiza tokens únicos a nivel de BD
- Buscador en vivo en todos los listados (Livewire)
- Página "Rechazados" con lógica correcta: `estado=2` asignado en todos los flujos de rechazo
- Fix: comprobante PDF solo disponible cuando existe `ConfirmacionPagos` — evita 404 en rechazados
- Footer global "Desarrollado con ♥ por wakedev.cl" en todos los layouts (sticky flex)
- Soporte de `storage:link` para assets públicos

### v1.0 — Versión inicial (2023)

Primera versión en producción, usada internamente en empresa del rubro eléctrico.

---

## Instalación

### Requisitos previos

- PHP 8.2+
- Composer
- Node.js 20+ / npm
- SQLite (incluido en PHP) o MySQL

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/wakeupL/btnPago.git
cd btnPago

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar base de datos en .env
# Para SQLite (más simple):
DB_CONNECTION=sqlite
# El archivo se crea automáticamente

# Para MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=btn_pago
DB_USERNAME=root
DB_PASSWORD=

# 6. Configurar Transbank en .env
TRANSBANK_ENVIRONMENT=integration   # "integration" o "production"
TRANSBANK_COMMERCE_CODE=            # vacío = usa credenciales de prueba de Transbank
TRANSBANK_API_KEY=                  # vacío = usa credenciales de prueba de Transbank

# 7. Ejecutar migraciones y seeder
php artisan migrate --seed

# 8. Crear enlace de storage (para logos)
php artisan storage:link

# 9. Compilar assets
npm run build

# 10. Iniciar servidor
php artisan serve
```

Acceder a `http://localhost:8000` con las credenciales del seeder.

---

## Configuración de Transbank

El sistema soporta tres modos sin cambiar código:

| Escenario | `.env` |
|---|---|
| Pruebas con credenciales públicas de Transbank | `TRANSBANK_ENVIRONMENT=integration` y dejar `COMMERCE_CODE` / `API_KEY` vacíos |
| Certificación con credenciales propias | `TRANSBANK_ENVIRONMENT=integration` + tus credenciales |
| Producción | `TRANSBANK_ENVIRONMENT=production` + credenciales reales del comercio |

Las credenciales públicas de integración de Transbank son:
- Commerce Code: `597055555532`
- API Key: `579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C`

---

## Estructura del proyecto

```
app/
├── Http/Controllers/
│   ├── BotonPagoController.php   # Lógica principal de pagos y respuestas WebpayPlus
│   └── Auth/                     # Controladores de autenticación (Breeze)
├── Livewire/
│   ├── Dashboard.php             # KPIs y actividad en tiempo real
│   ├── BtnActivos.php            # Listado de botones activos con búsqueda
│   ├── ListadoPagado.php         # Listado de pagos completados
│   ├── ListadoRechazo.php        # Listado de pagos rechazados
│   ├── Counter.php               # Formulario de generación de botón
│   └── Settings.php              # Panel de configuración (logo)
├── Models/
│   ├── BotonPago.php             # Scopes activos/pagados/rechazados, generarCortaToken()
│   └── ConfirmacionPagos.php     # Registro de confirmaciones exitosas de Transbank
├── Mail/PagoRealizado.php        # Email de notificación de pago
├── Providers/AppServiceProvider  # Configuración de Transbank al arrancar
└── helpers.php                   # chilePesos(), appLogo()

config/
└── transbank.php                 # Lee TRANSBANK_* desde .env

resources/views/
├── layouts/
│   ├── app.blade.php             # Layout autenticado (sticky footer)
│   └── guest.blade.php          # Layout público (sticky footer)
├── livewire/                     # Vistas de componentes Livewire
└── emails/                       # Templates de email (Markdown)
```

---

## Flujo de pago

```
Operador genera botón  →  Sistema crea registro en boton_pagos (estado: activo)
       ↓
URL corta enviada al cliente  →  Cliente accede y es redirigido a WebpayPlus
       ↓
Transbank procesa el pago
       ↓
┌─── Aprobado ──────────────────────────────────────────────────────────────────┐
│  - Crea ConfirmacionPagos con detalles de la transacción                      │
│  - Actualiza boton_pagos → estado: pagado                                     │
│  - Envía email de notificación                                                │
│  - Muestra comprobante en pantalla + PDF descargable                          │
└───────────────────────────────────────────────────────────────────────────────┘
┌─── Rechazado / Anulado ───────────────────────────────────────────────────────┐
│  - Actualiza boton_pagos → estado: rechazado                                  │
│  - Aparece en listado "Rechazados" (sin comprobante PDF)                      │
└───────────────────────────────────────────────────────────────────────────────┘
```

---

## Capturas de pantalla

> _Próximamente_

---

## Desarrollo

```bash
# Servidor de desarrollo
php artisan serve

# Watcher de assets (hot reload)
npm run dev

# Tests
php artisan test
```

---

## Licencia

Proyecto de uso personal/comercial privado.  
Desarrollado con ♥ por [wakedev.cl](https://wakedev.cl)
