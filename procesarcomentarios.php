<?php
session_start();

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if (!isset($_SESSION['correo'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Primero inicie sesión por favor.'
    ]);
    exit;
}

// Obtener los datos enviados desde el formulario
$nombre = trim($_POST['nombre']);
$comentario = $_POST['comentario'] ?? '';
$calificacion = intval($_POST['calificacion']) ?? 5; // Obtener la calificación y convertirla a entero

if (empty($nombre)) {
    $nombre = 'Anónimo';
}

if (empty($comentario)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'El comentario no puede estar vacío.'
    ]);
    exit;
}

// Obtener la fecha actual
$fecha = date('Y-m-d');

// Conectar a la base de datos
require_once 'conexion.php';

// Insertar el comentario y la calificación en la base de datos
$sql = "INSERT INTO comentarios (nombre, comentario, calificacion, fecha) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $nombre, $comentario, $calificacion, $fecha);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Comentario enviado exitosamente.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al enviar el comentario.'
    ]);
}

// Cerrar la conexión y la declaración
$stmt->close();
$conn->close();
?>
