# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Kivets** — a pet care services marketplace for Peru. Clients register pets and book providers (veterinarians, walkers, groomers, hotels, shelters, trainers, pet sitters, pet taxis, pet photographers). The app includes AI-powered features via Google Gemini for health analysis (feces, urine, skin images) and pet care plan generation.

## Commands

### Local Development

```bash
# First-time setup (install dependencies, copy .env, generate key, run migrations, build frontend)
composer setup

# Start all dev processes concurrently (server, queue worker, log viewer, Vite)
composer dev

# Run tests
composer test

# Run a single test
php artisan test --filter TestClassName
php artisan test tests/Feature/ExampleTest.php

# Linting (Laravel Pint)
./vendor/bin/pint

# Frontend only
npm run dev    # Vite dev server
npm run build  # Production build
```

### Artisan

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
php artisan queue:listen
```

### Deployment (Google Cloud Run)

```powershell
.\deploy.ps1 -ProjectId "tucandidatoperu" -Region "us-central1"
```

This uses `gcloud builds submit` to build the image via Cloud Build, then deploys to Cloud Run. The `.env` is injected at runtime from Google Secret Manager as `kivets-env` (mounted at `/secrets/.env`).

## Architecture

### Stack

- **Laravel 12** + **PHP 8.2** — backend framework
- **Livewire 3** — all UI is Livewire components (no separate API layer)
- **Tailwind CSS 4** + **Vite 7** — frontend styling and asset bundling
- **Spatie Permission** — role-based access control
- **Google Cloud Storage** — file storage in production (`FILESYSTEM_DISK=gcs`)
- **Google Gemini API** — AI vision analysis and care plan generation
- **Culqi** — payment processing (Peruvian payment gateway)
- **SQLite** (local dev) / **MySQL on Cloud SQL** (production)

### Application Structure

```
app/
  Livewire/
    Auth/          # Login, Register components
    Dashboard/     # Authenticated user features
    Demo/          # Public demo pages (no auth required)
    Pages/         # Public pages (Home, Search, Profile, PetProfile)
  Models/          # Eloquent models
  Services/        # Business logic services
  Support/         # Custom framework extensions
```

### Livewire Component Architecture

All routes map directly to Livewire full-page components — there are no traditional blade views with controllers. Components handle both state management and rendering.

**Public routes** → `app/Livewire/Pages/`  
**Authenticated routes** → `app/Livewire/Dashboard/`  
**AI demo routes** → `app/Livewire/Demo/`

### User Roles & Routing

Users have a single role via Spatie Permission. The `/dashboard` route inspects the user's role and redirects:
- Provider roles (`veterinarian`, `walker`, `groomer`, `hotel`, `shelter`, `trainer`, `pet_sitter`, `pet_taxi`, `pet_photographer`) → `ProviderDashboard`
- All others → `ClientDashboard`

Provider profiles are stored in separate model tables, each linked via `hasOne` on `User` (e.g., `Veterinarian`, `Walker`, `Groomer`).

### AI Services

**`AIVisionService`** (`app/Services/AIVisionService.php`) — calls Gemini API with base64-encoded images. Config is in `config/ai.php`. Requires `GEMINI_API_KEY` in `.env`. Returns structured JSON from Gemini that maps to `HealthAnalysis` model fields.

**`PetCareRecommendationService`** and **`DietRecommendationService`** — generate care plans stored as `CarePlan` records.

AI prompts are defined in `config/ai.php` under `health_analysis.prompts` and `breed_detection.prompt`.

### File Storage

Production uses a **custom GCS adapter** (`app/Support/GoogleCloudStorageAdapter.php`) registered in `AppServiceProvider`. This extends `FilesystemAdapter` to add proper `url()` and `temporaryUrl()` methods that Livewire's file preview requires. When adding new storage features, use `Storage::disk('public')` locally and `Storage::disk('gcs')` references abstracted via `FILESYSTEM_DISK`.

### Docker / Production Container

The Dockerfile builds a single container with PHP-FPM + Nginx + Supervisor. Supervisor manages:
- `php-fpm` — PHP process manager
- `nginx` — web server on port 8080
- `queue worker` — processes queued jobs

The entrypoint (`docker/entrypoint.sh`) at startup: copies `.env` from `/secrets/.env`, runs `php artisan migrate --force`, then caches config/routes/views.

### Key Environment Variables

| Variable | Purpose |
|---|---|
| `GEMINI_API_KEY` | Google Gemini API key for AI features |
| `GEMINI_MODEL` | Gemini model (default: `gemini-1.5-flash`) |
| `FILESYSTEM_DISK` | `local` (dev) or `gcs` (production) |
| `GOOGLE_CLOUD_PROJECT_ID` | GCS project |
| `GCS_BUCKET` | GCS bucket name |
| `DB_CONNECTION` | `sqlite` (dev) or `mysql` (production) |
| `DB_SOCKET` | Cloud SQL Unix socket path (production) |
| `AI_MAX_DAILY_ANALYSES` | Rate limit per user per day (default: 10) |

### Database Seeding

A `/seed-services` web route exists to run `DatabaseSeeder` directly from the browser (development convenience). Default demo credentials: `admin@mascotas.pe` / `password`.
