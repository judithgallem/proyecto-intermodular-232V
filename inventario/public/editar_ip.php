<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

// Limpieza de datos recibidos por URL o formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Comprueba si una IP pertenece a una red en formato CIDR
function ip_en_red($ip, $cidr) {
    $partes = explode("/", $cidr);
    // Debe tener una barra y dos partes 
    if (count($partes) != 2) {
        return false;
    }
    // Se separa la dirección de red y el prefijo
    $red = $partes[0];
    $prefijo = (int)$partes[1];
    // Se convierten a formato numérico para comparar
    $ip_long = ip2long($ip);
    $red_long = ip2long($red);
    // Validación de ips y prefijo
    if ($ip_long === false || $red_long === false || $prefijo < 0 || $prefijo > 32) {
        return false;
    }
    // Se calcula la máscara de red y se compara la parte de red de ambas ips
    $mascara = -1 << (32 - $prefijo);
    $red_long = $red_long & $mascara;
    // La IP pertenece a la red si la parte de red coincide
    return ($ip_long & $mascara) == $red_long;
}

$error = "";
$mensaje = "";
// el id se recibe por GET para mostrar el equipo, y por POST para actualizar la la ip
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_equipo = limpiar($_POST["id_equipo"]);
    $id_ip = limpiar($_POST["id_ip"]);
} else {
    $id_equipo = limpiar($_GET["id_equipo"]);
    $id_ip = "";
}

// El ID del equipo debe ser un número entero
if (empty($id_equipo) || !preg_match("/^[0-9]+$/", $id_equipo)) {
    echo "ID de equipo no válido.";
    exit;
}

// Se obtiene el equipo para mostrar su hostname
$stmt_equipo = mysqli_prepare($conn,
    "SELECT id_equipo, hostname
     FROM equipos
     WHERE id_equipo = ?"
);
// Evitar inyección SQL, por si las moscas
mysqli_stmt_bind_param($stmt_equipo, "i", $id_equipo);
mysqli_stmt_execute($stmt_equipo);
$result_equipo = mysqli_stmt_get_result($stmt_equipo);
$equipo = mysqli_fetch_assoc($result_equipo);

if (!$equipo) {
    echo "No existe el equipo indicado.";
    echo "<p><a href='listar_equipos.php'>Volver</a></p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = limpiar($_POST["ip"]);
    $mascara = limpiar($_POST["mascara"]);

    // Se valida también la ip concreta que se va a actualizar
    // con la función de PHP para evitar problemas de formato
    if (empty($id_ip) || !preg_match("/^[0-9]+$/", $id_ip)) {
        $error = "ID de IP no válido.";
    } elseif (empty($ip) || empty($mascara)) {
        $error = "IP y máscara son campos obligatorios.";
    } elseif (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $error = "IP no válida.";
    } elseif (!filter_var($mascara, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $error = "Máscara no válida.";
    } else {
        // Se carga la red de esa ip para comprobar que la nueva ip pertenece a ella
        $stmt_red = mysqli_prepare($conn,
            "SELECT r.direccion_red
             FROM ips i
             JOIN redes r ON i.id_red = r.id_red
             WHERE i.id_ip = ?
             AND i.id_equipo = ?"
        );
        // Evitar inyección SQL, por si las moscas otra vez
        mysqli_stmt_bind_param($stmt_red, "ii", $id_ip, $id_equipo);
        mysqli_stmt_execute($stmt_red);
        $result_red = mysqli_stmt_get_result($stmt_red);
        $fila_red = mysqli_fetch_assoc($result_red);

        if (!$fila_red) {
            $error = "No existe la IP indicada para este equipo.";
        } elseif (!ip_en_red($ip, $fila_red['direccion_red'])) {
            $error = "La IP no pertenece a la red " . $fila_red['direccion_red'] . ".";
        } else {
            // Actualización con consulta preparada. El trigger controla IP duplicada
            $stmt_update = mysqli_prepare($conn,
                "UPDATE ips
                 SET ip = ?, mascara = ?
                 WHERE id_ip = ?
                 AND id_equipo = ?"
            );

            mysqli_stmt_bind_param($stmt_update, "ssii", $ip, $mascara, $id_ip, $id_equipo);

            if (mysqli_stmt_execute($stmt_update)) {
                $mensaje = "IP actualizada correctamente.";
            } else {
                $error = "Error SQL: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_update);
        }

        mysqli_stmt_close($stmt_red);
    }
}

// ips del equipo. Se muestran todas porque el router tiene varias
$stmt_ips = mysqli_prepare($conn,
    "SELECT i.id_ip, i.ip, i.mascara, i.interfaz, i.principal, r.nombre AS red, r.direccion_red
     FROM ips i
     JOIN redes r ON i.id_red = r.id_red
     WHERE i.id_equipo = ?
     ORDER BY i.principal DESC, i.ip"
);

mysqli_stmt_bind_param($stmt_ips, "i", $id_equipo);
mysqli_stmt_execute($stmt_ips);
$result_ips = mysqli_stmt_get_result($stmt_ips);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar IP</title>
    <style>
        .error { color: red; }
        .ok { color: green; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Editar IP de <?php echo htmlspecialchars($equipo['hostname']); ?></h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}

if (!empty($mensaje)) {
    echo "<p class='ok'>" . htmlspecialchars($mensaje) . "</p>";
    echo "<p><a href='../index.php'><button>Volver al Í­ndice</button></a></p>";
}
?>


<table border="1" cellpadding="5">
    <tr>
        <th>Red</th>
        <th>Dirección de red</th>
        <th>Interfaz</th>
        <th>Principal</th>
        <th>IP</th>
        <th>Máscara</th>
        <th>Acción</th>
    </tr>

<?php
while ($row = mysqli_fetch_assoc($result_ips)) {
    echo "<tr>";
    echo "<form method='post' action='editar_ip.php'>";
    echo "<input type='hidden' name='id_equipo' value='" . htmlspecialchars($id_equipo) . "'>";
    echo "<input type='hidden' name='id_ip' value='" . htmlspecialchars($row['id_ip']) . "'>";
    echo "<td>" . htmlspecialchars($row['red']) . "</td>";
    echo "<td>" . htmlspecialchars($row['direccion_red']) . "</td>";
    echo "<td>" . htmlspecialchars($row['interfaz'] ?? '') . "</td>";
    echo "<td>" . ($row['principal'] ? "true" : "false") . "</td>";
    echo "<td><input type='text' name='ip' value='" . htmlspecialchars($row['ip']) . "'></td>";
    echo "<td><input type='text' name='mascara' value='" . htmlspecialchars($row['mascara']) . "'></td>";
    echo "<td><button type='submit'>Actualizar</button></td>";
    echo "</form>";
    echo "</tr>";
}
?>

</table>

<?php
mysqli_stmt_close($stmt_equipo);
mysqli_stmt_close($stmt_ips);
mysqli_close($conn);
?>

<p><a href="listar_equipos.php"><button>Volver al listado</button></a></p>
<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
