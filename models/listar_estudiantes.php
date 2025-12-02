<?php
include_once("conexion.php");

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$estcedula = isset($_POST['estcedula']) ? $_POST['estcedula'] : '';

$offset = ($page - 1) * $rows;

// Construir la consulta con filtro opcional
if (!empty($estcedula)) {
    $sql = "SELECT * FROM estudiantes WHERE estcedula LIKE ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$estcedula%";
    $stmt->bind_param("sii", $searchTerm, $offset, $rows);
    
    $sqlCount = "SELECT COUNT(*) as total FROM estudiantes WHERE estcedula LIKE ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param("s", $searchTerm);
} else {
    $sql = "SELECT * FROM estudiantes LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $offset, $rows);
    
    $sqlCount = "SELECT COUNT(*) as total FROM estudiantes";
    $stmtCount = $conn->prepare($sqlCount);
}

// Ejecutar consulta de datos
$stmt->execute();
$result = $stmt->get_result();
$items = array();

while ($row = $result->fetch_assoc()) {
    array_push($items, $row);
}

// Ejecutar consulta de total
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$rowCount = $resultCount->fetch_assoc();

// Formato requerido por EasyUI DataGrid
$response = array(
    "total" => $rowCount['total'],
    "rows" => $items
);

echo json_encode($response);

$stmt->close();
$stmtCount->close();
$conn->close();
?>