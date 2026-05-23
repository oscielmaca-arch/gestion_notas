<?php
include("conexion.php");

$estudiantes = pg_query($conexion, "SELECT * FROM estudiante");
$cursos = pg_query($conexion, "SELECT * FROM cursos");

if (isset($_POST['guardar'])) {
    $cod_est = $_POST['cod_est'];
    $cod_cur = $_POST['cod_cur'];
    $year = $_POST['year'];
    $periodo = $_POST['periodo'];

    pg_query($conexion, "INSERT INTO inscripciones (cod_cur, cod_est, year, periodo)
    VALUES ($cod_cur, $cod_est, $year, '$periodo')");

    echo "<div style='color:green;'>✅ Inscripción guardada</div>";

}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inscripción de Estudiantes</title>

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
    max-width:650px;
    margin:40px auto;
}

/* Tarjeta */
.card{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* Inputs y selects */
select,
input[type="number"]{

    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:22px;
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

/* Mensaje */
.success{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    text-align:center;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
Inscripción de Estudiantes
</div>

<div class="container">

<div class="top-buttons">

    <a href="index.php">
        <button class="btn">🏠 Menú</button>
    </a>

    <a href="inscripciones_lista.php">
        <button class="btn">Ver Inscripciones</button>
    </a>

</div>

<div class="card">

<?php
if (isset($_POST['guardar'])) {
    echo "<div class='success'>✅ Inscripción guardada correctamente</div>";
}
?>

<form method="POST">

<label>Estudiante</label>

<select name="cod_est" required>

<?php while($e = pg_fetch_assoc($estudiantes)) { ?>

<option value="<?php echo $e['cod_est']; ?>">

<?php echo $e['nomb_est']; ?>

</option>

<?php } ?>

</select>

<label>Curso</label>

<select name="cod_cur" required>

<?php while($c = pg_fetch_assoc($cursos)) { ?>

<option value="<?php echo $c['cod_cur']; ?>">

<?php echo $c['nomb_cur']; ?>

</option>

<?php } ?>

</select>

<label>Año</label>

<input
type="number"
name="year"
min="2020"
max="2035"
placeholder="Ejemplo: 2025"
required>

<label>Periodo</label>

<select name="periodo">

<option value="I">Periodo I</option>

<option value="II">Periodo II</option>

</select>

<button type="submit" name="guardar" class="btn">
Inscribir Estudiante
</button>

</form>

</div>

</div>

</body>
</html>
