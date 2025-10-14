<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  header('Location: index.php');
  exit;
}

// Guardar nuevo usuario 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre   = trim($_POST['nombre'] ?? '');
  $correo   = trim($_POST['correo'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $rol      = $_POST['rol'] ?? 'usuario';
  $estado   = $_POST['estado'] ?? 'activo';


  $errores = [];
  if ($nombre === '') $errores[] = "El nombre es obligatorio.";
  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "El correo no es válido.";
  if (strlen($password) < 4) $errores[] = "La contraseña debe tener al menos 4 caracteres.";

  if (empty($errores)) {
    try {
      $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password, rol, estado)
                             VALUES (:nombre, :correo, :password, :rol, :estado)");
      $stmt->execute([
        ':nombre' => $nombre,
        ':correo' => $correo,
        ':password' => md5($password), 
        ':rol' => $rol,
        ':estado' => $estado
      ]);
      header('Location: dashboard.php');
      exit;
    } catch (PDOException $e) {
      $error = "Error al guardar: " . ($e->getCode() === '23000' ? "El correo ya existe." : $e->getMessage());
    }
  } else {
    $error = implode("<br>", $errores);
  }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Agregar Usuario</title>
<style>
  body{font-family:sans-serif; background:#f4f4f4; padding:20px;}
  form{background:#fff; padding:20px; border-radius:10px; max-width:400px; margin:auto; box-shadow:0 2px 6px rgba(0,0,0,0.2);}
  input,select,button{display:block; width:100%; margin-bottom:10px; padding:8px; font-size:14px;}
  button{background:#111; color:white; border:none; border-radius:6px; cursor:pointer;}
  a{display:inline-block; margin-top:10px; text-decoration:none; color:#555;}
  .error{color:red; margin-bottom:10px;}
</style>
</head>
<body>
  <h2>Agregar nuevo usuario</h2>

  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="correo" placeholder="Correo electrónico" required>
    <input type="password" name="password" placeholder="Contraseña" required>

    <label for="rol">Rol:</label>
    <select name="rol" id="rol">
      <option value="usuario">Usuario</option>
      <option value="admin">Administrador</option>
    </select>

    <label for="estado">Estado:</label>
    <select name="estado" id="estado">
      <option value="activo">Activo</option>
      <option value="inactivo">Inactivo</option>
    </select>

    <button type="submit">Guardar</button>
    <a href="dashboard.php">Volver</a>
  </form>
</body>
</html>
