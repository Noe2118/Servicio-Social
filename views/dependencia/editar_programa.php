<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Programa - Dependencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --navy: #0f2b5b; --gray-bg: #f5f7fa; --border: #e2e6ed; --text-main: #1e293b; --text-muted: #64748b; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-bg); color: var(--text-main); }
        .top-bar { background: var(--navy); color: white; padding: 15px 30px; display: flex; align-items: center; gap: 15px; }
        .top-bar a { color: white; text-decoration: none; font-size: 1.2rem; }
        .main-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .form-card { background: white; border-radius: 12px; border: 1px solid var(--border); padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .form-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 25px; color: var(--navy); border-bottom: 1px solid var(--border); padding-bottom: 15px; }
        .form-label { font-weight: 600; font-size: 0.85rem; color: var(--text-muted); }
        .form-control, .form-select { border-radius: 8px; font-size: 0.9rem; padding: 10px 15px; }
        .btn-submit { background: #3b82f6; color: white; border-radius: 8px; padding: 12px 30px; font-weight: 600; border: none; }
        .btn-submit:hover { background: #2563eb; }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="<?= BASE_URL ?>/dependencia/misProgramas"><i class="fa-solid fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Volver a Mis Programas</h5>
</div>

<div class="main-container">
    <div class="form-card">
        <h2 class="form-title">Editar Programa: <?= htmlspecialchars($programa['folio_programa']) ?></h2>
        <form action="<?= BASE_URL ?>/dependencia/actualizarPrograma" method="POST">
            <input type="hidden" name="id_programa" value="<?= $programa['id_programa'] ?>">
            <div class="row g-4">
                <div class="col-md-8">
                    <label class="form-label">Nombre del Programa *</label>
                    <input type="text" name="nombre_programa" class="form-control" required value="<?= htmlspecialchars($programa['nombre_programa']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modalidad *</label>
                    <select name="modalidad" class="form-select" required>
                        <option value="Presencial" <?= $programa['modalidad'] == 'Presencial' ? 'selected' : '' ?>>Presencial</option>
                        <option value="Virtual" <?= $programa['modalidad'] == 'Virtual' ? 'selected' : '' ?>>Virtual</option>
                        <option value="Híbrida" <?= $programa['modalidad'] == 'Híbrida' ? 'selected' : '' ?>>Híbrida</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Cupos Totales Solicitados *</label>
                    <input type="number" name="cupos_totales" class="form-control" required min="<?= $programa['cupos_ocupados'] ?>" max="50" value="<?= htmlspecialchars($programa['cupos_totales']) ?>">
                    <small class="text-muted" style="font-size:0.7rem;">No puedes reducir cupos por debajo de los <?= $programa['cupos_ocupados'] ?> ya ocupados.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Horario *</label>
                    <input type="text" name="horario" class="form-control" required value="<?= htmlspecialchars($programa['horario']) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Ubicación *</label>
                    <input type="text" name="ubicacion" class="form-control" required value="<?= htmlspecialchars($programa['ubicacion']) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción de Actividades</label>
                    <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($programa['descripcion']) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Perfiles Requeridos (Carreras recomendadas)</label>
                    <textarea name="perfiles_requeridos" class="form-control" rows="2"><?= htmlspecialchars($programa['perfiles_requeridos']) ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre del Responsable Directo *</label>
                    <input type="text" name="responsable_nombre" class="form-control" required value="<?= htmlspecialchars($programa['responsable_nombre']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contacto del Responsable *</label>
                    <input type="text" name="responsable_contacto" class="form-control" required value="<?= htmlspecialchars($programa['responsable_contacto']) ?>">
                </div>
            </div>

            <div class="text-end mt-4 pt-3 border-top">
                <button type="submit" class="btn-submit"><i class="fa-solid fa-save me-2"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
