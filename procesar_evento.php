<?php
session_start(); // Iniciar la sesión
include 'conexion.php'; // Conectar a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_evento = $_POST['id_evento'];
    $accion = $_POST['accion'];
    $titulo = $_POST['titulo'];
    $seccion = $_POST['seccion'];
    $descripcion = $_POST['descripcion'];
    $fecha_evento = $_POST['fecha_evento'];

    switch ($accion) {
        case 'agregar':
            if (isset($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK) {
                $media = $_FILES['media']['name'];
                $media_tmp = $_FILES['media']['tmp_name'];
                $upload_dir = __DIR__ . '/imagenes/';// Carpeta donde guardar las imágenes
                move_uploaded_file($media_tmp, $upload_dir . $media);
            } else {
                $media = null;
            }

            $sql = "INSERT INTO eventos (seccion, titulo, media, descripcion, fecha_evento) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $seccion, $titulo, $media, $descripcion, $fecha_evento);

            if ($stmt->execute()) {
                $_SESSION['status'] = 'agregado'; // Almacenar mensaje en sesión
                header("Location: abcproductor.php");
                exit;
            } else {
                $_SESSION['status'] = 'error'; 
                header("Location: abcproductor.php");
                exit;
            }
            break;

        case 'eliminar':
            $stmt = $conn->prepare("DELETE FROM eventos WHERE id_evento = ?");
            $stmt->bind_param("i", $id_evento);

            if ($stmt->execute()) {
                $_SESSION['status'] = 'eliminado'; // Almacenar mensaje en sesión
                header("Location: abcproductor.php");
                exit;
            } else {
                $_SESSION['status'] = 'error'; // 
                header("Location: abcproductor.php");
                exit;
            }
            break;
    }

    $stmt->close();
    $conn->close();
}
?>
