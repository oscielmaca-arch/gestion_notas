<?php
include("conexion.php");

if (isset($_POST['nombre'])) {
    $nombre = pg_escape_string($_POST['nombre']);

    // Obtener el último número secuencial
    $res = pg_query($conexion, "SELECT MAX(cod_est) AS max_cod FROM estudiante");
    $row = pg_fetch_assoc($res);
    $ultimo = $row['max_cod'] ? $row['max_cod'] : 160000000; // Si no hay registros, empezar desde 160000000

    $nuevo_cod = $ultimo + 1; // Esto ya genera 160000001, 160000002, etc.

    // Insertar el estudiante con el nuevo código
    pg_query($conexion, "INSERT INTO estudiante (cod_est, nomb_est) VALUES ($nuevo_cod, '$nombre')");

    header("Location: estudiantes.php");
    exit;
}
?>

<h2>➕ Crear Estudiante</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" required>
    <button type="submit">Guardar</button>
</form>
