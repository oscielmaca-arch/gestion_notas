<?php
include("conexion.php");

$result = pg_query($conexion, "SELECT * FROM estudiante");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estudiantes</title>

<style>

body{
    font-family: Arial, sans-serif;
    background-color:#f4f4f4;
    margin:0;
    padding:0;
}

.header{
    background-color:#B71C1C;
    color:white;
    text-align:center;
    padding:20px;
    font-size:28px;
    font-weight:bold;
}

.container{
    width:95%;
    max-width:1100px;
    margin:30px auto;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

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

.top-buttons{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:10px;
}

table{
    width:100%;
    border-collapse:collapse;
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

.nombre{
    text-align:left;
}

.acciones{
    display:flex;
    justify-content:center;
    gap:8px;
}

.btn-edit{
    background-color:#1976D2;
}

.btn-edit:hover{
    background-color:#0D47A1;
}

.btn-delete{
    background-color:#D32F2F;
}

.btn-delete:hover{
    background-color:#8B0000;
}

</style>
</head>

<body>

<div class="header">
Gestión de Estudiantes
</div>

<div class="container">

<div class="top-buttons">

    <a href="index.php">
        <button class="btn">Menú</button>
    </a>

    <a href="crear_estudiante.php">
        <button class="btn">Agregar Estudiante</button>
    </a>

</div>

<div class="card">

<table>

<tr>
    <th>Código</th>
    <th>Nombre</th>
    <th>Acciones</th>
</tr>

<?php while($row = pg_fetch_assoc($result)): ?>

<tr>

<td>
<?php echo $row['cod_est']; ?>
</td>

<td class="nombre">
<?php echo $row['nomb_est']; ?>
</td>

<td>

<div class="acciones">

<button
class="btn btn-edit"
onclick="editarEstudiante(
<?php echo $row['cod_est']; ?>,
'<?php echo addslashes($row['nomb_est']); ?>'
)">
Editar
</button>

<button
class="btn btn-delete"
onclick="eliminarEstudiante(<?php echo $row['cod_est']; ?>)">
🗑 Eliminar
</button>

</div>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

<script>

function eliminarEstudiante(cod_est){

    if(confirm('¿Eliminar este estudiante?')){

        fetch('eliminar_estudiante.php', {

            method: 'POST',

            headers: {
                'Content-Type':'application/x-www-form-urlencoded'
            },

            body: 'cod_est=' + cod_est

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

function editarEstudiante(cod_est, nombre){

    let nuevoNombre = prompt(
        "Editar nombre del estudiante:",
        nombre
    );

    if(nuevoNombre !== null){

        fetch('editar_estudiante.php', {

            method: 'POST',

            headers: {
                'Content-Type':'application/x-www-form-urlencoded'
            },

            body:
                'cod_est=' + cod_est +
                '&nomb_est=' + encodeURIComponent(nuevoNombre)

        })

        .then(res => res.text())

        .then(data => {

            if(data.trim() === 'ok'){

                location.reload();

            } else {

                alert('Error al actualizar');

            }

        });

    }

}

</script>

</body>
</html>
