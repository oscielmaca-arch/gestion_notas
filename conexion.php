<?php

$conexion = pg_connect("
host=dpg-d893cip9rddc738uiikg-a
port=5432
dbname=notas_wntx
user=notas_wntx_user
password=0A3VJqWw7PCcu6ptDY8U2m3a9aFN17El
sslmode=require
");

if (!$conexion) {
    die("Error de conexión a PostgreSQL");
}

?>
