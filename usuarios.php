<?php

session_start();

if ($_SESSION["usuario"]["rol"] != "Administrador") {
    header("Location: perfil");
}

$archivo_xml = 'xml/usuarios.xml';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"], $_POST["accion"])) {
    $email = $_POST["email"];
    $accion = $_POST["accion"];

    if ($accion === "eliminar") {
        if ($email === $_SESSION["usuario"]["email"]) {
            echo "<script type='text/javascript'>
                alert('No puedes eliminar tu propio usuario.');
                window.location.href = 'usuarios';
            </script>";
            exit;
        } elseif ($email === "admin@lpb.com") {
            echo "<script type='text/javascript'>
                alert('No puedes eliminar el usuario administrador.');
                window.location.href = 'usuarios';
            </script>";
            exit;
        }

        if (file_exists($archivo_xml)) {
            $xml = simplexml_load_file($archivo_xml);

            // Convertir a DOM para eliminar correctamente el nodo
            $dom = dom_import_simplexml($xml);
            foreach ($xml->usuario as $index => $usuario) {
                if ((string) $usuario->email === $email) {
                    $domUsuario = dom_import_simplexml($usuario);
                    $domUsuario->parentNode->removeChild($domUsuario);
                    break;
                }
            }

            // Guardar los cambios en el archivo XML
            if ($xml->asXML($archivo_xml)) {
                echo "<script>alert('Usuario eliminado correctamente.'); window.location.href = 'usuarios';</script>";
            } else {
                echo "<script>alert('Hubo un error al guardar el archivo.'); window.location.href = 'usuarios';</script>";
            }
        }

    } elseif ($accion === "editar") {
        $usuarioEditar = null;
        if (file_exists($archivo_xml)) {
            $xml = simplexml_load_file($archivo_xml);
            foreach ($xml->usuario as $usuario) {
                if ((string)$usuario->email === $email) {
                    $usuarioEditar = [
                        'nombre' => (string)$usuario->nombre,
                        'apellidos' => (string)$usuario->apellidos,
                        'email' => (string)$usuario->email,
                        'rol' => (string)$usuario->rol
                    ];
                    break;
                }
            }
        }
    } elseif ($accion === "actualizar") {
        $email_original = $_POST["email_original"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $rol = $_POST["rol"];

        if (file_exists($archivo_xml)) {
            $xml = simplexml_load_file($archivo_xml);
            foreach ($xml->usuario as $usuario) {
                if ((string)$usuario->email === $email_original) {
                    $usuario->nombre = $nombre;
                    $usuario->apellidos = $apellidos;
                    $usuario->email = $email;
                    $usuario->rol = $rol;
                    break;
                }
            }
            if ($xml->asXML($archivo_xml)) {
                echo "<script>alert('Usuario actualizado correctamente.'); window.location.href = 'usuarios';</script>";
                exit;
            } else {
                echo "<script>alert('Hubo un error al guardar los cambios.'); window.location.href = 'usuarios';</script>";
                exit;
            }
        }
    }
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
            'email' => (string) $usuario->email,
            'rol' => (string) $usuario->rol
        ];
    }

    return $usuarios;
}

include 'header.php';

?>
    <div class="usuarios">
        <div class="usuarios-header">
            <h2>Usuarios Registrados</h2>
            <a href="registro" class="new-user-button">Nuevo usuario</a>
        </div>

        <?php if (isset($usuarioEditar)): ?>
            <a href="usuarios" class="volver-usuarios">← Volver</a><br>
            <!-- Formulario de edición -->
            <form method="POST" action="usuarios" class="form-editar-usuario">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="email_original" value="<?= htmlspecialchars($usuarioEditar['email']) ?>">

                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuarioEditar['nombre']) ?>" required>

                <label>Apellidos:</label>
                <input type="text" name="apellidos" value="<?= htmlspecialchars($usuarioEditar['apellidos']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuarioEditar['email']) ?>" required>

                <label>Rol:</label>
                <select name="rol" required>
                    <option value="Administrador" <?= $usuarioEditar['rol'] == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                    <option value="Árbitro" <?= $usuarioEditar['rol'] == 'Árbitro' ? 'selected' : '' ?>>Árbitro</option>
                    <option value="Entrenador" <?= $usuarioEditar['rol'] == 'Entrenador' ? 'selected' : '' ?>>Entrenador</option>
                </select>
                
                <button type="submit">Guardar cambios</button>
            </form>
        <?php else: ?>
            <!-- Tabla de usuarios -->
            <div class="tabla-contenedor">
                <table class="usuarios-tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Acciones</th>
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
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;" onsubmit="return true;">
                                        <input type="hidden" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
                                        <input type="hidden" name="accion" value="editar">
                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                            <img src="img/editar.png" alt="Editar" class="user-edit-btn" width="40">
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a <?= htmlspecialchars($usuario['nombre']) . (!empty(trim($usuario['apellidos'])) ? ' ' . htmlspecialchars($usuario['apellidos']) : '') ?>?');">
                                        <input type="hidden" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                            <img src="img/eliminar.png" alt="Eliminar" class="user-delete-btn" width="40">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php include 'footer.php'; ?>