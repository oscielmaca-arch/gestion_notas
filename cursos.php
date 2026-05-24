<?php
include("conexion.php");

$result = pg_query($conexion, "SELECT * FROM cursos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cursos</title>

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
    max-width:1000px;
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

/* Zona botones */
.top-buttons{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:10px;
}

/* Tabla */
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

/* Nombre alineado */
.nombre{
    text-align:left;
}

</style>
</head>

<body>

<div class="header">
Gestión de Cursos
</div>

<div class="container">

<div class="top-buttons">

    <a href="index.php">
        <button class="btn">Menú</button>
    </a>

    <a href="crear_curso.php">
        <button class="btn">Agregar Curso</button>
    </a>

</div>

<div class="card">

<table>

<tr>
    <th>ID</th>
    <th>Nombre del Curso</th>
</tr>

<?php while ($row = pg_fetch_assoc($result)) { ?>

<tr>

<td>
<?php echo $row['cod_cur']; ?>
</td>

<td class="nombre">
<?php echo $row['nomb_cur']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>
