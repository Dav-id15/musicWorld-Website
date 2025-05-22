<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/ea577ecbca.js" crossorigin="anonymous"></script>
	<title>Music World - Store</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="shortcut icon" href="img/logoApp.png" type="image/x-icon">
</head>

<body login="<?= isset($_SESSION["usuario"]['email']) ? 'true' : 'false'; ?>">
	<header>
		<div class="header-pc">
			<div id="logonav"><a href="./"><img id="logo" src="img/LogoMusicWorld.png" alt="Logo"></a></div>
			<nav id="navegador">
				<ul>
					<li><a href="pedidos" class="<?= ($current_page == 'pedidos') ? 'active' : '' ?>">Pedidos</a></li>
					<li><a href="tienda" class="<?= ($current_page == 'tienda') ? 'active' : '' ?>">Tienda</a></li>
					<li><a href="contacto" class="<?= ($current_page == 'contacto') ? 'active' : '' ?>">Contacto</a></li>
				</ul>
			</nav>
			<div>
				<?php if (isset($_SESSION["usuario"]["email"])) : ?>
					<div class="dropdown">
						<span class="welcome-msg"><?= htmlspecialchars($_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellidos"] . " (" . htmlspecialchars($_SESSION["usuario"]["rol"])) . ")" ?></span>
						<button class="dropdown-btn">
							<i class="fa-solid fa-chevron-down"></i>
						</button>
						<div class="dropdown-content">
							<a href="perfil">Mi perfil</a>
							<?php if ($_SESSION["usuario"]["rol"] === "Administrador") : ?>
								<a href="usuarios">Usuarios</a>
							<?php endif; ?>
							<a href="logout" class="logoutbtn">Cerrar sesión</a>
						</div>
					</div>
				<?php else : ?>
					<button class="login-button"><a href="login" class="loginbtn">Iniciar sesión</a></button>
				<?php endif; ?>
			</div>
		</div>
		<div class="header-movil">
			<div id="logonav">
				<a href="/">
					<img id="logo" src="img/LogoMusicWorld.png" alt="Logo">
				</a>
				<i class="fa fa-bars menu-icon" id="menu-icon"></i>
			</div>
			<nav id="navegador">
				<ul>
					<li><a href="tienda" class="<?= ($current_page == 'tienda') ? 'active' : '' ?>">Tienda</a></li>
					<li><a href="pedidos" class="<?= ($current_page == 'pedidos') ? 'active' : '' ?>">Pedidos</a></li>
					<li><a href="contacto" class="<?= ($current_page == 'contacto') ? 'active' : '' ?>">Contacto</a></li>
					<li>
						<?php if (isset($_SESSION["usuario"]["email"])) : ?>
							<div class="dropdown-movil">
								<span class="welcome-msg">
									<?= htmlspecialchars($_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellidos"] . " (" . htmlspecialchars($_SESSION["usuario"]["rol"])) . ")" ?>
								</span>
								<button class="dropdown-btn-movil">
									<i class="fa-solid fa-chevron-down"></i>
								</button>
								<div class="dropdown-content-movil">
									<a href="perfil">Mi perfil</a>
									<?php if ($_SESSION["usuario"]["rol"] === "Administrador") : ?>
										<a href="usuarios">Usuarios</a>
									<?php endif; ?>
									<a href="logout">Cerrar sesión</a>
								</div>
							</div>
						<?php else : ?>
							<a href="login" class="<?= ($current_page == 'login') ? 'active' : '' ?>">Iniciar sesión</a>
						<?php endif; ?>
					</li>
				</ul>
			</nav>
		</div>
	</header>