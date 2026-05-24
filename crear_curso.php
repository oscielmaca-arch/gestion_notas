<?php
include("conexion.php");

if (isset($_POST['guardar'])) {

    $nombre = $_POST['nombre'];

    pg_query($conexion, "
    INSERT INTO cursos (nomb_cur)
    VALUES ('$nombre')
    ");

    header("Location: cursos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Curso</title>

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
    width:90%;
    max-width:600px;
    margin:40px auto;
}

/* Tarjeta */
.card{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* Inputs */
input[type="text"]{

    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:25px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:16px;

}

/* Labels */
label{
    font-weight:bold;
    font-size:17px;
}

/* Botones */
.btn{
    background-color:#B71C1C;
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
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

</style>
</head>

<body>

<div class="header">
Agregar Curso
</div>

<div class="container">

<div class="top-buttons">

    <a href="index.php">
        <button class="btn">Menú</button>
    </a>

    <a href="cursos.php">
        <button class="btn">Volver</button>
    </a>

</div>

<div class="card">

<form method="POST">

<label>Nombre del curso</label>

<input
type="text"
name="nombre"
placeholder="Ejemplo: Bases de Datos"
required>

<button type="submit" name="guardar" class="btn">
Guardar Curso
</button>

</form>

</div>

</div>

</body>
</html>
