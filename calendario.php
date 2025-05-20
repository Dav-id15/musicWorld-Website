<?php
include 'header.php';

// Asumiendo que ya tienes estas variables de sesión definidas:
$usuarioSesion = trim($_SESSION["usuario"]["nombre"] . $_SESSION["usuario"]["apellidos"]);
$usuarioSesion = str_replace(' ', '', $usuarioSesion); 

$_SESSION['url-media'] = '../imagenes/';

// Cargar XML
$xml = new DOMDocument;
$xml->load('xml/musicWorld.xml');

// Cargar XSL
$xsl = new DOMDocument;
$xsl->load('xml/calendario.xsl');

// Obtener todos los pedidos
$xpath = new DOMXPath($xml);
$pedidos = $xpath->query("//pedido");
echo $usuarioSesion . "/-/-/-/-/-/";
foreach ($pedidos as $pedido) {
    $usuarioXML = $pedido->getElementsByTagName('usuario')->item(0)->nodeValue;
    $usuarioXML = str_replace(' ', '', trim($usuarioXML)); // Elimina espacios
    echo $usuarioXML . "/";
    // Compara usuarios
    if ($usuarioXML === $usuarioSesion) {
        echo $usuarioXML;
        // Agrega imagen solo a los pedidos del usuario actual
        $codigo = trim($pedido->getElementsByTagName('codigo')->item(0)->nodeValue);
        $rutaImagen = $_SESSION['url-media'] . $codigo . '.png';

        $rutaNodo = $xml->createElement('rutaImagenPedido', $rutaImagen);
        $pedido->appendChild($rutaNodo);
    } else {
        // Elimina del XML los pedidos de otros usuarios
        $pedido->parentNode->removeChild($pedido);
    }
}
// Transformación
$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);
echo $proc->transformToXML($xml);

include 'footer.php';
?>