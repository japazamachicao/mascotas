# Deployment Guide - Kivets to Google Cloud Run

## Prerequisites

1. **Google Cloud SDK** installed and configured
   ```bash
   gcloud auth login
   gcloud config set project YOUR_PROJECT_ID
   ```

2. **Docker** installed locally (for testing)

3. **Project ID** in GCP (e.g., `kivets-production`)

## Quick Start

### 1. Setup GCP Infrastructure

**Linux/Mac:**
```bash
bash setup-gcp.sh YOUR_PROJECT_ID us-central1
```

**Windows (PowerShell):**
```powershell
.\setup-gcp.ps1 -ProjectId "YOUR_PROJECT_ID" -Region "us-central1"
```

This creates:
- Cloud SQL MySQL instance: `kivets-db-production`
- Cloud Storage bucket for files
- Enables necessary GCP APIs

### 2. Create Secrets in Secret Manager

Generate and create the required secrets:

```bash
# Generate Laravel APP_KEY
php artisan key:generate --show

# Create APP_KEY secret
echo -n 'base64:YOUR_GENERATED_KEY' | gcloud secrets create APP_KEY --data-file=-

# Create DB_PASSWORD secret (use a strong password)
echo -n 'YOUR_DB_PASSWORD' | gcloud secrets create DB_PASSWORD --data-file=-

# Create GEMINI_API_KEY secret
echo -n 'YOUR_GEMINI_KEY' | gcloud secrets create GEMINI_API_KEY --data-file=-
```

### 3. Deploy to Cloud Run

**Linux/Mac:**
```bash
bash deploy.sh YOUR_PROJECT_ID us-central1
```

**Windows (PowerShell):**
```powershell
.\deploy.ps1 -ProjectId "YOUR_PROJECT_ID" -Region "us-central1"
```

This will:
- Build the Docker image
- Push to Google Container Registry
- Deploy to Cloud Run
- Connect to Cloud SQL
- Mount secrets as environment variables

### 4. Configure Custom Domain

```bash
# Map domain to Cloud Run
gcloud run domain-mappings create \
  --service kivets \
  --domain kivets.com \
  --region us-central1

# Get the verification records
gcloud run domain-mappings describe \
  --domain kivets.com \
  --region us-central1
```

### 5. Configure Cloudflare DNS

In Cloudflare dashboard:

1. Add CNAME record:
   - Type: `CNAME`
   - Name: `@` (or `www`)
   - Target: `ghs.googlehosted.com`
   - Proxy: ON (orange cloud)

2. SSL/TLS settings:
   - Mode: **Full (strict)**
   - Always Use HTTPS: **ON**
   - Minimum TLS Version: **1.2**

## Environment Variables

Cloud Run uses these env vars (set in `deploy.sh`):

- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `LOG_CHANNEL` - Basic config
- Secrets from Secret Manager:
  - `APP_KEY` - Laravel encryption key
  - `DB_PASSWORD` - MySQL password
  - `GEMINI_API_KEY` - AI API key

## Testing

After deployment:

```bash
# Get service URL
gcloud run services describe kivets --region us-central1 --format 'value(status.url)'

# Test health endpoint
curl https://YOUR_SERVICE_URL/health

# Test application
curl https://YOUR_SERVICE_URL
```

## Monitoring

View logs:
```bash
gcloud run services logs read kivets --region us-central1 --limit 50
```

View metrics in Cloud Console:
- Go to: Cloud Run > kivets > Metrics

## Troubleshooting

### Build fails
- Check Dockerfile syntax
- Verify all files exist in docker/ directory
- Check composer.json dependencies

### Database connection fails
- Verify Cloud SQL instance is running
- Check DB_PASSWORD secret is correct
- Ensure Cloud SQL connection string is accurate

### 502/503 errors
- Check container logs
- Verify health endpoint /health returns 200
- Check memory/CPU limits (may need to increase)

## Updating Application

To deploy changes:

```bash
git push origin main
bash deploy.sh YOUR_PROJECT_ID us-central1
```

## Rollback

To rollback to previous version:

```bash
gcloud run services update-traffic kivets \
  --to-revisions=kivets-00001-abc=100 \
  --region us-central1
```

## Cost Optimization

**Development:**
- Use `db-f1-micro` tier (~$10/month)
- Set `--min-instances=0` on Cloud Run
- Use shared VPC

**Production:**
- Upgrade to `db-n1-standard-1` (~$50/month)
- Set `--min-instances=1` for faster cold starts
- Enable Cloud CDN

## Security Checklist

- ✅ All secrets in Secret Manager (never in code)
- ✅ Cloud SQL private IP only
- ✅ HTTPS enforced via Cloudflare
- ✅ CORS configured properly
- ✅ Rate limiting via Cloudflare
- ✅ Regular backups enabled on Cloud SQL

## Support

For issues:
1. Check Cloud Run logs
2. Check Cloud SQL status
3. Verify DNS propagation
4. Check Cloudflare settings

## Architecture Diagram

```
User Request
    ↓
Cloudflare (DNS + CDN + SSL)
    ↓
Cloud Run (Laravel App)
    ↓
├── Cloud SQL (MySQL Database)
├── Secret Manager (Environment Vars)
└── Cloud Storage (File uploads)
```
