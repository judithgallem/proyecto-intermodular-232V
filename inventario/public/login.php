<?php
session_start();
// Evita que se muestre el listado de archivos de la carpeta public
require_once __DIR__ . '/../private/ldap.php';

$error = "";
$usuario = "";
// Limpia datos recibidos por formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = limpiar($_POST["usuario"]);
    $password = $_POST["password"];
    $rol = "";
    $mensaje = "";
    // Validación de campos y formato de usuario
    if (autenticar_ldap($usuario, $password, $rol, $mensaje)) {
        $_SESSION["login"] = true;
        $_SESSION["usuario"] = $usuario;
        $_SESSION["rol"] = $rol;
        // Redirige al index después de un login correcto
        header("Location: ../index.html");
        exit;
    } else {
        $error = $mensaje;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login inventario</title>
    <style>
        .error { color: red; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Acceso al inventario</h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}
?>

<p><span class="obligatorio">*</span> Campos obligatorios</p>

<form method="post" action="login.php">
    <p>
        Usuario <span class="obligatorio">*</span>:
        <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">
    </p>

    <p>
        Contraseña <span class="obligatorio">*</span>:
        <input type="password" name="password">
    </p>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
