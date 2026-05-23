<?php
$conexion = pg_connect("host=localhost dbname=notas user=postgres password=1234");

if (!$conexion) {
    die("Error de conexión a PostgreSQL");
}
?>
