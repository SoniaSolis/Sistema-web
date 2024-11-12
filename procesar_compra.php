<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de registro si no ha iniciado sesión
    header("Location: registro.php");
    exit();
}

// Obtén el ID del usuario desde la sesión
$id_cliente = $_SESSION['usuario']['id_clientes']; // 'id_cliente' esta en la sesión

// Verifica si el carrito existe en la sesión
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo 'El carrito está vacío. No se puede procesar la compra.';
    exit();
}

// Conexión a la base de datos
require 'conexion.php';


// Inserta la información del carrito en la base de datos
foreach ($_SESSION['carrito'] as $producto) {
    $id_producto = $producto['id_producto'];
    $cantidad = $producto['cantidad'];
    $total = $producto['precio'] * $cantidad;
    $fecha = date('Y-m-d H:i:s'); // Fecha y hora actuales
    
    
    // Inserta el registro en la base de datos
    $sql = "INSERT INTO carrito (fecha, cantidad, total, id_producto, id_clientes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiis", $fecha, $cantidad, $total, $id_producto, $id_cliente);

    if (!$stmt->execute()) {
        echo "Error al procesar la compra: " . $stmt->error;
        exit();
    }
}

// Redirige al usuario a una página de confirmación
header("Location: confirmar_compra.php"); // Crea una página de confirmación para mostrar que la compra fue exitosa
exit();

?>