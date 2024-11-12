<?php

// Iniciar sesión temporal
session_start();

// Conexión a la base de datos
require 'conexion.php';

// Obtener datos del formulario
$nombre_usuario = $_POST['user'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];
$telefono = $_POST['telefono'];

// Insertar datos en la tabla usuarios
$sql_usuarios = "INSERT INTO usuarios (correo, contrasena, acceso) VALUES (?, ?, 2)";
$stmt_usuarios = $conn->prepare($sql_usuarios);
$stmt_usuarios->bind_param("ss", $email, password_hash($contrasena, PASSWORD_DEFAULT));

// Ejecutar la consulta y verificar si fue exitosa
if ($stmt_usuarios->execute()) {
    $id_usuario = $stmt_usuarios->insert_id; // Obtener el id del último usuario insertado
    
    // Insertar datos en la tabla clientes
    $sql_clientes = "INSERT INTO clientes (nombre_cliente, telefono, id_usuario) VALUES (?, ?, ?)";
    $stmt_clientes = $conn->prepare($sql_clientes);
    $stmt_clientes->bind_param("ssi", $nombre_usuario, $telefono, $id_usuario);
    
    // Ejecutar la consulta y redirecciona
    if ($stmt_clientes->execute()) {
         // Establecer el mensaje de éxito en la sesión
         $_SESSION['mensaje'] = "Usuario agregado exitosamente";
        header("Location: registro.php");
    } else {
        echo "Error al registrar en clientes: " . $stmt_clientes->error;
    }
} else {
    echo "Error al registrar en usuarios: " . $stmt_usuarios->error;
}

// Cerrar las declaraciones y la conexión
$stmt_usuarios->close();
$stmt_clientes->close();
$conn->close();
?>
