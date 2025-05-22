<?php
session_start();

$archivo_xml = 'xml/usuarios.xml';

if ($_SESSION["usuario"]["rol"] != "Administrador") {
    header("Location: ./");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');

    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["contraseña"]);
    $confirm_password = trim($_POST["confirmar-contraseña"]);
    $rol = trim($_POST["rol"]);

    if ($password != $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden"]);
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    if (!file_exists($archivo_xml)) {
        $xml = new SimpleXMLElement('<usuarios></usuarios>');
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($archivo_xml);
    } else {
        $xml = simplexml_load_file($archivo_xml);
    }
    
    foreach ($xml->usuario as $usuarioXML) {
        if ((string) $usuarioXML->email === $email) {
            echo json_encode(["status" => "error", "message" => "El usuario ya existe"]);
            exit();
        }
    }

    $nuevo_usuario = $xml->addChild('usuario');
    $nuevo_usuario->addChild('nombre', $nombre);
    $nuevo_usuario->addChild('apellidos', $apellidos);
    $nuevo_usuario->addChild('email', $email);
    $nuevo_usuario->addChild('contraseña', $password_hashed);
    $nuevo_usuario->addChild('rol', $rol);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($archivo_xml);

    echo json_encode(["status" => "success", "message" => "Registro exitoso"]);
    exit();
}

include 'header.php';

?>
    <main>
        <div class="registro">
            <h1>Crear nueva cuenta</h1>

            <!-- Mostrar mensaje de error -->
            <p id="error-message" style="color: red; font-weight: bold;"></p>

            <form method="POST" action="registro">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos">

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <label for="confirmar-contraseña">Confirmar contraseña:</label>
                <input type="password" id="confirmar-contraseña" name="confirmar-contraseña" required>

                <label for="rol">Selecciona el rol:</label>
                <select class="select-rol" id="rol" name="rol" required>
                    <option value="Administrador">Administrador</option>
                    <option value="Usuario">Usuario</option>
                </select>

                <button type="submit">Crear cuenta</button>
            </form>
        </div>
    </main>

    
<?php include 'footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form").addEventListener("submit", function (event) {
            event.preventDefault();

            let formData = new FormData(this);
            let errorMessage = document.getElementById("error-message");

            fetch("registro", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "error") {
                    errorMessage.textContent = data.message;
                    errorMessage.style.color = "red";
                } else {
                    window.location.href = "perfil";
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
</script>