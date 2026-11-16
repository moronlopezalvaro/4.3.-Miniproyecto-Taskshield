# 4.3.-Miniproyecto-Taskshield

Intranet sencilla para el departamento de soporte: registrar incidencias, asignarlas a técnicos, cambiar su estado y eliminarlas. Hecha con PHP y PDO sobre MySQL (XAMPP).

## Arquitectura

| Fichero                          | Qué hace                                                                                                                                         |
| -------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------- |
| `config.php`                   | Credenciales de la base de datos con `define()`. No se sube al repositorio (está en `.gitignore`).                                           |
| `conexion.php`                 | Crea el objeto `$pdo` con excepciones activadas y sin emulación de sentencias preparadas. Lo incluyen todas las páginas con `require_once`. |
| `index.php`                    | Panel principal. `SELECT` preparado de las incidencias que no están resueltas, `fetchAll(PDO::FETCH_ASSOC)` y tabla con `foreach`.         |
| `nueva_incidencia.php`         | Alta. `filter_input` para los campos, `INSERT` preparado y redirección con `lastInsertId()`.                                               |
| `editar_incidencia.php?id=X`   | Cambia el estado. Comprueba con `fetch()` que el id existe y hace `UPDATE ... WHERE id = :id`.                                                |
| `eliminar_incidencia.php?id=X` | Pide confirmación, hace `DELETE ... WHERE id = :id` y comprueba con `rowCount()`.                                                            |
| `asignar_incidencia.php?id=X`  | Transacción: pone la incidencia "En Proceso" con su técnico y suma 1 a `tareas_activas`. Si algo falla, `rollBack()`.                       |
| `doc/schema.sql`               | Estructura de las tablas y datos de prueba.                                                                                                       |

Todas las consultas usan `prepare()` + `execute()` con marcadores nombrados, así que no hay inyección SQL. Los errores técnicos se guardan con `error_log()` y al usuario solo se le muestra un mensaje genérico.

## Cómo probarlo

1. Arrancar Apache y MySQL en XAMPP.
2. Importar `doc/schema.sql` en phpMyAdmin. Crea la base de datos `taskshield` con 3 técnicos y 4 incidencias.
3. Crear `config.php` con las credenciales (en XAMPP: usuario `root`, contraseña vacía).
4. Abrir `http://localhost/4.3.-Miniproyecto-Taskshield/index.php`.
5. Seguir los casos de `doc/casos_prueba.md`.
