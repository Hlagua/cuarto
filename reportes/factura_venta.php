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

// Definición de clase PDF institucional y moderna
class FacturaPDF extends FPDF
{
    private $ventaId;
    private $fecha;

    public function setVentaInfo($id, $fecha)
    {
        $this->ventaId = $id;
        $this->fecha = $fecha;
    }

    public function Header()
    {
        // Color burdeos de la UTA (120, 30, 36)
        $this->SetDrawColor(120, 30, 36);
        
        // Título de la Universidad
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(120, 30, 36);
        $this->Cell(115, 6, mb_convert_encoding('UNIVERSIDAD TÉCNICA DE AMBATO', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        
        // R.U.C. o Identificador de la tienda
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(120, 30, 36);
        $this->Cell(65, 6, 'R.U.C. 1890001402001', 0, 1, 'R');
        
        // Facultad
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(92, 93, 96);
        $this->Cell(115, 5, mb_convert_encoding('Facultad de Ingeniería en Sistemas, Electrónica e Industrial', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        
        // Caja de Factura (Título de sección)
        $x = $this->GetX();
        $y = $this->GetY();
        $this->SetXY($x, $y - 1);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColor(120, 30, 36);
        $this->Cell(65, 6, 'FACTURA', 0, 1, 'C', true);
        
        // Número de Factura
        $this->SetXY($x, $y + 5);
        $this->SetTextColor(120, 30, 36);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(65, 6, 'No. ' . str_pad($this->ventaId, 6, '0', STR_PAD_LEFT), 1, 1, 'C');
        
        // Dirección de la UTA y datos de contacto
        $this->SetXY(10, $y + 5);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(92, 93, 96);
        $this->Cell(115, 4, 'Av. Los Chasquis y Rio Payamino - Ambato, Ecuador', 0, 1, 'L');
        $this->Cell(115, 4, 'Telefono: (03) 2848487 - fisei.uta.edu.ec', 0, 1, 'L');
        
        $this->Ln(8);
        
        // Línea divisoria decorativa dorada
        $this->SetDrawColor(211, 156, 36); // #D39C24
        $this->SetLineWidth(0.6);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(4);
    }

    public function Footer()
    {
        $this->SetY(-25);
        // Línea divisoria
        $this->SetDrawColor(211, 156, 36);
        $this->SetLineWidth(0.4);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);
        
        // Lema y paginación
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(92, 93, 96);
        $this->Cell(0, 4, mb_convert_encoding('"Educarse es aprender a ser libres" — FISEI - UTA', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->Cell(0, 4, mb_convert_encoding('Pág. ' . $this->PageNo() . ' de {nb}', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }
}

// Inicialización de FPDF
$pdf = new FacturaPDF('P', 'mm', 'A4');
$pdf->setVentaInfo($venta['id'], $venta['fecha']);
$pdf->AliasNbPages();
$pdf->SetMargins(10, 15, 10);
$pdf->AddPage();

// Datos Informativos de la Factura (Cliente, Fecha, etc.)
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(120, 30, 36);
$pdf->Cell(20, 6, 'FECHA:', 0, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(80, 6, $venta['fecha'], 0, 0);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(120, 30, 36);
$pdf->Cell(20, 6, 'ESTADO:', 0, 0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(40, 167, 69); // Verde éxito
$pdf->Cell(70, 6, 'PAGADO', 0, 1);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(120, 30, 36);
$pdf->Cell(20, 6, 'CLIENTE:', 0, 0);
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(80, 6, mb_convert_encoding(strtoupper($_SESSION['usuario'] ?? 'Consumidor Final'), 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Ln(6);

// Encabezado de la Tabla
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(120, 30, 36); // Burdeos
$pdf->SetDrawColor(120, 30, 36);

$pdf->Cell(20, 8, 'ID PROD.', 1, 0, 'C', true);
$pdf->Cell(85, 8, mb_convert_encoding('DESCRIPCIÓN DEL PRODUCTO', 'ISO-8859-1', 'UTF-8'), 1, 0, 'L', true);
$pdf->Cell(20, 8, 'CANTIDAD', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'P. UNITARIO', 1, 0, 'R', true);
$pdf->Cell(35, 8, 'SUBTOTAL', 1, 1, 'R', fill: true);

// Filas de la Tabla (Alternando color)
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(226, 232, 240); // Gris claro

$fill = false;
foreach ($detalles as $d) {
    $pdf->SetFillColor(248, 249, 250); // Gris muy claro para filas alternas
    
    $pdf->Cell(20, 8, (string) $d['producto_id'], 'LBR', 0, 'C', $fill);
    $pdf->Cell(85, 8, mb_convert_encoding($d['nombre'], 'ISO-8859-1', 'UTF-8'), 'BR', 0, 'L', $fill);
    $pdf->Cell(20, 8, (string) $d['cantidad'], 'BR', 0, 'C', $fill);
    $pdf->Cell(30, 8, '$' . number_format((float) $d['precio_unitario'], 2), 'BR', 0, 'R', $fill);
    $pdf->Cell(35, 8, '$' . number_format((float) $d['subtotal'], 2), 'BR', 1, 'R', $fill);
    
    $fill = !$fill;
}

// Totales de la Factura
$pdf->SetDrawColor(120, 30, 36); // Volver al color principal para el cierre de totales

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(155, 7, 'SUBTOTAL', 'LBR', 0, 'R');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(35, 7, '$' . number_format((float) $venta['total'], 2), 'BR', 1, 'R');

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(155, 7, 'DESCUENTO', 'LBR', 0, 'R');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(35, 7, '$0.00', 'BR', 1, 'R');

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(120, 30, 36);
$pdf->Cell(155, 8, 'TOTAL A PAGAR', 'LBR', 0, 'R');
$pdf->SetTextColor(120, 30, 36);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 8, '$' . number_format((float) $venta['total'], 2), 'BR', 1, 'R');

// Salida del PDF
$pdf->Output('I', 'factura_' . $venta['id'] . '.pdf');