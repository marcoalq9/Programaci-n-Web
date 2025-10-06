<?php
require __DIR__ . '/db.php';
$provincias = $pdo->query("SELECT id, nombre FROM provincias ORDER BY nombre")->fetchAll();
?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<title>Registro</title>
<form action="guardar.php" method="post">
  <label>Nombre</label>
  <input type="text" name="nombre" required maxlength="100">

  <label>Apellidos</label>
  <input type="text" name="apellidos" required maxlength="150">

  <label>Provincia</label>
  <select name="provincia_id" required>
    <option value="">Seleccione…</option>
    <?php foreach ($provincias as $p): ?>
      <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
    <?php endforeach; ?>
  </select>

  <button type="submit">Registrar</button>
</form>
</html>
