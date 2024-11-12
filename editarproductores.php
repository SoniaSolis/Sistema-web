<?php
session_start();
// Verifica que haya sesión, de lo contrario solicita el registro
if (!isset($_SESSION['correo'])) {
    header('Location: registro.php');
    exit;
}

require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $id_productor = $_POST['id_productor'];
    $nombre_productor = $_POST['nombre_productor'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $giro = $_POST ['giro'];
    $ubicacion = $_POST['ubicacion'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $id_administrador = 1; // Solo hay un administrador

    switch ($accion) {
        case 'agregar':
            // Verificar el último id_usuario
            $resultado_usuario = $conn->query("SELECT MAX(id_usuario) AS max_id FROM usuarios");
            $row = $resultado_usuario->fetch_assoc();
            $nuevo_id_usuario = $row['max_id'] + 1;

            // Insertar el nuevo usuario en la tabla usuarios
            $stmt = $conn->prepare("INSERT INTO usuarios (id_usuario, correo, contrasena, acceso) VALUES (?, ?, ?, 3)");
            $stmt->bind_param("iss", $nuevo_id_usuario, $correo, password_hash($contrasena, PASSWORD_DEFAULT));
            if ($stmt->execute()) {
                // Insertar el nuevo productor en la tabla productores
                $stmt = $conn->prepare("INSERT INTO productores (nombre_productor, fecha_ingreso, ubicacion, id_usuario, id_administrador) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", $nombre_productor, $fecha_ingreso, $ubicacion, $nuevo_id_usuario, $id_administrador);
                if ($stmt->execute()) {
                    $_SESSION['status'] = 'pagregado'; // Almacenar mensaje en sesión
                    header("Location: abcproductor.php");
                    exit;
                }
            }
            $_SESSION['status'] = 'error'; 
            header("Location: abcproductor.php");
            exit;

        case 'eliminar':
            // Obtener el id_usuario antes de eliminar el productor
            $stmt = $conn->prepare("SELECT id_usuario FROM productores WHERE id_productor = ?");
            $stmt->bind_param("i", $id_productor);
            $stmt->execute();
            $stmt->bind_result($id_usuario);
            $stmt->fetch();
            $stmt->close();

            // Eliminar el productor
            $stmt = $conn->prepare("DELETE FROM productores WHERE id_productor = ?");
            $stmt->bind_param("i", $id_productor);
            if ($stmt->execute()) {
                // Eliminar el usuario correspondiente
                $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
                $stmt->bind_param("i", $id_usuario);
                if ($stmt->execute()) {
                    $_SESSION['status'] = 'peliminado'; // Almacenar mensaje en sesión
                    header("Location: abcproductor.php");
                    exit;
                }
            }
            $_SESSION['status'] = 'error'; 
            header("Location: abcproductor.php");
            exit;

        case 'editar':
            // Actualizar los datos del productor
            $data = array(
                'id_productor' => $id_productor,
                'nombre_productor' => $nombre_productor,
                'fecha_ingreso' => $fecha_ingreso,
                'giro' => $giro,
                'ubicacion' => $ubicacion
            );

            // Construir la consulta de actualización
            list($sql, $types, $params) = construirConsultaActualizacionProductores($data, $id_productor);

            // Preparar y ejecutar la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            // Actualizar los datos del usuario
            if (!empty($correo) || !empty($contrasena)) {
                // Obtener el id_usuario
                $stmt = $conn->prepare("SELECT id_usuario FROM productores WHERE id_productor = ?");
                $stmt->bind_param("i", $id_productor);
                $stmt->execute();
                $stmt->bind_result($id_usuario);
                $stmt->fetch();
                $stmt->close();

                $data_usuario = array(
                    'correo' => $correo,
                    'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT)
                );

                // Construir la consulta de actualización para el usuario
                list($sql_usuario, $types_usuario, $params_usuario) = construirConsultaActualizacionUsuarios($data_usuario, $id_usuario);

                // Preparar y ejecutar la consulta para el usuario
                $stmt = $conn->prepare($sql_usuario);
                $stmt->bind_param($types_usuario, ...$params_usuario);
                if ($stmt->execute()) {
                    $_SESSION['status'] = 'peditado'; // Almacenar mensaje en sesión
                    header("Location: abcproductor.php");
                    exit;
                }
            }
            $_SESSION['status'] = 'error'; 
            header("Location: abcproductor.php");
            exit;
    }

    $stmt->close();
    $conn->close();
}

function construirConsultaActualizacionProductores($data, $id_productor) {
    $sql = "UPDATE productores SET ";
    $params = [];
    $types = '';

    foreach ($data as $campo => $valor) {
        if (!empty($valor)) {
            $sql .= "$campo = ?, ";
            $params[] = $valor;
            $types .= 's';
        }
    }

    // Remover la última coma y agregar la cláusula WHERE
    $sql = rtrim($sql, ', ') . " WHERE id_productor = ?";
    $params[] = $id_productor;
    $types .= 'i';

    return [$sql, $types, $params];
}

function construirConsultaActualizacionUsuarios($data, $id_usuario) {
    $sql = "UPDATE usuarios SET ";
    $params = [];
    $types = '';

    foreach ($data as $campo => $valor) {
        if (!empty($valor)) {
            $sql .= "$campo = ?, ";
            $params[] = $valor;
            $types .= 's';
        }
    }

    // Remover la última coma y agregar la cláusula WHERE
    $sql = rtrim($sql, ', ') . " WHERE id_usuario = ?";
    $params[] = $id_usuario;
    $types .= 'i';

    return [$sql, $types, $params];
}
?>
