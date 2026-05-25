# Guía para Agentes (AI & Developers) - Sistema de Gestión del Servicio Social (ITCH)

## Contexto del Proyecto
Este sistema es un MVP para digitalizar y automatizar el proceso de inscripción, seguimiento y liberación del Servicio Social en el Instituto Tecnológico de Chetumal (ITCH).
Existen tres actores principales:
- **Alumno**: Inscribe su servicio, sube reportes bimensuales y finales.
- **Jefe del DGTyV (Administrador)**: Revisa documentos, aprueba reportes y emite constancias de liberación.
- **Responsable de la Dependencia (Supervisor)**: Valida la asistencia y el desempeño del alumno externamente.

**Stack Técnico Estricto:** PHP Nativo (8.0+), MySQL/MariaDB (vía PDO), Apache (XAMPP), HTML, CSS, JavaScript Vanilla/Bootstrap (sin frameworks de JS como React o Vue).

---

## Estructura de Carpetas (Mini-Framework MVC)
El proyecto utiliza un patrón MVC personalizado y enrutamiento dinámico mediante `index.php`.
- `/core`: Clases base del framework (`Router.php`, `Controller.php`, clase de conexión a la Base de Datos).
- `/controllers`: Clases que manejan la lógica de negocio y las peticiones web.
- `/models`: Clases que se comunican con la base de datos (Reglas de negocio).
- `/views`: Archivos PHP puros (o mezcla de HTML y PHP) para la presentación de datos.
- `/public` o `/assets` (Opcional pero recomendado): Para CSS, JS, e imágenes estáticas.

---

## Convenciones de Código
1. **Nomenclatura de Archivos y Clases**: Las clases deben tener el formato `PascalCase` y coincidir con el nombre de archivo (ej. `AlumnoController.php`).
2. **Sufijos de Controladores**: Todos los controladores deben terminar en `Controller` (ej. `LoginController`, `AdminController`).
3. **Sufijos de Modelos**: Todos los modelos deben terminar en `Model` (ej. `AlumnoModel`, `ReporteModel`).
4. **Vistas**: Deben agruparse en subcarpetas con el prefijo del controlador (ej. `/views/alumno/dashboard.php`). No usar motores de plantillas como Blade o Twig, solo PHP puro estructurado.
5. **Tipado**: Utiliza el tipado estricto y declaraciones de tipo en PHP 8+ cuando sea posible.

---

## Reglas de Enrutamiento y Peticiones Web
El `Router.php` atrapa todas las URLs y las traduce de la siguiente manera:
`dominio.com/controlador/metodo/param1/param2`

- `controlador` -> Instancia automáticamente `ControladorController`.
- `metodo` -> Ejecuta la función `metodo()` dentro de ese controlador.
- `param1`, `param2` -> Se pasan como argumentos a la función `metodo($param1, $param2)`.

**Controlador/Método por Defecto**:
Si se accede a la raíz (`/`), cargará por defecto el `LoginController` y su método `index()`.

**Peticiones AJAX**:
Para consumir endpoints desde JavaScript vía `fetch()` o `XMLHttpRequest`:
1. Envía la petición a una ruta válida, ej. `/alumno/subir_reporte`.
2. El controlador debe retornar datos llamando al método heredado `$this->jsonResponse($arrayDatos);`.
3. Validar siempre el método HTTP usando `$_SERVER['REQUEST_METHOD']`.

---

## Instrucciones para el Acceso a Datos (Modelos y PDO)
1. **Conexión PDO**: Crea o utiliza una clase Singleton para la conexión a la base de datos dentro de `/core/Database.php`.
2. **Instanciación en Controladores**:
   ```php
   $alumnoModel = new AlumnoModel();
   $datos = $alumnoModel->obtenerPorId($id);
   ```
3. **Seguridad SQL (Crucial)**: NUNCA concatenar variables directamente en las consultas SQL. Usar SIEMPRE sentencias preparadas de PDO (`prepare()`, `bindValue()` / `bindParam()`, `execute()`) para evitar Inyección SQL.
4. **Manejo de Errores**: Usar bloques `try { ... } catch(PDOException $e) { ... }` para las transacciones complejas o para capturar fallos de BD.
