#!/bin/bash

# Script de setup inicial de infraestructura GCP
# Uso: ./setup-gcp.sh [PROJECT_ID] [REGION]

set -e

PROJECT_ID=${1:-"kivets-production"}
REGION=${2:-"us-central1"}
DB_INSTANCE="kivets-db-production"
DB_NAME="kivets_production"
BUCKET_NAME="${PROJECT_ID}-storage"

echo "🏗️  Setting up GCP infrastructure for Kivets..."
echo "Project: ${PROJECT_ID}"
echo "Region: ${REGION}"
echo ""

# Habilitar APIs necesarias
echo "📦 Enabling required APIs..."
gcloud services enable \
  run.googleapis.com \
  sqladmin.googleapis.com \
  secretmanager.googleapis.com \
  storage-component.googleapis.com \
  cloudbuild.googleapis.com \
  --project=${PROJECT_ID}

echo "✅ APIs enabled"
echo ""

# Crear Cloud SQL instance
echo "💾 Creating Cloud SQL instance..."
if gcloud sql instances describe ${DB_INSTANCE} --project=${PROJECT_ID} 2>/dev/null; then
    echo "ℹ️  Cloud SQL instance already exists"
else
    gcloud sql instances create ${DB_INSTANCE} \
      --database-version=MYSQL_8_0 \
      --tier=db-f1-micro \
      --region=${REGION} \
      --root-password=$(openssl rand -base64 32) \
      --backup \
      --backup-start-time=03:00 \
      --project=${PROJECT_ID}
    
    echo "✅ Cloud SQL instance created"
fi

# Crear base de datos
echo "📊 Creating database..."
gcloud sql databases create ${DB_NAME} \
  --instance=${DB_INSTANCE} \
  --project=${PROJECT_ID} 2>/dev/null || echo "ℹ️  Database already exists"

echo "✅ Database ready"
echo ""

# Crear Cloud Storage bucket
echo "🪣 Creating Cloud Storage bucket..."
if gsutil ls -p ${PROJECT_ID} gs://${BUCKET_NAME} 2>/dev/null; then
    echo "ℹ️  Bucket already exists"
else
    gsutil mb -p ${PROJECT_ID} -l ${REGION} gs://${BUCKET_NAME}
    gsutil iam ch allUsers:objectViewer gs://${BUCKET_NAME}
    echo "✅ Bucket created"
fi

echo ""

# Crear secrets (requiere valores manuales)
echo "🔐 Creating secrets in Secret Manager..."
echo ""
echo "You need to create the following secrets manually:"
echo ""
echo "1. APP_KEY (Laravel app key):"
echo "   Generate: php artisan key:generate --show"
echo "   Create: echo -n 'base64:YOUR_KEY' | gcloud secrets create APP_KEY --data-file=- --project=${PROJECT_ID}"
echo ""
echo "2. DB_PASSWORD (from Cloud SQL root password or create new user):"
echo "   Create: echo -n 'YOUR_PASSWORD' | gcloud secrets create DB_PASSWORD --data-file=- --project=${PROJECT_ID}"
echo ""
echo "3. GEMINI_API_KEY:"
echo "   Create: echo -n 'YOUR_GEMINI_KEY' | gcloud secrets create GEMINI_API_KEY --data-file=- --project=${PROJECT_ID}"
echo ""

# Información de conexión
CONNECTION_NAME="${PROJECT_ID}:${REGION}:${DB_INSTANCE}"

echo "✅ Infrastructure setup completed!"
echo ""
echo "📝 Important information:"
echo "Cloud SQL Connection Name: ${CONNECTION_NAME}"
echo "Database Name: ${DB_NAME}"
echo "Storage Bucket: gs://${BUCKET_NAME}"
echo ""
echo "Next steps:"
echo "1. Create the secrets listed above"
echo "2. Run ./deploy.sh ${PROJECT_ID} ${REGION}"
