
<?php
session_start();

if (!isset($_SESSION['docente'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Notas - Unillanos</title>
<style>

/* Reset y fuente */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

/* Franja superior blanca con logo */
.header-top {
    background-color: white;
    display: flex;
    align-items: center;
    padding: 10px 20px;
    border-bottom: 5px solid #B71C1C; /* Separador rojo */
}

.header-top img {
    height: 60px;
    margin-right: 20px;
}

.header-top h1 {
    color: #B71C1C;
    font-size: 1.8em;
    margin: 0;
}

/* Main con tarjetas */
main {
    padding: 40px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}

/* Tarjetas clicables */

.card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background-color: #F2F2F2;
    border-radius: 15px;
    border: 2px solid #B71C1C; /* borde rojo fino */
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    width: 220px;
    padding: 30px 20px;
    transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s, border-color 0.3s;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    background-color: #B71C1C;
    color: white;
    border-color: #D32F2F; /* borde más intenso al hover */
}

.card img {
    height: 80px;
    margin-bottom: 20px;
    transition: filter 0.3s;
}

.card:hover img {
    filter: brightness(0) invert(1);
}

.card h2 {
    margin: 0;
    font-size: 1.2em;
    transition: color 0.3s;
}

.card:hover h2 {
    color: white;
}
</style>
</head>
<body>

<!-- Franja superior con logo -->
<div class="header-top">
    <img src="logo_unillanos.png" alt="Logo Unillanos">
    <h1>Gestión de Notas</h1>
</div>

<!-- Main con tarjetas -->

<main>
    <a href="estudiantes.php" class="card">
        <img src="school.svg" alt="Gestionar estudiantes">
        <h2>Estudiantes</h2>
        <p>Agregar, editar o eliminar estudiantes del sistema.</p>
    </a>

    <a href="cursos.php" class="card">
        <img src="book.svg" alt="Gestionar cursos">
        <h2>Cursos</h2>
        <p>Crear y actualizar los cursos disponibles por semestre.</p>
    </a>

    <a href="inscripcion.php" class="card">
        <img src="assignment.svg" alt="Inscribir estudiantes">
        <h2>Inscripciones</h2>
        <p>Registrar a los estudiantes en los cursos correspondientes.</p>
    </a>

    <a href="notas.php" class="card">
        <img src="bar_chart.svg" alt="Registrar notas">
        <h2>Registro de Notas</h2>
        <p>Agregar y actualizar las notas parciales por cohorte.</p>
    </a>
</main>

</body>
</html>
