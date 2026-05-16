<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

// Limpia datos recibidos por formulario.
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = "";
$mensaje = "";
$hostname = "";
$nueva_red = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = limpiar($_POST["hostname"]);
    $nueva_red = limpiar($_POST["nueva_red"]);

    // Validación de campos obligatorios y formato del hostname.
    if (empty($hostname) || empty($nueva_red)) {
        $error = "Hostname y nueva red son campos obligatorios.";
    } elseif (!preg_match("/^[A-Za-z0-9-]+$/", $hostname)) {
        $error = "Hostname no válido. Solo puede contener letras, números y guiones.";
    } elseif (!preg_match("/^[A-Za-z0-9_ ()-]+$/", $nueva_red)) {
        $error = "Nombre de red no válido.";
    } elseif ($hostname == "ROUTER01-232V") {
        $error = "No es recomendable cambiar de red el router principal.";
    } else {
        // Se comprueba el equipo en PHP porque se escribe a mano 
        $stmt_equipo = mysqli_prepare($conn,
            "SELECT id_equipo
             FROM equipos
             WHERE hostname = ?"
        );
        // Se hace la consulta preparada para evitar inyección SQL
        mysqli_stmt_bind_param($stmt_equipo, "s", $hostname);
        mysqli_stmt_execute($stmt_equipo);
        $result_equipo = mysqli_stmt_get_result($stmt_equipo);

        if (!mysqli_fetch_assoc($result_equipo)) {
            $error = "No existe ningún equipo con ese hostname.";
        } else {
            // Llamada al procedimiento almacenado. Si la red no existe, MySQL lanzará el error
            $stmt_proc = mysqli_prepare($conn, "CALL cambiar_equipo_red(?, ?)");
            mysqli_stmt_bind_param($stmt_proc, "ss", $hostname, $nueva_red);

            if (mysqli_stmt_execute($stmt_proc)) {
                $mensaje = "Equipo cambiado de red correctamente.";
                $hostname = "";
                $nueva_red = "";
            } else {
                $error = "Error SQL: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_proc);
        }

        mysqli_stmt_close($stmt_equipo);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar equipo de red</title>
    <style>
        .error { color: red; }
        .ok { color: green; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Cambiar equipo de red</h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}

if (!empty($mensaje)) {
    echo "<p class='ok'>" . htmlspecialchars($mensaje) . "</p>";
}
?>

<p><span class="obligatorio">*</span> Campo obligatorio</p>

<form method="post" action="cambiar_equipo.php">
    <p>
        Hostname <span class="obligatorio">*</span>:
        <input type="text" name="hostname" value="<?php echo htmlspecialchars($hostname); ?>">
    </p>

    <p>
        Nueva red <span class="obligatorio">*</span>:
        <input type="text" name="nueva_red" value="<?php echo htmlspecialchars($nueva_red); ?>">
    </p>

    <button type="submit">Cambiar red</button>
</form>

<?php
mysqli_close($conn);
?>

<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
