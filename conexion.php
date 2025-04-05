<?php
try {
    $conexion = new PDO(
        "mysql:host=127.0.0.1;dbname=familiagastos;charset=utf8",
        "root",
        ""
    );
    // Mostrar errores en PDO
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
