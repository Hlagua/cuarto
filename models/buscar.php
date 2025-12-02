<?php
include "conexion.php";

// Obtener parámetros
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$estcedula = isset($_POST['estcedula']) ? trim($_POST['estcedula']) : '';

$offset = ($page - 1) * $rows;

// Búsqueda por cédula
if (!empty($estcedula)) {
    $sqlSelect = "SELECT * FROM estudiantes WHERE estcedula LIKE '%$estcedula%' LIMIT $offset, $rows";
    $sqlCount = "SELECT COUNT(*) as total FROM estudiantes WHERE estcedula LIKE '%$estcedula%'";
} else {
    $sqlSelect = "SELECT * FROM estudiantes LIMIT $offset, $rows";
    $sqlCount = "SELECT COUNT(*) as total FROM estudiantes";
}

// Obtener datos
$respuesta = $conn->query($sqlSelect);
$resultado = array();
if ($respuesta->num_rows > 0) {
    while ($fila = $respuesta->fetch_assoc()) {
        array_push($resultado, $fila);
    }
}

// Obtener total
$respuestaCount = $conn->query($sqlCount);
$total = 0;
if ($respuestaCount->num_rows > 0) {
    $filaCount = $respuestaCount->fetch_assoc();
    $total = $filaCount['total'];
}

// Respuesta en formato EasyUI
$response = array(
    "total" => intval($total),
    "rows" => $resultado
);

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>