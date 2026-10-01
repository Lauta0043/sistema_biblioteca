# 📚 Sistema de Gestión de Biblioteca

## 🎯 Objetivo General

Desarrollar un **Sistema de Gestión de Biblioteca** web completo utilizando tecnologías PHP y MySQL para administrar libros, usuarios, préstamos y devoluciones.

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Uso |
|------------|-----|
| **PHP** | Lógica del sistema y backend |
| **MySQL** | Almacenamiento de datos |
| **HTML/CSS** | Interfaz de usuario |
| **JavaScript** | Validaciones e interacción dinámica |
| **Git** | Control de versiones |

---

## 📊 Estructura de la Base de Datos

### Tablas Principales

#### 1. **libros**
Información del catálogo bibliográfico:
- `id` (PK)
- `titulo`
- `autor`
- `isbn` (único)
- `editorial`
- `anio`
- `categoria`
- `descripcion`
- `estado` (disponible/prestado)

#### 2. **usuarios**
Socios que solicitan préstamos:
- `id` (PK)
- `nombre_completo`
- `email` (único)
- `telefono`
- `direccion`
- `dni` (único)
- `fecha_registro`
- `estado` (activo/suspendido)

#### 3. **prestamos**
Relación entre libros y usuarios:
- `id` (PK)
- `libro_id` (FK → libros.id)
- `usuario_id` (FK → usuarios.id)
- `fecha_prestamo`
- `fecha_devolucion` (prevista: 14 días)
- `fecha_dev_real`
- `estado` (activo/devuelto/vencido)
- `observaciones`

#### 4. **usuarios_sistema**
Administradores del sistema:
- `id` (PK)
- `usuario`
- `password` (hash con `password_hash()`)
- `nombre`
- `email`
- `rol`

---

## 🔄 Relaciones de Datos

```
LIBROS (1) ──────── (N) PRESTAMOS
                              │
                              │ (N)
                              ▼
USUARIOS (socios) (1) ──────── (N) PRESTAMOS
```

**Nota:** Cada préstamo pertenece a un solo libro y un solo usuario.

---

## 📁 Estructura del Proyecto

```
sistema_biblioteca/
│
├── index.php              → Página principal
├── login.php              → Autenticación
├── logout.php             → Cierre de sesión
├── dashboard.php          → Panel de control
│
├── config/
│   ├── database.php       → Conexión a BD
│   └── config.php         → Configuración general
│
├── includes/
│   ├── header.php         → Encabezado reutilizable
│   ├── footer.php         → Pie de página
│   └── auth.php           → Funciones de autenticación
│
├── libros/
│   ├── index.php          → Listar libros
│   ├── crear.php          → Formulario nuevo libro
│   ├── editar.php         → Editar libro
│   ├── eliminar.php       → Eliminar libro
│   └── detalle.php        → Ver detalles
│
├── usuarios/
│   ├── index.php          → Listar usuarios
│   ├── crear.php          → Nuevo usuario
│   ├── editar.php         → Editar usuario
│   ├── detalle.php        → Detalles usuario
│   └── eliminar.php       → Eliminar usuario
│
├── prestamos/
│   ├── index.php          → Listar préstamos
│   ├── nuevo.php          → Crear préstamo
│   ├── devolver.php       → Registrar devolución
│   └── historial.php      → Historial de préstamos
│
├── assets/
│   ├── css/               → Estilos
│   ├── js/                → Scripts
│   └── img/               → Imágenes
│
└── sql/
    ├── estructura.sql     → Script de creación BD
    └── datos_prueba.sql   → Datos de prueba
```

---

## ⚙️ Configuración de Base de Datos

**Nombre:** `biblioteca`

**Conexión (config/database.php):**
```php
DB_HOST
DB_NAME
DB_USER
DB_PASS
```

**Recomendado:** PDO o MySQLi con **prepared statements** para prevenir SQL Injection.

---

## 📋 Funcionalidades Obligatorias

### 1. 🔐 Sistema de Login/Logout
- Validación de usuario y contraseña
- Creación de sesión activa
- Protección de páginas con verificación de sesión
- Página de logout funcional

### 2. 📖 CRUD de Libros
- ✅ Listar todos los libros
- ✅ Buscar por título/autor
- ✅ Crear nuevo libro
- ✅ Editar información
- ✅ Eliminar (solo si no está prestado)
- ✅ Ver detalles completos

### 3. 👥 CRUD de Usuarios
- ✅ Listar usuarios registrados
- ✅ Buscar por nombre/email
- ✅ Crear nuevo usuario
- ✅ Editar datos
- ✅ Ver detalles y estado
- ✅ Eliminar (solo sin préstamos activos)

### 4. 📜 Gestión de Préstamos
- ✅ Registrar préstamo nuevo
- ✅ Validaciones automáticas:
  - Libro existe y está disponible
  - Usuario existe y está activo
  - Sin préstamos vencidos
  - Máximo 3 préstamos simultáneos
- ✅ Fecha de devolución automática (14 días)
- ✅ Registrar devolución real
- ✅ Calcular días de demora si corresponde

---

## ⚠️ Reglas de Negocio Importantes

### Para Usuarios:
- Estado debe ser `activo`
- Máximo **3 préstamos simultáneos**
- No puede tener préstamos vencidos
- No puede superar el límite de préstamos

### Para Libros:
- Debe existir en el catálogo
- Debe estar con estado `disponible`
- No eliminable mientras esté prestado

### Para Préstamos:
- Duración estándar: **14 días**
- Estados: `activo`, `devuelto`, `vencido`
- Validar antes de crear préstamo

---

## 🔒 Seguridad Implementada

| Medida | Descripción |
|--------|-------------|
| **Prepared Statements** | Evitar SQL Injection en todas las consultas |
| **password_hash()** | Contraseñas seguras para admin |
| **password_verify()** | Verificación segura de contraseñas |
| **Sesiones PHP** | Protección de páginas sensibles |
| **htmlspecialchars()** | Sanitización de salidas HTML |
| **Validación de datos** | En PHP y JavaScript |
| **Validación de tipos** | Asegurar integridad de datos |

---

## 📊 Dashboard (Panel de Control)

Información visualizada tras el login:

- 📚 Total de libros
- ✅ Libros disponibles
- 📦 Libros prestados
- 👥 Usuarios activos
- 📜 Préstamos activos
- ⏰ Préstamos vencidos
- 📋 Próximos vencimientos
- 🕐 Últimos préstamos
- 📖 Libros más prestados
- ⚡ Accesos rápidos

---

## 🗓️ Cronograma de Desarrollo

### **Semana 1**

| Día | Tareas |
|-----|--------|
| 1-2 | Base de datos, Login, Sesiones |
| 3-4 | CRUD libros, CRUD usuarios |
| 5 | Dashboard, Integración, Pruebas |

### **Semana 2**

| Día | Tareas |
|-----|--------|
| 1-2 | Préstamos, Devoluciones, Historial |
| 3 | Dashboard completo, Validaciones |
| 4 | Extras y mejoras |
| 5 | Testing, Documentación, Presentación |

---

## ✅ Requisitos Mínimos para Aprobar

- [ ] Login/logout funcional
- [ ] CRUD de libros completo
- [ ] CRUD de usuarios completo
- [ ] Registrar préstamo nuevo
- [ ] Registrar devolución
- [ ] 2-3 validaciones de negocio implementadas
- [ ] Base de datos correctamente estructurada
- [ ] Sin vulnerabilidades críticas (SQL Injection, XSS, etc.)

---

## 🔄 Flujo del Sistema

```
USUARIO → HTML → PHP → MySQL → PHP → HTML
```

**Ejemplo: Agregar un libro**
```
Formulario HTML → POST → PHP recibe datos → Valida → INSERT → MySQL
```

**Ejemplo: Mostrar libros**
```
MySQL → SELECT → PHP → HTML → Tabla en pantalla
```

---

## 📝 Notas Adicionales

- El proyecto es una evolución de ejercicios anteriores (CRUD Productos, Sistema Estudiantes)
- Se recomienda seguir las mejores prácticas de desarrollo web
- Documentar cada funcionalidad implementada
- Realizar pruebas unitarias antes de la integración final

---

*Documento generado para el Sistema de Gestión de Biblioteca*
*Fecha: 2026-09-30*
