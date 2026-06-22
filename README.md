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

El operador genera el botón desde el panel, el cliente paga desde su dispositivo a través de WebpayPlus, y el sistema registra automáticamente el resultado, envía una notificación por email y genera un comprobante PDF descargable.

---

## Características

- **Generación de botones de pago** con monto y número de documento
- **URL corta única** para compartir con el cliente (8 caracteres, índice UNIQUE en BD, protegida contra reutilización)
- **Dashboard con KPIs** en tiempo real: activos, pagados, monto recaudado, actividad diaria
- **Listados con búsqueda en vivo** (Livewire): botones activos y pagados
- **Número de documento único**: índice UNIQUE en BD + validación al generar, evita comprobantes cruzados
- **Comprobante de pago** rediseñado con soporte dark mode completo
- **Comprobante PDF** descargable tras pago exitoso (DomPDF)
- **Notificación por email HTML** con diseño profesional compatible con Gmail, Outlook y Apple Mail
- **Panel de configuración**: logo dinámico + correo responsable de notificaciones
- **Credenciales Transbank por `.env`**: integración/producción configurable sin tocar código
- **Regeneración de botón**: renueva el token WebpayPlus sin perder el registro
- **Soporte SQLite y MySQL**
- **Protección contra pagos duplicados**: patrón PRG + `firstOrCreate` a nivel de BD

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

### v2.2 — Correcciones y robustez (2026)

**Integridad de pagos**
- **Número de documento único**: índice UNIQUE en `boton_pagos.documento` + validación al generar. Un documento ya pagado o con botón activo no se puede volver a generar; si estaba rechazado, se reutiliza la misma fila (reintento) en lugar de duplicar.
- **Fix confirmación de pago**: se reemplazó un destructuring incorrecto de `firstOrCreate()` (devolvía `null`) por `wasRecentlyCreated`. Antes, al confirmar un pago no se guardaba la confirmación, no se marcaba el botón como pagado ni se enviaba el correo.
- **Fix `actualizarToken`**: ahora refresca también `url_wp`, evitando el desajuste token/URL entre ambientes que dejaba la página de Webpay en blanco.

**Transbank en producción**
- `AppServiceProvider` ahora **falla con error claro** si `TRANSBANK_ENVIRONMENT=production` pero faltan credenciales, en vez de caer en silencio a las de prueba. Hace `trim()` a los valores para tolerar espacios al pegar en `.env`.

**Logo y PDF (independientes del hosting)**
- **Logo servido por ruta** (`GET /logo`) en vez de depender del symlink `public/storage` — funciona aunque el hosting tenga el `public` en otra ruta o no permita enlaces simbólicos.
- **Comprobante PDF**: logos incrustados en base64 desde archivos locales (DomPDF no resuelve URLs); logo principal tomado del logo configurable de la app; imagen de footer opcional (`public/imgs/comprobante-footer.png`); logos y footer centrados.

**Limpieza**
- Buscadores en vivo corregidos a la sintaxis de Livewire 3 (`wire:model.live.debounce`); antes no filtraban.
- Eliminada la card KPI y la página/menú de **Rechazados** (el estado `rechazado` se conserva internamente para bloquear reintentos).
- Eliminado `postcss.config.js` obsoleto (sobrante de Tailwind v3) que rompía `vite build` bajo `"type": "module"`.

### v2.1 — Mejoras continuas (2026)

**Email y notificaciones**
- Plantilla de email rediseñada en HTML/CSS inline — compatible con todos los clientes de correo (Gmail, Outlook, Apple Mail)
- Correo de notificaciones configurable desde el panel de ajustes (ya no requiere editar `.env`)
- Soporte para Gmail SMTP y Resend como proveedores de correo
- Servicio `AppSettings` para persistir configuraciones de la app en `storage/app/settings.json`

**Seguridad y consistencia de datos**
- **Prevención de pagos duplicados**: patrón PRG (Post-Redirect-Get) — la página de éxito es ahora una ruta GET independiente `/comprobante/{documento}`, idempotente ante recargas
- **`firstOrCreate` en `ConfirmacionPagos`**: escudo a nivel de BD que impide crear registros duplicados aunque Transbank permita múltiples commits del mismo token en integración
- Email y cambio de estado solo se ejecutan cuando el registro es genuinamente nuevo (`$esNuevo`)
- **URL corta protegida**: `urlCorta()` verifica el estado del botón antes de redirigir; botones pagados o rechazados muestran mensaje de error en lugar de permitir un segundo pago

**UI y experiencia**
- Comprobante de pago rediseñado: cabecera verde con ✓ SVG, monto destacado, tabla espaciada con `divide-y`, tarjeta enmascarada con `••••`, fecha formateada con Carbon
- Soporte dark mode completo en el comprobante con variantes `dark:` en todos los elementos
- Fix: eliminada redeclaración de `chilePesos()` en vistas Blade (`comprobantePago`, `descargarPdf`) que causaba `FatalError` en PHP 8.4
- Fix: botón PDF solo visible en rechazados cuando existe `ConfirmacionPagos` (evita 404)
- Footer "Desarrollado con ♥ por wakedev.cl" en todos los layouts con posición sticky (flex column)
- Créditos anteriores eliminados de todas las vistas públicas

### v2.0 — Actualización mayor de stack (2026)

**Stack actualizado**
- Laravel 9 → **12** (nueva estructura: `bootstrap/app.php` fluente, `bootstrap/providers.php`, eliminados Kernels legacy)
- Livewire 2 → **3** (componentes en `App\Livewire`, `wire:model.live`, `wire:confirm`)
- Tailwind CSS 3 → **4** (`@tailwindcss/vite` plugin nativo, `@import "tailwindcss"`)
- Vite 4 → **6** (ESM modules, `laravel-vite-plugin ^1.0`)
- `barryvdh/laravel-dompdf` 2 → **3** (soporte Laravel 12)
- Pest 1 → **3**

**Mejoras de código**
- Modelos con `$fillable`, `$casts`, constantes de estado y scopes Eloquent (`activos()`, `pagados()`, `rechazados()`)
- Consultas SQL optimizadas: filtrado por scope en BD, eager loading `with(['user', 'confirmacion'])`
- Helper global `chilePesos()` centralizado en `app/helpers.php`
- Helper `appLogo()` con fallback al logo por defecto
- Credenciales Transbank movidas a `.env` con `config/transbank.php`

**Nuevas funcionalidades**
- Dashboard real con Livewire: KPIs, últimos pagos, actividad diaria, selector de período
- Panel de Configuración: upload dinámico de logo (PNG/JPG/SVG/WebP, máx. 2 MB)
- Índice UNIQUE en `boton_pagos.corta_token`
- Buscador en vivo en todos los listados
- Página "Rechazados" con lógica de estado correcta

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

# 5. Base de datos en .env
# SQLite (recomendado para empezar):
DB_CONNECTION=sqlite

# MySQL:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=btn_pago
DB_USERNAME=root
DB_PASSWORD=

# 6. Transbank en .env
TRANSBANK_ENVIRONMENT=integration   # "integration" o "production"
TRANSBANK_COMMERCE_CODE=            # vacío = credenciales de prueba de Transbank
TRANSBANK_API_KEY=                  # vacío = credenciales de prueba de Transbank

# 7. Correo en .env (elegir uno)
# Gmail:
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD="contraseña de aplicación de 16 caracteres"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-correo@gmail.com"
MAIL_FROM_NAME="BtnPago"

# 8. Ejecutar migraciones y seeder
php artisan migrate --seed

# 9. Enlace de storage (opcional: el logo se sirve por la ruta /logo,
#    así que no es imprescindible para mostrarlo)
php artisan storage:link

# 10. Compilar assets
npm run build

# 11. Iniciar servidor
php artisan serve
```

---

## Configuración de Transbank

| Escenario | `.env` |
|---|---|
| Pruebas con credenciales públicas | `TRANSBANK_ENVIRONMENT=integration` — dejar `COMMERCE_CODE` y `API_KEY` vacíos |
| Certificación con credenciales propias | `TRANSBANK_ENVIRONMENT=integration` + tus credenciales |
| Producción | `TRANSBANK_ENVIRONMENT=production` + credenciales reales del comercio |

Credenciales públicas de integración de Transbank:
- Commerce Code: `597055555532`
- API Key: `579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C`

---

## Configuración de correo

El correo responsable de notificaciones se configura desde **Panel → Configuración → Notificaciones**, sin editar el `.env`.

Para el envío real se recomienda una de estas opciones:

| Proveedor | Ventaja | Configuración |
|---|---|---|
| Gmail SMTP | Sin registro adicional | `MAIL_HOST=smtp.gmail.com` + contraseña de aplicación |
| [Resend](https://resend.com) | 3.000 correos/mes gratis, ideal para producción | `MAIL_MAILER=resend` + `RESEND_KEY=re_xxx` |

---

## Estructura del proyecto

```
app/
├── Http/Controllers/
│   ├── BotonPagoController.php   # Lógica de pagos, respuesta Transbank, comprobante, URL corta
│   └── Auth/                     # Controladores de autenticación (Breeze)
├── Livewire/
│   ├── Dashboard.php             # KPIs y actividad en tiempo real
│   ├── BtnActivos.php            # Listado de botones activos con búsqueda
│   ├── ListadoPagado.php         # Listado de pagos completados
│   ├── Counter.php               # Formulario de generación de botón
│   └── Settings.php              # Panel de configuración (logo + correo)
├── Models/
│   ├── BotonPago.php             # Scopes activos/pagados/rechazados, generarCortaToken()
│   └── ConfirmacionPagos.php     # Registro de confirmaciones exitosas de Transbank
├── Mail/PagoRealizado.php        # Email HTML de notificación de pago
├── Services/AppSettings.php      # Lectura/escritura de settings.json (logo, correo)
├── Providers/AppServiceProvider  # Configuración de Transbank al arrancar
└── helpers.php                   # chilePesos(), appLogo(), appLogoPath(), pdfImage()

config/
└── transbank.php                 # Lee TRANSBANK_* desde .env

resources/views/
├── layouts/
│   ├── app.blade.php             # Layout autenticado (sticky footer flex)
│   └── guest.blade.php           # Layout público (sticky footer flex)
├── livewire/                     # Vistas de componentes Livewire
├── emails/pago-realizado.blade.php  # Template HTML/CSS inline del correo
└── comprobantePago.blade.php     # Comprobante web con dark mode
```

---

## Flujo de pago

```
Operador genera botón  →  boton_pagos (estado: activo) + URL corta única
       ↓
Cliente accede a URL corta  →  Sistema verifica estado (solo activo pasa)
       ↓
Redirige a WebpayPlus  →  Cliente completa el pago
       ↓
Transbank responde a /respuestaPago
       ↓
┌─── Aprobado ──────────────────────────────────────────────────────────────────┐
│  - firstOrCreate en ConfirmacionPagos (evita duplicados ante recargas)        │
│  - boton_pagos → estado: pagado                                               │
│  - Envía email HTML al correo configurado en panel                            │
│  - Redirect PRG → GET /comprobante/{doc} (idempotente)                        │
└───────────────────────────────────────────────────────────────────────────────┘
┌─── Rechazado / Cancelado ─────────────────────────────────────────────────────┐
│  - boton_pagos → estado: rechazado (bloquea reintentos sobre el mismo botón)  │
│  - El generador puede reactivar un documento rechazado para reintentar         │
└───────────────────────────────────────────────────────────────────────────────┘
```

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
