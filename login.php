<?php
session_start();

if (isset($_SESSION["usuario"]["email"])) {
    header("Location: ./");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json');

    $email = trim($_POST["email"]);
    $password = trim($_POST["contraseña"]);

    // Cargar el archivo XML
    $xml = simplexml_load_file("xml/usuarios.xml");
    if (!$xml) {
        echo json_encode(["status" => "error", "message" => "Error al cargar los datos"]);
        exit();
    }

    // Buscar el usuario en el XML
    foreach ($xml->usuario as $usuarioXML) {
        if ((string) trim($usuarioXML->email) === $email) {
            // Verificar la contraseña
            if (password_verify($password, (string) trim($usuarioXML->contraseña))) {
                $_SESSION["usuario"]["nombre"] = (string) trim($usuarioXML->nombre);
                $_SESSION["usuario"]["apellidos"] = (string) trim($usuarioXML->apellidos);
                $_SESSION["usuario"]["email"] = (string) trim($usuarioXML->email);
                $_SESSION["usuario"]["rol"] = (string) trim($usuarioXML->rol);

                $redirectUrl = "./";
                if ($_SESSION["usuario"]["rol"] === "Administrador") {
                    $redirectUrl = "usuarios.php";
                } else {
                    $redirectUrl = "pedidos";
                }

                echo json_encode(["status" => "success", "message" => "Inicio de sesión exitoso", "redirect" => $redirectUrl]);
                exit();
            } else {
                echo json_encode(["status" => "error", "message" => "La contraseña es incorrecta"]);
                exit();
            }
        }
    }

    // Si no encontró el usuario
    echo json_encode(["status" => "error", "message" => "El usuario no existe"]);
    exit();
}

include 'header.php';
?>
    <main>
        <div class="login">
            <h1>Iniciar sesión</h1>

            <!-- Mostrar mensaje de error -->
            <p id="error-message" style="color: red; font-weight: bold;"></p>

            <form method="POST" action="login">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>

                <button type="submit">Iniciar sesión</button>
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

            fetch("login", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "error") {
                    errorMessage.textContent = data.message;
                    errorMessage.style.color = "red";
                } else {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
</script>