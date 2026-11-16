<?php

require_once __DIR__ . '/conexion.php';

$incidencias = [];
$error = '';

try {
    $stmt = $pdo->prepare('SELECT id, titulo, descripcion, prioridad, email_cliente, estado, tecnico_id
                        FROM incidencias
                        WHERE estado <> :estado
                        ORDER BY id DESC');
    $stmt->execute([':estado' => 'Resuelta']);
    $incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error SELECT incidencias: ' . $e->getMessage());
    $error = 'No se han podido cargar las incidencias.';
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Incidencias</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>TaskShield: incidencias activas</h1>

    <p>
        <a href="nueva_incidencia.php">+ Nueva incidencia</a>
    </p>

    <?php if ($error) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <?php if (count($incidencias) === 0) { ?>
        <p>No hay incidencias activas.</p>
    <?php } else { ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Email cliente</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($incidencias as $incidencia) { ?>
                <tr>
                    <td><?php echo $incidencia['id']; ?></td>
                    <td><?php echo $incidencia['titulo']; ?></td>
                    <td><?php echo $incidencia['descripcion']; ?></td>
                    <td><?php echo $incidencia['prioridad']; ?></td>
                    <td><?php echo $incidencia['email_cliente']; ?></td>
                    <td><?php echo $incidencia['estado']; ?></td>
                    <td><?php echo $incidencia['tecnico_id'] ? $incidencia['tecnico_id'] : 'Sin asignar'; ?></td>
                    <td>
                        <a href="editar_incidencia.php?id=<?php echo $incidencia['id']; ?>">Editar</a> |
                        <a href="asignar_incidencia.php?id=<?php echo $incidencia['id']; ?>">Asignar</a> |
                        <a href="eliminar_incidencia.php?id=<?php echo $incidencia['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</body>

</html>