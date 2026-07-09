# ✅ CHECKLIST FINAL - ANTES DE DESPLEGAR EN RAILWAY

Marca cada item conforme lo completes:

## 🔐 SEGURIDAD

- [ ] Cambiar contraseña del admin (no usar admin123)
- [ ] Verificar que `.env` no esté en el repositorio Git
- [ ] Revisar `SECURITY_NOTES.php` para consideraciones de seguridad
- [ ] Cambiar el hash de contraseña del admin en `schema.sql`:
  ```sql
  -- Ejecutar en tu terminal:
  -- php -r "echo password_hash('tu_nueva_contraseña', PASSWORD_BCRYPT);"
  -- Copiar el hash y actualizar en schema.sql
  ```

## 📁 ESTRUCTURA

- [ ] Verificar que existen estas carpetas:
  - [ ] `public/`
  - [ ] `php/config/`
  - [ ] `php/auth/`
  - [ ] `php/api/`
  - [ ] `css/page-sections/`
  - [ ] `database/`

- [ ] Verificar que existen estos archivos:
  - [ ] `public/index.php`
  - [ ] `public/login.html`
  - [ ] `public/dashboard.html`
  - [ ] `public/landing.php`
  - [ ] `php/config/db.php`
  - [ ] `php/auth/session.php`
  - [ ] `php/auth/procesar_login.php`
  - [ ] `php/api/usuarios.php`
  - [ ] `php/api/ventas.php`
  - [ ] `.htaccess`
  - [ ] `Procfile`
  - [ ] `composer.json`
  - [ ] `.env.example`
  - [ ] `database/schema.sql`

## 💾 BASE DE DATOS LOCAL

- [ ] Crear base de datos local:
  ```bash
  mysql -u root -p < database/schema.sql
  ```

- [ ] Verificar datos creados:
  ```sql
  SELECT * FROM usuarios;
  SELECT * FROM servicios;
  ```

## 🧪 PRUEBAS LOCALES

- [ ] Probar login con credenciales por defecto
  - Email: `admin@esteticavictoria.com`
  - Contraseña: `admin123`

- [ ] Verificar redirección a dashboard (admin)

- [ ] Crear un usuario tipo "user"

- [ ] Logout y login con usuario "user"

- [ ] Verificar que accede a landing.php con saludo personalizado

- [ ] Verificar que dashboard no es accesible para usuarios normales

- [ ] Verificar saludo con nombre en landing page

## 🚀 RAILWAY - PREPARACIÓN

- [ ] Repositorio en GitHub actualizado
- [ ] Todos los archivos confirmados con Git
- [ ] `.env` está en `.gitignore`
- [ ] `.env.example` tiene valores de plantilla (no secretos)

## 🚂 RAILWAY - DEPLOYMENT

- [ ] Conectar Railway a GitHub
- [ ] Crear nuevo proyecto en Railway
- [ ] Agregar servicio MySQL
- [ ] Copiar variables de conexión a BD
- [ ] Configurar variables de entorno en Railway:
  ```
  ENVIRONMENT=production
  DB_HOST=nombre-servicio-db.railway.internal
  DB_USER=root
  DB_PASS=contraseña_de_railway
  DB_NAME=railway
  ```

## 🔧 RAILWAY - BASE DE DATOS

- [ ] Obtener credenciales MySQL de Railway
- [ ] Conectar a BD remota desde terminal:
  ```bash
  mysql -h tu-host.railway.internal -u root -p
  ```

- [ ] Crear base de datos:
  ```sql
  CREATE DATABASE railway;
  USE railway;
  ```

- [ ] Ejecutar script SQL:
  ```bash
  mysql -h tu-host.railway.internal -u root -p railway < database/schema.sql
  ```

## 🌐 PRUEBAS EN PRODUCCIÓN

- [ ] Acceder al login: `https://tu-proyecto.railway.app/public/login.html`

- [ ] Probar login con admin

- [ ] Verificar dashboard funciona

- [ ] Crear usuario de prueba

- [ ] Logout e ingresa con usuario de prueba

- [ ] Verificar landing page personalizada

- [ ] Verificar que se ven saludo y botón logout

## 🎉 FINALIZACION

- [ ] Cambiar credenciales por defecto
- [ ] Documentar todos los usuarios creados
- [ ] Guardar respaldos de contraseñas en lugar seguro
- [ ] Configurar alertas en Railway (opcional)
- [ ] Compartir URL de acceso con equipo
- [ ] Crear manual de usuario final

## 📞 SOPORTE

Si algo no funciona:

1. Revisa los logs en Railway:
   ```
   Railway Dashboard → Tu Proyecto → Logs
   ```

2. Verifica archivo `RAILWAY_QUICK_GUIDE.md`

3. Consulta `SECURITY_NOTES.php` para problemas de seguridad

4. Revisa `INSTRUCCIONES_DEPLOYMENT.md` para pasos detallados

---

## 🎯 NOTAS IMPORTANTES

**Estructura de URLs después del deploy:**
- `https://tu-proyecto.railway.app/public/index.php` (redirige a login)
- `https://tu-proyecto.railway.app/public/login.html`
- `https://tu-proyecto.railway.app/public/dashboard.html`
- `https://tu-proyecto.railway.app/public/landing.php`

**Variables de entorno en Railway:**
- El servicio de BD genera automáticamente la contraseña
- Copiar exactamente desde el panel de Railway
- No incluir puerto, Railway usa 3306 automáticamente

**En caso de emergencia:**
- Railway proporciona backups automáticos
- Puedes revertir a versión anterior en Railway
- Mantén backup de `database/schema.sql` en lugar seguro

---

✅ **Cuando completes todo este checklist, tu plataforma estará lista para producción.**

Si necesitas ayuda: victorymeraz@gmail.com
