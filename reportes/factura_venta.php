<?php
session_start();
require_once __DIR__ . '/../fpdf186/fpdf.php';
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/VentaModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';

if (!AuthController::estaLogueado()) {
    die('Acceso no autorizado');
}

$ventaId = (int) ($_GET['venta_id'] ?? 0);
$datos = VentaModel::obtenerVentaCompleta($ventaId);

if (!$datos) {
    die('Venta no encontrada');
}

$venta = $datos['venta'];
$detalles = $datos['detalles'];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Factura de venta #' . $venta['id']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode('Fecha: ' . $venta['fecha']), 0, 1);
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 8, 'Producto', 1);
$pdf->Cell(25, 8, 'Cant.', 1, 0, 'C');
$pdf->Cell(35, 8, 'P. unitario', 1, 0, 'R');
$pdf->Cell(35, 8, 'Subtotal', 1, 0, 'R');
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
foreach ($detalles as $d) {
    $pdf->Cell(70, 8, utf8_decode($d['nombre']), 1);
    $pdf->Cell(25, 8, (string) $d['cantidad'], 1, 0, 'C');
    $pdf->Cell(35, 8, '$' . number_format((float) $d['precio_unitario'], 2), 1, 0, 'R');
    $pdf->Cell(35, 8, '$' . number_format((float) $d['subtotal'], 2), 1, 0, 'R');
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(130, 8, 'Total general', 1, 0, 'R');
$pdf->Cell(35, 8, '$' . number_format((float) $venta['total'], 2), 1, 0, 'R');
$pdf->Output('I', 'factura_' . $venta['id'] . '.pdf');
