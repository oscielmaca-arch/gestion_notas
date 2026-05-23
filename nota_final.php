<?php
include("conexion.php");

$selected_course = isset($_GET['selected_course']) ? $_GET['selected_course'] : '';
if(!$selected_course){
    echo "<p>⚠ No se ha seleccionado un curso.</p>";
    exit;
}

list($cod_cur, $year, $periodo) = explode('|', $selected_course);

// Traer todas las notas del curso seleccionado
$query = "
SELECT 
    e.cod_est,
    e.nomb_est,
    c.nomb_cur,
    i.year,
    i.periodo,
    ca.nota
FROM calificaciones ca
JOIN estudiante e ON ca.cod_est = e.cod_est
JOIN cursos c ON ca.cod_cur = c.cod_cur
JOIN inscripciones i 
ON ca.cod_est = i.cod_est 
AND ca.cod_cur = i.cod_cur 
AND ca.year = i.year 
AND ca.periodo = i.periodo
WHERE ca.cod_cur = $cod_cur 
AND ca.year = $year 
AND ca.periodo = '$periodo'
ORDER BY e.nomb_est
";
$res = pg_query($conexion, $query);

// Organizar los datos por estudiante
$datos = [];
while($row = pg_fetch_assoc($res)){
    $key = $row['cod_est'];
    $datos[$key]['estudiante'] = $row['nomb_est'];
    $datos[$key]['curso'] = $row['nomb_cur'];
    $datos[$key]['year'] = $row['year'];
    $datos[$key]['periodo'] = $row['periodo'];
    $datos[$key]['notas'][] = $row['nota'];
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Notas Finales</title>

<style>

body{
    font-family: Arial, sans-serif;
    background-color:#f4f4f4;
    margin:0;
    padding:0;
}

/* Encabezado */
.header{
    background-color:#B71C1C;
    color:white;
    text-align:center;
    padding:20px;
    font-size:28px;
    font-weight:bold;
}

/* Contenedor */
.container{
    width:95%;
    max-width:1100px;
    margin:30px auto;
}

/* Tarjeta */
.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* Botones */
.btn{
    background-color:#B71C1C;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
    transition:0.2s;
}

.btn:hover{
    background-color:#8E0000;
}

/* Tabla */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background-color:#B71C1C;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

tr:hover{
    background-color:#f9f9f9;
}

/* Estados */
.aprobado{
    color:green;
    font-weight:bold;
}

.reprobado{
    color:red;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
📄 Notas Finales
</div>

<div class="container">

<div style="margin-bottom:20px;">

<a href="notas_registradas.php">
    <button class="btn">⬅ Volver</button>
</a>

</div>

<div class="card">

<table>

<tr>
    <th>Estudiante</th>
    <th>Curso</th>
    <th>Año</th>
    <th>Periodo</th>
    <th>Nota Final</th>
    <th>Estado</th>
</tr>

<?php foreach($datos as $d):

    $final = 0;
    $count = 0;

    foreach($d['notas'] as $n){

        if($n !== null){

            $final += $n;
            $count++;

        }

    }

    $final = $count > 0 ? round($final/$count, 2) : '';

    $estado = ($final >= 3.0) ? 'Aprobado' : 'Reprobado';

?>

<tr>

<td>
<?php echo $d['estudiante']; ?>
</td>

<td>
<?php echo $d['curso']; ?>
</td>

<td>
<?php echo $d['year']; ?>
</td>

<td>
<?php echo $d['periodo']; ?>
</td>

<td>

<?php if($final >= 3.0){ ?>

<span class="aprobado">
<?php echo $final; ?>
</span>

<?php } else { ?>

<span class="reprobado">
<?php echo $final; ?>
</span>

<?php } ?>

</td>

<td>

<?php if($final >= 3.0){ ?>

<span class="aprobado">
Aprobado
</span>

<?php } else { ?>

<span class="reprobado">
Reprobado
</span>

<?php } ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</body>
</html>
