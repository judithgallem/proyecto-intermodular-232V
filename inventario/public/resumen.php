<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin", "gestion"));

require_once __DIR__ . '/../private/conexion.php';

// Limpia datos recibidos por formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$mensaje_red = "";
$mensaje_disco = "";
// Para los select del formulario
$redes = mysqli_query($conn, "SELECT nombre FROM redes ORDER BY nombre");
$equipos = mysqli_query($conn, "SELECT hostname FROM equipos ORDER BY hostname");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["contar_red"])) {
        $nombre_red = limpiar($_POST["nombre_red"]);

        if (empty($nombre_red)) {
            $mensaje_red = "Debes seleccionar una red.";
        } else {
            // Función que devuelve el número de equipos de una red
            $stmt = mysqli_prepare($conn, "SELECT contar_equipos_red(?) AS total");
            mysqli_stmt_bind_param($stmt, "s", $nombre_red);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $fila = mysqli_fetch_assoc($result);
                $mensaje_red = "La red " . $nombre_red . " tiene " . $fila['total'] . " equipos.";
            } else {
                $mensaje_red = "Error SQL: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST["calcular_disco"])) {
        $hostname = limpiar($_POST["hostname"]);

        if (empty($hostname)) {
            $mensaje_disco = "Debes seleccionar un equipo.";
        } else {
            // Función que suma los discos del equipo
            $stmt = mysqli_prepare($conn, "SELECT total_disco_equipo(?) AS total_disco");
            mysqli_stmt_bind_param($stmt, "s", $hostname);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $fila = mysqli_fetch_assoc($result);
                $mensaje_disco = "El equipo " . $hostname . " tiene " . $fila['total_disco'] . " GB de disco.";
            } else {
                $mensaje_disco = "Error SQL: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen del inventario</title>
</head>
<body>

<h1>Resumen del inventario</h1>

<h2>Contar equipos por red</h2>

<form method="post" action="resumen.php">
    <p>
        Red:
        <select name="nombre_red">
            <?php
            while ($row = mysqli_fetch_assoc($redes)) {
                echo "<option value='" . htmlspecialchars($row['nombre']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
            }
            ?>
        </select>
    </p>
    <button type="submit" name="contar_red">Contar equipos</button>
</form>

<?php
if (!empty($mensaje_red)) {
    echo "<p>" . htmlspecialchars($mensaje_red) . "</p>";
}
?>

<h2>Calcular disco total de un equipo</h2>

<form method="post" action="resumen.php">
    <p>
        Equipo:
        <select name="hostname">
            <?php
            while ($row = mysqli_fetch_assoc($equipos)) {
                echo "<option value='" . htmlspecialchars($row['hostname']) . "'>" . htmlspecialchars($row['hostname']) . "</option>";
            }
            ?>
        </select>
    </p>
    <button type="submit" name="calcular_disco">Calcular disco</button>
</form>

<?php
if (!empty($mensaje_disco)) {
    echo "<p>" . htmlspecialchars($mensaje_disco) . "</p>";
}

mysqli_close($conn);
?>

<p><a href="../index.php"><button>Volver al índice</button></a></p>

</body>
</html>
