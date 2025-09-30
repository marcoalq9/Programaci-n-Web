<?php
declare(strict_types=1);
date_default_timezone_set('America/Mexico_City'); // ajusta tu zona
$ahora = new DateTime('now');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Fecha y hora (PHP)</title>
  <meta http-equiv="refresh" content="1">
</head>
<body>
  <h1>Fecha y hora con PHP</h1>
  <p><?= $ahora->format('d/m/Y H:i:s') ?></p>
</body>
</html>
