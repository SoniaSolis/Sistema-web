<?php

   
//conexion a la base de datos 
require 'conexion.php';

 //Obtener datos del formulario
$correo = $_POST['correo'];
$password = $_POST['password'];
session_start();
$_SESSION['correo']=$correo;

// Consultar base de datos para verificar credenciales
//se colocan signo ? que son llamados marcadores de posicion previene inyecciones de SQL 
$sql = "SELECT * FROM usuarios WHERE correo = ?";
//preparar la consulta sirve para optimizar y la compilacion de datos 
$stmt = $conn->prepare($sql);
// ss --> ambos parametros son string y asocia los marcadores ? con los parametros correo password
$stmt->bind_param("s", $correo);
//ejecuta la consulta
$stmt->execute();
//obtiene los resultados de la consulta
$resultado = $stmt->get_result();
//fila de resultados como arreglo 
$filas = $resultado->fetch_assoc();

if ($filas) {
    if (password_verify($password, $filas['contrasena'])) {
        if ($filas['acceso'] == 1) { //administrador
        header("Location: abcproductor.php");
        exit();
        } elseif ($filas['acceso'] == 2) { //cliente
            //se agrego la linea 35 a 53
            // Obtener el id_usuario del usuario autenticado
            $id_usuario = $filas['id_usuario'];

            // Obtener el id_clientes asociado al id_usuario
            $stmt_clientes = $conn->prepare("SELECT id_clientes FROM clientes WHERE id_usuario = ?");
            $stmt_clientes->bind_param("i", $id_usuario);
            $stmt_clientes->execute();
            $resultado_clientes = $stmt_clientes->get_result();

            if ($resultado_clientes->num_rows > 0) {
                $cliente = $resultado_clientes->fetch_assoc();
                $_SESSION['usuario'] = $cliente;
                // Redirigir a perfilProductor.php
                header("Location: productores.php");
                exit();
            } else {
                // Manejo de error si no se encuentra el cliente asociado
                echo "No se encontró el cliente asociado.";
                exit();
            }
            
        } elseif ($filas['acceso'] == 3 ) { //Productor

            
            // Obtiene el id_usuario 
            $id_usuario = $filas['id_usuario'];

            // Obtener datos del productor usando id_usuario
            $stmt = $conn->prepare("SELECT * FROM productores WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado_productor = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($resultado_productor->num_rows > 0) {
                $productor = $resultado_productor->fetch_assoc();

                // Pasar datos a la sesión
                $_SESSION['productor'] = $productor;

                // Redirigir a perfilProductor.php
                header("Location: perfilProductorP.php");
                exit();
            } else {
                echo "No se encontraron resultados para el productor.";
            }
        } else {
            header("Location: registro.php?error=invalid_access");
            exit();
        }
    } else {
        header("Location: registro.php?error=invalid_credentials");
        exit();
    }
}else {
    header("Location: registro.php?error=invalid_credentials");
    exit();
}

//cierra la declaracion 
$stmt->close();
//cierra la conexión
$conn->close();
?>
