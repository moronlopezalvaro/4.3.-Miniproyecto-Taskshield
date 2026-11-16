<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Asignar incidencia</title>
</head>

<body>
    <h1>Asignar incidencia a un técnico</h1>

    <h2>Técnicos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Tareas activas</th>
        </tr>
    </table>

    <form method="post" action="asignar_incidencia.php">
        <p>
            <label>ID del técnico:
                <input type="number" name="tecnico_id">
            </label>
            <button type="submit">Asignar</button>
        </p>
    </form>

    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>