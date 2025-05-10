<?php include 'includes/conexion.php'; ?>
<?php include 'includes/funciones.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros Existentes</title>
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

        .content {
            padding: 2rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .table-container {
            overflow-x: auto;
            margin: 2rem 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        .mini-map {
            height: 150px;
            width: 200px;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .coordinates {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            margin-top: 1rem;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            .container {
                border-radius: 0;
            }
            
            body {
                padding: 0;
            }
            
            th, td {
                padding: 0.8rem 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Registros Existentes</h1>
            <p>Listado completo de personas registradas</p>
        </header>

        <div class="content">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">
                    ¡Registro guardado correctamente!
                </div>
            <?php endif; ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Edad</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Coordenadas</th>
                            <th>Mapa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $sql = "SELECT p.*, d.calle_numero, d.colonia, d.estado, d.codigo_postal, d.latitud, d.longitud 
                                    FROM personas p
                                    LEFT JOIN direcciones d ON p.direccion_id = d.id
                                    ORDER BY p.fecha_registro DESC";
                            $stmt = $conn->query($sql);
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                                echo "<td>" . $row['edad'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                
                                // Dirección completa
                                echo "<td>";
                                if ($row['calle_numero']) {
                                    echo "<strong>" . htmlspecialchars($row['calle_numero']) . "</strong><br>";
                                    echo htmlspecialchars($row['colonia']) . "<br>";
                                    echo htmlspecialchars($row['estado']) . ", CP: " . 
                                        htmlspecialchars($row['codigo_postal']);
                                } else {
                                    echo "Sin dirección";
                                }
                                echo "</td>";
                                
                                // Coordenadas
                                echo "<td class='coordinates'>";
                                if ($row['latitud'] && $row['longitud']) {
                                    echo "Lat: " . number_format($row['latitud'], 6) . "<br>";
                                    echo "Lon: " . number_format($row['longitud'], 6);
                                } else {
                                    echo "Sin coordenadas";
                                }
                                echo "</td>";
                                
                                // Mini mapa
                                echo "<td>";
                                if ($row['latitud'] && $row['longitud']) {
                                    echo "<div class='mini-map' id='map-" . $row['id'] . "'></div>";
                                } else {
                                    echo "No disponible";
                                }
                                echo "</td>";
                                
                                echo "</tr>";
                            }
                        } catch(PDOException $e) {
                            echo "<tr><td colspan='8'>Error al obtener registros: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <a href="index.php" class="btn">Nuevo Registro</a>
            
            <div class="footer-links">
                <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Inicializar mini mapas para cada registro
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            // Volver a ejecutar la consulta para los mapas
            $stmt = $conn->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['latitud'] && $row['longitud']) {
                    echo "var miniMap = L.map('map-{$row['id']}', {
                        zoomControl: false,
                        dragging: false,
                        scrollWheelZoom: false,
                        doubleClickZoom: false,
                        boxZoom: false,
                        keyboard: false,
                        tap: false
                    }).setView([{$row['latitud']}, {$row['longitud']}], 15);";
                    
                    echo "L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(miniMap);";
                    
                    echo "L.marker([{$row['latitud']}, {$row['longitud']}]).addTo(miniMap);";
                }
            }
            ?>
        });
    </script>
</body>
</html>