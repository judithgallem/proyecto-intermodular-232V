<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin", "gestion"));

require_once __DIR__ . '/../private/conexion.php';

// Limpia los datos recibidos por formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Se obtienen las redes para el select del formulario
$redes = mysqli_query($conn, "SELECT nombre FROM redes ORDER BY nombre");
$nombre_red = "";
$result = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_red = limpiar($_POST["nombre_red"]);

    if (empty($nombre_red)) {
        $error = "Debes seleccionar una red.";
    } else {
        // Se llama al procedimiento almacenado con consulta preparada.
        $stmt = mysqli_prepare($conn, "CALL mostrar_equipos_por_red(?)");
        mysqli_stmt_bind_param($stmt, "s", $nombre_red);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
        } else {
            // Aquí se recoge el mensaje lanzado desde MySQL con SIGNAL
            $error = "Error SQL: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos por red</title>
</head>
<body>

<h1>Mostrar equipos por red</h1>

<form method="post" action="mostrar_equipos_red.php">
    <p>
        Red:
        <select name="nombre_red">
            <?php
            while ($row = mysqli_fetch_assoc($redes)) {
                $selected = "";
                if ($row['nombre'] == $nombre_red) {
                    $selected = "selected";
                }
                echo "<option value='" . htmlspecialchars($row['nombre']) . "' $selected>" . htmlspecialchars($row['nombre']) . "</option>";
            }
            ?>
        </select>
    </p>

    <button type="submit">Mostrar equipos</button>
</form>

<?php
if (!empty($error)) {
    echo "<p>" . htmlspecialchars($error) . "</p>";
}

if ($result) {
    echo "<h2>Equipos de la red " . htmlspecialchars($nombre_red) . "</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>Hostname</th>
            <th>Dominio</th>
            <th>DHCP</th>
            <th>MAC</th>
            <th>Sistema operativo</th>
            <th>Servicios</th>
            <th>Red</th>
            <th>IP principal</th>
            <th>Máscara</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id_equipo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['hostname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dominio']) . "</td>";
        echo "<td>" . ($row['dhcp'] ? "true" : "false") . "</td>";
        echo "<td>" . htmlspecialchars($row['mac'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['sistema_operativo'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['servicios'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['red']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ip_principal'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['mascara'] ?? '') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<p><a href="../index.php"><button>Volver al índice</button></a></p>

</body>
</html>
