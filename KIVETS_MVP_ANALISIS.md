# 📋 KIVETS / TodoPeludos — Análisis Completo del MVP

> **Documento de referencia técnica y roadmap de mejoras.**
> Última actualización: 2026-06-12
> Propósito: Documentar el estado completo del MVP para retomar el desarrollo sin necesidad de re-analizar el codebase.

---

## 1. RESUMEN DEL PROYECTO

**Kivets (TodoPeludos.com)** es un marketplace de servicios para mascotas enfocado en Perú. Conecta dueños de mascotas (clientes) con profesionales de cuidado animal (proveedores). Incluye funcionalidades de IA con Google Gemini para análisis de salud y detección de razas.

### Stack Tecnológico
| Componente | Tecnología |
|-----------|------------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Livewire 3 (SPA-like, sin API REST) |
| Estilos | Tailwind CSS 4, Vite 7 |
| BD Local | SQLite |
| BD Producción | MySQL (Cloud SQL) |
| Permisos | Spatie Permission (roles) |
| Storage | Local (dev) / Google Cloud Storage (prod) |
| IA | Google Gemini API (vision + text) |
| Pagos | Culqi (dependencia instalada, NO implementada) |
| QR | simplesoftwareio/simple-qrcode |
| Deploy | Docker → Google Cloud Run |

### Cómo ejecutar
```bash
# Con Laragon (PHP 8.3 en C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\)
C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe artisan serve --port=8000
npm run dev

# Credenciales demo (tras ejecutar seeders)
# Email: admin@todopeludos.com (o admin@mascotas.pe según seeder)
# Pass: password
```

---

## 2. ARQUITECTURA DE ARCHIVOS

### Estructura Principal
```
app/
├── Http/                          # Middleware (vacío en esta app)
├── Livewire/
│   ├── Auth/
│   │   ├── Login.php              # Autenticación con email/password
│   │   └── Register.php           # Registro con selector de rol (10 roles)
│   ├── Dashboard/
│   │   ├── CarePlanGenerator.php  # [DESACTIVADO] Plan de cuidado IA
│   │   ├── ClientAddresses.php    # CRUD de direcciones del cliente (ubigeo)
│   │   ├── ClientDashboard.php    # Panel cliente: lista de mascotas
│   │   ├── ClientFavorites.php    # Lista de proveedores favoritos
│   │   ├── HealthAnalyzer.php     # [DESACTIVADO] Análisis de salud IA
│   │   ├── HealthHistory.php      # [DESACTIVADO] Historial de análisis
│   │   ├── PetForm.php            # Crear/Editar mascota (3 tabs: general, comportamiento, salud + IA)
│   │   ├── ProviderAppointments.php # Gestión de citas recibidas (proveedor)
│   │   └── ProviderDashboard.php  # Panel proveedor: perfil, portafolio, horarios (453 líneas)
│   ├── Demo/
│   │   ├── DemoCarePlan.php       # [DESACTIVADO] Demo público del plan de cuidado
│   │   └── DemoHealthAnalyzer.php # [DESACTIVADO] Demo público del análisis de salud
│   └── Pages/
│       ├── Home.php               # Landing page con proveedores destacados
│       ├── PetProfile.php         # Perfil público de mascota (acceso por UUID)
│       ├── Profile.php            # Perfil público del proveedor (reseñas, citas, favoritos)
│       └── Search.php             # Búsqueda con filtros avanzados y paginación
├── Models/
│   ├── Appointment.php            # Cita: client_id, provider_id, pet_id, scheduled_at, status, notes
│   ├── CarePlan.php               # Plan de cuidado IA: pet_data (JSON), plan_data (JSON)
│   ├── Department.php             # Ubigeo: Departamento
│   ├── District.php               # Ubigeo: Distrito (tiene department_id, province_id)
│   ├── Groomer.php                # Perfil estilista
│   ├── HealthAnalysis.php         # Análisis de salud IA: findings, recommendations (JSON)
│   ├── Pet.php                    # Mascota (SoftDeletes): nombre, especie, raza, peso, comportamiento, salud
│   ├── PetHotel.php               # Perfil hotel de mascotas
│   ├── PetPhotographer.php        # Perfil fotógrafo
│   ├── PetSitter.php              # Perfil cuidador
│   ├── PetTaxi.php                # Perfil taxi de mascotas
│   ├── PortfolioImage.php         # Imagen de portafolio (user_id, image_path, title)
│   ├── Province.php               # Ubigeo: Provincia
│   ├── Review.php                 # Reseña: user_id, provider_id, rating, comment
│   ├── Shelter.php                # Perfil albergue
│   ├── Trainer.php                # Perfil adiestrador
│   ├── User.php                   # Usuario: name, email, password, profile_photo_path + 9 relaciones de proveedor
│   ├── UserAddress.php            # Dirección: name, address, reference, district_id, is_default, coordinates
│   ├── Veterinarian.php           # Perfil veterinario
│   └── Walker.php                 # Perfil paseador
├── Providers/
│   └── AppServiceProvider.php     # Registra GCS adapter custom
├── Services/
│   ├── AIVisionService.php        # Llamadas a Gemini API (base64 images) — 7KB
│   ├── DietRecommendationService.php # Recomendaciones de dieta — 10KB
│   └── PetCareRecommendationService.php # Planes de cuidado IA — 18KB
└── Support/
    └── GoogleCloudStorageAdapter.php # Adapter GCS con url() y temporaryUrl()

resources/views/
├── components/
│   ├── dashboard/                 # Componentes reutilizables del dashboard
│   └── layouts/
│       └── app.blade.php          # Layout principal (navbar, sidebar, footer) — 20KB
├── livewire/
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── dashboard/
│   │   ├── care-plan-generator.blade.php   # 34KB
│   │   ├── client-addresses.blade.php      # 23KB
│   │   ├── client-dashboard.blade.php      # 7KB
│   │   ├── client-favorites.blade.php      # 5KB
│   │   ├── health-analyzer.blade.php       # 25KB
│   │   ├── health-history.blade.php        # 26KB
│   │   ├── pet-form.blade.php              # 25KB
│   │   ├── provider-appointments.blade.php # 9KB
│   │   └── provider-dashboard.blade.php    # 54KB (el más grande)
│   ├── demo/
│   │   ├── demo-care-plan.blade.php
│   │   └── demo-health-analyzer.blade.php
│   └── pages/
│       ├── home.blade.php          # 25KB — Landing page
│       ├── pet-profile.blade.php   # 9KB
│       ├── profile.blade.php       # 67KB (el más grande de toda la app)
│       └── search.blade.php        # 46KB

config/
└── ai.php                         # Prompts de Gemini, config de modelos
```

---

## 3. RUTAS DE LA APLICACIÓN

### Rutas Públicas
| Ruta | Componente | Descripción |
|------|-----------|-------------|
| `GET /` | `Pages\Home` | Landing page con hero, categorías, proveedores destacados, sección IA |
| `GET /register` | `Auth\Register` | Registro con selector de rol |
| `GET /login` | `Auth\Login` | Login con email/password |
| `GET /buscar` | `Pages\Search` | Búsqueda de proveedores con filtros |
| `GET /perfil/{id}` | `Pages\Profile` | Perfil público del proveedor |
| `GET /p/{uuid}` | `Pages\PetProfile` | Perfil público de mascota (por UUID) |
| `POST /logout` | Closure | Cerrar sesión |
| `GET /seed-services` | Closure | ⚠️ Ejecuta seeders (PELIGRO EN PROD) |

### Rutas Protegidas (requieren auth)
| Ruta | Componente | Descripción |
|------|-----------|-------------|
| `GET /dashboard` | Closure → redirect | Detecta rol y redirige a ClientDashboard o ProviderDashboard |
| `GET /dashboard/proveedor` | `Dashboard\ProviderDashboard` | Panel del proveedor (editar perfil, portafolio, horarios) |
| `GET /dashboard/proveedor/citas` | `Dashboard\ProviderAppointments` | Citas recibidas (confirmar, cancelar, completar) |
| `GET /dashboard/mascota/crear` | `Dashboard\PetForm` | Formulario nueva mascota |
| `GET /dashboard/mascota/editar/{pet}` | `Dashboard\PetForm` | Editar mascota existente |
| `GET /dashboard/direcciones` | `Dashboard\ClientAddresses` | CRUD de direcciones del cliente |
| `GET /dashboard/favoritos` | `Dashboard\ClientFavorites` | Lista de proveedores favoritos |

### Rutas DESACTIVADAS (comentadas en web.php)
| Ruta | Componente | Descripción |
|------|-----------|-------------|
| `/demo/analisis` | `Demo\DemoHealthAnalyzer` | Demo público de análisis de salud IA |
| `/demo/plan-cuidado` | `Demo\DemoCarePlan` | Demo público de plan de cuidado IA |
| `/dashboard/salud/analizar` | `Dashboard\HealthAnalyzer` | Análisis de salud IA (autenticado) |
| `/dashboard/salud/plan` | `Dashboard\CarePlanGenerator` | Generador de plan de cuidado IA |
| `/dashboard/salud/historial` | `Dashboard\HealthHistory` | Historial de análisis de salud |

---

## 4. ESQUEMA DE BASE DE DATOS

### Tabla: `users`
```
id, name, email, password, profile_photo_path, email_verified_at, remember_token, timestamps
```
- Roles vía Spatie: `client`, `veterinarian`, `walker`, `groomer`, `hotel`, `shelter`, `trainer`, `pet_sitter`, `pet_taxi`, `pet_photographer`
- Relaciones: pets, favoriteProviders, reviewsReceived, reviewsWritten, appointmentsAsClient, appointmentsAsProvider, portfolio

### Tabla: `pets` (SoftDeletes)
```
id, user_id(FK), name, species, breed, birth_date, gender, color, weight(decimal 5,2),
chip_id, is_sterilized, medical_notes, profile_photo_path, uuid(unique),
qr_code_path, behavior(JSON), health_features(JSON),
detected_breeds(JSON), breed_confidence(float), nutritional_needs(JSON), breed_detected_at,
timestamps, deleted_at
```
- `behavior` JSON: `{energy_level, sociable_kids, sociable_dogs, sociable_cats, fear_fireworks, fear_cars}`
- `health_features` JSON: `{vaccination_date, deworming_date}`

### Tablas de Proveedores (9 tablas, estructura similar)

**`veterinarians`:**
```
id, verification_document_path, is_verified, user_id(FK), license_number,
bio, address, district_id(FK→districts), allows_home_visits, emergency_24h,
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`walkers`:**
```
id, verification_document_path, is_verified, user_id(FK), experience,
district_id(FK), hourly_rate(decimal 8,2),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`groomers`:**
```
id, verification_document_path, is_verified, user_id(FK), bio, address,
district_id(FK), allows_home_visits,
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`pet_hotels`:**
```
id, verification_document_path, is_verified, user_id(FK), bio, address,
district_id(FK), capacity(int), has_transport, cage_free,
check_in_time(time), check_out_time(time),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`shelters`:**
```
id, verification_document_path, is_verified, user_id(FK), bio, address,
district_id(FK), capacity(int),
accepting_adoptions, accepting_volunteers, accepting_donations, donation_info,
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`trainers`:**
```
id, verification_document_path, is_verified, user_id(FK), bio,
certification, methodology, allows_home_visits, district_id(FK),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`pet_sitters`:**
```
id, verification_document_path, is_verified, user_id(FK), bio,
housing_type, has_yard, allows_home_visits, district_id(FK),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`pet_taxis`:**
```
id, verification_document_path, is_verified, user_id(FK), bio,
vehicle_type, has_ac, provides_crate, district_id(FK),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

**`pet_photographers`:**
```
id, verification_document_path, is_verified, user_id(FK), bio,
specialty, has_studio, district_id(FK),
website_url, facebook_url, instagram_url, tiktok_url, whatsapp_number,
availability(JSON), price_from(decimal 8,2), verification_attempts,
timestamps
```

### Tablas de Relación
```
appointments:    id, client_id(FK→users), provider_id(FK→users), pet_id(FK→pets nullable), scheduled_at, status('pending','confirmed','cancelled','completed'), notes, timestamps
reviews:         id, user_id(FK), provider_id(FK→users), rating(int), comment, timestamps
favorites:       id, user_id(FK), provider_id(FK→users), timestamps (unique: user_id+provider_id)
portfolio_images: id, user_id(FK), image_path, title, timestamps
user_addresses:  id, user_id(FK), name, address, reference, district_id, is_default, coordinates(JSON), timestamps, deleted_at
```

### Tablas de IA
```
health_analyses: id, pet_id(FK), user_id(FK), analysis_type('feces','urine','skin'), image_path, ai_response(JSON), findings(JSON), requires_attention(bool), recommendations, confidence_score(float), timestamps
care_plans:      id, user_id(FK), pet_id(FK), pet_data(JSON), plan_data(JSON), generation_method, is_favorite(bool), timestamps
```

### Tablas Ubigeo (Perú)
```
departments:  id(char 2), name
provinces:    id(char 4), department_id(FK), name
districts:    id(char 6), province_id(FK), department_id, name
```

---

## 5. INVENTARIO DE FUNCIONALIDADES — QUÉ EXISTE Y QUÉ FALTA

### ✅ FUNCIONAL (Lo que ya está implementado y funcionando)

| # | Funcionalidad | Vista/Componente | Notas |
|---|--------------|------------------|-------|
| 1 | Registro con 10 roles | `Auth\Register` | client + 9 tipos de proveedor |
| 2 | Login/Logout | `Auth\Login` + closure | Básico, sin "recordar", sin reset password |
| 3 | Landing page | `Pages\Home` | Hero, categorías, proveedores destacados, sección IA, CTA |
| 4 | Búsqueda de proveedores | `Pages\Search` | Filtro por servicio, ubicación, verificado, 24h, domicilio, etc. |
| 5 | Perfil público proveedor | `Pages\Profile` | Tabs: About, Reseñas, Portafolio, Contacto. Modal de cita |
| 6 | Dashboard cliente | `Dashboard\ClientDashboard` | Lista de mascotas con acciones rápidas |
| 7 | CRUD de mascotas | `Dashboard\PetForm` | 3 tabs: General (datos+foto), Comportamiento (energía, sociabilidad), Salud (vacunas, notas) |
| 8 | Perfil público de mascota | `Pages\PetProfile` | Acceso por UUID, QR-ready |
| 9 | Detección de raza con IA | `Dashboard\PetForm` → `AIVisionService` | Gemini Vision analiza foto y autocompleta raza |
| 10 | Recomendaciones nutricionales | `DietRecommendationService` | Se genera junto con la detección de raza |
| 11 | Dashboard proveedor | `Dashboard\ProviderDashboard` | Editar perfil, bio, precios, redes sociales, horarios, portafolio, verificación |
| 12 | Gestión de citas (proveedor) | `Dashboard\ProviderAppointments` | Ver, confirmar, cancelar, completar citas |
| 13 | Agendar cita (cliente) | `Pages\Profile` → modal | Fecha, hora, notas. Genera link WhatsApp al confirmar |
| 14 | Reseñas | `Pages\Profile` | Rating 1-5 + comentario. Ordenar por fecha/rating |
| 15 | Favoritos | `Pages\Search` + `Dashboard\ClientFavorites` | Toggle corazón, lista de favoritos |
| 16 | Direcciones | `Dashboard\ClientAddresses` | CRUD con ubigeo cascada (Depto→Prov→Distrito), default |
| 17 | Portafolio proveedor | `Dashboard\ProviderDashboard` | Subir/eliminar imágenes con título |
| 18 | Verificación documental | `Dashboard\ProviderDashboard` | Subir PDF/imagen, máx 2 intentos |

### 🔒 CONSTRUIDO PERO DESACTIVADO (Rutas comentadas)

| # | Funcionalidad | Componente | Estado |
|---|--------------|-----------|--------|
| 1 | Análisis de salud IA (heces, orina, piel) | `Dashboard\HealthAnalyzer` + `Demo\DemoHealthAnalyzer` | Componente completo (25KB vista), rutas comentadas en web.php |
| 2 | Planes de cuidado IA | `Dashboard\CarePlanGenerator` + `Demo\DemoCarePlan` | Componente completo (35KB vista), rutas comentadas |
| 3 | Historial de análisis IA | `Dashboard\HealthHistory` | Componente completo (26KB vista), ruta comentada |

### ❌ NO EXISTE (Lo que falta completamente)

| # | Funcionalidad | Impacto |
|---|--------------|---------|
| 1 | **"Mis Citas" para el cliente** | CRÍTICO — El cliente agenda pero no puede ver sus citas |
| 2 | **Panel de Administración** | CRÍTICO — No hay forma de verificar proveedores, moderar, ver stats |
| 3 | **Perfil editable del cliente** | ALTO — No se puede cambiar nombre, foto, email o contraseña |
| 4 | **Reset de contraseña** | ALTO — Si pierdes la contraseña, no hay recuperación |
| 5 | **Verificación de email** | MEDIO — MustVerifyEmail está comentado en User.php |
| 6 | **Pagos online** | ALTO — Culqi instalado pero sin implementar |
| 7 | **Notificaciones** | ALTO — No hay emails ni notificaciones in-app |
| 8 | **Chat/Mensajería** | MEDIO — Toda la comunicación depende de WhatsApp externo |
| 9 | **Mapas/Geolocalización** | MEDIO — Sin Google Maps, sin "cerca de mí" |
| 10 | **Catálogo de servicios/precios** | MEDIO — Solo hay un campo genérico `price_from` |
| 11 | **Eliminación de mascotas** | BAJO — SoftDeletes en modelo pero sin botón/acción |
| 12 | **Footer** | BAJO — La app no tiene footer |

---

## 6. 🐛 BUGS Y PROBLEMAS TÉCNICOS

| # | Problema | Severidad | Archivo | Línea |
|---|----------|-----------|---------|-------|
| 1 | **`/seed-services` expuesto sin protección** — Cualquiera puede resetear la BD en producción | 🔴 Crítico | `routes/web.php` | L51-60 |
| 2 | **Credenciales demo inconsistentes** — CLAUDE.md dice `admin@mascotas.pe`, seeders usan `admin@todopeludos.com` | 🔴 Alto | `CLAUDE.md` L131 vs seeders |
| 3 | **Branding inconsistente** — Se mezcla "TodoPeludos", "Kivets" y "mascotas" | 🟡 Medio | Múltiples archivos |
| 4 | **Reseñas sin validación de servicio** — Cualquier usuario logueado puede reseñar sin haber contratado | 🟡 Medio | `app/Livewire/Pages/Profile.php` L133-156 |
| 5 | **No se valida duplicidad de reseñas** — Un usuario puede dejar múltiples reseñas al mismo proveedor | 🟡 Medio | `app/Livewire/Pages/Profile.php` L133 |
| 6 | **`/logout` sin middleware auth** — POST abierto | 🟡 Medio | `routes/web.php` L62-67 |
| 7 | **MustVerifyEmail comentado** — Sin verificación de email | 🟡 Medio | `app/Models/User.php` L5 |
| 8 | **Home muestra stats hardcodeados** — "4.9/5 Calificación", "100% Verificados" no son reales | 🟡 Medio | `home.blade.php` L44-49 |
| 9 | **Archivos de portafolio no se borran del storage** — Solo se elimina el registro DB | 🟢 Bajo | `ProviderDashboard.php` L437-446 |
| 10 | **QR Code nunca se genera visualmente** — Tiene UUID pero no genera el código QR | 🟢 Bajo | `PetForm.php` — falta llamar a simplesoftwareio |

---

## 7. MEJORAS PROPUESTAS (45+ items)

### 🔴 PRIORIDAD CRÍTICA — Sprint 1 y 2

#### Para CLIENTES
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| C1 | ✅ **Mis Citas (vista cliente)** | Dashboard con lista de citas agendadas, filtrar por estado, cancelar | Media |
| C2 | ✅ **Selección de mascota al agendar** | Campo `pet_id` existe en appointments pero no se usa en el modal | Baja |
| C3 | ✅ **Perfil editable del cliente** | Cambiar nombre, email, foto, contraseña | Media |
| C4 | **Historial de servicios** | Log de servicios contratados con fecha, proveedor, precio | Media |
| C5 | **Notificaciones por email** | Confirmación de registro, cita confirmada/cancelada | Media |
| C6 | ✅ **Restricción de reseñas** | Solo reseñar tras servicio completado, máx 1 por proveedor | Baja |

#### Para PROVEEDORES
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| P1 | **Dashboard con estadísticas** | Total citas, calificación promedio, vistas de perfil, ingresos | Media |
| P2 | **Catálogo de servicios con precios** | Tabla de servicios individuales (vs. solo `price_from`) | Alta |
| P3 | **Contra-propuesta de horario** | Si el horario no conviene, proponer alternativa al cliente | Media |
| P4 | **Motivo de rechazo en citas** | Al cancelar/rechazar, indicar razón | Baja |
| P5 | **Responder reseñas** | El proveedor puede dar una respuesta pública a cada reseña | Media |
| P6 | **Notificación de nueva cita** | Email/alerta cuando un cliente agenda | Media |

#### Plataforma
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| A1 | ✅ **Panel de Administración** | Dashboard con KPIs, gestión de usuarios, verificación, moderación | Alta |
| A2 | **Pagos con Culqi** | Cobro online, comisiones, historial, reembolsos | Alta |
| A3 | ✅ **Reset de contraseña** | Flujo "Olvidé mi contraseña" por email | Baja |
| A4 | **Verificación de email** | Activar MustVerifyEmail, enviar email de confirmación | Baja |

---

### 🟡 PRIORIDAD IMPORTANTE — Sprint 3 y 4

#### Para CLIENTES
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| C7 | **Chat/Mensajería in-app** | Comunicación interna sin depender de WhatsApp | Alta |
| C8 | **Búsqueda por rango de precios** | Slider o inputs min/max en la búsqueda | Baja |
| C9 | **Búsqueda por rating mínimo** | Filtro "4+ estrellas" | Baja |
| C10 | **Multi-mascota en citas** | Agendar para varias mascotas a la vez | Media |
| C11 | **QR funcional para mascotas** | Generar el QR visual con la URL del perfil público | Baja |
| C12 | **Carnet de vacunación digital** | Historial completo de vacunas exportable | Media |

#### Para PROVEEDORES
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| P7 | **Calendario visual** | Vista tipo agenda con slots de disponibilidad | Alta |
| P8 | **Bloqueo de fechas** | Marcar días no disponibles (vacaciones, feriados) | Media |
| P9 | **Portafolio mejorado** | Categorías, videos, drag & drop para ordenar | Media |
| P10 | **Onboarding guiado** | Wizard paso a paso + checklist de completitud del perfil | Media |
| P11 | **Multi-rol** | Un usuario puede ser veterinario Y groomer | Alta |
| P12 | **Reportes descargables** | Exportar historial de citas en CSV/PDF | Media |

#### Plataforma
| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| A5 | **Google Maps integrado** | Mapa en perfil, "cerca de mí", búsqueda por proximidad | Alta |
| A6 | **SEO avanzado** | Meta tags dinámicos, URLs amigables, sitemap, Schema.org | Media |
| A7 | **Landing pages por categoría** | `/veterinarios-en-miraflores` con SEO optimizado | Media |
| A8 | **Yape/Plin como pago** | Alternativas locales al pago con tarjeta | Media |
| A9 | **Activar funcionalidades IA** | Descomentar rutas de análisis de salud y planes de cuidado | Baja |

---

### 🟢 PRIORIDAD FUTURA — Sprint 5+

| # | Mejora | Descripción | Complejidad |
|---|--------|-------------|-------------|
| F1 | **PWA (Progressive Web App)** | Instalar en celular, notificaciones push, modo offline | Alta |
| F2 | **Modo oscuro** | Dark mode en toda la interfaz | Media |
| F3 | **GPS tracking en paseos** | Seguimiento en tiempo real durante paseos | Alta |
| F4 | **Live updates en hospedajes** | Fotos/videos durante el servicio | Alta |
| F5 | **Teleconsulta veterinaria** | Videollamada con el veterinario | Alta |
| F6 | **Emergencias 24/7** | Botón de emergencia, geolocalización de clínicas | Alta |
| F7 | **Gamificación** | Badges, puntos, niveles de proveedor (Bronce→Diamante) | Media |
| F8 | **Programa de referidos** | "Invita a un amigo y obtén descuento" | Media |
| F9 | **Suscripciones premium** | Plan gratis vs Premium vs Oro para proveedores | Alta |
| F10 | **Sistema de disputas** | Resolución de conflictos, mediación, garantía | Alta |
| F11 | **Seguro para mascotas** | Partnership con aseguradoras | Alta |
| F12 | **Comunidad/Blog** | Foro, tips, eventos, mascotas en adopción | Media |
| F13 | **Multi-idioma** | Español + Inglés + Quechua | Media |
| F14 | **WhatsApp Business API** | Mensajes automáticos, no solo links wa.me | Alta |
| F15 | **API pública** | REST API para integraciones de terceros | Alta |

---

## 8. ROADMAP SUGERIDO

```
Sprint 1 (Fundamentos)          Sprint 2 (Monetización)
├── A1: Panel Admin básico       ├── A2: Integración Culqi
├── C1: Mis Citas (cliente)      ├── P2: Catálogo de servicios
├── C3: Perfil editable          ├── A8: Yape/Plin
├── A3: Reset password           ├── P1: Dashboard con stats
├── A4: Verificación email       └── A9: Activar features IA
└── Fix bugs 1-4,6,7

Sprint 3 (Confianza)             Sprint 4 (Comunicación)
├── C6: Reseñas verificadas      ├── C7: Chat in-app
├── P5: Responder reseñas        ├── C5: Emails de notificación
├── C11: QR funcional            ├── P6: Alerta de nueva cita
├── P10: Onboarding guiado       └── P7: Calendario visual
└── A6: SEO avanzado

Sprint 5 (Crecimiento)
├── A5: Google Maps
├── F1: PWA
├── A7: Landing pages SEO
├── P11: Multi-rol
└── F7: Gamificación
```

---

## 9. CONFIGURACIÓN IA (config/ai.php)

### Modelos y Endpoints
- **Proveedor:** Google Gemini
- **Modelo:** `gemini-1.5-flash` (configurable via `GEMINI_MODEL`)
- **API URL:** `https://generativelanguage.googleapis.com/v1beta/models/`
- **Límite diario:** 10 análisis por usuario (`AI_MAX_DAILY_ANALYSES`)

### Prompts configurados
1. **Análisis de heces:** Identifica color, consistencia, parásitos, anomalías → JSON
2. **Análisis de orina:** Color, claridad, sangre, deshidratación → JSON
3. **Análisis de piel/lengua:** Lesiones, parásitos, erupciones, mucosas → JSON
4. **Detección de raza:** Razas (máx 3 para mestizos), confianza, tamaño, tipo de pelaje → JSON

---

## 10. DEPLOYMENT (Producción)

### Google Cloud Run
- **Proyecto GCP:** `tucandidatoperu`
- **Región:** `us-central1`
- **Contenedor:** PHP-FPM + Nginx + Supervisor (queue worker)
- **BD:** MySQL en Cloud SQL (socket Unix)
- **Storage:** Google Cloud Storage (bucket configurado en `GCS_BUCKET`)
- **Secretos:** `.env` montado desde Google Secret Manager (`kivets-env` → `/secrets/.env`)

### Comando de deploy
```powershell
.\deploy.ps1 -ProjectId "tucandidatoperu" -Region "us-central1"
```

---

> **NOTA:** Este documento debe actualizarse cada vez que se implemente una mejora significativa. Marcar items como ✅ completados en la sección 7.
