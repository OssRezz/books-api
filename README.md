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

### 3. Ejecutar migraciones y seeders (Requerido crear la base de datos antes de ejecutar este comando)

```bash
php artisan migrate --seed
```

### 4. Levantar servidor

```bash
php artisan serve
```

### 5(Opcional). En sistemas unix brindar permisos  a storage/ y bootstrap/

```bash
sudo chmod -R 777 storage/  bootstrap/
```

Accede desde: `http://localhost:8000`

---


## 🧪 Testing

Este proyecto cuenta con una suite completa de pruebas automatizadas utilizando **PHPUnit**, enfocadas en verificar la integridad y funcionamiento correcto de todos los módulos de la API:

- Autenticación
- Usuarios
- Libros
- Préstamos y devoluciones

Las pruebas están organizadas dentro del directorio `tests/Feature`, y hacen uso de factories, seeders y base de datos en memoria para asegurar un entorno controlado.

### ⚙️ Configuración del entorno de pruebas

1. Copia el archivo de entorno principal:

```bash
cp .env .env.testing
```

2. Asegúrate de configurar `.env.testing` con una base de datos separada. Se recomienda SQLite en memoria:

```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

Esto asegura que los datos se eliminen automáticamente después de cada prueba.

### ▶️ Ejecutar pruebas

Para correr toda la suite de pruebas, ejecuta:

```bash
php artisan test
```

Laravel se encargará de preparar automáticamente la base de datos de pruebas, ejecutar migraciones y correr los tests.

### 📂 Cobertura de pruebas

Los tests implementados cubren los siguientes módulos y escenarios:

| Módulo       | Escenarios cubiertos                                                                 |
|--------------|----------------------------------------------------------------------------------------|
| Auth         | Login exitoso, login fallido, acceso protegido sin token, campos faltantes           |
| Users        | Listado de usuarios, creación, edición, eliminación                                  |
| Books        | Listado, creación, actualización y eliminación de libros                             |
| Borrowings   | Préstamos válidos, límite de 3 libros, retorno de libros, consulta de préstamo actual, filtros |

Cada test también valida las respuestas esperadas, errores, códigos HTTP y la estructura estandarizada del JSON de salida.

---

## 🐳 Docker (opcional)

El proyecto incluye archivos listos para Docker:

```bash
docker compose up --build
```

Docker no es usado para desarrollo, su enfoque esta orientado a servidores de producción. Al levantar la app, ingresamos por el puerto 8080

---

## ☁️ Despliegue en AWS (referencia)

Este sistema fue desplegado en un entorno productivo utilizando un servidor **EC2 de AWS**, configurado con:

- Amazon Linux
- Docker
- **NGINX** como reverse proxy apuntando al backend
- **Certbot** para certificado SSL con Let's Encrypt
- **RDS** base de datos MySQL en AWS



---

## 🌐 Documentación de la API

Importa el archivo Postman disponible en `docs/lendify-api.postman_collection.json`.

Variables globals desde de la colección: 

`{{URL}}` → `http://localhost:8000` o el puerto de Docker

`{{TOKEN}}` → La aplicación usa `Authorization` con bearer token, una vez se haga Login, se setea en las variables de entorno de la colección


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
