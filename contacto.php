<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    echo "<script type='text/javascript'>
        alert('Hemos recibido tu mensaje, te responderemos a la mayor brevedad posible. ¡Gracias!');
        window.location.href = 'contacto';
    </script>";
    exit();
}

include 'header.php';
?>
<main>
    <div class="contacto">
        <div><h1>Contacto</h1></div>
        
        <form id="contacto-form" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" required></textarea>
            
            <button type="submit">Enviar</button>
        </form>
    </div>
</main>
<?php include 'footer.php'; ?>