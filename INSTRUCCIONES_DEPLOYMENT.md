# Estética Victoria - Sistema de Gestión

Sistema completo de login, dashboard admin y landing page personalizada para Estética Victoria.

## 📋 Características

- ✅ Sistema de autenticación con dos tipos de usuario (admin y user)
- ✅ Dashboard administrativo con gestión de usuarios y ventas
- ✅ Landing page personalizada con saludo por nombre
- ✅ Consultas de ventas diarias y por rango de fechas
- ✅ Gráficos de ventas en tiempo real
- ✅ Base de datos optimizada con MySQL
- ✅ Listo para desplegar en Railway

## 🗂️ Estructura del Proyecto

```
landing page IAA/
├── public/                 # Archivos públicos
│   ├── index.php          # Redireccionamiento
│   ├── login.html         # Formulario de login
│   ├── dashboard.html     # Dashboard admin
│   └── landing.php        # Landing page autenticada
├── php/
│   ├── config/
│   │   └── db.php         # Configuración de BD
│   ├── auth/
│   │   ├── session.php    # Gestión de sesiones
│   │   ├── procesar_login.php
│   │   └── logout.php
│   └── api/
│       ├── usuarios.php   # API usuarios
│       └── ventas.php     # API ventas
├── css/
│   ├── page-sections/
│   │   └── style.css
│   └── img/
├── database/
│   └── schema.sql         # Script de BD
├── .htaccess             # Reescritura de URLs
├── .env.example          # Variables de entorno
├── Procfile              # Configuración Railway
└── composer.json         # Dependencias PHP
```

## 🚀 Instalación Local

### Requisitos
- PHP 8.0+
- MySQL 5.7+
- Composer (opcional)

### Pasos

1. **Clonar o descargar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/landing-page-iaa.git
   cd landing-page-iaa
   ```

2. **Crear base de datos**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   ```
   Edita `.env` con tus datos:
   ```env
   ENVIRONMENT=local
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=
   DB_NAME=estetica_victoria
   ```

4. **Ejecutar servidor local**
   ```bash
   cd public
   php -S localhost:8000
   ```

5. **Acceder a la aplicación**
   - URL: `http://localhost:8000`
   - Login: `admin@esteticavictoria.com` / `admin123`

## 🚂 Desplegar en Railway

### 1. Configurar el Repositorio

1. Asegúrate de que el repositorio esté en GitHub
2. Confirma que tengas `.htaccess` y `Procfile` en la raíz

### 2. Crear Proyecto en Railway

1. Abre [railway.app](https://railway.app)
2. Inicia sesión con tu cuenta GitHub
3. Haz clic en "Create Project"
4. Selecciona "Deploy from GitHub"
5. Selecciona tu repositorio

### 3. Agregar Base de Datos MySQL

1. En el panel del proyecto, haz clic en "+ Add"
2. Selecciona "MySQL"
3. Railway creará automáticamente la BD

### 4. Configurar Variables de Entorno

En el proyecto en Railway, ve a "Variables" y agrega:

```env
ENVIRONMENT=production
DB_HOST=nombre-del-servicio-db.railway.internal
DB_USER=root
DB_PASS=contraseña_generada_por_railway
DB_NAME=railway
```

**Nota:** Railway asigna automáticamente el puerto 3306 para MySQL. Obtén la contraseña desde el panel de la BD.

### 5. Inicializar Base de Datos

1. Conecta a MySQL desde tu computadora:
   ```bash
   mysql -h tu-host-railway.railway.app -P puerto -u root -p
   ```

2. Ejecuta el script SQL:
   ```bash
   mysql -h tu-host-railway.railway.app -P puerto -u root -p < database/schema.sql
   ```

   O copia el contenido de `schema.sql` y ejecuta en el cliente de Railway.

### 6. Deploy

1. Confirma los cambios en tu repositorio
2. Railway detectará automáticamente los cambios
3. Se iniciará el deploy
4. Una vez completado, abrirá tu dominio público

## 🔐 Usuarios por Defecto

**Admin:**
- Email: `admin@esteticavictoria.com`
- Contraseña: `admin123`

**Importante:** Cambia la contraseña del admin en el primer login.

## 📱 Funcionalidades

### Login
- Autenticación segura con contraseñas hasheadas
- Redireccionamiento según tipo de usuario
- Sesiones persistentes

### Dashboard Admin
- 📊 **Resumen:** Ventas del día, mes, y gráfico de últimos 7 días
- 💰 **Gestión de Ventas:** Ver ventas por fecha
- 👥 **Gestión de Usuarios:** Crear, editar y eliminar usuarios

### Landing Page
- 🎉 Saludo personalizado con nombre del usuario
- 📱 Botón de salir en la esquina superior derecha
- 🎨 Diseño responsivo y moderno

## 🛠️ Desarrollo

### Agregar un Nuevo Usuario

```php
// POST a /php/api/usuarios.php?accion=crear
nombre=Juan Pérez&email=juan@example.com&contraseña=123456&tipo_usuario=user
```

### Consultar Ventas

```php
// GET a /php/api/ventas.php?accion=diarias&fecha=2024-01-15
// GET a /php/api/ventas.php?accion=rango&fecha_inicio=2024-01-01&fecha_fin=2024-01-31
```

## 📧 Contacto

Para preguntas o soporte:
- Email: victorymeraz@gmail.com
- Instagram: @esteticavictoria

## 📄 Licencia

Proyecto privado para Estética Victoria
