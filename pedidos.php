<?php
include 'header.php';

// Asumiendo que ya tienes estas variables de sesión definidas:
if(!Isset($_SESSION["usuario"])){
    $usuarioSesion = "null";
    $usuarioSesionRol = "null";
} else {
    $usuarioSesion = trim($_SESSION["usuario"]["email"]); 
    $usuarioSesionRol = trim($_SESSION["usuario"]["rol"]);
}
$usuarioSesion = str_replace(' ', '', $usuarioSesion);

$_SESSION['url-media'] = '../imagenes/';

// Cargar XML
$xml = new DOMDocument;
$xml->load('xml/musicWorld.xml');

// Cargar XSL
$xsl = new DOMDocument;
$xsl->load('xml/pedidos.xsl');

// Obtener todos los pedidos
$xpath = new DOMXPath($xml);
$pedidos = $xpath->query("//pedido");
foreach ($pedidos as $pedido) {
    $codPedidoNode = $pedido->getElementsByTagName('cod_pedido')->item(0);
    if ($codPedidoNode) {
        $codLimpio = trim(preg_replace('/\s+/', '', $codPedidoNode->nodeValue));
        $codPedidoNode->nodeValue = $codLimpio;
    }

    $usuarioXML = $pedido->getElementsByTagName('correo')->item(0)->nodeValue;
    $usuarioXML = str_replace(' ', '', trim($usuarioXML));

    if (($usuarioXML === $usuarioSesion) || ($usuarioSesionRol === "Administrador")) {
        $codigo = trim($pedido->getElementsByTagName('codigo')->item(0)->nodeValue);
        $rutaImagen = $_SESSION['url-media'] . $codigo . '.png';

        $rutaNodo = $xml->createElement('rutaImagenPedido', $rutaImagen);
        $pedido->appendChild($rutaNodo);
    } else {
        $pedido->parentNode->removeChild($pedido);
    }
}

// Transformación
if (!isset($_SESSION['usuario'])) {
    echo <<<HTML
        <main>
            <br>
            <h1 style="color:white">ㅤㅤInicia sesion para ver tus pedidos ➤</h1>
        </main>
    HTML;   
} else {
    $proc = new XSLTProcessor;
    $proc->importStylesheet($xsl);
    echo $proc->transformToXML($xml);
    echo "<main></main>";
}

include 'footer.php';
?>