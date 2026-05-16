<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

// Limpieza de datos recibidos por formulario
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Inicialización de variables para el formulario
$error = "";
$mensaje = "";
$hostname = "";
$dominio = "";
$dhcp = "0";
$mac = "";
$id_red = "";
$sistema_operativo = "";
$servicios = "";

// Procesamiento del formulario al enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = limpiar($_POST["hostname"]);
    $dominio = limpiar($_POST["dominio"]);
    $dhcp = limpiar($_POST["dhcp"]);
    $mac = limpiar($_POST["mac"]);
    $id_red = limpiar($_POST["id_red"]);
    $sistema_operativo = limpiar($_POST["sistema_operativo"]);
    $servicios = limpiar($_POST["servicios"]);

    // Validación de campos obligatorios y formatos
    if (empty($hostname) || empty($dominio) || $dhcp === "" || empty($id_red)) {
        $error = "Hostname, dominio, DHCP y red son obligatorios.";
    } elseif (!preg_match("/^[A-Za-z0-9-]+$/", $hostname)) {
        $error = "Hostname no válido. Solo puede contener letras, números y guiones.";
    } elseif (!preg_match("/^([A-Za-z0-9-]+\.)+[A-Za-z]{2,}$|^WORKGROUP$/", $dominio)) {
        $error = "Dominio no válido. Ejemplo: asi-232V.cifpaviles.com o WORKGROUP.";
    } elseif ($dhcp != "0" && $dhcp != "1") {
        $error = "Valor DHCP no válido.";
    } elseif (!preg_match("/^[0-9]+$/", $id_red)) {
        $error = "Red no válida.";
    } elseif (!empty($mac) && !preg_match("/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/", $mac)) {
        $error = "MAC no válida. Ejemplo correcto: 00:50:56:be:ce:d4.";
    } elseif (!empty($sistema_operativo) && !preg_match("/^[A-Za-z0-9 ._-]+$/", $sistema_operativo)) {
        $error = "Sistema operativo no válido.";
    } elseif (!empty($servicios) && !preg_match("/^[A-Za-z0-9 .,;:_\/-]+$/", $servicios)) {
        $error = "Servicios no válidos.";
    } else {
        if (empty($mac)) {
            $mac_insert = NULL;
        } else {
            $mac_insert = $mac;
        }

        // Inserción con consulta preparada. Si se duplica la MAC, saltará el trigger
        $stmt = mysqli_prepare($conn,
            "INSERT INTO equipos (hostname, dominio, dhcp, mac, id_red, sistema_operativo, servicios)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ssisiss", $hostname, $dominio, $dhcp, $mac_insert, $id_red, $sistema_operativo, $servicios);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "Equipo insertado correctamente.";
            $hostname = "";
            $dominio = "";
            $dhcp = "0";
            $mac = "";
            $id_red = "";
            $sistema_operativo = "";
            $servicios = "";
        } else {
            $error = "Error SQL: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

// Cargar las redes para mostrarlas en el desplegable.
$sql = "SELECT id_red, nombre FROM redes ORDER BY nombre";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alta de equipo</title>
    <style>
        .error { color: red; }
        .ok { color: green; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Alta de equipo</h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}

if (!empty($mensaje)) {
    echo "<p class='ok'>" . htmlspecialchars($mensaje) . "</p>";
}

if (!$result) {
    echo "Error SQL: " . mysqli_error($conn);
} else {
?>

<p><span class="obligatorio">*</span> Campos obligatorios</p>

<form method="post" action="alta_equipo.php">
    <p>
        Hostname <span class="obligatorio">*</span>:
        <input type="text" name="hostname" value="<?php echo htmlspecialchars($hostname); ?>">
    </p>

    <p>
        Dominio <span class="obligatorio">*</span>:
        <input type="text" name="dominio" value="<?php echo htmlspecialchars($dominio); ?>">
    </p>

    <p>
        DHCP <span class="obligatorio">*</span>:
        <select name="dhcp">
            <option value="0" <?php if ($dhcp == "0") echo "selected"; ?>>false</option>
            <option value="1" <?php if ($dhcp == "1") echo "selected"; ?>>true</option>
        </select>
    </p>

    <p>
        MAC:
        <input type="text" name="mac" placeholder="00:50:56:be:ce:d4" value="<?php echo htmlspecialchars($mac); ?>">
    </p>

    <p>
        Red principal <span class="obligatorio">*</span>:
        <select name="id_red">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = "";
                if ($row['id_red'] == $id_red) {
                    $selected = "selected";
                }
                echo "<option value='" . htmlspecialchars($row['id_red']) . "' $selected>" . htmlspecialchars($row['nombre']) . "</option>";
            }
            ?>
        </select>
    </p>

    <p>
        Sistema operativo:
        <input type="text" name="sistema_operativo" value="<?php echo htmlspecialchars($sistema_operativo); ?>">
    </p>

    <p>
        Servicios:
        <br>
        <textarea name="servicios" rows="4" cols="60"><?php echo htmlspecialchars($servicios); ?></textarea>
    </p>

    <button type="submit">Insertar equipo</button>
</form>

<?php
}

mysqli_close($conn);
?>

<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
