<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar el archivo XML
$xml = simplexml_load_file("xml/temporadas.xml");

// Determinar la temporada seleccionada
if (!isset($_SESSION['temporada'])) {
    // Si no hay una temporada seleccionada en la sesión, buscar la activa o la última
    $temporadaActiva = null;
    $ultimaTemporada = null;

    foreach ($xml->temporada as $temporada) {
        $estado = (string) $temporada->estado;
        $nombreTemporada = (string) $temporada->nombre;

        if ($estado === 'Activa') {
            $temporadaActiva = $nombreTemporada;
        }
        $ultimaTemporada = $nombreTemporada;
    }

    // Usar la temporada activa si existe, de lo contrario usar la última
    $_SESSION['temporada'] = $temporadaActiva ?? $ultimaTemporada;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['temporada']) && isset($_POST['ajax'])) {
    $_SESSION['temporada'] = htmlspecialchars($_POST['temporada']);
	$_SESSION['temporada_cambiada'] = true;
    echo json_encode(["status" => "success", "message" => "Temporada actualizada"]);
    exit();
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
	<title>LPB - Baloncesto</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/responsive.css">
	<link rel="shortcut icon" href="img/basketball.png" type="image/x-icon">
</head>

<body login="<?= isset($_SESSION['userName']) ? 'true' : 'false'; ?>">
	<header>
		<div class="header-pc">
			<div id="logonav"><a href="./"><img id="logo" src="img/logo200x200.png" alt="Logo"></a></div>
			<nav id="navegador">
				<ul>
					<li><a href="./" class="<?= ($current_page == 'LM_Grupo4') ? 'active' : '' ?>">Inicio</a></li>
					<li><a href="noticias" class="<?= ($current_page == 'noticias') ? 'active' : '' ?>">Noticias</a></li>
					<li><a href="clasificacion" class="<?= ($current_page == 'clasificacion') ? 'active' : '' ?>">Clasificación</a></li>
					<li><a href="equipos" class="<?= ($current_page == 'equipos') ? 'active' : '' ?>">Equipos</a></li>
					<li><a href="calendario" class="<?= ($current_page == 'calendario') ? 'active' : '' ?>">Calendario</a></li>
					<li><a href="contacto" class="<?= ($current_page == 'contacto') ? 'active' : '' ?>">Contacto</a></li>
				</ul>
			</nav>
			<div class="temporada-select">
				<label for="temporadaSelect">Temporada:</label>
				<select id="temporadaSelect" name="temporada">
					<?php
					if ($xml) {
						foreach ($xml->temporada as $temporada) {
							$nombreTemporada = htmlspecialchars((string) $temporada->nombre);
							$estado = (string) $temporada->estado;

							$rolUsuario = isset($_SESSION["rol"]) ? $_SESSION["rol"] : '';

							if ($estado !== 'En creación' || in_array($rolUsuario, ['Administrador', 'Entrenador'])) {
								$selected = ($_SESSION['temporada'] === $nombreTemporada) ? 'selected' : '';
								echo "<option value=\"$nombreTemporada\" $selected>$nombreTemporada</option>";
							}
						}
					} else {
						echo "<option value=\"\">Error al cargar las temporadas</option>";
					}
					?>
				</select>
			</div>
			<div>
				<?php if (isset($_SESSION["usuario"]["userName"])) : ?>
					<div class="dropdown">
						<span class="welcome-msg"><?= htmlspecialchars($_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellidos"] . " (" . htmlspecialchars($_SESSION["usuario"]["rol"])) . ")" ?></span>
						<button class="dropdown-btn">
							<i class="fa-solid fa-chevron-down"></i>
						</button>
						<div class="dropdown-content">
							<a href="perfil">Mi perfil</a>
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
					<img id="logo" src="img/logo200x200.png" alt="Logo">
					<img src="img/lpb.png" alt="logo">
				</a>
				<i class="fa fa-bars menu-icon" id="menu-icon"></i>
			</div>
			<nav id="navegador">
				<ul>
					<li><a href="./" class="<?= ($current_page == 'LM_Grupo4') ? 'active' : '' ?>">Inicio</a></li>
					<li><a href="noticias" class="<?= ($current_page == 'noticias') ? 'active' : '' ?>">Noticias</a></li>
					<li><a href="clasificacion" class="<?= ($current_page == 'clasificacion') ? 'active' : '' ?>">Clasificación</a></li>
					<li><a href="equipos" class="<?= ($current_page == 'equipos') ? 'active' : '' ?>">Equipos</a></li>
					<li><a href="calendario" class="<?= ($current_page == 'calendario') ? 'active' : '' ?>">Calendario</a></li>
					<li><a href="contacto" class="<?= ($current_page == 'contacto') ? 'active' : '' ?>">Contacto</a></li>
					<li>
						<?php if (isset($_SESSION["usuario"]["userName"])) : ?>
							<div class="dropdown-movil">
								<span class="welcome-msg">
									<?= htmlspecialchars($_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellidos"] . " (" . htmlspecialchars($_SESSION["usuario"]["rol"])) . ")" ?>
								</span>
								<button class="dropdown-btn-movil">
									<i class="fa-solid fa-chevron-down"></i>
								</button>
								<div class="dropdown-content-movil">
									<a href="perfil">Mi perfil</a>
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