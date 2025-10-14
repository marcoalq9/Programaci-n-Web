<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  exit;
}

$usuario = $_SESSION['usuario'];

// Solo los administradores pueden ver el panel
if ($usuario['rol'] !== 'admin') {
  echo "Acceso denegado.";
  exit;
}

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Panel de Administración</title>
<style>
  body{font-family:sans-serif; margin:20px;}
  table{border-collapse:collapse; width:100%;}
  th,td{border:1px solid #ccc; padding:8px;}
  th{background:#eee;}
  a.btn{padding:6px 10px; background:#333; color:white; text-decoration:none; border-radius:4px;}
</style>
</head>
<body>
  <h1>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?> (Admin)</h1>
  <p><a href="logout.php">Cerrar sesión</a> | <a class="btn" href="agregar.php">Agregar usuario</a></p>

  <table>
    <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr>
    <?php foreach ($usuarios as $u): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['correo']) ?></td>
        <td><?= $u['rol'] ?></td>
        <td><?= $u['estado'] ?></td>
        <td>
          <a href="editar.php?id=<?= $u['id'] ?>">Editar</a> |
          <a href="eliminar.php?id=<?= $u['id'] ?>" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a> |
          <a href="deshabilitar.php?id=<?= $u['id'] ?>">Deshabilitar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
