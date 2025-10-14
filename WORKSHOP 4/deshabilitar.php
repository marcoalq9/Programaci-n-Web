<?php
session_start();
require __DIR__ . '/db.php';


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: index.php');
  exit;
}


$id = $_GET['id'] ?? null;

if (!$id) {
  header('Location: dashboard.php');
  exit;
}

//  Evitar que el admin se deshabilite a sí mismo
if ($id == $_SESSION['usuario']['id']) {
  echo "<p style='color:red; font-family:sans-serif;'>No puedes deshabilitar tu propio usuario.</p>";
  echo "<p><a href='dashboard.php'>Volver</a></p>";
  exit;
}


$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
  echo "<p>Usuario no encontrado.</p><a href='dashboard.php'>Volver</a>";
  exit;
}


$nuevo_estado = ($usuario['estado'] === 'activo') ? 'inactivo' : 'activo';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("UPDATE usuarios SET estado = :estado WHERE id = :id");
  $stmt->execute([':estado' => $nuevo_estado, ':id' => $id]);
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Deshabilitar Usuario</title>
<style>
  body{font-family:sans-serif; background:#f9f9f9; padding:30px;}
  .card{background:#fff; padding:25px; border-radius:10px; max-width:400px; margin:auto; box-shadow:0 3px 8px rgba(0,0,0,0.15);}
  form{margin-top:20px;}
  button{padding:10px 20px; border:none; border-radius:5px; cursor:pointer;}
  .confirmar{background:#111; color:#fff;}
  .cancelar{background:#7f8c8d; color:#fff; text-decoration:none; padding:10px 20px; border-radius:5px;}
</style>
</head>
<body>
  <div class="card">
    <h2><?= $usuario['estado'] === 'activo' ? 'Deshabilitar' : 'Habilitar' ?> usuario</h2>
    <p>¿Deseas <?= $usuario['estado'] === 'activo' ? 'deshabilitar' : 'habilitar' ?> a 
      <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>?</p>

    <form method="post">
      <button type="submit" class="confirmar">
        Sí, <?= $usuario['estado'] === 'activo' ? 'deshabilitar' : 'habilitar' ?>
      </button>
      <a href="dashboard.php" class="cancelar">Cancelar</a>
    </form>
  </div>
</body>
</html>
