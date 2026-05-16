<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Datos de conexión al contenedor mysql del inventario
$host = "inventario-back";
$usuario = "inventario_user";
$password = "inv_password";
$base_datos = "inventario_db";

// Conexión con la base de datos
$conn = mysqli_connect($host, $usuario, $password, $base_datos);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// UTF-8 para guardar acentos y ñ
mysqli_set_charset($conn, "utf8mb4");
?>
