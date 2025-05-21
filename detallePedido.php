<?php
session_start(); 
include 'header.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: tienda.php");
    exit();
}

$usuarioSesion = str_replace(' ', '', trim($_SESSION['usuario']['email']));
$usuarioSesionRol = trim($_SESSION['usuario']['rol']);

if (!isset($_GET['cod'])) {
    header("Location: tienda.php");
    exit();
}

$codPedido = trim($_GET['cod']);

$xml = new DOMDocument;
$xml->load('xml/musicWorld.xml');

$xpath = new DOMXPath($xml);
$pedidoNodo = null;

foreach ($xpath->query("//pedido") as $pedido) {
    $codXml = trim($pedido->getElementsByTagName('cod_pedido')->item(0)->nodeValue);
    if ($codXml === $codPedido) {
        $pedidoNodo = $pedido;
        break;
    }
}

if ($pedidoNodo === null) {
    header("Location: tienda.php");
    exit();
}

$correoPedido = str_replace(' ', '', trim($pedidoNodo->getElementsByTagName('correo')->item(0)->nodeValue));

if (($usuarioSesion !== $correoPedido) && ($usuarioSesionRol !== "Administrador")) {
    header("Location: tienda.php");
    exit();
}

$codigo = trim($pedidoNodo->getElementsByTagName('codigo')->item(0)->nodeValue);
$articulo = trim($pedidoNodo->getElementsByTagName('articulo')->item(0)->nodeValue);
$direccion = trim($pedidoNodo->getElementsByTagName('direccion')->item(0)->nodeValue);
$fecha = trim($pedidoNodo->getElementsByTagName('fecha')->item(0)->nodeValue);
$hora = trim($pedidoNodo->getElementsByTagName('hora')->item(0)->nodeValue);
$cantidad = trim($pedidoNodo->getElementsByTagName('cantidad')->item(0)->nodeValue);
$precio = trim($pedidoNodo->getElementsByTagName('precio')->item(0)->nodeValue);
$pagado = trim($pedidoNodo->getElementsByTagName('pagado')->item(0)->nodeValue);
$correo = $correoPedido;

// Construir la ruta de la imagen usando el código, priorizando esta ruta
$rutaImagenPedido = '../imagenes/' . $codigo . '.png';

// Si la imagen no existe, intentar con la ruta de XML (opcional)
if (!file_exists($rutaImagenPedido)) {
    $rutaNodo = $pedidoNodo->getElementsByTagName('rutaImagenPedido');
    if ($rutaNodo->length > 0) {
        $rutaImagenPedido = trim($rutaNodo->item(0)->nodeValue);
    } else {
        $rutaImagenPedido = ''; // No hay imagen
    }
}
?>

<main class="detalle-pedido">
    <h1>Detalle del pedido</h1>

    <p><strong>Artículo:</strong> <?= htmlspecialchars($articulo) ?></p>
    <p><strong>Dirección:</strong> <?= htmlspecialchars($direccion) ?></p>
    <p><strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?></p>
    <p><strong>Hora:</strong> <?= htmlspecialchars($hora) ?></p>
    <p><strong>Cantidad:</strong> <?= htmlspecialchars($cantidad) ?></p>
    <p><strong>Precio unitario:</strong> <?= htmlspecialchars($precio) ?> €</p>
    <p><strong>Total pagado:</strong> <?= htmlspecialchars($pagado) ?> €</p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>

    <?php if ($rutaImagenPedido && file_exists($rutaImagenPedido)): ?>
        <img src="<?= htmlspecialchars($rutaImagenPedido) ?>" alt="Imagen del pedido" />
    <?php endif; ?>

    <a href="pedidos.php">← Volver a mis pedidos</a>
</main>

<?php include 'footer.php'; ?>
