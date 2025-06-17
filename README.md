# 📚 Lendify API - Backend Laravel

Este proyecto es una API REST desarrollada con **Laravel**, diseñada para gestionar préstamos de libros entre usuarios. Cuenta con funcionalidades clave como registro de usuarios, gestión de libros, préstamos, devoluciones y control de disponibilidad, todo con validaciones robustas y arquitectura organizada.

---

## ✨ Características Principales

- CRUD de usuarios y libros
- Lógica de préstamos y devoluciones de libros
- Validaciones robustas con `FormRequest`
- Estructura de respuesta estandarizada
- Testing con PHPUnit
- Docker para desarrollo y despliegue
- Configuración para entornos de producción

---

## 🤝 Tecnologías Utilizadas

- Laravel 12
- PHP 8.2
- MySQL o PostgreSQL
- Docker
- PHPUnit

---

## 📁 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Models/
├── Services/
├── Traits/
routes/
└── api.php
```

---

## 🚀 Ejecución Local

### 1. Clonar y configurar

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus datos de conexión:

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

### 4. Levantar servidor

```bash
php artisan serve
```

Accede desde: `http://localhost:8000`

---

## ✅ Testing

### Configuración

Copia tu entorno base:

```bash
cp .env .env.testing
```

En `.env.testing`, asegúrate de usar una base de datos separada (recomendado SQLite para velocidad):

```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Ejecutar pruebas

```bash
php artisan test
```

Esto ejecutará todas las pruebas de `Feature` y `Unit`, usando una base en memoria y datos falsos mediante factories y seeders.

---

## 🐳 Docker (opcional)

El proyecto incluye archivos listos para Docker:

```bash
docker compose up --build
```

Ideal para entornos controlados o producción. Puedes personalizar el `docker-compose.yml` para apuntar a producción si lo necesitas.

---

## ☁️ Despliegue en AWS (referencia)

Este sistema fue desplegado en un entorno productivo utilizando un servidor **EC2 de AWS**, configurado con:

- Amazon Linux
- Docker
- MySQL como servicio gestionado (RDS)
- **NGINX** como reverse proxy apuntando al backend
- **Certbot** para certificado SSL con Let's Encrypt
- **RDS** base de datos MySQL en AWS



---

## 🌐 Documentación de la API

Importa el archivo Postman disponible en `docs/lendify-api.postman_collection.json`.

Variable global: `{{APP_URL}}` → `http://localhost:8000` o tu dominio

---

## 📊 Endpoints Principales

### Usuarios

- `GET /api/users`
- `POST /api/users`
- `PUT /api/users/{id}`
- `DELETE /api/users/{id}`

### Libros

- `GET /api/books`
- `POST /api/books`
- `PUT /api/books/{id}`
- `DELETE /api/books/{id}`

### Préstamos

- `POST /api/users/{userId}/borrowings/borrow`
- `POST /api/users/{userId}/borrowings/return`
- `GET /api/borrowings/book/{bookId}/current-borrower`
- `GET /api/borrowings/books/filter`

---
