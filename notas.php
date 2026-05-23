
<?php
include("conexion.php");

// Cargar inscripciones en array
$inscripciones_array = [];
$res = pg_query($conexion, "
SELECT i.cod_cur, i.cod_est, e.nomb_est, c.nomb_cur, i.year, i.periodo
FROM inscripciones i
JOIN estudiante e ON i.cod_est = e.cod_est
JOIN cursos c ON i.cod_cur = c.cod_cur
");
while($row = pg_fetch_assoc($res)){
    $inscripciones_array[] = $row;
}

// Cargar cohortes en array
$cohortes_array = [];
$res2 = pg_query($conexion, "SELECT * FROM cohortes ORDER BY posicion");
while($row = pg_fetch_assoc($res2)){
    $cohortes_array[] = $row;
}

// Inicializar seleccionados
$selected_inscripcion = '';
$selected_cohorte = '';

if (isset($_POST['guardar'])) {
    // Tomar los datos del select
    $data = explode("|", $_POST['inscripcion']);
    $cod_est = $data[0];
    $cod_cur = $data[1];
    $year = $data[2];
    $periodo = $data[3];

    $cod_cohorte = $_POST['cod_cohorte'];
    $nota = $_POST['nota'];

    // ⚠ Verificar duplicados antes de insertar
    $check = pg_query($conexion, "
        SELECT * FROM calificaciones
        WHERE cod_est = $cod_est
        AND cod_cur = $cod_cur
        AND year = $year
        AND periodo = '$periodo'
        AND cod_cohorte = $cod_cohorte
    ");

    if(pg_num_rows($check) > 0){
        echo "<p style='color:red;'>⚠ Ya existe una nota para este estudiante en este cohorte.</p>";
    } else {
        pg_query($conexion, "
        INSERT INTO calificaciones (cod_cur, cod_est, year, periodo, cod_cohorte, nota)
        VALUES ($cod_cur, $cod_est, $year, '$periodo', $cod_cohorte, $nota)
        ");
        echo "<p style='color:green;'>✅ Nota guardada</p>";
    }

    // Mantener selección
    $selected_inscripcion = $_POST['inscripcion'];
    $selected_cohorte = $cod_cohorte;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Notas</title>

<style>

body{
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin:0;
    padding:0;
}

/* Encabezado */
.header{
    background-color:#B71C1C;
    color:white;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
}

/* Contenedor principal */
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
    box-shadow:0 5px 15px rgba(0,0,0,0.15);
}

/* Inputs y selects */
select,
input[type="number"]{
    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:20px;
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

/* Botones superiores */
.top-buttons{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

/* Mensajes */
.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:8px;
    margin-bottom:20px;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:8px;
    margin-bottom:20px;
}

</style>
</head>

<body>

<div class="header">
Registro de Notas
</div>

<div class="container">

<div class="top-buttons">

    <a href="index.php">
        <button class="btn">🏠 Menú</button>
    </a>

    <a href="notas_registradas.php">
        <button class="btn">Ver Notas</button>
    </a>

</div>

<div class="card">

<form method="POST">

<label>Estudiante y Curso</label>

<select name="inscripcion">

<?php foreach($inscripciones_array as $i){

    $val = $i['cod_est']."|".$i['cod_cur']."|".$i['year']."|".$i['periodo'];

    $sel = ($val == $selected_inscripcion) ? 'selected' : '';

?>

<option value="<?php echo $val; ?>" <?php echo $sel; ?>>

<?php
echo $i['nomb_est']." - ".$i['nomb_cur'].
" (".$i['year']."/".$i['periodo'].")";
?>

</option>

<?php } ?>

</select>

<label>Cohorte</label>

<select name="cod_cohorte">

<?php foreach($cohortes_array as $c){

    $sel = ($c['cod_cohorte'] == $selected_cohorte) ? 'selected' : '';

?>

<option value="<?php echo $c['cod_cohorte']; ?>" <?php echo $sel; ?>>

<?php echo $c['desc_cohorte']; ?>

</option>

<?php } ?>

</select>

<label>Nota</label>

<input
type="number"
step="0.1"
name="nota"
min="0"
max="5"
placeholder="Ejemplo: 4.5"
required>

<button type="submit" name="guardar" class="btn">
Guardar Nota
</button>

</form>

</div>
</div>

</body>
</html>
