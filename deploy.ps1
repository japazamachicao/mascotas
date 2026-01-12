# Script de deployment a Cloud Run para Windows (PowerShell)
# Uso: .\deploy.ps1 -ProjectId "tucandidatoperu" -Region "us-central1"

param (
    [string]$ProjectId = "tucandidatoperu",
    [string]$Region = "us-central1"
)

$ErrorActionPreference = "Stop"

$SERVICE_NAME = "kivets"
$IMAGE_NAME = "gcr.io/$ProjectId/$SERVICE_NAME"

Write-Host "🚀 Deploying Kivets to Cloud Run..." -ForegroundColor Cyan
Write-Host "Project: $ProjectId"
Write-Host "Region: $Region"
Write-Host ""

# Verificar gcloud
if (-not (Get-Command gcloud -ErrorAction SilentlyContinue)) {
    Write-Error "❌ Error: gcloud CLI no está instalado. Instala desde: https://cloud.google.com/sdk/docs/install"
    exit 1
}

# Configurar proyecto
Write-Host "📝 Setting project..." -ForegroundColor Yellow
gcloud config set project $ProjectId

# Build de la imagen
Write-Host "🔨 Building Docker image..." -ForegroundColor Yellow
gcloud builds submit --tag $IMAGE_NAME .

# Deploy a Cloud Run
Write-Host "☁️  Deploying to Cloud Run..." -ForegroundColor Yellow
gcloud run deploy $SERVICE_NAME `
  --image $IMAGE_NAME `
  --platform managed `
  --region $Region `
  --allow-unauthenticated `
  --memory 512Mi `
  --cpu 1 `
  --timeout 300 `
  --max-instances 10 `
  --min-instances 0 `
  --port 8080 `
  --set-env-vars="APP_NAME=Kivets,APP_ENV=production,APP_DEBUG=false,LOG_CHANNEL=stderr,DB_CONNECTION=mysql,DB_SOCKET=/cloudsql/tucandidatoperu:us-central1:tucandidato-db,DB_DATABASE=kivets" `
  --set-secrets="/var/www/html/.env=kivets-env:latest" `
  --add-cloudsql-instances="tucandidatoperu:us-central1:tucandidato-db"

# Obtener URL del servicio
$SERVICE_URL = gcloud run services describe $SERVICE_NAME --region $Region --format 'value(status.url)'

Write-Host ""
Write-Host "✅ Deployment completed!" -ForegroundColor Green
Write-Host "🌐 Service URL: $SERVICE_URL" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:"
Write-Host "1. Configure custom domain: gcloud run domain-mappings create --service $SERVICE_NAME --domain kivets.com --region $Region"
Write-Host "2. Update Cloudflare DNS to point to Cloud Run"
Write-Host "3. Test the application: curl $SERVICE_URL/health"
