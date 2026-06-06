<html lang="es">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de Gestión de Estudiantes - UTA</title>
        <script src="js/operaciones.js"></script>
        <link rel="stylesheet" type="text/css" href="jquery/themes/default/easyui.css">
        <link rel="stylesheet" href="css/estilo.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <?php require_once "controllers/controller.php" ?>
        <?php require_once "controllers/AuthController.php" ?>
    </head>
    <body>
        <header>
            <img src="imagenes/banner.png" />
        </header>
        
        <nav role="navigation" aria-label="Navegación principal">
            <ul>
                <li><a href="?accion=Inicio" aria-label="Ir a Inicio">Inicio</a></li>
                <li><a href="?accion=Nosotros" aria-label="Conocer sobre nosotros">Nosotros</a></li>
                <li><a href="?accion=Servicios" aria-label="Ver servicios disponibles">Servicios</a></li>
                <li><a href="?accion=Contactanos" aria-label="Contactar con nosotros">Contáctanos</a></li>
                <?php if (AuthController::esAdmin()): ?>
                <li><a href="?accion=Productos" aria-label="Administrar productos">Productos</a></li>
                <?php endif; ?>
                <?php if (AuthController::estaLogueado()): ?>
                <li><a href="?accion=ResumenFacturacion" aria-label="Total de Facturas Generadas">Total de Facturas Generadas</a></li>
                <?php endif; ?>
            </ul>
            <?php if (AuthController::estaLogueado()): ?>
            <p class="text-end small px-3 mb-0" style="color:#fff;">
                Sesión: <?= htmlspecialchars($_SESSION['usuario']) ?> (<?= htmlspecialchars($_SESSION['rol']) ?>)
            </p>
            <?php endif; ?>
        </nav>

        <article role="main">
            <?php
                $mvc = new EnlacesPaginaController();
                $mvc -> EnlacesPaginaController();
            ?>
        </article>

        <footer role="contentinfo">
            <p>Copyright 2025 - Todos los derechos reservados</p>
        </footer>
    </body>
</html>