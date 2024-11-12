<?php

require 'conexion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $id_productor = $_POST['id_productor'];
    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion_producto = $_POST['descripcion_producto'];
    $precio = $_POST['precio'];
    $unidad = $_POST['unidad'];
    $imagen = $_FILES['imagen'];

    switch ($accion) {
        case 'agregar':
            // Procesar la imagen
            $nombre_imagen = $imagen['name'];
            $temp_imagen = $imagen['tmp_name'];
            $directorio_imagenes = '';
            $ruta_imagen = $directorio_imagenes . $nombre_imagen;
            move_uploaded_file($temp_imagen, $ruta_imagen);

            // Insertar el nuevo producto
            $stmt = $conn->prepare("INSERT INTO productos (nombre_producto, descripcion_producto, precio, unidad, imagen, id_productor) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $nombre_producto, $descripcion_producto, $precio, $unidad, $ruta_imagen, $id_productor);
            break;

        case 'eliminar':
            // Eliminar el producto
            $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
            $stmt->bind_param("i", $id_producto);
            break;

        case 'editar':
            // Procesar la imagen si se sube una nueva
            if ($imagen['size'] > 0) {
                $nombre_imagen = $imagen['name'];
                $temp_imagen = $imagen['tmp_name'];
                $directorio_imagenes = '';
                $ruta_imagen = $directorio_imagenes . $nombre_imagen;
                move_uploaded_file($temp_imagen, $ruta_imagen);
            } else {
                // Conservar la imagen actual si no se sube una nueva
                $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
                $stmt->bind_param("i", $id_producto);
                $stmt->execute();
                $stmt->bind_result($imagen_actual);
                $stmt->fetch();
                $ruta_imagen = $imagen_actual;
                $stmt->close();
            }

            // Crear array con los datos a actualizar
            $data = array(
                'nombre_producto' => $nombre_producto,
                'descripcion_producto' => $descripcion_producto,
                'precio' => $precio,
                'unidad' => $unidad,
                'imagen' => $ruta_imagen // Aquí se guarda la ruta de la imagen
            );

            // Construir la consulta de actualización usando la función
            list($sql, $types, $params) = construirConsultaActualizacionProductos($data, $id_producto);

            // Preparar y ejecutar la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            break;
    }

    if ($stmt->execute()) {
        header("Location:perfilProductorP.php?status=success");
    } else {
        header("Location:perfilProductorP.php?status=error");
    }

    $stmt->close();
    $conn->close();
}

function construirConsultaActualizacionProductos($data, $id_producto) {
    $sql = "UPDATE productos SET ";
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
    $sql = rtrim($sql, ', ') . " WHERE id_producto = ?";
    $params[] = $id_producto;
    $types .= 'i';

    return [$sql, $types, $params];
}

?>