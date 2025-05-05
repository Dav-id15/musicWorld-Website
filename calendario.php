<?php
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jornada'])) {
    $_SESSION['jornada'] = (int) $_POST['jornada'];
}

// Cargar el XML
$xml = new DOMDocument;
$xml->load('xml/temporadas.xml');

// Cargar el XSL
$xsl = new DOMDocument;
$xsl->load('xml/calendario.xsl');

// Configurar el procesador XSLT
$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);

// Parámetros
$temporadaSeleccionada = isset($_SESSION['temporada']) ? $_SESSION['temporada'] : '';
$jornadaSeleccionada = isset($_SESSION['jornada']) ? $_SESSION['jornada'] : 1;
$rol = isset($_SESSION["usuario"]['rol']) ? $_SESSION["usuario"]['rol'] : '';

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
$partidos = $xpath->query("//temporada[nombre='$temporadaSeleccionada']/jornadas/jornada[numero='$jornadaSeleccionada']/partidos/partido");
$extensiones = ['png', 'jpg', 'jpeg', 'webp'];

foreach ($partidos as $partido) {
    $equipo1 = $partido->getElementsByTagName('equipo1')->item(0)->nodeValue;
    $basePathEquipo1 = "img/temporadas/Temporada $temporadaSeleccionada/$equipo1/$equipo1";
    $rutaValidaEquipo1 = getValidImagePath($basePathEquipo1, $extensiones);

    if ($rutaValidaEquipo1) {
        $rutaImagenEquipo1 = $xml->createElement('rutaImagenEquipo1', $rutaValidaEquipo1);
        $partido->appendChild($rutaImagenEquipo1);
    }

    $equipo2 = $partido->getElementsByTagName('equipo2')->item(0)->nodeValue;
    $basePathEquipo2 = "img/temporadas/Temporada $temporadaSeleccionada/$equipo2/$equipo2";
    $rutaValidaEquipo2 = getValidImagePath($basePathEquipo2, $extensiones);

    if ($rutaValidaEquipo2) {
        $rutaImagenEquipo2 = $xml->createElement('rutaImagenEquipo2', $rutaValidaEquipo2);
        $partido->appendChild($rutaImagenEquipo2);
    }
}

$temporadaEnCreacion = $xpath->query("//temporada[nombre='$temporadaSeleccionada' and estado='En creación']");

if ($temporadaEnCreacion->length > 0 && !in_array($rol, ['Administrador', 'Árbitro'])) {
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

    header("Location: calendario");
    exit();
}

$proc->setParameter('', 'temporadaSeleccionada', $temporadaSeleccionada);
$proc->setParameter('', 'jornadaSeleccionada', $jornadaSeleccionada);
$proc->setParameter('', 'rolUsuario', $rol);

// Transformar y mostrar el resultado
echo $proc->transformToXML($xml);
?>

<main></main>

<script>
    function cambiarTemporada(temporada) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('temporada', temporada);
        urlParams.delete('jornada');
        window.location.search = urlParams.toString();
    }

    function cambiarJornada(jornada) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('jornada', jornada);
        window.location.search = urlParams.toString();
    }
</script>

<?php include 'footer.php'; ?>