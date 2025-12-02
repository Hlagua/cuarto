        <link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<div class="uta-container">
    
    <h1 class="uta-title">Contáctanos</h1>
    <p class="uta-subtitle">Estamos ubicados en el Campus Huachi</p>

    <div class="uta-grid" style="align-items: flex-start;">

        <!-- Columna Izquierda: Información -->
        <div class="uta-card" style="flex: 1; background-color: #fcfcfc;">
            <h3 style="border-bottom: 2px solid #D39C24; padding-bottom: 10px; margin-bottom: 20px; display: inline-block;">
                Información Oficial
            </h3>

            <div style="margin-bottom: 30px;">
                <h4 style="color: #333; margin-bottom: 5px;">📍 Dirección</h4>
                <p style="margin: 0;">Av. Los Chasquis y Río Payamino<br>Ambato - Ecuador</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: #333; margin-bottom: 5px;">📞 Teléfonos</h4>
                <p style="margin: 0;">(03) 370-0090 <br> Ext. 8001 (Sistemas)</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: #333; margin-bottom: 5px;">✉️ Correo Electrónico</h4>
                <p style="margin: 0;">info@uta.edu.ec</p>
            </div>

            <!-- Mapa Embebido (Google Maps iframe) -->
            <div style="width: 100%; height: 250px; background: #ddd; margin-top: 20px;">
                 <iframe 
                    src="https://maps.google.com/maps?q=Universidad%20Tecnica%20de%20Ambato%20Campus%20Huachi&t=&z=16&ie=UTF8&iwloc=&output=embed" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>

        <!-- Columna Derecha: Formulario -->
        <div class="uta-card" style="flex: 1.5; border-top: 5px solid #781E24;">
            <h3>Envíanos un Mensaje</h3>
            <p>Completa el formulario y te responderemos a la brevedad posible.</p>

            <form action="#" method="POST">
                
                <div class="form-group">
                    <label class="uta-label">Nombre Completo:</label>
                    <input type="text" class="uta-input" placeholder="Ej: Juan Pérez">
                </div>

                <div class="form-group">
                    <label class="uta-label">Correo Institucional:</label>
                    <input type="email" class="uta-input" placeholder="usuario@uta.edu.ec">
                </div>

                <div class="form-group">
                    <label class="uta-label">Asunto:</label>
                    <input type="text" class="uta-input" placeholder="Motivo del contacto">
                </div>

                <div class="form-group">
                    <label class="uta-label">Mensaje:</label>
                    <textarea class="uta-textarea" rows="5" placeholder="Escribe aquí tu consulta..."></textarea>
                </div>

                <button type="submit" class="btn-uta "><i class="fa-solid fa-right-to-bracket"></i></button>

            </form>
        </div>

    </div>
</div>