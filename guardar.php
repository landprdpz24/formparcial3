<?php
include 'includes/conexion.php';
include 'includes/funciones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar datos personales
    $nombre = sanitizar($_POST['nombre']);
    $apellidos = sanitizar($_POST['apellidos']);
    $edad = sanitizar($_POST['edad']);
    $email = sanitizar($_POST['email']);
    
    // Sanitizar datos de dirección
    $calle_numero = sanitizar($_POST['calle_numero']);
    $colonia = sanitizar($_POST['colonia']);
    $estado = sanitizar($_POST['estado']);
    $codigo_postal = sanitizar($_POST['codigo_postal']);
    $latitud = sanitizar($_POST['latitud']);
    $longitud = sanitizar($_POST['longitud']);
    
    try {
        $conn->beginTransaction();
        
        // 1. Insertar dirección
        $sqlDireccion = "INSERT INTO direcciones 
                        (calle_numero, colonia, estado, codigo_postal, latitud, longitud) 
                        VALUES (:calle_numero, :colonia, :estado, :codigo_postal, :latitud, :longitud)";
        
        $stmtDireccion = $conn->prepare($sqlDireccion);
        $stmtDireccion->bindParam(':calle_numero', $calle_numero);
        $stmtDireccion->bindParam(':colonia', $colonia);
        $stmtDireccion->bindParam(':estado', $estado);
        $stmtDireccion->bindParam(':codigo_postal', $codigo_postal);
        $stmtDireccion->bindParam(':latitud', $latitud);
        $stmtDireccion->bindParam(':longitud', $longitud);
        $stmtDireccion->execute();
        
        $direccion_id = $conn->lastInsertId();
        
        // 2. Insertar persona
        $sqlPersona = "INSERT INTO personas 
                      (nombre, apellidos, edad, email, direccion_id) 
                      VALUES (:nombre, :apellidos, :edad, :email, :direccion_id)";
        
        $stmtPersona = $conn->prepare($sqlPersona);
        $stmtPersona->bindParam(':nombre', $nombre);
        $stmtPersona->bindParam(':apellidos', $apellidos);
        $stmtPersona->bindParam(':edad', $edad);
        $stmtPersona->bindParam(':email', $email);
        $stmtPersona->bindParam(':direccion_id', $direccion_id);
        $stmtPersona->execute();
        
        $conn->commit();
        
        // Redireccionar con mensaje de éxito
        header("Location: listar.php?success=1");
        exit();
    } catch(PDOException $e) {
        $conn->rollBack();
        
        // Mostrar página de error con diseño
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error en el Registro</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background-color: #f1f5f9;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    padding: 20px;
                }
                .error-container {
                    background: white;
                    padding: 2rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    max-width: 600px;
                    text-align: center;
                }
                h1 {
                    color: #dc3545;
                    margin-bottom: 1rem;
                }
                p {
                    margin-bottom: 1.5rem;
                    color: #212529;
                }
                .btn {
                    display: inline-block;
                    background: #4361ee;
                    color: white;
                    padding: 0.8rem 1.5rem;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 500;
                    transition: background 0.3s;
                }
                .btn:hover {
                    background: #3a0ca3;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>Error en el Registro</h1>
                <p>Ocurrió un error al intentar guardar tus datos. Por favor intenta nuevamente.</p>
                <p><strong>Detalle del error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?></p>
                <a href="index.php" class="btn">Volver al formulario</a>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>