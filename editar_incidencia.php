<?php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Editar incidencia</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 30px;">
    <h1>Editar incidencia</h1>
    <p><strong>#</strong> </p>
    <p>Estado actual: <strong></strong></p>

    <form method="post" action="editar_incidencia.php?id=">
        <label>Nuevo estado:
            <select name="estado">
                <option value="Pendiente">Pendiente</option>
                <option value="En Proceso">En Proceso</option>
                <option value="Resuelta">Resuelta</option>
            </select>
        </label>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>