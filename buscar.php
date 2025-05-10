<?php include 'includes/conexion.php'; ?>
<?php include 'includes/funciones.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Direcciones</title>
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

        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .search-input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        .search-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .map-container {
            margin: 2rem 0;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        #map {
            height: 500px;
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

        .address-details {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: var(--border-radius);
        }

        .address-details h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .address-details p {
            margin-bottom: 0.5rem;
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
            .search-form {
                flex-direction: column;
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
            <h1>Buscador de Direcciones</h1>
            <p>Encuentra ubicaciones en el mapa</p>
        </header>

        <div class="form-container">
            <section class="form-section">
                <h2>Buscar Dirección</h2>
                
                <form id="searchForm" class="search-form">
                    <input type="text" id="addressInput" class="search-input" 
                           placeholder="Ej: Av. Reforma 123, CDMX" required>
                    <button type="submit" class="btn">Buscar</button>
                </form>
                
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
                
                <div class="address-details" id="addressDetails">
                    <h3>Detalles de la Dirección</h3>
                    <p>Busca una dirección para ver los detalles aquí</p>
                </div>
            </section>
            
            <div class="footer-links">
                <a href="index.php">Volver al formulario</a>
                <a href="listar.php">Ver registros</a>
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
        
        // Manejador de búsqueda de dirección
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const address = document.getElementById('addressInput').value;
            
            if (address.trim() === '') return;
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&countrycodes=MX`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const result = data[0];
                        selectedLatLng = [parseFloat(result.lat), parseFloat(result.lon)];
                        
                        // Centrar mapa en la ubicación encontrada
                        map.setView(selectedLatLng, 16);
                        
                        // Actualizar coordenadas mostradas
                        document.getElementById('latitud-display').textContent = selectedLatLng[0].toFixed(6);
                        document.getElementById('longitud-display').textContent = selectedLatLng[1].toFixed(6);
                        
                        // Mover/crear marcador
                        if (marker) {
                            marker.setLatLng(selectedLatLng);
                        } else {
                            marker = L.marker(selectedLatLng).addTo(map)
                                .bindPopup('Ubicación encontrada').openPopup();
                        }
                        
                        // Obtener detalles de la dirección
                        return fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${selectedLatLng[0]}&lon=${selectedLatLng[1]}`);
                    } else {
                        throw new Error('Dirección no encontrada');
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        // Mostrar detalles de la dirección
                        const addressDetails = document.getElementById('addressDetails');
                        addressDetails.innerHTML = `
                            <h3>Detalles de la Dirección</h3>
                            ${data.address.road ? `<p><strong>Calle:</strong> ${data.address.road} ${data.address.house_number || ''}</p>` : ''}
                            ${data.address.neighbourhood ? `<p><strong>Colonia:</strong> ${data.address.neighbourhood}</p>` : 
                              data.address.suburb ? `<p><strong>Colonia:</strong> ${data.address.suburb}</p>` : ''}
                            ${data.address.city ? `<p><strong>Ciudad:</strong> ${data.address.city}</p>` : ''}
                            ${data.address.state ? `<p><strong>Estado:</strong> ${data.address.state}</p>` : ''}
                            ${data.address.postcode ? `<p><strong>Código Postal:</strong> ${data.address.postcode}</p>` : ''}
                            ${data.address.country ? `<p><strong>País:</strong> ${data.address.country}</p>` : ''}
                        `;
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    console.error('Error en la búsqueda:', error);
                });
        });
        
        // Manejador de clic en el mapa
        map.on('click', function(e) {
            selectedLatLng = e.latlng;
            
            // Actualizar coordenadas mostradas
            document.getElementById('latitud-display').textContent = selectedLatLng.lat.toFixed(6);
            document.getElementById('longitud-display').textContent = selectedLatLng.lng.toFixed(6);
            
            // Mover/crear marcador
            if (marker) {
                marker.setLatLng(selectedLatLng);
            } else {
                marker = L.marker(selectedLatLng).addTo(map)
                    .bindPopup('Ubicación seleccionada').openPopup();
            }
            
            // Obtener detalles de la dirección
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${selectedLatLng.lat}&lon=${selectedLatLng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        const addressDetails = document.getElementById('addressDetails');
                        addressDetails.innerHTML = `
                            <h3>Detalles de la Dirección</h3>
                            ${data.address.road ? `<p><strong>Calle:</strong> ${data.address.road} ${data.address.house_number || ''}</p>` : ''}
                            ${data.address.neighbourhood ? `<p><strong>Colonia:</strong> ${data.address.neighbourhood}</p>` : 
                              data.address.suburb ? `<p><strong>Colonia:</strong> ${data.address.suburb}</p>` : ''}
                            ${data.address.city ? `<p><strong>Ciudad:</strong> ${data.address.city}</p>` : ''}
                            ${data.address.state ? `<p><strong>Estado:</strong> ${data.address.state}</p>` : ''}
                            ${data.address.postcode ? `<p><strong>Código Postal:</strong> ${data.address.postcode}</p>` : ''}
                            ${data.address.country ? `<p><strong>País:</strong> ${data.address.country}</p>` : ''}
                        `;
                        
                        // Actualizar el campo de búsqueda
                        const addressParts = [];
                        if (data.address.road) addressParts.push(data.address.road);
                        if (data.address.house_number) addressParts.push(data.address.house_number);
                        if (data.address.city) addressParts.push(data.address.city);
                        if (data.address.state) addressParts.push(data.address.state);
                        
                        document.getElementById('addressInput').value = addressParts.join(', ');
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