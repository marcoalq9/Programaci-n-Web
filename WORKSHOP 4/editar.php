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


$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
  exit("Usuario no encontrado.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre   = trim($_POST['nombre'] ?? '');
  $correo   = trim($_POST['correo'] ?? '');
  $rol      = $_POST['rol'] ?? 'usuario';
  $estado   = $_POST['estado'] ?? 'activo';

  $errores = [];
  if ($nombre === '') $errores[] = "El nombre es obligatorio.";
  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Correo no válido.";


  $nueva_pass = trim($_POST['password'] ?? '');
  $actualizar_pass = ($nueva_pass !== '');

  if (empty($errores)) {
    try {
      if ($actualizar_pass) {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, correo = :correo, rol = :rol, estado = :estado, password = :password 
                WHERE id = :id";
        $params = [
          ':nombre' => $nombre,
          ':correo' => $correo,
          ':rol' => $rol,
          ':estado' => $estado,
          ':password' => md5($nueva_pass),
          ':id' => $id
        ];
      } else {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, correo = :correo, rol = :rol, estado = :estado 
                WHERE id = :id";
        $params = [
          ':nombre' => $nombre,
          ':correo' => $correo,
          ':rol' => $rol,
          ':estado' => $estado,
          ':id' => $id
        ];
      }

      $stmt = $pdo->prepare($sql);
      $stmt->execute($params);

      header('Location: dashboard.php');
      exit;
    } catch (PDOException $e) {
      $error = "Error al actualizar: " . ($e->getCode() === '23000' ? "El correo ya existe." : $e->getMessage());
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
<title>Editar Usuario</title>
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
  <h2>Editar usuario</h2>

  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="nombre" placeholder="Nombre completo" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    <input type="email" name="correo" placeholder="Correo electrónico" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

    <label for="password">Nueva contraseña (opcional):</label>
    <input type="password" name="password" id="password" placeholder="Dejar en blanco para no cambiar">

    <label for="rol">Rol:</label>
    <select name="rol" id="rol">
      <option value="usuario" <?= $usuario['rol']==='usuario'?'selected':'' ?>>Usuario</option>
      <option value="admin" <?= $usuario['rol']==='admin'?'selected':'' ?>>Administrador</option>
    </select>

    <label for="estado">Estado:</label>
    <select name="estado" id="estado">
      <option value="activo" <?= $usuario['estado']==='activo'?'selected':'' ?>>Activo</option>
      <option value="inactivo" <?= $usuario['estado']==='inactivo'?'selected':'' ?>>Inactivo</option>
    </select>

    <button type="submit">Guardar cambios</button>
    <a href="dashboard.php">Volver</a>
  </form>
</body>
</html>
