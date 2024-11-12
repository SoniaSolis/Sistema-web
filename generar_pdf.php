<?php
session_start();
require 'conexion.php';
require ('fpdf186/fpdf.php');

// Verificar si el carrito está en la sesión
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "El carrito está vacío.";
    exit;
}

// Obtener los datos del carrito
$carrito = $_SESSION['carrito'];
$productosPorProductor = [];

// Obtener información de la base de datos para cada producto en el carrito
foreach ($carrito as $item) {
    $id_producto = $item['id_producto'];
    $query = "SELECT p.*, pr.nombre_productor, pr.ubicacion 
              FROM productos p 
              JOIN productores pr ON p.id_productor = pr.id_productor 
              WHERE p.id_producto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
        $id_productor = $producto['id_productor'];

        // Agrupar productos por productor
        if (!isset($productosPorProductor[$id_productor])) {
            $productosPorProductor[$id_productor] = [
                'nombre_productor' => $producto['nombre_productor'],
                'ubicacion' => $producto['ubicacion'],
                'productos' => [],
                'total' => 0
            ];
        }

        $producto['cantidad'] = $item['cantidad'];
        $productosPorProductor[$id_productor]['productos'][] = $producto;
        $productosPorProductor[$id_productor]['total'] += $producto['precio'] * $item['cantidad'];
    }
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Confirmar Compra', 0, 1, 'C');
$pdf->Ln(10);

foreach ($productosPorProductor as $id_productor => $infoProductor) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, $infoProductor['nombre_productor'], 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Ubicacion: ' . $infoProductor['ubicacion'], 0, 1);
    $pdf->Ln(5);

    foreach ($infoProductor['productos'] as $producto) {
        $pdf->Cell(0, 10, $producto['nombre_producto'] . ' - Cantidad: ' . $producto['cantidad'] . ' - Precio: $' . number_format($producto['precio'], 2), 0, 1);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Total: $' . number_format($infoProductor['total'], 2), 0, 1);
    $pdf->Ln(10);
}

$pdf->Output('I', 'confirmar_compra.pdf');
?>
