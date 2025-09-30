<?php
// guardar.php
require __DIR__ . '/db.php';

// 1) Sanitización y validación básica del lado servidor
$nombre   = trim($_POST['nombre']   ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$correo   = trim($_POST['correo']   ?? '');
$telefono = trim($_POST['telefono'] ?? '');

$errores = [];

if ($nombre === '' || mb_strlen($nombre) > 100)     { $errores[] = "Nombre es obligatorio (máx. 100)."; }
if ($apellido === '' || mb_strlen($apellido) > 100) { $errores[] = "Apellido es obligatorio (máx. 100)."; }

if ($correo === '' || mb_strlen($correo) > 150 || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
  $errores[] = "Correo inválido o vacío (máx. 150).";
}

if ($telefono === '' || mb_strlen($telefono) > 30 || !preg_match('/^[0-9+\-\s()]{7,30}$/', $telefono)) {
  $errores[] = "Teléfono inválido (use solo dígitos y (), +, -, espacio).";
}

if ($errores) {
  http_response_code(422);
  echo "<h2>Corrige los siguientes errores:</h2><ul>";
  foreach ($errores as $e) echo "<li>" . htmlspecialchars($e, ENT_QUOTES, 'UTF-8') . "</li>";
  echo "</ul><p><a href=\"index.php\">Volver</a></p>";
  exit;
}

// 2) Insertar con prepared statements (evita SQL Injection)
$sql = "INSERT INTO contactos (nombre, apellido, correo, telefono) VALUES (:nombre, :apellido, :correo, :telefono)";
$stmt = $pdo->prepare($sql);

try {
  $stmt->execute([
    ':nombre'   => $nombre,
    ':apellido' => $apellido,
    ':correo'   => $correo,
    ':telefono' => $telefono,
  ]);
} catch (PDOException $e) {
  // Manejo de correo duplicado (índice UNIQUE)
  if ($e->getCode() === '23000') {
    http_response_code(409);
    echo "<h2>El correo ya existe.</h2><p><a href=\"index.php\">Volver</a></p>";
    exit;
  }
  http_response_code(500);
  echo "Error al guardar.";
  // echo "<pre>{$e->getMessage()}</pre>"; // (solo en desarrollo)
  exit;
}

// 3) Redirigir a página de gracias
header('Location: gracias.php');
exit;
