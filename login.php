<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cod_doc = $_POST['cod_doc'];
    $clave = $_POST['clave'];

    $sql = "SELECT * FROM docentes 
            WHERE cod_doc='$cod_doc'
            AND clave='$clave'";

    $resultado = pg_query($conexion, $sql);

    if (pg_num_rows($resultado) > 0) {

        $docente = pg_fetch_assoc($resultado);

        $_SESSION['docente'] = $docente['nomb_doc'];
        $_SESSION['cod_doc'] = $docente['cod_doc'];

        header("Location: index.php");
        exit();

    } else {
        $error = "Código o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
</head>

<body style="
font-family: Arial;
background:#f4f4f4;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
">

<div style="
background:white;
padding:30px;
border-radius:10px;
width:300px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
">

<h2 style="text-align:center;">
Iniciar Sesión
</h2>

<?php
if(isset($error)){
    echo "<p style='color:red;'>$error</p>";
}
?>

<form method="POST">

<label>Código Docente</label><br>
<input type="number" name="cod_doc" required
style="width:100%; padding:8px;"><br><br>

<label>Contraseña</label><br>
<input type="password" name="clave" required
style="width:100%; padding:8px;"><br><br>

<button type="submit"
style="
width:100%;
padding:10px;
background:#B71C1C;
color:white;
border:none;
border-radius:5px;
">
Ingresar
</button>

</form>

</div>

</body>
</html>
