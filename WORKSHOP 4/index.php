<?php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = trim($_POST['correo'] ?? '');
  $password = trim($_POST['password'] ?? '');

  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo AND estado = 'activo'");
  $stmt->execute([':correo' => $correo]);
  $usuario = $stmt->fetch();

  if ($usuario && md5($password) === $usuario['password']) {
    $_SESSION['usuario'] = $usuario;
    header('Location: dashboard.php');
    exit;
  } else {
    $error = "Correo o contraseña incorrectos, o el usuario está deshabilitado.";
  }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Login</title>
<style>
  body { font-family:sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; }
  form { background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
  input { display:block; width:100%; margin-bottom:10px; padding:8px; }
  button { width:100%; padding:10px; background:#333; color:#fff; border:none; border-radius:5px; cursor:pointer; }
  .error { color:red; margin-bottom:10px; }
</style>
</head>
<body>
  <form method="post">
    <h2>Iniciar sesión</h2>
    <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>
