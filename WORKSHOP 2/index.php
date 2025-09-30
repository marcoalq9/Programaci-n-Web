<?php
// index.php
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Workshop 2 — Registro de Contacto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    *{box-sizing:border-box} body{font-family:system-ui,Segoe UI,Roboto,sans-serif; margin:0; padding:2rem; background:#f8fafc}
    .card{max-width:520px; margin:0 auto; background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,.05)}
    h1{margin:0 0 8px} p{color:#6b7280; margin-top:0}
    label{display:block; font-weight:600; margin:.75rem 0 .25rem}
    input{width:100%; padding:.7rem .85rem; border:1px solid #d1d5db; border-radius:10px}
    button{margin-top:1rem; padding:.8rem 1rem; border:0; border-radius:12px; background:#111827; color:#fff; font-weight:600; cursor:pointer}
    button:hover{opacity:.9}
    .req{color:#ef4444}
  </style>
</head>
<body>
  <div class="card">
    <h1>Registro de Contacto</h1>
    <p>Ingresa tus datos y se guardarán en MySQL.</p>

    <form action="guardar.php" method="post" novalidate>
      <label>Nombre <span class="req">*</span></label>
      <input type="text" name="nombre" required maxlength="100" />

      <label>Apellido <span class="req">*</span></label>
      <input type="text" name="apellido" required maxlength="100" />

      <label>Correo <span class="req">*</span></label>
      <input type="email" name="correo" required maxlength="150" />

      <label>Teléfono <span class="req">*</span></label>
      <input type="tel" name="telefono" required maxlength="30" pattern="[0-9+\-\s()]{7,30}" />

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>
