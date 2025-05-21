<?php
session_start();

if (!isset($_SESSION["usuario"]["email"])) {
    header("Location: login");
    exit();
}

$archivo_xml = "xml/usuarios.xml";

$email = trim($_SESSION["usuario"]["email"]);
$nombre = trim($_SESSION["usuario"]["nombre"]);
$apellidos = trim($_SESSION["usuario"]["apellidos"]);
$rol = trim($_SESSION["usuario"]["rol"]);
$userData = getUserData($email, $archivo_xml);

function getUserData($username, $archivo_xml) {
    $xml = simplexml_load_file($archivo_xml);

    foreach ($xml->usuario as $email) {
        if (trim($email->email) == $username) {
            return [
                'nombre' => (string) trim($email->nombre),
                'apellidos' => (string) trim($email->apellidos),
                'email' => (string) trim($email->email)
            ];
        }
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newNombre = $_POST["nombre"];
    $newApellidos = $_POST["apellidos"];
    $newEmail = $_POST["email"];

    $xml = simplexml_load_file($archivo_xml);

    foreach ($xml->usuario as $usuario) {
        if (trim($usuario->email) == $email) {
            $usuario->nombre = $newNombre;
            $usuario->apellidos = $newApellidos;
            $usuario->email = $newEmail;
            $_SESSION["usuario"]["nombre"] = $newNombre;
            $_SESSION["usuario"]["apellidos"] = $newApellidos;
            $_SESSION["usuario"]["email"] = $newEmail;
            break;
        }
    }

    $xml->asXML($archivo_xml);

    echo "<script type='text/javascript'>
        alert('Datos actualizados correctamente');
        window.location.href = 'perfil';
    </script>";
    exit();
}

include 'header.php';
?>
    <main>
        <div class="perfil">
            <h1>Mi perfil</h1>
        
            <section class="perfil-modificar-datos">
                <h2>Modificar Datos</h2>
                <form action="perfil" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($userData['nombre']) ?>" class="perfil-input" required>
        
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($userData['apellidos']) ?>" class="perfil-input" required>
        
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" class="perfil-input" required>
        
                    <input type="submit" name="update_user" value="Actualizar Datos">
                </form>
            </section>
        </div>
    </main>
<?php include 'footer.php'; ?>