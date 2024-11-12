<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tianguis";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    echo "Error de conexión: ".
     mysqli_connect_error();
}

//echo "Conexión exitosa";

// Ejemplo de consulta SQL
//$sql = "SELECT * FROM productores";
//$result = $conn->query($sql);

//if ($result->num_rows > 0) {
    // Procesar datos
//    while($row = $result->fetch_assoc()) {
//        echo "Campo: " . $row["nombre_productor"]. "<br>";
//    }
//} else {
//    echo "0 resultados";
//}


// Cerrar conexión
//$conn->close();
?>
