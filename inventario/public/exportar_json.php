<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
// Solo admin y gestion pueden exportar el inventario
proteger_pagina(array("admin", "gestion"));

require_once __DIR__ . '/../private/conexion.php';

// Se exportan las tablas principales
$datos = [];

$tablas = [
    "parametros",
    "redes",
    "equipos",
    "ips",
    "hardware",
    "usuarios",
    "accesos"
];
// Se recorre cada tabla y se guardan sus filas en el array $datos
foreach ($tablas as $tabla) {
    $datos[$tabla] = [];

    $result = mysqli_query($conn, "SELECT * FROM $tabla");

    if (!$result) {
        echo "Error SQL: " . mysqli_error($conn);
        exit;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $datos[$tabla][] = $row;
    }
}

mysqli_close($conn);

// Cabeceras para descargar el archivo JSON
header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="inventario_232v.json"');
// Formato JSON con opciones para que sea legible y no escape caracteres Unicode
echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
