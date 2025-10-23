# EstadoCol Blog (MVC PHP + Bootstrap 5)

Blog educativo minimalista para explicar Elementos básicos del Estado colombiano.
- Arquitectura MVC ligera (sin frameworks).
- Almacenamiento en `storage/posts.json`.
- Contenidos propios: infografías (SVG), pósters (CSS), animación tipo "video" (Canvas), entrevistas con grabación de audio (MediaRecorder).
- Sin YouTube ni recursos descargados.

## Requisitos
- PHP 8+
- Apache con `mod_rewrite` (o usar `php -S localhost:8000 -t public` y adaptar rutas).

## Run local
```bash
php -S localhost:8000 -t public
