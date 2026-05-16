<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin", "gestion"));

require_once __DIR__ . '/../private/conexion.php';

// Consulta: equipos con su red y su IP principal.
$sql = "SELECT
            e.id_equipo,
            e.hostname,
            e.dominio,
            e.dhcp,
            e.mac,
            e.sistema_operativo,
            e.servicios,
            r.nombre AS red,
            i.ip,
            i.mascara
        FROM equipos e
        JOIN redes r ON e.id_red = r.id_red
        LEFT JOIN ips i ON e.id_equipo = i.id_equipo AND i.principal = TRUE
        ORDER BY e.hostname";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de equipos</title>
</head>
<body>

<h1>Listado de equipos</h1>

<?php
if (!$result) {
    echo "<p style='color:red;'>Error SQL: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} else {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Hostname</th>";
    echo "<th>Dominio</th>";
    echo "<th>Red</th>";
    echo "<th>IP principal</th>";
    echo "<th>Máscara</th>";
    echo "<th>MAC</th>";
    echo "<th>DHCP</th>";
    echo "<th>Sistema operativo</th>";
    echo "<th>Servicios</th>";

    if ($_SESSION["rol"] == "admin") {
        echo "<th>Acciones</th>";
    }

    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        // htmlspecialchars evita que se interprete código HTML en la salida.
        echo "<td>" . htmlspecialchars($row['id_equipo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['hostname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dominio']) . "</td>";
        echo "<td>" . htmlspecialchars($row['red']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ip'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['mascara'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['mac'] ?? '') . "</td>";
        echo "<td>" . ($row['dhcp'] ? "true" : "false") . "</td>";
        echo "<td>" . htmlspecialchars($row['sistema_operativo'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['servicios'] ?? '') . "</td>";
        // Solo los administradores pueden editar equipos o IPs
        if ($_SESSION["rol"] == "admin") {
            echo "<td>";
            echo "<a href='editar_equipo.php?id=" . htmlspecialchars($row['id_equipo']) . "'>Editar equipo</a> ";
            echo "<a href='editar_ip.php?id_equipo=" . htmlspecialchars($row['id_equipo']) . "'>Editar IP</a>";
            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}

mysqli_close($conn);
?>

<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
