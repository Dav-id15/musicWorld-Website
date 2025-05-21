<?php
session_start();

if (isset($_SESSION["usuario"]["email"])) {
    session_destroy(); // Destruye todas las variables de sesión
    header("Location: ./tienda"); // Redirige a pedidos
    exit();
} else {
    header("Location: login"); // Redirige a la página de inicio de sesión
    exit();
}
