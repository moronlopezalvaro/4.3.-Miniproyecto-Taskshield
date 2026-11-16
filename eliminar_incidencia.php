<?php

require_once __DIR__ . '/conexion.php';

$mensaje = '';
$error = '';
$borrada = false;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $error = 'El id de la incidencia no es válido.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare('DELETE FROM incidencias WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            $mensaje = 'La incidencia ' . $id . ' se ha eliminado correctamente.';
            $borrada = true;
        } else {
            $error = 'No existe ninguna incidencia con el id ' . $id . '.';
        }
    } catch (PDOException $e) {
        error_log('Error DELETE incidencias: ' . $e->getMessage());
        $error = 'No se ha podido eliminar la incidencia.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Eliminar incidencia</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Eliminar incidencia</h1>

    <?php if ($mensaje) { ?>
        <p class="ok"><?php echo $mensaje; ?></p>
    <?php } ?>

    <?php if ($error) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($id && !$borrada && !$error) { ?>
        <p>¿Seguro que quieres eliminar la incidencia <?php echo $id; ?>? No se puede deshacer.</p>
        <form method="post" action="eliminar_incidencia.php?id=<?php echo $id; ?>">
            <button type="submit">Sí, eliminar</button>
        </form>
    <?php } ?>

    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>