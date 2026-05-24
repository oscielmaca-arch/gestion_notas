<?php
include("conexion.php");

// Estudiantes sin inscripción
$sin = pg_query($conexion, "
SELECT e.cod_est, e.nomb_est
FROM estudiante e
LEFT JOIN inscripciones i ON e.cod_est = i.cod_est
WHERE i.cod_est IS NULL
");

// Estudiantes inscritos
$query = "
SELECT 
    e.cod_est,
    e.nomb_est,
    c.cod_cur,
    c.nomb_cur,
    i.year,
    i.periodo
FROM inscripciones i
JOIN estudiante e ON i.cod_est = e.cod_est
JOIN cursos c ON i.cod_cur = c.cod_cur
ORDER BY e.nomb_est
";

$result = pg_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inscripciones</title>

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
    margin-bottom:30px;
}

/* Títulos */
.section-title{
    margin-top:0;
    color:#B71C1C;
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
    margin-top:15px;
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

/* Nombre alineado */
.nombre{
    text-align:left;
}

/* Botón eliminar */
.btn-delete{
    background-color:#D32F2F;
}

.btn-delete:hover{
    background-color:#8B0000;
}

/* Botones superiores */
.top-buttons{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:10px;
}

</style>
</head>

<body>

<div class="header">
Gestión de Inscripciones
</div>

<div class="container">

<!-- Botones -->
<div class="top-buttons">

    <a href="index.php">
        <button class="btn">Menú</button>
    </a>

    <a href="inscripcion.php">
        <button class="btn">Nueva Inscripción</button>
    </a>

</div>

<!-- Sin inscripción -->
<div class="card">

<h2 class="section-title">
⚠ Estudiantes sin inscripción
</h2>

<table>

<tr>
    <th>Código</th>
    <th>Nombre</th>
</tr>

<?php while($s = pg_fetch_assoc($sin)) { ?>

<tr>

<td>
<?php echo $s['cod_est']; ?>
</td>

<td class="nombre">
<?php echo $s['nomb_est']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>


<!-- Inscritos -->
<div class="card">

<h2 class="section-title">
Estudiantes Inscritos
</h2>

<input
type="text"
id="busqueda"
placeholder="Buscar por código o nombre..."
style="
width:100%;
padding:12px;
margin-bottom:25px;
border:1px solid #ccc;
border-radius:8px;
font-size:16px;
box-sizing:border-box;
"
onkeyup="filtrarEstudiantes()"
>

<?php

$agrupados = [];

pg_result_seek($result, 0);

while($row = pg_fetch_assoc($result)){

    $nombre = $row['nomb_est'];

    $agrupados[$nombre][] = $row;

}

?>

<?php foreach($agrupados as $estudiante => $inscripciones):

$codigo = $inscripciones[0]['cod_est'];

?>

<div
class="estudiante-card"
data-codigo="<?php echo $codigo; ?>"
data-nombre="<?php echo strtolower($estudiante); ?>"
style="
background:#f9f9f9;
padding:20px;
border-radius:12px;
margin-bottom:20px;
border-left:6px solid #B71C1C;
">

<h3 style="
margin-top:0;
color:#B71C1C;
">
<?php echo $estudiante; ?>
</h3>

<table>

<tr>
    <th>Curso</th>
    <th>Año</th>
    <th>Periodo</th>
    <th>⚙ Acción</th>
</tr>

<?php foreach($inscripciones as $row): ?>

<tr>

<td>
<?php echo $row['nomb_cur']; ?>
</td>

<td>
<?php echo $row['year']; ?>
</td>

<td>
<?php echo $row['periodo']; ?>
</td>

<td>

<button
class="btn btn-delete"
onclick="eliminarInscripcion(
<?php echo $row['cod_est']; ?>,
<?php echo $row['cod_cur']; ?>,
<?php echo $row['year']; ?>,
'<?php echo $row['periodo']; ?>'
)">
🗑 Eliminar
</button>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<?php endforeach; ?>

</div>

<script>

function filtrarEstudiantes(){

    let input = document.getElementById('busqueda');

    let filtro = input.value.toLowerCase();

    let cards = document.querySelectorAll('.estudiante-card');

    cards.forEach(card => {

        let codigo = card.dataset.codigo.toLowerCase();

        let nombre = card.dataset.nombre.toLowerCase();

        if(
            codigo.includes(filtro)
            ||
            nombre.includes(filtro)
        ){

            card.style.display = 'block';

        } else {

            card.style.display = 'none';

        }

    });

}

</script>

<script>

function eliminarInscripcion(cod_est, cod_cur, year, periodo){

    if(confirm('¿Eliminar esta inscripción?')){

        fetch('eliminar_inscripcion.php', {

            method: 'POST',

            headers: {
                'Content-Type':'application/x-www-form-urlencoded'
            },

            body:
                'cod_est=' + cod_est +
                '&cod_cur=' + cod_cur +
                '&year=' + year +
                '&periodo=' + encodeURIComponent(periodo)

        })

        .then(res => res.text())

        .then(data => {

            if(data.trim() === 'ok'){

                location.reload();

            } else {

                alert('Error: ' + data);

            }

        });

    }

}

</script>

</body>
</html>
