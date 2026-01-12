#!/bin/bash

# Script de deployment a Cloud Run
# Uso: ./deploy.sh [PROJECT_ID] [REGION]

set -e

PROJECT_ID=${1:-"kivets-production"}
REGION=${2:-"us-central1"}
SERVICE_NAME="kivets"
IMAGE_NAME="gcr.io/${PROJECT_ID}/${SERVICE_NAME}"

echo "🚀 Deploying Kivets to Cloud Run..."
echo "Project: ${PROJECT_ID}"
echo "Region: ${REGION}"
echo ""

# Verificar que gcloud está instalado
if ! command -v gcloud &> /dev/null; then
    echo "❌ Error: gcloud CLI no está instalado"
    echo "Instala desde: https://cloud.google.com/sdk/docs/install"
    exit 1
fi

# Configurar proyecto
echo "📝 Setting project..."
gcloud config set project ${PROJECT_ID}

# Build de la imagen
echo "🔨 Building Docker image..."
gcloud builds submit --tag ${IMAGE_NAME} .

# Deploy a Cloud Run
echo "☁️  Deploying to Cloud Run..."
gcloud run deploy ${SERVICE_NAME} \
  --image ${IMAGE_NAME} \
  --platform managed \
  --region ${REGION} \
  --allow-unauthenticated \
  --memory 512Mi \
  --cpu 1 \
  --timeout 300 \
  --max-instances 10 \
  --min-instances 0 \
  --port 8080 \
  --set-env-vars="APP_NAME=Kivets,APP_ENV=production,APP_DEBUG=false,LOG_CHANNEL=stderr" \
  --set-secrets="DB_PASSWORD=DB_PASSWORD:latest,APP_KEY=APP_KEY:latest,GEMINI_API_KEY=GEMINI_API_KEY:latest" \
  --add-cloudsql-instances="${PROJECT_ID}:${REGION}:kivets-db-production"

# Obtener URL del servicio
SERVICE_URL=$(gcloud run services describe ${SERVICE_NAME} --region ${REGION} --format 'value(status.url)')

echo ""
echo "✅ Deployment completed!"
echo "🌐 Service URL: ${SERVICE_URL}"
echo ""
echo "Next steps:"
echo "1. Configure custom domain: gcloud run domain-mappings create --service ${SERVICE_NAME} --domain kivets.com --region ${REGION}"
echo "2. Update Cloudflare DNS to point to Cloud Run"
echo "3. Test the application: curl ${SERVICE_URL}/health"
