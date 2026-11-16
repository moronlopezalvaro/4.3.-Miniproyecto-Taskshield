<?php

require_once __DIR__ . '/conexion.php';

$mensaje = '';
$error = '';
$incidencia = false;
$tecnicos = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $error = 'El id de la incidencia no es válido.';
} else {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tecnicoId = filter_input(INPUT_POST, 'tecnico_id', FILTER_VALIDATE_INT);

        if (!$tecnicoId) {
            $error = 'El id del técnico no es válido.';
        } else {
            try {
                $pdo->beginTransaction();

                $stmt1 = $pdo->prepare('UPDATE incidencias
                                        SET estado = :estado, tecnico_id = :tecnico
                                        WHERE id = :id');
                $stmt1->execute([':estado' => 'En Proceso', ':tecnico' => $tecnicoId, ':id' => $id]);
                if ($stmt1->rowCount() === 0) {
                    throw new Exception('La incidencia no existe o ya estaba asignada a ese técnico.');
                }

                $stmt2 = $pdo->prepare('UPDATE tecnicos
                                        SET tareas_activas = tareas_activas + 1
                                        WHERE id = :tecnico');
                $stmt2->execute([':tecnico' => $tecnicoId]);
                if ($stmt2->rowCount() === 0) {
                    throw new Exception('El técnico ' . $tecnicoId . ' no existe.');
                }

                $pdo->commit();
                $mensaje = 'Incidencia asignada al técnico ' . $tecnicoId . '.';
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log('Error BD asignación: ' . $e->getMessage());
                $error = 'Error al asignar. No se ha guardado ningún cambio.';
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log('Error asignación: ' . $e->getMessage());
                $error = 'No se ha podido asignar: el técnico o la incidencia no existen. No se ha guardado ningún cambio.';
            }
        }
    }

    try {
        $stmt = $pdo->prepare('SELECT id, titulo, estado, tecnico_id FROM incidencias WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $incidencia = $stmt->fetch();

        if (!$incidencia) {
            $error = 'No existe ninguna incidencia con el id ' . $id . '.';
        }

        $stmt = $pdo->prepare('SELECT id, nombre, tareas_activas FROM tecnicos ORDER BY id');
        $stmt->execute();
        $tecnicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error SELECT asignar: ' . $e->getMessage());
        $error = 'Ha ocurrido un error al cargar los datos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Asignar incidencia</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Asignar incidencia a un técnico</h1>

    <?php if ($mensaje) { ?>
        <p class="ok"><?php echo $mensaje; ?></p>
    <?php } ?>

    <?php if ($error) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if ($incidencia) { ?>
        <p><strong>#<?php echo $incidencia['id']; ?></strong> <?php echo $incidencia['titulo']; ?></p>
        <p>Estado: <?php echo $incidencia['estado']; ?> ·
            Técnico: <?php echo $incidencia['tecnico_id'] ? $incidencia['tecnico_id'] : 'Sin asignar'; ?></p>

        <h2>Técnicos</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tareas activas</th>
            </tr>
            <?php foreach ($tecnicos as $tecnico) { ?>
                <tr>
                    <td><?php echo $tecnico['id']; ?></td>
                    <td><?php echo $tecnico['nombre']; ?></td>
                    <td><?php echo $tecnico['tareas_activas']; ?></td>
                </tr>
            <?php } ?>
        </table>

        <form method="post" action="asignar_incidencia.php?id=<?php echo $incidencia['id']; ?>">
            <p>
                <label>ID del técnico:
                    <input type="number" name="tecnico_id">
                </label>
                <button type="submit">Asignar</button>
            </p>
        </form>
    <?php } ?>

    <p><a href="index.php">Volver al listado</a></p>
</body>

</html>