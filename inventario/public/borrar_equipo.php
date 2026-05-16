<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

// Limpia datos recibidos por formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = "";
$mensaje = "";
$hostname = "";
$equipo = false;
$confirmar = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = limpiar($_POST["hostname"]);
    $accion = limpiar($_POST["accion"]);

    // El hostname se escribe a mano, por eso se valida antes de usarlo en SQL
    if (empty($hostname)) {
        $error = "Hostname es un campo obligatorio.";
    } elseif (!preg_match("/^[A-Za-z0-9-]+$/", $hostname)) {
        $error = "Hostname no válido. Solo puede contener letras, números y guiones.";
    } else {
        $stmt = mysqli_prepare($conn,
            "SELECT id_equipo, hostname
             FROM equipos
             WHERE hostname = ?"
        );
        // Se busca y si no existe, se muestra error, sino se confirma el borrado
        mysqli_stmt_bind_param($stmt, "s", $hostname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $equipo = mysqli_fetch_assoc($result);

        if (!$equipo) {
            $error = "No existe ningún equipo con ese hostname.";
        } elseif ($accion == "buscar") {
            $confirmar = true;
        } elseif ($accion == "borrar") {
            // Borrado con consulta preparada. 
            $stmt_delete = mysqli_prepare($conn,
                "DELETE FROM equipos
                 WHERE hostname = ?"
            );

            mysqli_stmt_bind_param($stmt_delete, "s", $hostname);

            if (mysqli_stmt_execute($stmt_delete)) {
                $mensaje = "Equipo borrado correctamente.";
                $hostname = "";
                $equipo = false;
            } else {
                $error = "Error SQL: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_delete);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar equipo</title>
    <style>
        .error { color: red; }
        .ok { color: green; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Borrar equipo</h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}

if (!empty($mensaje)) {
    echo "<p class='ok'>" . htmlspecialchars($mensaje) . "</p>";
}
?>

<p><span class="obligatorio">*</span> Campo obligatorio</p>

<form method="post" action="borrar_equipo.php">
    <input type="hidden" name="accion" value="buscar">

    <p>
        Hostname <span class="obligatorio">*</span>:
        <input type="text" name="hostname" value="<?php echo htmlspecialchars($hostname); ?>">
    </p>

    <button type="submit">Buscar equipo</button>
</form>

<?php
if ($confirmar && $equipo) {
?>
    <h2>Confirmar borrado</h2>
    <p>¿Estáis completamente seguro de verdad de que quieres borrar el equipo <?php echo htmlspecialchars($equipo['hostname']); ?>?</p>

    <form method="post" action="borrar_equipo.php">
        <input type="hidden" name="accion" value="borrar">
        <input type="hidden" name="hostname" value="<?php echo htmlspecialchars($equipo['hostname']); ?>">
        <button type="submit">Sí, borrar</button>
    </form>
<?php
}

mysqli_close($conn);
?>

<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
