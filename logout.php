<?php
session_start();

if (isset($_SESSION["usuario"]["email"])) {
    session_destroy(); // Destruye todas las variables de sesión
    header("Location: ./"); // Redirige a inicio
    exit();
} else {
    header("Location: login"); // Redirige a la página de inicio de sesión
    exit();
}
