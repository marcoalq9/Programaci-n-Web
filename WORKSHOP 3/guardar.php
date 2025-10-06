<?php
require __DIR__ . '/db.php';

$nombre       = trim($_POST['nombre'] ?? '');
$apellidos    = trim($_POST['apellidos'] ?? '');
$provincia_id = (int)($_POST['provincia_id'] ?? 0);

if ($nombre === '' || $apellidos === '' || $provincia_id <= 0) {
  echo "Faltan datos."; exit;
}

$sql = "INSERT INTO usuarios (nombre, apellidos, provincia_id)
        VALUES (:nombre, :apellidos, :provincia_id)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':nombre' => $nombre,
  ':apellidos' => $apellidos,
  ':provincia_id' => $provincia_id,
]);

// El username será el mismo que el campo nombre
header("Location: login.php?username=" . urlencode($nombre));
exit;
