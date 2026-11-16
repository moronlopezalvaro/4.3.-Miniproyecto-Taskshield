<?php

require_once __DIR__ . '/conexion.php';

$mensaje = '';
$error = '';
$incidencia = false;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $error = 'El id de la incidencia no es válido.';
} else {
    try {
        $stmt = $pdo->prepare('SELECT id, titulo, estado FROM incidencias WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $incidencia = $stmt->fetch();

        if (!$incidencia) {
            $error = 'No existe ninguna incidencia con el id ' . $id . '.';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);

            if (!in_array($estado, ['Pendiente', 'En Proceso', 'Resuelta'])) {
                $error = 'El estado no es válido.';
            } else {
                $stmt = $pdo->prepare('UPDATE incidencias SET estado = :estado WHERE id = :id');
                $stmt->execute([':estado' => $estado, ':id' => $id]);

                if ($stmt->rowCount() > 0) {
                    $mensaje = 'Estado actualizado correctamente.';
                    $incidencia['estado'] = $estado;
                } else {
                    $mensaje = 'No se ha cambiado nada (el estado ya era ese).';
                }
            }
        }
    } catch (PDOException $e) {
        error_log('Error editar incidencia: ' . $e->getMessage());
        $error = 'Ha ocurrido un error al acceder a la incidencia.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Editar incidencia</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 30px;">
    <h1>Editar incidencia</h1>

    <?php if ($mensaje) { ?>
        <p style="color:green"><?php echo $mensaje; ?></p>
    <?php } ?>

    <?php if ($error) { ?>
        <p style="color:red"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($incidencia) { ?>
        <p><strong>#<?php echo $incidencia['id']; ?></strong> <?php echo $incidencia['titulo']; ?></p>
        <p>Estado actual: <strong><?php echo $incidencia['estado']; ?></strong></p>

        <form method="post" action="editar_incidencia.php?id=<?php echo $incidencia['id']; ?>">
            <label>Nuevo estado:
                <select name="estado">
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Resuelta">Resuelta</option>
                </select>
            </label>
            <button type="submit">Guardar</button>
        </form>
    <?php } ?>

    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>