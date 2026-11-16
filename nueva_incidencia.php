<?php

require_once __DIR__ . '/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $prioridad   = filter_input(INPUT_POST, 'prioridad', FILTER_SANITIZE_SPECIAL_CHARS);
    $email       = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$titulo || !$descripcion) {
        $error = 'El título y la descripción son obligatorios.';
    } elseif (!in_array($prioridad, ['Baja', 'Media', 'Alta'])) {
        $error = 'La prioridad no es válida.';
    } elseif (!$email) {
        $error = 'El email del cliente no es válido.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO incidencias (titulo, descripcion, prioridad, email_cliente, estado) VALUES (:titulo, :descripcion, :prioridad, :email, :estado)');
            $stmt->execute([
                ':titulo'      => $titulo,
                ':descripcion' => $descripcion,
                ':prioridad'   => $prioridad,
                ':email'       => $email,
                ':estado'      => 'Pendiente',
            ]);

            $nuevoId = $pdo->lastInsertId();
            header('Location: editar_incidencia.php?id=' . $nuevoId);
            exit;
        } catch (PDOException $e) {
            error_log('Error INSERT incidencias: ' . $e->getMessage());
            $error = 'No se ha podido guardar la incidencia.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>TaskShield - Nueva incidencia</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Nueva incidencia</h1>

    <?php if ($error) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

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