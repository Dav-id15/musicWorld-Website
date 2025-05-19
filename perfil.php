<?php
session_start();

if (!isset($_SESSION["usuario"]["userName"])) {
    header("Location: login");
    exit();
}

$archivo_xml = "xml/usuarios.xml";

$userName = $_SESSION["usuario"]["userName"];
$nombre = $_SESSION["usuario"]["nombre"];
$apellidos = $_SESSION["usuario"]["apellidos"];
$rol = $_SESSION["usuario"]["rol"];
$userData = getUserData($userName, $archivo_xml);

function getUserData($username, $archivo_xml) {
    $xml = simplexml_load_file($archivo_xml);

    foreach ($xml->usuario as $userName) {
        if ($userName->userName == $username) {
            return [
                'nombre' => (string) $userName->nombre,
                'apellidos' => (string) $userName->apellidos,
                'userName' => (string) $userName->userName
            ];
        }
    }

    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newNombre = $_POST["nombre"];
    $newApellidos = $_POST["apellidos"];
    $newuserName = $_POST["userName"];

    $xml = simplexml_load_file($archivo_xml);

    foreach ($xml->usuario as $usuario) {
        if ($usuario->userName == $userName) {
            $usuario->nombre = $newNombre;
            $usuario->apellidos = $newApellidos;
            $usuario->userName = $newuserName;
            $_SESSION["usuario"]["nombre"] = $newNombre;
            $_SESSION["usuario"]["apellidos"] = $newApellidos;
            $_SESSION["usuario"]["userName"] = $newuserName;
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

function getUsersFromXML($archivo_xml) {
    if (!file_exists($archivo_xml)) {
        return [];
    }

    $xml = simplexml_load_file($archivo_xml);
    $usuarios = [];

    foreach ($xml->usuario as $usuario) {
        $usuarios[] = [
            'nombre' => (string) $usuario->nombre,
            'apellidos' => (string) $usuario->apellidos,
            'userName' => (string) $usuario->userName,
            'rol' => (string) $usuario->rol
        ];
    }

    return $usuarios;
}

include 'header.php';
?>
    <main>
        <div class="<?= ($_SESSION["usuario"]["rol"] === "Administrador") ? 'perfil-admin' : 'perfil' ?>">
            <h1>Mi perfil</h1>
        
            <section class="perfil-modificar-datos">
                <h2>Modificar Datos</h2>
                <form action="perfil" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($userData['nombre']) ?>" class="perfil-input" required>
        
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($userData['apellidos']) ?>" class="perfil-input" required>
        
                    <label for="userName">Usuario:</label>
                    <input type="text" id="userName" name="userName" value="<?= htmlspecialchars($userData['userName']) ?>" class="perfil-input" required>
        
                    <input type="submit" name="update_user" value="Actualizar Datos">
                </form>
            </section>
        
            <?php if ($rol === 'Administrador'): ?>
            <section class="perfil-usuarios">    
                <h2>Usuarios Registrados</h2>
                <div class="boton-centrado">
                    <button class="new-user-button"><a href="registro">Nuevo usuario</a></button>
                </div>
                <div class="tabla-contenedor">
                    <table class="usuarios-tabla">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $usuarios = getUsersFromXML($archivo_xml);

                            usort($usuarios, function ($a, $b) {
                                return strcasecmp($a['nombre'], $b['nombre']);
                            });

                            foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                    <td><?= htmlspecialchars($usuario['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($usuario['userName']) ?></td>
                                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>
<?php include 'footer.php'; ?>