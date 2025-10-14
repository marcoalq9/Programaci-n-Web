<?php
session_start();
require __DIR__ . '/db.php';

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: index.php');
  exit;
}


$id = $_GET['id'] ?? null;

if (!$id) {
  header('Location: dashboard.php');
  exit;
}

// Evitar que el admin se elimine a sí mismo
if ($id == $_SESSION['usuario']['id']) {
  echo "<p style='color:red; font-family:sans-serif;'>No puedes eliminar tu propio usuario.</p>";
  echo "<p><a href='dashboard.php'>Volver</a></p>";
  exit;
}

// Obtener usuario para confirmar
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
  echo "<p>Usuario no encontrado.</p><a href='dashboard.php'>Volver</a>";
  exit;
}

// Si el usuario confirma la eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
  $stmt->execute([':id' => $id]);
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Eliminar Usuario</title>
<style>
  body{font-family:sans-serif; background:#f9f9f9; padding:30px;}
  .card{background:#fff; padding:25px; border-radius:10px; max-width:400px; margin:auto; box-shadow:0 3px 8px rgba(0,0,0,0.15);}
  form{margin-top:20px;}
  button{padding:10px 20px; border:none; border-radius:5px; cursor:pointer;}
  .eliminar{background:#c0392b; color:#fff;}
  .cancelar{background:#7f8c8d; color:#fff; text-decoration:none; padding:10px 20px; border-radius:5px;}
</style>
</head>
<body>
  <div class="card">
    <h2>Eliminar usuario</h2>
    <p>¿Estás seguro de que deseas eliminar a <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>?</p>

    <form method="post">
      <button type="submit" class="eliminar">Sí, eliminar</button>
      <a href="dashboard.php" class="cancelar">Cancelar</a>
    </form>
  </div>
</body>
</html>
