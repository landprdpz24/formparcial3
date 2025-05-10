<?php include 'includes/conexion.php'; ?>
<?php include 'includes/funciones.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Datos Personales</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #4cc9f0;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            background-color: #f1f5f9;
            color: var(--dark);
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }

        .form-container {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
            background: var(--light);
            padding: 1.5rem;
            border-radius: var(--border-radius);
        }

        h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }

        input, select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .map-container {
            margin: 2rem 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        #map {
            height: 450px;
            width: 100%;
        }

        .coordinates-container {
            background: var(--dark);
            color: white;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .coordinates-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.8rem;
            border-radius: 6px;
            flex: 1;
            min-width: 200px;
        }

        .coordinates-label {
            font-size: 0.9rem;
            color: var(--success);
            margin-bottom: 0.3rem;
        }

        .coordinates-value {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                border-radius: 0;
            }
            
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Registro</h1>
            <p>Complete sus datos personales y ubicación</p>
        </header>

        <div class="form-container">
            <form action="guardar.php" method="post" id="mainForm">
                <section class="form-section">
                    <h2>Información Personal</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre">Nombre(s):</label>
                            <input type="text" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edad">Edad:</label>
                            <input type="number" id="edad" name="edad" required min="1" max="120">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h2>Ubicación Geográfica</h2>
                    <p>Seleccione su ubicación exacta en el mapa haciendo clic en el lugar correspondiente</p>

                    <div class="Buscar">
                        <label for="buscar">¿Prefiere buscar?</label>
                        <a href="buscar.php" target="_blank">Buscar Dirección</a></div>
                    
                    <div class="map-container">
                        <div id="map"></div>
                    </div>
                    
                    <div class="coordinates-container">
                        <div class="coordinates-box">
                            <div class="coordinates-label">Latitud:</div>
                            <div class="coordinates-value" id="latitud-display">No seleccionada</div>
                        </div>
                        <div class="coordinates-box">
                            <div class="coordinates-label">Longitud:</div>
                            <div class="coordinates-value" id="longitud-display">No seleccionada</div>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="calle_numero">Calle y Número:</label>
                            <input type="text" id="calle_numero" name="calle_numero" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="colonia">Colonia:</label>
                            <input type="text" id="colonia" name="colonia" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <input type="text" id="estado" name="estado" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal:</label>
                            <input type="text" id="codigo_postal" name="codigo_postal" required>
                        </div>
                    </div>
                </section>
                
                <input type="hidden" id="latitud" name="latitud">
                <input type="hidden" id="longitud" name="longitud">
                
                <button type="submit" class="btn btn-block">Guardar Registro</button>
            </form>
            
            <div class="footer-links">
                <a href="listar.php">Ver registros existentes</a>
                <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa centrado en México
        const map = L.map('map').setView([23.6345, -102.5528], 5);
        
        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker = null;
        let selectedLatLng = null;
        
        // Manejador de clic en el mapa
        map.on('click', function(e) {
            selectedLatLng = e.latlng;
            
            // Actualizar coordenadas mostradas
            document.getElementById('latitud-display').textContent = selectedLatLng.lat.toFixed(6);
            document.getElementById('longitud-display').textContent = selectedLatLng.lng.toFixed(6);
            
            // Actualizar campos ocultos
            document.getElementById('latitud').value = selectedLatLng.lat;
            document.getElementById('longitud').value = selectedLatLng.lng;
            
            // Mover/crear marcador
            if (marker) {
                marker.setLatLng(selectedLatLng);
            } else {
                marker = L.marker(selectedLatLng).addTo(map)
                    .bindPopup('Ubicación seleccionada').openPopup();
            }
            
            // Hacer reverse geocoding para obtener dirección aproximada
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${selectedLatLng.lat}&lon=${selectedLatLng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        document.getElementById('calle_numero').value = 
                            (data.address.road || '') + (data.address.house_number ? ' ' + data.address.house_number : '');
                        document.getElementById('colonia').value = 
                            data.address.neighbourhood || data.address.suburb || '';
                        document.getElementById('estado').value = 
                            data.address.state || '';
                        document.getElementById('codigo_postal').value = 
                            data.address.postcode || '';
                    }
                })
                .catch(error => {
                    console.error('Error en reverse geocoding:', error);
                });
        });
        
        // Opcional: Geolocalización del navegador
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const userLatLng = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLatLng, 15);
                    
                    // Simular clic en la ubicación del usuario
                    map.fire('click', {
                        latlng: L.latLng(userLatLng)
                    });
                },
                error => {
                    console.warn('Error obteniendo la ubicación:', error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        }
    </script>
</body>
</html>