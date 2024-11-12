<?php
session_start();

// Conexión a la base de datos 
require 'conexion.php';

// Obtener los datos del formulario
$data = [
    'nombre_negocio' => $_POST['nombre_negocio'],
    'imagen_productor'=> $_POST['imagen_productor'],
    'descripcion' => $_POST['descripcion'],
    'telefono' => $_POST['telefono'],
    'horario_atencion' => $_POST['horario_atencion'],
    'horario_tianguis' => $_POST['horario_tianguis'],
    'promociones' => $_POST['promociones']
];

// Obtener el id_productor
$id_productor = $_POST['id_productor'];

// Construir la consulta de actualización usando la funcion
list($sql, $types, $params) = construirConsultaActualizacion($data, $id_productor);

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Recuperar los datos actualizados
    $stmt = $conn->prepare("SELECT * FROM productores WHERE id_productor = ?");
    $stmt->bind_param("i", $id_productor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $productor_actualizado = $resultado->fetch_assoc();

    // Actualizar los datos en la sesión
    $_SESSION['productor'] = $productor_actualizado;

    header("Location: perfilproductorP.php");
} else {
    header("Location: perfilproductor.php?status=error");
}

$stmt->close();
$conn->close();

//funcion para actualizar solo los datos que ingrese el usuario
function construirConsultaActualizacion($data, $id_productor) {
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

?>
