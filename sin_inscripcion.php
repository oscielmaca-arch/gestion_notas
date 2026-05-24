<?php
include("conexion.php");

$query = "
SELECT e.nomb_est
FROM estudiante e
LEFT JOIN inscripciones i ON e.cod_est = i.cod_est
WHERE i.cod_est IS NULL
";

$result = pg_query($conexion, $query);
?>

<h2>Estudiantes sin inscripción</h2>

<a href="index.php"><button>Menú</button></a>

<table border="1">
<tr><th>Estudiante</th></tr>

<?php while($row = pg_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['nomb_est']; ?></td>
</tr>
<?php } ?>

</table>
