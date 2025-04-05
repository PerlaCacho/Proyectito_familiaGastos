<?php
    include 'conexion.php';

    $error = "";
    $mensaje = "";
    $nombre = "";
    $tipo = "";
    $valor = "";

    if (isset($_POST['submit1'])) {
        $nombre = isset($_POST['txtNombre']) ? trim($_POST['txtNombre']) : "";
        $tipo = isset($_POST['cmbTipo']) ? trim($_POST['cmbTipo']) : "";
        $valor = isset($_POST['txtValor']) ? $_POST['txtValor'] : "";

        if (empty($nombre)) $error .= "El nombre es obligatorio.<br>";
        if (empty($tipo)) $error .= "Selecciona un tipo de gasto.<br>";
        if (!is_numeric($valor) || $valor <= 0) $error .= "El valor del gasto debe ser un número positivo.<br>";

        if (empty($error)) {
            $stmt = $conexion->prepare("INSERT INTO gastos (nombre, tipo, valor) VALUES (:nombre, :tipo, :valor)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':tipo' => $tipo,
                ':valor' => $valor
            ]);
            $mensaje = "Gasto registrado correctamente.";
            $nombre = $tipo = $valor = "";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Gastos Familiares</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3e5d0;
        }
        .bg-coffee {
            background-color: #d2b48c;
        }
        .table thead {
            background-color: #a67c52;
            color: white;
        }
        .btn-coffee {
            background-color: #8b5e3c;
            color: white;
            border: none;
        }
        .btn-coffee:hover {
            background-color: #70442b;
        }
        .card-coffee {
            background-color: #f8f0e3;
            border: 1px solid #d2b48c;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📋Gastos Familiares📋</h2>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($mensaje)) : ?>
        <div class="alert alert-success"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php" class="card card-coffee p-4 shadow-sm">
        <div class="mb-3">
            <label for="txtNombre" class="form-label">Persona</label>
            <input type="text" class="form-control" id="txtNombre" name="txtNombre" value="<?= htmlspecialchars($nombre) ?>">
        </div>
        <div class="mb-3">
            <label for="cmbTipo" class="form-label">Tipo de gasto</label>
            <select class="form-select" id="cmbTipo" name="cmbTipo">
                <option value="">Seleccionar</option>
                <option value="Alimentación" <?= $tipo == "Alimentación" ? 'selected' : '' ?>>Alimentación</option>
                <option value="Transporte" <?= $tipo == "Transporte" ? 'selected' : '' ?>>Transporte</option>
                <option value="Salud" <?= $tipo == "Salud" ? 'selected' : '' ?>>Salud</option>
                <option value="Educación" <?= $tipo == "Educación" ? 'selected' : '' ?>>Educación</option>
                <option value="Otros" <?= $tipo == "Otros" ? 'selected' : '' ?>>Otros</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtValor" class="form-label">Valor del gasto</label>
            <input type="number" class="form-control" id="txtValor" name="txtValor" step="0.01" value="<?= htmlspecialchars($valor) ?>">
        </div>
        <button type="submit" name="submit1" class="btn btn-coffee">Registrar Gasto</button>
    </form>

    <hr class="my-5">
    <h3 class="mb-3 text-center">Registro de Gastos</h3>

    <table class="table table-bordered shadow-sm">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo de Gasto</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conexion->query("SELECT nombre, tipo, valor FROM gastos ORDER BY id DESC");
            $total = 0;
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($fila['tipo']) . "</td>";
                echo "<td>$" . number_format($fila['valor'], 2) . "</td>";
                echo "</tr>";
                $total += $fila['valor'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="table-warning">
                <th colspan="2" class="text-end">Total acumulado:</th>
                <th>$<?= number_format($total, 2) ?></th>
            </tr>
        </tfoot>
    </table>

</div>

</body>
</html>
