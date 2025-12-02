<?php
/**
 * Cerrar sesión
 */

require_once 'auth.php';

// Cerrar sesión
logout();

// Redirigir al login
header("Location: login.php?success=" . urlencode("Sesión cerrada correctamente"));
exit();
