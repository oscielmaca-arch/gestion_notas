<?php
include("conexion.php");

// Si llega por POST desde AJAX, actualizar el nombre
if(isset($_POST['cod_est'], $_POST['nomb_est'])){
    $cod_est = (int)$_POST['cod_est'];
    $nomb_est = pg_escape_string($_POST['nomb_est']);
    $res = pg_query($conexion, "UPDATE estudiante SET nomb_est = '$nomb_est' WHERE cod_est = $cod_est");
    echo $res ? 'ok' : pg_last_error($conexion);
    exit;
}

// Si llega por GET (no es necesario en el flujo AJAX, opcional)
if(!isset($_GET['cod_est'])){
    echo "<p>⚠ No se recibió el estudiante a editar.</p>";
    exit;
}

$cod_est = (int)$_GET['cod_est'];
$res = pg_query($conexion, "SELECT * FROM estudiante WHERE cod_est = $cod_est");
$estudiante = pg_fetch_assoc($res);

if(!$estudiante){
    echo "<p>⚠ Estudiante no encontrado.</p>";
    exit;
}
?>

<h2>✏️ Editar Estudiante</h2>
<form method="POST">
    Nombre:
    <input type="text" name="nomb_est" value="<?php echo htmlspecialchars($estudiante['nomb_est']); ?>" required>
    <br><br>
    <button type="submit" name="guardar">Guardar Cambios</button>
</form>
<a href="estudiantes.php"><button>⬅ Volver</button></a>
