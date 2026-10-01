# 📚 Sistema de Gestión de Biblioteca

![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

Sistema web completo para la gestión de bibliotecas, desarrollado con PHP y MySQL. Permite administrar libros, usuarios, préstamos y devoluciones con un panel de control intuitivo.

**Versión actual**: 1.0.0  
**Última actualización**: 2026-09-30  
**Estado del proyecto**: En desarrollo

---

## 🎯 Características Principales

- ✅ **Gestión de Libros**: CRUD completo con búsqueda y filtrado
- ✅ **Gestión de Usuarios**: Registro, edición y control de socios
- ✅ **Sistema de Préstamos**: Control de préstamos y devoluciones automáticas
- ✅ **Panel de Control (Dashboard)**: Estadísticas en tiempo real
- ✅ **Seguridad**: Autenticación con sesiones y contraseñas hash
- ✅ **Validaciones**: Reglas de negocio para préstamos simultáneos
- ✅ **Historial**: Registro completo de todas las operaciones

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Versión Mínima |
|------------|----------------|
| PHP | 8.2+ |
| MySQL/MariaDB | 5.7+ |
| HTML5 | - |
| CSS3 | - |
| JavaScript (Vanilla) | ES6+ |

---

## 📁 Estructura del Proyecto

```
sistema_biblioteca/
│
├── index.php              → Página principal
├── login.php              → Autenticación de administradores
├── logout.php             → Cierre de sesión seguro
├── dashboard.php          → Panel de control con estadísticas
│
├── config/
│   ├── database.php       → Configuración de conexión a BD
│   └── config.php         → Configuración general del sistema
│
├── includes/
│   ├── header.php         → Encabezado reutilizable
│   ├── footer.php         → Pie de página con scripts
│   └── auth.php           → Funciones de autenticación y sesiones
│
├── libros/
│   ├── index.php          → Listar todos los libros
│   ├── crear.php          → Formulario para nuevo libro
│   ├── editar.php         → Editar información del libro
│   ├── eliminar.php       → Eliminar libro (con validaciones)
│   └── detalle.php        → Ver detalles completos
│
├── usuarios/
│   ├── index.php          → Listar usuarios registrados
│   ├── crear.php          → Formulario para nuevo usuario
│   ├── editar.php         → Editar datos del usuario
│   ├── detalle.php        → Información detallada del usuario
│   └── eliminar.php       → Eliminar usuario (con validaciones)
│
├── prestamos/
│   ├── index.php          → Listar préstamos activos e históricos
│   ├── nuevo.php          → Registrar préstamo nuevo
│   ├── devolver.php       → Procesar devolución de libro
│   └── historial.php      → Historial completo de préstamos
│
├── assets/
│   ├── css/               → Estilos CSS del sistema
│   ├── js/                → Scripts JavaScript para interactividad
│   └── img/               → Imágenes y recursos visuales
│
├── sql/
│   ├── estructura.sql     → Script de creación de base de datos
│   └── datos_prueba.sql   → Datos de ejemplo para pruebas
│
├── README.md              → Este archivo de documentación
└── .gitignore             → Archivos ignorados por Git
```

---

## 🚀 Instalación y Configuración

### Requisitos Previos

- PHP 8.2 o superior
- MySQL/MariaDB 5.7 o superior
- XAMPP, WAMP o similar (opcional)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd sistema_biblioteca
   ```

2. **Configurar la base de datos**
   
   a. Crear una base de datos vacía en MySQL:
   ```sql
   CREATE DATABASE biblioteca;
   USE biblioteca;
   ```

   b. Importar la estructura:
   ```bash
   mysql -u root -p < sql/estructura.sql
   ```

   c. Opcional: Importar datos de prueba:
   ```bash
   mysql -u root -p biblioteca < sql/datos_prueba.sql
   ```

3. **Configurar la conexión a base de datos**

   Editar `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'biblioteca');
   define('DB_USER', 'tu_usuario');
   define('DB_PASS', 'tu_contraseña');
   ```

4. **Ejecutar el sistema**
   
   Abre tu navegador y accede a:
   ```
   http://localhost/sistema_biblioteca/
   ```

---

## 📊 Funcionalidades del Sistema

### 1. Panel de Control (Dashboard)

El dashboard muestra estadísticas clave:

- 📚 Total de libros en catálogo
- ✅ Libros disponibles
- 📦 Libros actualmente prestados
- 👥 Usuarios activos registrados
- 📜 Préstamos activos actuales
- ⏰ Préstamos vencidos por devolver
- 📋 Próximos vencimientos (próximos 7 días)
- 🕐 Últimos préstamos realizados

### 2. Gestión de Libros

Operaciones disponibles:

| Acción | Descripción |
|--------|-------------|
| Listar | Ver todos los libros con paginación |
| Buscar | Filtrar por título, autor o ISBN |
| Crear | Agregar nuevo libro al catálogo |
| Editar | Modificar información existente |
| Eliminar | Remover libro (solo si no está prestado) |
| Detalles | Ver información completa del libro |

### 3. Gestión de Usuarios

Operaciones disponibles:

| Acción | Descripción |
|--------|-------------|
| Listar | Ver todos los usuarios socios |
| Buscar | Filtrar por nombre o email |
| Crear | Registrar nuevo usuario socio |
| Editar | Actualizar datos del usuario |
| Detalles | Ver historial de préstamos del usuario |
| Eliminar | Remover usuario (solo sin préstamos activos) |

### 4. Préstamos y Devoluciones

Flujo completo de gestión:

1. **Crear préstamo**: Validaciones automáticas antes de registrar
2. **Devolución**: Procesamiento automático con cálculo de días de demora
3. **Historial**: Registro completo de todas las operaciones

**Validaciones automáticas:**
- ✅ Libro existe y está disponible
- ✅ Usuario existe y está activo
- ✅ Sin préstamos vencidos pendientes
- ✅ Máximo 3 préstamos simultáneos por usuario
- ⏰ Fecha de devolución automática: 14 días después del préstamo

---

## 🔒 Seguridad Implementada

| Medida | Descripción |
|--------|-------------|
| **Prepared Statements** | Todas las consultas SQL usan PDO con prepared statements para prevenir SQL Injection |
| **Contraseñas Hash** | Uso de `password_hash()` y `password_verify()` para autenticación segura |
| **Sesiones PHP** | Protección de páginas sensibles con verificación de sesión activa |
| **Sanitización** | Uso de `htmlspecialchars()` en todas las salidas HTML |
| **Validación de Datos** | Validación en PHP y JavaScript antes de procesar datos |
| **Tipos de Datos** | Definición estricta de tipos para prevenir errores |

---

## ⚠️ Reglas de Negocio

### Para Usuarios Socios:
- Estado debe ser `activo` para solicitar préstamos
- Máximo **3 préstamos simultáneos** permitidos
- No puede tener préstamos vencidos pendientes
- Email y DNI deben ser únicos

### Para Libros:
- ISBN debe ser único en el catálogo
- Estados posibles: `disponible`, `prestado`
- No eliminable mientras tenga préstamos activos

### Para Préstamos:
- Duración estándar: **14 días**
- Estados posibles: `activo`, `devuelto`, `vencido`
- Fecha de devolución real se registra automáticamente
- Cálculo automático de días de demora si corresponde

---

## 📊 Dashboard - Métricas Disponibles

El panel de control muestra:

```
┌─────────────────────────────────────────┐
│  📚 Libros Totales:     XXXX            │
│  ✅ Disponibles:        XXXX            │
│  📦 Prestados:          XXXX            │
│  👥 Usuarios Activos:   XXXX            │
│  📜 Préstamos Activos:  XXXX            │
│  ⏰ Vencidos:           XXXX            │
└─────────────────────────────────────────┘

📋 Próximos vencimientos (7 días)
🕐 Últimos préstamos realizados
⚡ Accesos rápidos a funciones comunes
```

---

## 🗓️ Cronograma de Desarrollo

### Semana 1
- **Día 1-2**: Base de datos, Login, Sesiones
- **Día 3-4**: CRUD libros, CRUD usuarios  
- **Día 5**: Dashboard, Integración, Pruebas

### Semana 2
- **Día 1-2**: Préstamos, Devoluciones, Historial
- **Día 3**: Dashboard completo, Validaciones
- **Día 4**: Extras y mejoras de funcionalidad
- **Día 5**: Testing final, Documentación, Presentación

---

## ✅ Requisitos Mínimos para Funcionamiento

El sistema considera como mínimo operativo cuando tiene:

- [x] Login/logout funcional
- [x] CRUD completo de libros
- [x] CRUD completo de usuarios
- [x] Registrar préstamo nuevo con validaciones
- [x] Registrar devolución con cálculo de demora
- [x] 2-3 validaciones de negocio implementadas
- [x] Base de datos correctamente estructurada
- [x] Sin vulnerabilidades críticas (SQL Injection, XSS, etc.)

---

## 📝 Estructura de la Base de Datos

### Tablas Principales

#### `libros` - Catálogo Bibliográfico
```sql
id (PK), titulo, autor, isbn (único), editorial, anio, 
categoria, descripcion, estado (disponible/prestado)
```

#### `usuarios` - Socios de la Biblioteca
```sql
id (PK), nombre_completo, email (único), telefono, 
direccion, dni (único), fecha_registro, 
estado (activo/suspendido)
```

#### `prestamos` - Registro de Préstamos
```sql
id (PK), libro_id (FK), usuario_id (FK), 
fecha_prestamo, fecha_devolucion (prevista), 
fecha_dev_real, estado (activo/devuelto/vencido), 
observaciones
```

#### `administradores` - Usuarios del Sistema
```sql
id (PK), usuario, password (hash), nombre, email, rol
```

### Relaciones

```
LIBROS (1) ──────── (N) PRESTAMOS
                              │
                              │ (N)
                              ▼
USUARIOS (socios) (1) ──────── (N) PRESTAMOS
```

---

## 🎨 Interfaz de Usuario

La interfaz incluye:

- ✅ Diseño limpio y moderno con CSS3
- ✅ Formularios intuitivos y validados
- ✅ Tablas de datos claras y legibles
- ✅ Mensajes de error y éxito visibles
- ✅ Responsive para diferentes dispositivos
- ✅ Feedback visual inmediato al usuario

---

## 🔧 Configuración Opcional

### Personalización del Dashboard

Editar `dashboard.php` para agregar:

```php
// Agregar nuevas métricas
$metricas = [
    'total_libros' => $totalLibros,
    'libros_disponibles' => $disponibles,
    // ... agregar más
];
```

### Estilos Personalizados

Editar `assets/css/style.css` para:

- Cambiar colores y paletas
- Ajustar tipografías
- Modificar espaciados
- Agregar animaciones

---

## 📦 Archivos Importantes

| Archivo | Propósito |
|---------|-----------|
| `config/database.php` | Configuración de conexión a BD |
| `config/config.php` | Configuración general del sistema |
| `includes/auth.php` | Funciones de autenticación y sesiones |
| `sql/estructura.sql` | Script para crear la base de datos |
| `sql/datos_prueba.sql` | Datos de ejemplo para pruebas |

---

## 🐛 Solución de Problemas Comunes

### Error de conexión a BD
```bash
# Verificar credenciales en config/database.php
# Asegurar que la BD existe: mysql -u root -p -e "SHOW DATABASES;"
```

### Contraseña no válida
```php
// La contraseña debe estar hashada
// Usar password_hash() al crear usuarios administradores
```

### No se puede eliminar libro/usuario
- Verificar que no tenga préstamos activos
- Revisar el estado del registro (debe ser 'activo')

---

## 📚 Recursos Adicionales

- [Documentación oficial de PHP](https://www.php.net/docs)
- [Documentación de MySQL](https://dev.mysql.com/doc/)
- [PHP Security Best Practices](https://phpsecurity.readthedocs.io/)

---

## 📄 Licencia

Este proyecto está disponible bajo la licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 👥 Contribuidores

- Desarrollador principal: Kremer Lautaro

---

## 📞 Contacto

Para reportar bugs o sugerencias:
- Issue en GitHub
- Email: Kremerlautaro267@email.com

---

**Versión actual**: 1.0.0  
**Última actualización**: 2026-09-30  
**Estado del proyecto**: En desarrollo
