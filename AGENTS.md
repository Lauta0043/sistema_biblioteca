# 🤖 AGENTS.md — Sistema de Gestión de Biblioteca

## 📋 Propósito

Este archivo guía para agentes AI que trabajan en este proyecto. Proporciona contexto, estructura, límites y reglas para desarrollo colaborativo, testing automatizado, revisión de código, implementación de características, etc.

---

## 🏗️ Arquitectura del Proyecto

### Base de Datos
- Nombre: `sistema_biblioteca` (o `biblioteca`)
- Motor: MySQL 5.7+ / MariaDB
- Tablas principales:
  - `libros`: `id, titulo, autor, isbn, editorial, anio, categoria, descripcion, estado`
  - `usuarios`: `id, nombre_completo, email(dni único), telefono, direccion, dni(único), fecha_registro, estado`
  - `prestamos`: `id, libro_id(FK), usuario_id(FK), fecha_prestamo, fecha_devolucion(vigilado), fecha_dev_real, estado, observaciones`
  - `administradores`: `id, usuario, password(hash), nombre, email, rol`

### Estructura de Archivos
```
sistema_biblioteca/
├── index.php              → Página principal (login)
├── login.php              → Autenticación de administradores
├── logout.php             → Cierre de sesión seguro
├── config/
│   ├── database.php       → Configuración de conexión a BD
│   └── sistema_biblioteca.sql → Script completo de base de datos
├── includes/
│   ├── header.php         → Encabezado reutilizable
│   ├── footer.php         → Pie de página con scripts
│   └── auth.php           → Funciones de autenticación y sesiones
├── libros/
│   ├── index.php          → Listar todos los libros
│   ├── crear.php          → Formulario para nuevo libro
│   ├── editar.php         → Editar información del libro
│   ├── eliminar.php       → Eliminar libro (con validaciones)
│   └── detalle.php        → Ver detalles completos
├── usuarios/
│   ├── index.php          → Listar usuarios registrados
│   ├── crear.php          → Formulario para nuevo usuario
│   ├── editar.php         → Editar datos del usuario
│   ├── detalle.php        → Información detallada del usuario
│   └── eliminar.php       → Eliminar usuario (con validaciones)
├── prestamos/
│   ├── index.php          → Listar préstamos activos é históricos
│   ├── nuevo.php          → Registrar préstamo nuevo
│   ├── devolver.php       → Procesar devolución de libro
│   └── historial.php      → Historial completo de préstamos
├── assets/
│   ├── css/style.css      → Estilos CSS del sistema
│   ├── js/                → Scripts JavaScript para interactividad
│   └── img/               → Imágenes y recursos visuales
├── dashboard.php          → Panel de control con estadísticas
└── sql/
    ├── estructura.sql     → Script de creación de base de datos
    └── datos_prueba.sql   → Datos de ejemplo para pruebas
```

### Relaciones Clave
- `LIBROS(1) ──────── (N) PRESTAMOS`
- `USUARIOS(socios)(1) ──────── (N) PRESTAMOS`
- Cada préstamo pertenece a un único libro Y un único usuario

---

## 🔒 Configuración de Seguridad Obligatoria

### Antes de Cualquier Operación:

1. **Usar Prepared Statements** (PDO o MySQLi):
```php
$stmt = $pdo->prepare("SELECT * FROM ?");
$stmt->execute([$table]);
```
NO aceptar inputs directamente en consultas SQL.

2. **Hashing de Contraseñas**:
```php
$hash = password_hash($password, PASSWORD_DEFAULT); // al registrar
if (password_verify($input, $hash)) { // al login
```

3. **Sanitización HTML Escapes**:
```php
echo htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
```

4. **Validación de Sesión**:
Cada archivo "sensible" (dashboard, CRUDs) debe verificar sesión activa:
```php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit;
}
```

5. **Tipos de Datos estrictos**: Definir tipos en base de datos + validación en PHP antes del INSERT/UPDATE.

---

## 📋 Reglas de Negocio — NO TOCAR SIN RAZÓN CLÍNICA

### Para Usuarios Socios:
| Regla | Implementación |
|-------|----------------|
| Estado debe ser `activo` | Comprobar antes de permitir préstamos |
| Máximo 3 préstamos simultáneos | `SELECT COUNT(*) FROM prestamos WHERE estado='activo' AND usuario_id=? LIMIT 1` |
| No puede tener préstamos vencidos pending | Validar en cada operación de préstamo |
| Email y DNI únicos | Clave única + índice en BD |

### Para Libros:
| Regla | Implementación |
|-------|----------------|
| ISBN debe ser único | `UNIQUE (isbn)` en esquema SQL |
| Estados posibles: `disponible`, `prestado` | Enumeración estricta del tipo ENUM o CHECK constraint |
| No eliminable mientras tenga préstamos activos | `SELECT COUNT(*) FROM prestamos WHERE libro_id=? AND estado='activo' > 0` → bloquear eliminación |

### Para Préstamos:
| Regla | Implementación |
|-------|----------------|
| Duración estándar: 14 días | `$devolucion = fecha_prestamo + INTERVAL 14 DAY;` |
| Estados posibles: `activo`, `devuelto`, `vencido` | ENUM en base de datos |
| Fecha de devolución real se registra automáticamente | Al procesar devolución, actualizar campo con fecha actual |
| Cálculo automático de días de demora | `$demora = max(0, diferencia(días_dev_real, devolucion))` |

### Para Devoluciones:
- Verificar estado del préstamo antes de registrar devuelta
- Actualizar estado a `devuelto`
- Calcular y mostrar días de demora si corresponde
- Cambiar estado del libro a `disponible`

---

## 🎯 Funcionalidades Críticas — Prioridad Máxima (TODO/NOT A COMPLETE)

### 1. Sistema de Login/Logout ✅ Creado, NO FUNCIONAL
**Objetivo**: Validar credenciales, crear sesión, proteger páginas.
- [ ] `index.php`: Formulario principal + redirección post-login
- [ ] `login.php`: Verificar usuario/password contra base de datos
- [ ] `logout.php`: Destruir cookies PHP y redirigir
- [ ] `auth.php`: Funciones auxiliares de sesión (start_session, verify_login, etc.)

### 2. CRUD de Libros ❌ NO INICIADO
**Objetivo**: Gestión completa del catálogo bibliográfico.
Archivos a crear en `/libros/`:
- [ ] `index.php` - Listar libros con paginación + búsqueda por título/autor/ISBN
- [ ] `crear.php` - Formulario POST con validación + guardado único ISBN
- [ ] `editar.php` - Formulario UPDATE con confirmación antes de cambiar estado
- [ ] `eliminar.php` - DELETE solo si COUNT(prestamos_activos)=0
- [ ] `detalle.php` - Mostrar toda información del libro

### 3. CRUD de Usuarios ❌ NO INICIADO
**Objetivo**: Gestión de socios de la biblioteca.
Archivos a crear en `/usuarios/`:
- [ ] `index.php` - Listar usuarios con filtrado por nombre/email
- [ ] `crear.php` - Formulario registro + generación auto de fecha_registro
- [ ] `editar.php` - Modificar datos sin permitir cambiar email/DNI únicos
- [ ] `detalle.php` - Informacion completa + historial de préstamos del usuario
- [ ] `eliminar.php` - DELETE solo si SIN préstamos activos + estado no suspendido

### 4. Gestión de Préstamos ❌ NO INICIADO
**Objetivo**: Control de préstamos con validaciones automáticas.
Archivos a crear en `/prestamos/`:
- [ ] `index.php` - Listar préstamos con estados: activo, devuelto, vencido + fechas
- [ ] `nuevo.php` - Crear préstamo CON VALIDACIONES ESTRUCTURALES (no saltar sin comprobar reglas de negocio)
- [ ] `devolver.php` - Procesar devolución + auto-calcular demora + actualizar libro a disponible
- [ ] `historial.php` - Ver historial completo por usuario o libro

### 5. Dashboard ⚠️ ESTRUCTURA NECESARIA PERO INCOMPLETA
**Objetivo**: Panel de control con métricas en tiempo real.
- [ ] `dashboard.php`: Querés SQL complejos para calcular:
  - Total libros / disponibles / prestados
  - Usuarios activos registrados
  - Préstamos activos actuales / vencidos
  - Próximos vencimientos (7 días)
  - Últimos préstamos realizados
  - Libros más prestados

---

## 🧪 Testing — Estrategia Recomendada

### Validaciones Estructurales a Implementar:

1. **Integridad de Referencias**: Intentar crear préstamo con libro_id no existente → debe dar error PDOException o manejo SQL en PHP.

2. **Unique Constraints**:
   - INSERT libro con ISBN duplicado → `Duplicate entry error` manejado elegantemente
   - INSERT usuario con email/ dni duplicados → same handling

3. **Reglas de Negocio antes de Operar**:
   ```php
   function canCreatePrestamo($libro_id, $usuario_id) {
       // Libro existe y disponible?
       // Usuario existe y activo?
       // Sin préstamos vencidos pendiente?
       // Menos de 3 préstamos activos para ese usuario?
       return $resultado_validacion;
   }
   ```

4. **Sanitización Outputs**: Nunca `echo` datos crudos directamente → siempre `htmlspecialchars()` primero.

5. **SQL Injection Attacks**: Testear con inputs maliciosos en todos los puntos de contacto. Usar PDO prepared statements o MySQLi bind_param en todas las consultas.

---

## 📊 Estado Actual del Proyecto — Diagnóstico Real

### Archivos existentes (sin contenido funcional):
- ✅ `index.php` - HTML básico, rota `/login` no funciona correctamente → `action="/login"` pero archivo `login.php` no está o no existe
- ⚠️ `configs/sistema_biblioteca.sql` - SQL base de datos presente (verificar si tiene estructura completa)
- ❌ `includes/auth.php`, `header.php`, `footer.php` - NO CREADOS
- ❌ Archivos CRUD en `/libros/`, `/usuarios/`, `/prestamos/` - NO CREADOS
- ❌ `dashboard.php` - NO CREADO
- ⚠️ `assets/css/style.css` - Referenciada pero probablemente no existe o está vacía

### Prioridades (según README.md docs):

**Semana 1**:
- Día 1-2: **Base de datos + Login + Sesiones** = ¡ESTARÍA TERMINO ESTE PASO!
- Día 3-4: CRUD libros, CRUD usuarios = TODO FALTA CREADO
- Día 5: Dashboard, Integración, Pruebas = TODO PENDING

**Semana 2**:
- Día 1-2: Préstamos, Devoluciones = NO INICIADO
- Día 3: Dashboard completo + Validaciones Estructurales = REQUIERE IMPLEMENTACIÓN DE VALIDACIONES EN PHP
- Día 4: Extras / mejoras
- Día 5: Testing final, Documentación ✅ EXISTE (README.md)

### Conclusión: Proyecto en etapa inicial con buena documentación pero sin implementación de código. Estructura de directorios creada pero vacía de lógica.

---

## 🚀 Checklist — Qué hacer primero

1. [ ] Crear `config/config.php` si no existe
2. [ ] Verificar estructura SQL en `sql/sistema_biblioteca.sql` - debe tener 4 tablas + relaciones
3. [ ] Implementar `includes/auth.php` con sesión básica (start_session, verify_login($usuario), etc.)
4. [ ] Crear `includes/header.php` y `includes/footer.php` reutilización
5. [ ] Completar `login.php` y `logout.php` + `index.php` funcional
6. [ ] Implementar módulo Libro completo (5 archivos PHP)
7. [ ] Implementar módulo Usuario completo (5 archivos PHP)
8. [ ] Implementar módulo Préstamo completo (4 archivos PHP con validaciones Estructurales)
9. [ ] Crear dashboard con consultas SQL complejos + validaciones en PHP
10. [ ] Testear todos los flujos completos + casos frontera

---

## 🧩 Revisión de Errores Comunes

### NO HACER:
- ❌ `SELECT * FROM usuarios WHERE usuario = '`.$usuario.'`' → SQL Injection directo
- ❌ `echo $usuario` → XSS en outputs HTML
- ❌ Borrar libro/usuario sin verificar préstamos activos → lógica inconsistente
- ❌ Permitir préstamo cuando libros disponibles = 0 para ese usuario

### HACER:
- ✅ `$stmt->execute([$campo, ...])` con PDO prepared statements
- ✅ `htmlspecialchars($texto)` antes de echo en HTML
- ✅ Validar estado=`activo` + préstamos < 3 + sin vencidos antes de permitir préstamo
- ✅ Manejar errores PDO gracefully (PDOException caught)

---

## 📞 Recursos Adicionales - Documentación Oficial

### API Referenciales:

1. [Documentación oficial de PHP](https://www.php.net/docs)
2. [Documentación de MySQL](https://dev.mysql.com/doc/)
3. [PHP Security Best Practices](https://phpsecurity.readthedocs.io/)
4. [PDO Prepared Statements](https://www.php.net/pdo.prepare)
5. [password_hash()](https://www.php.net/password_hash)
6. [password_verify()](https://www.php.net/password_verify)

### Referencia de Archivos Clave:
- `config/database.php` → Configuración de conexión a BD (host, name, user, pass)
- `includes/auth.php` → Funciones auxiliares de autenticación y sesiones (crearé esto primero)
- `sql/estructura.sql` → Script para crear base de datos con 4 tablas + relaciones
- `assets/css/style.css` → Estilos del sistema (necesita ser creado o mover desde root `/assets/css/style.css`)

---

## 📄 Licencia de Código por Agentes

Este código es generado bajo la licencia MIT. Modificaciones deben:
1. Atribuir al autor original
2. Preservar avisos de copyright y noticiero de licencia cuando sea aplicable a todo el repositorio completo o parte significativa del mismo.
3. Declarar cambios cuando se publique o distribuya modificaciones.

---

## 🙏 Contribuidores

- Desarrollador principal: Kremer Lautaro
- Otros desarrolladores (agregar según avance)

---

**Estado del Proyecto**: Estructura de directorios creada | Base de datos definida en documento | Lógica PHP pendiente implementación completa  
**Fecha última actualización**: 2026-10-04
