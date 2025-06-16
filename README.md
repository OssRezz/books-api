# 📚 Lendify API - Backend Laravel

Este proyecto es una API REST desarrollada con **Laravel**, siguiendo buenas prácticas de arquitectura, validación y documentación. "Lendify" es una plataforma de préstamos de libros, diseñada para facilitar la gestión de usuarios, libros, préstamos y devoluciones.

---

## ✨ Características Principales

- CRUD de usuarios y libros
- Lógica de préstamos y devoluciones de libros
- Validaciones robustas con `FormRequest`
- Documentación organizada mediante Postman
- Dockerizado para ambientes locales o productivos
- Arquitectura limpia y mantenible (responsabilidad por capas)
- Respuestas estandarizadas con formato uniforme

---

## 🤝 Tecnologías Utilizadas

- Laravel 12
- PHP 8.2
- MySQL o PostgreSQL
- Docker
- Laravel Sail (opcional para entorno local)
- PHPUnit

---

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/         # Controladores de cada módulo
│   ├── Requests/            # FormRequest con validaciones
│   └── Resources/           # Formato de salida consistente
├── Models/                  # Modelos Eloquent
├── Services/                # Lógica de negocio reutilizable
├── Helpers/                 # Funciones comunes
routes/
├── api.php                 # Rutas de la API REST
config/
└── lendify.php             # Configuración personalizada (si aplica)
```

---

## 🚀 Ejecución Local

### 1. Clonar y configurar

```bash
cp .env.example .env
php artisan key:generate
```

Configura tus variables de entorno en `.env`, por ejemplo:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lendify
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

### 4. Levantar el servidor

```bash
php artisan serve
```

La app correrá en `http://localhost:8000`

---

## 🚧 Docker

Para entornos productivos o pruebas integradas:

```bash
docker compose up --build
```

Revisa que tu archivo `Dockerfile` y `docker-compose.yml` estén correctamente configurados.

---

## 🌐 Documentación de la API

Toda la API está documentada en una colección **Postman** organizada por módulos:

- Autenticación (si aplica)
- Usuarios
- Libros
- Préstamos
- Devoluciones

### 🔗 Importar la colección

Puedes importar el archivo `docs/lendify-api.postman_collection.json` en Postman.

La variable global `{{APP_URL}}` debe apuntar a tu backend:

```env
APP_URL=http://localhost:8000
```

---

## 🎓 Buenas Prácticas Aplicadas

### 🔒 Seguridad

- Validaciones estrictas con `FormRequest`
- Protección CSRF (excepto en API si se configura como `stateless`)
- Manejo de errores con respuestas consistentes

### ✅ Validaciones

Todos los endpoints están protegidos por clases `FormRequest`, que contienen las reglas de validación centralizadas y reutilizables.

### 📊 Formato de Respuesta

La estructura estandarizada de las respuestas es:

```json
{
  "success": true,
  "code": 200,
  "message": "Books list",
  "data": [...]
}
```

En errores:

```json
{
  "success": false,
  "code": 422,
  "message": "Validation failed",
  "data": {
    "title": ["The title field is required."]
  }
}
```

### 🧳 Pruebas

Se utilizan pruebas unitarias con PHPUnit:

```bash
php artisan test
```

- Pruebas en controllers, servicios y helpers
- Factories y seeders para generar datos falsos

---

## 📊 Endpoints Principales

### 👤 Usuarios

- `GET /api/users`
- `POST /api/users`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

### 📖 Libros

- `GET /api/books`
- `POST /api/books`
- `PUT /api/books/{id}`
- `DELETE /api/books/{id}`

### 💼 Préstamos

- `POST /api/borrows` - Crear préstamo (acepta array de `book_ids` y `user_id`)
- `GET /api/borrows` - Ver préstamos activos y devueltos

### 📃 Devoluciones

- `POST /api/returns` - Devolver libros (basado en préstamos pendientes)

---

## 🌟 Contribuciones

Este proyecto puede escalarse para agregar:

- Notificaciones por email
- Webhooks (para integraciones externas)
- Historial de actividad
- Roles y permisos avanzados

---

## 🙌 Créditos

Desarrollado con ❤️ por el equipo de Lendify. Para dudas, soporte o contribuciones, por favor abre un issue o contáctanos.

---

## 📄 Licencia

Este proyecto está bajo la licencia [MIT](LICENSE).

