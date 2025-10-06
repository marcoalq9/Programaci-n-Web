<?php $username = $_GET['username'] ?? ''; ?>
<!doctype html>
<html lang="es">
<meta charset="utf-8">
<title>Login</title>
<form>
  <label>Username</label>
  <input type="text" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" readonly>

  <label>Contraseña</label>
  <input type="password" name="password">

  <button type="submit">Ingresar</button>
</form>
</html>
