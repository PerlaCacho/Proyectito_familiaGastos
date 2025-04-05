<?php
    include 'conexion.php';

    $error = "";
    $mensaje = "";
    $nombre = "";
    $tipo = "";
    $valor = "";

    if (isset($_REQUEST['submit1'])) {
        $nombre = isset($_REQUEST['txtNombre']) ? trim($_REQUEST['txtNombre']) : "";
        $tipo = isset($_REQUEST['cmbTipo']) ? trim($_REQUEST['cmbTipo']) : "";
        $valor = isset($_REQUEST['txtValor']) ? $_REQUEST['txtValor'] : "";

        // Validación de campos 
        if (empty($nombre)) {
            $error .= "El nombre es obligatorio.<br>";
        }

        if (empty($tipo)) {
            $error .= "Selecciona un tipo de gasto.<br>";
        }

        if (!is_numeric($valor) || $valor <= 0) {
            $error .= "El valor del gasto debe ser un número positivo.<br>";
        }

        // Si no hay errores, insertamos en la base de datos
        if (empty($error)) {
            $stmt = $conexion->prepare("INSERT INTO gastos (nombre, tipo, valor) VALUES (:nombre, :tipo, :valor)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':tipo' => $tipo,
                ':valor' => $valor
            ]);
            $mensaje = "Gasto registrado correctamente.";
            // Limpiar campos después del registro
            $nombre = "";
            $tipo = "";
            $valor = "";
        }
    }
?>