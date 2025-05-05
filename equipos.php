<?php
include 'header.php';


// Guardar la temporada anterior para detectar cambios
$temporadaAnterior = isset($_SESSION['ultima_temporada']) ? $_SESSION['ultima_temporada'] : null;
$_SESSION['ultima_temporada'] = isset($_SESSION['temporada']) ? $_SESSION['temporada'] : null;

// Si se cambió la temporada, reseteamos el equipo seleccionado
if (isset($_SESSION['temporada_cambiada']) && $_SESSION['temporada_cambiada']) {
    unset($_SESSION['temporada_cambiada']);

    // Si NO viene por POST con un equipo, lo mandamos al listado general
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['equipo'])) {
        header("Location: equipos");
        exit();
    }

    

}

// Cargar el XML
$xml = new DOMDocument;
$xml->load('xml/temporadas.xml');

// Cargar el XSL
$xsl = new DOMDocument;
$xsl->load('xml/equipos.xsl');

// Configurar el procesador XSLT
$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);

// Parámetros
$temporadaSeleccionada = isset($_SESSION['temporada']) ? $_SESSION['temporada'] : '';


$cambioDeTemporada = $temporadaAnterior && $temporadaAnterior !== $temporadaSeleccionada;

$rol = isset($_SESSION["usuario"]['rol']) ? $_SESSION["usuario"]['rol'] : '';


$equipoSeleccionado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equipo'])) {
    $equipoSeleccionado = htmlspecialchars($_POST['equipo']);
}

// Comprobar si el equipo seleccionado existe en la temporada actual
if ($equipoSeleccionado) {
    $xpath = new DOMXPath($xml);
    $equipoExiste = $xpath->query("//temporada[nombre='$temporadaSeleccionada']/equipos/equipo[nombre='$equipoSeleccionado']");

    if ($equipoExiste->length === 0) {
        // El equipo no existe en la temporada, lo eliminamos
        $equipoSeleccionado = null;
    }
}




if ($equipoSeleccionado) {
    $proc->setParameter('', 'equipoSeleccionado', $equipoSeleccionado);
}

$proc->setParameter('', 'temporadaSeleccionada', $temporadaSeleccionada);
$proc->setParameter('', 'rolUsuario', $rol);

function getValidImagePath($basePath, $extensions) {
    foreach ($extensions as $ext) {
        $path = $basePath . '.' . $ext;
        if (file_exists($path)) {
            return $path;
        }
    }
    return null;
}

$xpath = new DOMXPath($xml);
$equipos = $xpath->query("//temporada[nombre='$temporadaSeleccionada']/equipos/equipo");
$extensiones = ['png', 'jpg', 'jpeg', 'webp'];

foreach ($equipos as $equipo) {
    $nombreEquipo = $equipo->getElementsByTagName('nombre')->item(0)->nodeValue;
    $basePath = "img/temporadas/Temporada $temporadaSeleccionada/$nombreEquipo/$nombreEquipo";
    $rutaValida = getValidImagePath($basePath, $extensiones);

    if ($rutaValida) {
        $rutaImagen = $xml->createElement('rutaImagen', $rutaValida);
        $equipo->appendChild($rutaImagen);
    }
}

$jugadores = $xpath->query("//temporada[nombre='$temporadaSeleccionada']/equipos/equipo/jugadores/jugador");

foreach ($jugadores as $jugador) {
    $nombreJugador = $jugador->getElementsByTagName('nombre')->item(0)->nodeValue;
    $apellidosJugador = $jugador->getElementsByTagName('apellidos')->item(0)->nodeValue;
    $nombreEquipo = $jugador->parentNode->parentNode->getElementsByTagName('nombre')->item(0)->nodeValue;
    $basePath = "img/temporadas/Temporada $temporadaSeleccionada/$nombreEquipo/$nombreJugador $apellidosJugador";
    $rutaValida = getValidImagePath($basePath, $extensiones);

    if ($rutaValida) {
        $rutaImagen = $xml->createElement('rutaImagen', $rutaValida);
        $jugador->appendChild($rutaImagen);
    }
}

$temporadaEnCreacion = $xpath->query("//temporada[nombre='$temporadaSeleccionada' and estado='En creación']");

if ($temporadaEnCreacion->length > 0 && !in_array($rol, ['Administrador', 'Entrenador'])) {
    // Buscar la temporada activa
    $temporadaActiva = $xpath->query("//temporada[estado='Activa']/nombre")->item(0);
    if ($temporadaActiva) {
        $temporadaSeleccionada = $temporadaActiva->nodeValue;
    } else {
        // Si no hay temporadas activas, seleccionar la última temporada
        $ultimaTemporada = $xpath->query("//temporada/nombre")->item($xpath->query("//temporada/nombre")->length - 1);
        if ($ultimaTemporada) {
            $temporadaSeleccionada = $ultimaTemporada->nodeValue;
        }
    }

    header("Location: equipos");
    exit();
}

// Transformar y mostrar el resultado
echo $proc->transformToXML($xml);

echo "<main></main>";
?>

<script>
    const equipoSeleccionado = <?= json_encode($equipoSeleccionado); ?>;
</script>

<?php include 'footer.php'; ?>