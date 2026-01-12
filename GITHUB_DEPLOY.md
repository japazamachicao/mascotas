# Guía de Despliegue desde GitHub con Cloud Build

Esta es la forma más robusta de desplegar, ya que evita problemas locales de subida de archivos.

## Pasos

### 1. Subir cambios a GitHub
Asegúrate de que todo tu código (incluido `Dockerfile` y `cloudbuild.yaml`) esté en tu repositorio remoto.

```bash
git add .
git commit -m "Configurar Cloud Build"
git push origin master
```

### 2. Conectar repositorio en GCP

1. Ve a la consola de Google Cloud: [Cloud Build > Triggers](https://console.cloud.google.com/cloud-build/triggers)
2. Clic en **"Crear activador"** (Create Trigger).
3. **Nombre:** `Deploy Kivets`
4. **Evento:** Push to a branch
5. **Fuente:** Selecciona tu repositorio de GitHub (te pedirá autorizar si es la primera vez).
6. **Rama:** `^master$` (o `main`)
7. **Configuración:** Archivo de configuración de Cloud Build (yaml o json)
8. **Ubicación:** `cloudbuild.yaml` (default)
9. Clic en **Crear**.

### 3. Ejecutar el primer despliegue

1. En la lista de Triggers, busca el que acabas de crear.
2. Clic en el botón **EJECUTAR** (Run) a la derecha.
3. Esto iniciará el proceso de build en la nube.

Puedes ver el progreso en la pestaña **Historial**.

### Nota Importante sobre Permisos

Cloud Build necesita permisos para desplegar en Cloud Run y acceder a Cloud SQL.
Si el build falla por permisos, ve a IAM y asegúrate de que el email de servicio de Cloud Build (`[NUMERO]@cloudbuild.gserviceaccount.com`) tenga los roles:
- **Cloud Run Admin**
- **Service Account User**
- **Cloud SQL Client**
