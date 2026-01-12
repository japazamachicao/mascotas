# Script de setup inicial de infraestructura GCP para Windows (PowerShell)
# Uso: .\setup-gcp.ps1 -ProjectId "tucandidatoperu" -Region "us-central1"

param (
    [string]$ProjectId = "tucandidatoperu",
    [string]$Region = "us-central1"
)

$ErrorActionPreference = "Stop"

$DB_INSTANCE = "kivets-db-production"
$DB_NAME = "kivets_production"
$BUCKET_NAME = "$ProjectId-storage"

Write-Host "Setting up GCP infrastructure for Kivets..." -ForegroundColor Cyan
Write-Host "Project: $ProjectId"
Write-Host "Region: $Region"
Write-Host ""

# Verificar gcloud
if (-not (Get-Command gcloud -ErrorAction SilentlyContinue)) {
    Write-Error "Error: gcloud CLI not installed. Install from: https://cloud.google.com/sdk/docs/install"
    exit 1
}

# Configurar proyecto
Write-Host "Setting project..." -ForegroundColor Yellow
gcloud config set project $ProjectId

# Habilitar APIs necesarias
Write-Host "Enabling required APIs..." -ForegroundColor Yellow
gcloud services enable `
  run.googleapis.com `
  sqladmin.googleapis.com `
  secretmanager.googleapis.com `
  storage-component.googleapis.com `
  cloudbuild.googleapis.com `
  --project=$ProjectId

Write-Host "APIs enabled" -ForegroundColor Green
Write-Host ""

# Crear Cloud SQL instance
Write-Host "Creating Cloud SQL instance..." -ForegroundColor Yellow
gcloud sql instances describe $DB_INSTANCE --project=$ProjectId 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "Cloud SQL instance already exists" -ForegroundColor Gray
} else {
    # Generar password aleatoria
    $rootPassword = -join ((33..126) | Get-Random -Count 32 | % {[char]$_})
    
    gcloud sql instances create $DB_INSTANCE `
      --database-version=MYSQL_8_0 `
      --tier=db-f1-micro `
      --region=$Region `
      --root-password=$rootPassword `
      --backup `
      --backup-start-time=03:00 `
      --project=$ProjectId
    
    Write-Host "Cloud SQL instance created" -ForegroundColor Green
    Write-Host "ROOT PASSWORD: $rootPassword" -ForegroundColor Red
    Write-Host " (Save this, you will need it for DB_PASSWORD secret)" -ForegroundColor Red
}

# Crear base de datos
Write-Host "Creating database..." -ForegroundColor Yellow
$databases = gcloud sql databases list --instance=$DB_INSTANCE --project=$ProjectId 2>&1
if ($databases -match $DB_NAME) {
    Write-Host "Database already exists" -ForegroundColor Gray
} else {
    gcloud sql databases create $DB_NAME `
      --instance=$DB_INSTANCE `
      --project=$ProjectId
    Write-Host "Database created" -ForegroundColor Green
}

Write-Host ""

# Crear Cloud Storage bucket
Write-Host "Creating Cloud Storage bucket..." -ForegroundColor Yellow
gsutil ls -p $ProjectId "gs://$BUCKET_NAME" 2>&1 | Out-Null
if ($LASTEXITCODE -eq 0) {
    Write-Host "Bucket already exists" -ForegroundColor Gray
} else {
    gsutil mb -p $ProjectId -l $Region "gs://$BUCKET_NAME"
    gsutil iam ch allUsers:objectViewer "gs://$BUCKET_NAME"
    Write-Host "Bucket created" -ForegroundColor Green
}

Write-Host ""

# Instrucciones para secretos
Write-Host "Creating secrets in Secret Manager..." -ForegroundColor Cyan
Write-Host ""
Write-Host "You need to create the following secrets manually:"
Write-Host ""
Write-Host "1. APP_KEY (Laravel app key):"
Write-Host "   Generate: php artisan key:generate --show"
Write-Host "   Create: echo -n 'base64:YOUR_KEY' | gcloud secrets create APP_KEY --data-file=- --project=$ProjectId"
Write-Host ""
Write-Host "2. DB_PASSWORD (use the one generated above or yours):"
Write-Host "   Create: echo -n 'YOUR_PASSWORD' | gcloud secrets create DB_PASSWORD --data-file=- --project=$ProjectId"
Write-Host ""
Write-Host "3. GEMINI_API_KEY:"
Write-Host "   Create: echo -n 'YOUR_GEMINI_KEY' | gcloud secrets create GEMINI_API_KEY --data-file=- --project=$ProjectId"
Write-Host ""

# Informacion de conexion
$CONNECTION_NAME = "$ProjectId`:$Region`:$DB_INSTANCE"

Write-Host "Infrastructure setup completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Important information:"
Write-Host "Cloud SQL Connection Name: $CONNECTION_NAME"
Write-Host "Database Name: $DB_NAME"
Write-Host "Storage Bucket: gs://$BUCKET_NAME"
Write-Host ""
Write-Host "Next steps:"
Write-Host "1. Create the secrets listed above"
Write-Host "2. Run .\deploy.ps1 -ProjectId $ProjectId -Region $Region"
