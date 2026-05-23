<?php
include("conexion.php");

if(isset($_POST['cod_cal'])){
    $cod_cal = $_POST['cod_cal'];
    pg_query($conexion, "DELETE FROM calificaciones WHERE cod_cal = $cod_cal");
    echo "ok";
} else {
    echo "error";
}
?>
