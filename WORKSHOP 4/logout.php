<?php
session_start();

// Eliminar todos los datos de sesión
session_unset();     // Limpia las variables de sesión
session_destroy();   // Destruye la sesión actual

// Redirigir al login
header('Location: index.php');
exit;
