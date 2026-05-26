# Guía de Seeders y URLs del Sistema

> **Base URL:** `http://localhost/SistemaServicioSocial`
> Todas las rutas de abajo se construyen a partir de esa base.

---

## Inicio de Sesión

| URL | Descripción |
|-----|-------------|
| `http://localhost/SistemaServicioSocial/auth/login` | Página de inicio de sesión para todos los roles |

---

## Seeders disponibles

Los seeders son endpoints del `SetupController` que insertan datos de prueba en la base de datos.
Se recomienda ejecutarlos **en este orden**.

---

### 1. Seeder de Programas y Dependencia (`seed_programas`)

**URL:**
```
http://localhost/SistemaServicioSocial/setup/seed_programas
```

**Qué hace:**
- Crea el usuario y perfil de la dependencia **CONAFOR**.
- Inserta **3 programas de servicio social** en estado `Aprobado`:
  - `SS-2026-001` — Reforestación y Cuidado Ambiental (Presencial, 10 cupos)
  - `SS-2026-002` — Desarrollo de Sistema de Inventario (Virtual, 3 cupos)
  - `SS-2026-003` — Campaña de Concientización Digital (Híbrida, 5 cupos)

**Credenciales generadas:**

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Dependencia | `contacto@conafor.gob.mx` | `dependencia123` |

> Es seguro ejecutarlo múltiples veces; verifica duplicados por folio antes de insertar.

---

### 2. Seeder de Alumno de Prueba (`seed_alumno`)

**URL:**
```
http://localhost/SistemaServicioSocial/setup/seed_alumno
```

**Requisito previo:** Ejecutar `seed_programas` primero (necesita al menos un programa en la BD).

**Qué hace:**
- Crea un usuario con rol `Alumno`.
- Crea el perfil del alumno.
- Crea una **asignación activa** al primer programa disponible.

**Credenciales y datos generados:**

| Campo | Valor |
|-------|-------|
| Correo | `alumno@itch.edu.mx` |
| Contraseña | `alumno123` |
| No. Control | `22560001` |
| Nombre | Juan Carlos Pérez López |
| Carrera | Ing. en Sistemas Computacionales |
| Horas completadas | 250 / 500 |
| Estado | En curso |

> Si el alumno ya existe, solo muestra sus credenciales sin volver a insertar.

---

### 3. Seeder de Administrador y Datos Completos (`seed_admin`)

**URL:**
```
http://localhost/SistemaServicioSocial/setup/seed_admin
```

**Qué hace:**
- Crea el usuario administrador **DGTyV** si no existe.
- Crea **3 dependencias** adicionales de prueba con sus usuarios.
- Inserta **3 programas** en estado `En Revisión por DGTyV` (para probar el flujo de aprobación del admin).
- Actualiza el alumno con `id_alumno = 1` a **500 horas** para probar la liberación.

**Credenciales generadas:**

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador (DGTyV) | `admin@itch.edu.mx` | `admin123` |
| Dependencia | `gobierno@test.mx` | `test123` |
| Dependencia | `inegi@test.mx` | `test123` |
| Dependencia | `manosalaobra@test.mx` | `test123` |

> Los programas de prueba tienen folios `PRG-2023-089`, `PRG-2023-090`, `PRG-2023-091`.

---

### 4. Seeder de Fase 4 — Seguimiento y Dependencias (`seed_fase4`)

**URL:**
```
http://localhost/SistemaServicioSocial/setup/seed_fase4
```

**Propósito:** Probar el flujo completo del **Responsable de la Dependencia**: dashboard con contadores, listado de programas con barra de progreso de cupos y evaluación cualitativa de alumnos.

**Qué hace:**
- Crea el usuario y perfil de la dependencia **DIF Municipal**.
- Inserta **3 programas** asociados (Desarrollo Web, Prácticas de Ingeniería, Análisis de Datos).
- Crea la alumna **Ana García López** con asignación `Activo` a uno de los programas.

**Credenciales generadas:**

| Rol | Correo | Contraseña | Org. |
|-----|--------|------------|------|
| Dependencia | `contacto@difmunicipal.gob.mx` | `dif123` | DIF Municipal |
| Alumno | `agarcia@itch.edu.mx` | `alumno123` | — |

**Datos del alumno generado:**

| Campo | Valor |
|-------|-------|
| Nombre | Ana García López |
| No. Control | `19120155` |
| Estado de asignación | Activo |

**Flujo de prueba sugerido:**
1. Inicia sesión con `contacto@difmunicipal.gob.mx / dif123`.
2. Revisa el Dashboard — verifica los contadores de Alumnos Activos, Programas Aprobados y Evaluaciones Pendientes (servidos por `DependenciaModel` vía PDO).
3. Ve a **Mis Programas** — verifica la tabla con las barras de progreso de cupos (`cupos_ocupados / cupos_totales`).
4. Ve a **Alumnos y Evaluaciones** — selecciona a "García López, Ana", llena el formulario y envíalo. El `DependenciaController` procesará el POST y el `EvaluacionModel` insertará el registro con sentencia preparada.

---

## Nota: Admin creado automáticamente

El administrador (`admin@itch.edu.mx / admin123`) también **se crea automáticamente** la primera vez que cualquier usuario visita la página de login, gracias al método `verificarEInicializarAdmin()` en `AuthController`. No es estrictamente necesario ejecutar `seed_admin` solo para tener acceso al panel de administrador.

---

## Orden de ejecución recomendado

```
1. http://localhost/SistemaServicioSocial/setup/seed_programas
2. http://localhost/SistemaServicioSocial/setup/seed_alumno
3. http://localhost/SistemaServicioSocial/setup/seed_admin    (opcional, datos admin + programas en revisión)
4. http://localhost/SistemaServicioSocial/setup/seed_fase4    (opcional, flujo dependencia y evaluaciones)
5. http://localhost/SistemaServicioSocial/auth/login          ← Iniciar sesión
```

---

## Resumen de credenciales finales

| Rol | Correo | Contraseña | Dashboard |
|-----|--------|------------|-----------|
| Administrador (DGTyV) | `admin@itch.edu.mx` | `admin123` | `/dgtyv/dashboard` |
| Alumno | `alumno@itch.edu.mx` | `alumno123` | `/alumno/dashboard` |
| Dependencia (CONAFOR) | `contacto@conafor.gob.mx` | `dependencia123` | *(rol externo)* |
| Dependencia | `gobierno@test.mx` | `test123` | *(rol externo)* |
| Dependencia | `inegi@test.mx` | `test123` | *(rol externo)* |
| Dependencia | `manosalaobra@test.mx` | `test123` | *(rol externo)* |
| Dependencia (DIF) | `contacto@difmunicipal.gob.mx` | `dif123` | `/dependencia/dashboard` |
| Alumno (Fase 4) | `agarcia@itch.edu.mx` | `alumno123` | `/alumno/dashboard` |

