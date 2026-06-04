<?php
include "conexion.php";

$page = intval($_POST['page'] ?? 1);
$rows = intval($_POST['rows'] ?? 10);
$cedula = trim($_POST['cedula'] ?? '');
$offset = ($page - 1) * $rows;

$where = $cedula ? "WHERE cedula LIKE '%$cedula%'" : "";
$select = $conn->query("SELECT * FROM estudiantes $where LIMIT $offset,$rows");
$count  = $conn->query("SELECT COUNT(*) AS total FROM estudiantes $where")->fetch_assoc()['total'];

echo json_encode(["total" => intval($count), "rows" => $select->fetch_all(MYSQLI_ASSOC)]);
$conn->close();
