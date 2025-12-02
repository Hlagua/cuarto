<html lang="es">
    <head> 
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de Gestión de Estudiantes - UTA</title>
        <script src="js/operaciones.js"></script>
        <link rel="stylesheet" type="text/css" href="jquery/themes/default/easyui.css">
        <link rel="stylesheet" href="css/estilo.css" />
        <?php require_once "controllers/controller.php" ?>
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
            </ul>
        </nav>

        <article role="main">
            <?php
                $mvc = new EnlacesPaginaController();
                $mvc -> EnlacesPaginaController();
            ?>
        </article>

        <footer role="contentinfo">
            <p>Copyright 2024 - Todos los derechos reservados</p>
        </footer>
    </body>
</html>