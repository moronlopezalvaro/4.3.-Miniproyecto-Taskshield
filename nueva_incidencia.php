<?php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Nueva incidencia</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 30px;">
    <h1>Nueva incidencia</h1>

    <form method="post" action="nueva_incidencia.php">
        <p>
            <label>Título:<br>
                <input type="text" name="titulo">
            </label>
        </p>
        <p>
            <label>Descripción:<br>
                <textarea name="descripcion" rows="4" cols="40"></textarea>
            </label>
        </p>
        <p>
            <label>Prioridad:<br>
                <select name="prioridad">
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </label>
        </p>
        <p>
            <!-- type="text" y no type="email" para que la validación la haga PHP y se pueda probar -->
            <label>Email del cliente:<br>
                <input type="text" name="email">
            </label>
        </p>
        <p>
            <button type="submit">Guardar</button>
        </p>
    </form>

    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>