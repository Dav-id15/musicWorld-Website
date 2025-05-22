<?php
include 'header.php';

$_SESSION['url-media'] = '../imagenes/';

// Cargar XML
$xml = new DOMDocument;
$xml->load('xml/musicWorld.xml');

// Cargar XSL
$xsl = new DOMDocument;
$xsl->load('xml/tienda.xsl');


// Obtener todos los artículos
$xpath = new DOMXPath($xml);
$articulos = $xpath->query("//articulos/articulo");

foreach ($articulos as $articulo) {
    // Obtener y limpiar el código del artículo
    $codigo = trim($articulo->getElementsByTagName('codigo')->item(0)->nodeValue);

    // Construir la ruta de la imagen
    $rutaImagen = $_SESSION['url-media'] . $codigo . '.png';

    // Crear y añadir el nodo rutaImagenArticulo
    $rutaNodo = $xml->createElement('rutaImagenArticulo', $rutaImagen);
    $articulo->appendChild($rutaNodo);
}

$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);
echo $proc->transformToXML($xml);
echo "<main></main>";


include 'footer.php'; ?>