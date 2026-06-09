<?php
// Rutas de JasperStarter
$jasperstarter = '/opt/java/openjdk8/bin/java -jar ../reportes/JasperStarter/lib/jasperstarter.jar';
$jasper = '../reportes/jrxml-jasper/reporte.jasper';

// CAMBIO CRUCIAL: Guardamos el PDF en la carpeta temporal de Linux
$output = '/tmp/reporte_estudiantes'; 
$jdbc   = '../reportes/JasperStarter/jdbc';

$DEBUG = true; // Déjalo en true para la última prueba

// Nos saltamos la compilación porque reporte.jasper ya existe en el servidor.
// PROCESAR REPORTE DIRECTAMENTE:
$cmdRun =
    "$jasperstarter process \"$jasper\" ".
    "-o \"$output\" -f pdf ".
    "-t mysql ".
    "-H kodama.proxy.rlwy.net ".
    "--db-port 43206 ".
    "-u root ".
    "-p \"pzhkIBEHOQnMtGvEROfMaAOAHVdfhwFF\" ".
    "-n railway ".
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

// ENVIAR EL PDF AL NAVEGADOR
$pdf = $output . ".pdf";

if (!file_exists($pdf)) {
    exit("<h3>❌ No se generó el PDF</h3>");
}

if (!$DEBUG) {
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=Reporte_Final.pdf");
    readfile($pdf);
    
    // Opcional: borrar el temporal después de mostrarlo para no llenar la memoria
    unlink($pdf);
    exit;
} else {
    echo "<h3>¡ÉXITO! PDF generado en: $pdf</h3>";
    echo "<p>Cambia \$DEBUG = false; para que el PDF se descargue o se muestre en pantalla automáticamente.</p>";
}
?>