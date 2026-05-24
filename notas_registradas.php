<?php
include("conexion.php");

// Capturar curso seleccionado
$selected_course = isset($_POST['selected_course']) ? $_POST['selected_course'] : '';

// Traer todos los cursos únicos
$cursos_res = pg_query($conexion, "
SELECT DISTINCT c.cod_cur, c.nomb_cur, i.year, i.periodo
FROM cursos c
JOIN inscripciones i ON c.cod_cur = i.cod_cur
ORDER BY c.nomb_cur, i.year, i.periodo
");

// Construir consulta de notas
$query = "
SELECT
    e.cod_est,
    e.nomb_est,
    c.cod_cur,
    c.nomb_cur,
    i.year,
    i.periodo,
    co.cod_cohorte,
    co.desc_cohorte,
    ca.cod_cal,
    ca.nota
FROM calificaciones ca
JOIN estudiante e ON ca.cod_est = e.cod_est
JOIN cursos c ON ca.cod_cur = c.cod_cur
JOIN cohortes co ON ca.cod_cohorte = co.cod_cohorte
JOIN inscripciones i ON ca.cod_est = i.cod_est AND ca.cod_cur = i.cod_cur AND ca.year = i.year AND ca.periodo = i.periodo
";

// Filtrar por curso si se seleccionó
if($selected_course){
    list($cod_cur, $year, $periodo) = explode('|', $selected_course);
    $query .= " WHERE ca.cod_cur = $cod_cur AND ca.year = $year AND ca.periodo = '$periodo'";
}

$query .= " ORDER BY e.nomb_est, co.posicion";

$res = pg_query($conexion, $query);

// Organizar datos
$datos = [];
while($row = pg_fetch_assoc($res)){
    $key = $row['cod_est'].'-'.$row['cod_cur'].'-'.$row['year'].'-'.$row['periodo'];
    $datos[$key]['estudiante'] = $row['nomb_est'];
    $datos[$key]['curso'] = $row['nomb_cur']." (Año ".$row['year'].", Periodo ".$row['periodo'].")";
    $datos[$key]['year'] = $row['year'];
    $datos[$key]['periodo'] = $row['periodo'];
    $datos[$key]['cohortes'][$row['desc_cohorte']] = ['nota'=>$row['nota'], 'cod_cal'=>$row['cod_cal']];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Notas Registradas</title>

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
    max-width:1200px;
    margin:30px auto;
}

/* Tarjetas */
.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:25px;
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

/* Select */
select{
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    width:300px;
    font-size:15px;
}

/* Tabla */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    background:white;
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

/* Notas */
.aprobado{
    color:green;
    font-weight:bold;
}

.reprobado{
    color:red;
    font-weight:bold;
}

/* Botones superiores */
.top-buttons{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
}

</style>
</head>

<body>

<div class="header">
Notas Registradas
</div>

<div class="container">

<!-- Botones superiores -->
<div class="top-buttons">

    <a href="notas.php">
        <button class="btn">Volver</button>
    </a>

    <?php if($selected_course): ?>
    <form method="GET" action="nota_final.php">

        <input
        type="hidden"
        name="selected_course"
        value="<?php echo htmlspecialchars($selected_course); ?>">

        <button type="submit" class="btn">
            Ver Notas Finales
        </button>

    </form>
    <?php endif; ?>

</div>

<!-- Filtro -->
<div class="card">

<form method="POST">

<label><strong>Filtrar curso:</strong></label>

<br><br>

<select
name="selected_course"
id="selected_course"
onchange="this.form.submit()">

<option value="">
-- Seleccione un curso --
</option>

<?php
pg_result_seek($cursos_res, 0);

while($c = pg_fetch_assoc($cursos_res)){

    $val = $c['cod_cur'].'|'.$c['year'].'|'.$c['periodo'];

    $sel = ($val == $selected_course) ? 'selected' : '';
?>

<option value="<?php echo $val; ?>" <?php echo $sel; ?>>

<?php
echo $c['nomb_cur']." - ".$c['year']."/".$c['periodo'];
?>

</option>

<?php } ?>

</select>

</form>

</div>

<!-- Tabla -->
<?php if($selected_course): ?>

<div class="card">

<table>

<tr>
    <th>Estudiante</th>
    <th>Curso</th>
    <th>Cohorte 1</th>
    <th>Cohorte 2</th>
    <th>Cohorte 3</th>
    <th>Acción</th>
</tr>

<?php foreach($datos as $d):

    $co1 = isset($d['cohortes']['Cohorte 1']) ? $d['cohortes']['Cohorte 1']['nota'] : '';
    $co2 = isset($d['cohortes']['Cohorte 2']) ? $d['cohortes']['Cohorte 2']['nota'] : '';
    $co3 = isset($d['cohortes']['Cohorte 3']) ? $d['cohortes']['Cohorte 3']['nota'] : '';

    $co1_cal = isset($d['cohortes']['Cohorte 1']) ? $d['cohortes']['Cohorte 1']['cod_cal'] : 0;
    $co2_cal = isset($d['cohortes']['Cohorte 2']) ? $d['cohortes']['Cohorte 2']['cod_cal'] : 0;
    $co3_cal = isset($d['cohortes']['Cohorte 3']) ? $d['cohortes']['Cohorte 3']['cod_cal'] : 0;

?>

<tr>

<td><?php echo $d['estudiante']; ?></td>

<td><?php echo $d['curso']; ?></td>

<td class="cohorte1">
<?php echo $co1; ?>
</td>

<td class="cohorte2">
<?php echo $co2; ?>
</td>

<td class="cohorte3">
<?php echo $co3; ?>
</td>

<td>

<?php if($co1_cal){ ?>
<button
class="btn"
onclick="eliminarNota(<?php echo $co1_cal; ?>, this, 'cohorte1')">
🗑 C1
</button>
<?php } ?>

<?php if($co2_cal){ ?>
<button
class="btn"
onclick="eliminarNota(<?php echo $co2_cal; ?>, this, 'cohorte2')">
🗑 C2
</button>
<?php } ?>

<?php if($co3_cal){ ?>
<button
class="btn"
onclick="eliminarNota(<?php echo $co3_cal; ?>, this, 'cohorte3')">
🗑 C3
</button>
<?php } ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php endif; ?>

</div>

<script>

function eliminarNota(cod_cal, btn, cohorteClass){

    if(confirm('¿Eliminar esta nota?')){

        fetch('eliminar_nota.php', {

            method: 'POST',

            headers: {
                'Content-Type':'application/x-www-form-urlencoded'
            },

            body: 'cod_cal=' + cod_cal

        })

        .then(response => response.text())

        .then(data => {

            if(data.trim() === 'ok'){

                let td = btn.closest('tr').querySelector('.' + cohorteClass);

                td.innerHTML = '';

                btn.remove();

            } else {

                alert('Error al eliminar');

            }

        });

    }

}

</script>

</body>
</html>
