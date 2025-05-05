<?php
include 'header.php';

// Cargar el XML
$xml = new DOMDocument;
$xml->load('xml/temporadas.xml');

// Cargar el XSL
$xsl = new DOMDocument;
$xsl->load('xml/clasificacion.xsl');

// Configurar el procesador XSLT
$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);

// Parámetros
$temporadaSeleccionada = isset($_SESSION['temporada']) ? $_SESSION['temporada'] : '';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

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

    header("Location: clasificacion");
    exit();
}

$proc->setParameter('', 'temporadaSeleccionada', $temporadaSeleccionada);
$proc->setParameter('', 'rolUsuario', $rol);

// Transformar y mostrar el resultado
echo $proc->transformToXML($xml);

echo "<main></main>";

include 'footer.php';