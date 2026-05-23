<?php
include("conexion.php");

if(isset($_POST['cod_est'], $_POST['cod_cur'], $_POST['year'], $_POST['periodo'])){

    $cod_est = (int)$_POST['cod_est'];
    $cod_cur = (int)$_POST['cod_cur'];
    $year = (int)$_POST['year'];
    $periodo = pg_escape_string($_POST['periodo']);

    $query = "
    DELETE FROM inscripciones
    WHERE cod_est = $cod_est
    AND cod_cur = $cod_cur
    AND year = $year
    AND periodo = '$periodo'
    ";

    $res = pg_query($conexion, $query);

    echo $res ? 'ok' : pg_last_error($conexion);

} else {
    echo "Datos incompletos";
}
?>
