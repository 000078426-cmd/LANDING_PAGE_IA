# GUÍA RÁPIDA - RAILWAY

## Checklist Antes de Desplegar

- [ ] Repositorio en GitHub actualizado
- [ ] `.htaccess` presente en la raíz
- [ ] `Procfile` presente en la raíz
- [ ] `composer.json` presente en la raíz
- [ ] Variable de entorno `ENVIRONMENT=production`

## Conexión a BD en Railway

### Desde Terminal

```bash
# Obtén la conexión del panel de Railway
mysql -h xxxxx.railway.internal -u root -p

# Luego ejecuta el script SQL
```

### Crear Base de Datos Inicial

```sql
CREATE DATABASE IF NOT EXISTS railway;
USE railway;

-- Copiar todo el contenido de database/schema.sql aquí
```

## Posibles Problemas

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solución:** Verifica que:
1. Las variables de entorno estén correctas en Railway
2. El formato correcto es `DB_HOST=nombre-del-servicio-db.railway.internal`
3. Espera 2-3 minutos después de crear el servicio MySQL

### Sesiones no persisten

**Solución:** Railway usa almacenamiento temporal. Para producción, considera:
1. Usar Redis para sesiones
2. Configurar JWT tokens
3. Usar base de datos para almacenar sesiones

### Permisos insuficientes

**Solución:** Verifica que el usuario MySQL tenga permisos:
```sql
GRANT ALL PRIVILEGES ON railway.* TO 'root'@'%';
FLUSH PRIVILEGES;
```

## Escalabilidad Futura

Para agregar funcionalidades sin perder datos:

1. Realiza backups regular de la BD
2. Usa migraciones de BD
3. Mantén el repositorio Git actualizado
4. Documenta cambios en changelog

## Monitoreo

Railway proporciona:
- Logs en tiempo real
- Métricas de CPU y memoria
- Alertas de deploy fallidos
- Monitor de base de datos
