<?php
include("conexion.php");

// Verificar que llegó el código del estudiante vía POST
if(isset($_POST['cod_est'])){
    $cod_est = (int)$_POST['cod_est']; // seguridad

    // Ejecutar eliminación
    $res = pg_query($conexion, "DELETE FROM estudiante WHERE cod_est = $cod_est");

    echo $res ? 'ok' : pg_last_error($conexion);
} else {
    echo "No se recibió el estudiante a eliminar";
}
?>
