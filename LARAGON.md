# Guía de Inicio Local con Laragon (Base de Datos)

Esta guía explica cómo ejecutar la aplicación **Kivets** en tu entorno local usando el servidor de desarrollo estándar de Laravel (`php artisan serve`) pero conectándose al servidor de base de datos MySQL provisto por **Laragon**.

---

## Pasos para iniciar la aplicación:

### Paso 1: Iniciar Laragon
1. Abre **Laragon**.
2. Haz clic en **"Start All"** (Iniciar Todo) para levantar el servidor de base de datos MySQL (y Apache).

### Paso 2: Abrir la terminal de Laragon
1. En Laragon, haz clic en el botón **"Terminal"**.
   > *Nota: Al usar la terminal propia de Laragon, te aseguras de tener `php`, `composer`, `node` y `npm` correctamente configurados en tu PATH.*
2. Ve al directorio del proyecto (si no estás ya ahí):
   ```cmd
   cd "d:\Proyectos - pe\mascotas"
   ```

### Paso 3: Ejecutar el servidor de desarrollo
1. En la terminal de Laragon, ejecuta:
   ```bash
   composer dev
   ```
   
   > **¿Qué hace este comando?**
   > Levanta de forma simultánea todos los procesos requeridos:
   > - **Laravel Server (`php artisan serve --port=3000`)** en http://127.0.0.1:3000
   > - **Vite (`npm run dev`)** para compilar estilos y scripts con Tailwind CSS 4.
   > - **Queue listener (`php artisan queue:listen`)** para procesar los análisis de IA y colas de trabajo.

---

## Acceso al proyecto:

Una vez que el comando esté corriendo, abre tu navegador e ingresa a:

👉 **[http://127.0.0.1:3000](http://127.0.0.1:3000)**
