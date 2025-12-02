<?php

/* ===================================================
   CONFIG
   =================================================== */

$jasperstarter = 'C:/JasperStarter36/bin/jasperstarter.bat';

$jrxml  = 'C:/Users/Daniel/Documents/NetBeansProjects/Proyecto/CuartoUtaP/src/reportes/reporte.jrxml';
$jasper = 'C:/Users/Daniel/Documents/NetBeansProjects/Proyecto/CuartoUtaP/src/reportes/reporte.jasper';

$output = 'C:/Users/Daniel/Downloads/reporte';
$jdbc   = 'C:/JasperStarter36/jdbc';

/* ===================================================
   ACTIVAR DEBUG? (true / false)
   =================================================== */

$DEBUG = false; // <-- CAMBIA A true SI QUIERES VER ERRORES


/* ===================================================
   1) COMPILAR
   =================================================== */

$cmdCompile = "\"$jasperstarter\" compile \"$jrxml\" -o \"" . dirname($jasper) . "\"";
$cmdCompileErr = $cmdCompile . " 2>&1";
exec($cmdCompileErr, $outCompile, $codeCompile);

if ($DEBUG) {
    echo "<h2>DEBUG COMPILACIÓN</h2><pre>";
    echo "CMD: $cmdCompileErr\n\n";
    print_r($outCompile);
    echo "CODE: $codeCompile";
    echo "</pre>";
}

if ($codeCompile !== 0) {
    exit("<h3>❌ ERROR: Falló compilación del JRXML</h3>");
}

/* ===================================================
   2) PROCESAR REPORTE
   =================================================== */

$cmdRun =
    "\"$jasperstarter\" process \"$jasper\" ".
    "-o \"$output\" -f pdf ".
    "-t mysql ".
    "-H localhost ".
    "-u root ".
    "-p \"\" ".
    "-n cuarto ".
    "--jdbc-dir \"$jdbc\"";

$cmdRunErr = $cmdRun . " 2>&1";
exec($cmdRunErr, $outRun, $codeRun);

if ($DEBUG) {
    echo "<h2>DEBUG EJECUCIÓN</h2><pre>";
    echo "CMD: $cmdRunErr\n\n";
    print_r($outRun);
    echo "CODE: $codeRun";
    echo "</pre>";
}

if ($codeRun !== 0) {
    exit("<h3>❌ ERROR: Falló ejecución del reporte</h3>");
}

/* ===================================================
   3) ENVIAR PDF
   =================================================== */

$pdf = $output . ".pdf";

if (!file_exists($pdf)) {
    exit("<h3>❌ No se generó el PDF</h3>");
}

if (!$DEBUG) {
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=reporte.pdf");
    readfile($pdf);
    exit;
} else {
    echo "<h3>PDF generado en: $pdf</h3>";
}

?>
