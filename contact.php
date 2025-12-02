<?php 
// Librerías necesarias para PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Requiere los archivos de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Verificar si se debe omitir la inserción en base de datos
$skipDB = isset($_POST['skip_db']) && $_POST['skip_db'] === 'true';

// Solo conectar a la base de datos si no se debe omitir
if (!$skipDB) {
    // Conexión a MySQL
    $host = "localhost"; // o el host que Webcindario te dé
    $user = "root"; // tu usuario MySQL
    $pass = ""; // tu contraseña MySQL
    $dbname = "anakonda"; // el nombre de tu BD

    // Crea conexión
    $conn = new mysqli($host, $user, $pass, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
}

// Verifica si se recibieron todos los campos del formulario
if (isset($_POST['dni'])    &&
	isset($_POST['nombres'])   &&
    isset($_POST['apellidos']) &&
    isset($_POST['email'])     &&
    isset($_POST['carrera'])   &&
    isset($_POST['ciclo'])) {
	
     // Captura los datos enviados por POST
	$dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $carrera = $_POST['carrera'];
    $ciclo = $_POST['ciclo'];
    $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : 'Sin comentarios';
    $correoDestino = isset($_POST['correo_destino']) ? $_POST['correo_destino'] : $email; // Si no hay destino, usar el mismo email

    // Asunto dinámico con carrera
    $subject = 'Confirmación de registro - ' . $carrera;
        
     // Validación del correo del remitente
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	echo json_encode(['error' => true, 'message' => 'Formato de correo inválido']);
        exit;
    }

    // Validación del correo de destino
    if (!filter_var($correoDestino, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => true, 'message' => 'Correo de destino no válido']);
        exit;
    }

    // Validación de campos obligatorios
    if (empty($dni) || empty($nombres) || empty($apellidos) || empty($email) || empty($carrera) || empty($ciclo)) {
        echo json_encode(['error' => true, 'message' => 'Todos los campos obligatorios deben ser completados']);
        exit;
    }

    // Inserta los datos en la base de datos solo si no se debe omitir
    if (!$skipDB) {
        $sql = "INSERT INTO usuarios_universitarios (dni, nombres, apellidos, correo, carrera, ciclo, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['error' => true, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("sssssss", $dni, $nombres, $apellidos, $email, $carrera, $ciclo, $comentarios);

        // Ejecuta la consulta
        if (!$stmt->execute()) {
            echo json_encode(['error' => true, 'message' => 'Error al guardar en la base de datos: ' . $stmt->error]);
            exit;
        }

        // Cierra la consulta y conexión
        $stmt->close();
        $conn->close();
    }

	// Crear una instancia de PHPMailer (con excepciones habilitadas)
	$mail = new PHPMailer(true);

	try {
        // Configuración del servidor SMTP de Gmail
	    $mail->isSMTP();
	    $mail->CharSet = 'UTF-8';  // Establecer codificación UTF-8
	    $mail->Encoding = 'base64';  // Codificación base64 para caracteres especiales
	    $mail->Host = 'smtp.gmail.com'; 
	    $mail->SMTPAuth   = true;
	    $mail->Username= 'lfernandez@isil.pe'; //correo Gmail
	    $mail->Password = 'emytqsmfmcfdqkfe'; //contraseña de aplicación de Gmail
	    $mail->SMTPSecure = "ssl";          
	    $mail->Port       = 465;                                  
	    
        // Establece el remitente (persona que llena el formulario)
	    $mail->setFrom('lfernandez@isil.pe', $nombres . ' ' . $apellidos);  
        
        // Establece el destinatario (correo dinámico)
        $mail->addAddress($correoDestino); 

         // Configura el contenido del correo
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';  // Asegurar UTF-8 para el contenido                             
        $mail->Subject = $subject;

        // Contenido HTML del correo con HTML ytabla
        $mail->Body = '
        <html>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        </head>
        <body>
        <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #ffffff; color: #000;">

        <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 20px; text-align: center;">
            ¡Bienvenido ' . $nombres . ' ' . $apellidos . '!
        </h2>
        <p style="font-size: 15px;">
            Gracias por registrarte en la <strong>Universidad Autónoma del Perú</strong>. Tu carrera <strong>' . $carrera . '</strong> te espera. ¡Seguiremos mejorando para ti!
        </p>
        <p style="font-size: 15px;">
            A continuación, confirmamos tus datos:
        </p>

        <div style="max-width: 700px; margin: auto; border: 1px solid #ccc; border-radius: 6px; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; table-layout: auto; word-wrap: break-word; font-family: Arial, sans-serif;">
            <thead>
                <tr style="background-color: #00B0F0; color: white; text-align: left; font-size: 15px;">
                <th style="padding: 12px 10px; border: 1px solid #ccc;">DNI</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Nombres</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Apellidos</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Correo</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Carrera</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Ciclo</th>
                <th style="padding: 12px 10px; border: 1px solid #ccc;">Comentarios</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td style="padding: 10px; border: 1px solid #ddd;">' . $dni . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . $nombres . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . $apellidos . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;"><a href="mailto:' . $email . '">' . $email . '</a></td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . $carrera . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . $ciclo . '</td>
                <td style="padding: 10px; border: 1px solid #ddd;">' . ($comentarios ?: 'Sin comentarios') . '</td>
                </tr>
            </tbody>
            </table>

            <p style="margin-top: 30px; text-align: center;">
            Tu registro ha sido completado exitosamente.<br>
            <strong>Equipo REGISTRO - Universidad Autónoma del Perú</strong>
            </p>

            <div style="background-color: #667eea; color: white; text-align: center; padding: 15px; font-size: 20px; font-weight: bold;">
            REGISTRO
            </div>
        </div>
        </div>
        </body>
        </html>';

        // Envía el correo
	    $mail->send();

        // Responder con JSON si viene desde la API
        if ($skipDB) {
            echo json_encode(['success' => true, 'message' => 'Correo enviado correctamente']);
        } else {
            // Redirecciona con mensaje de éxito (comportamiento antiguo)
            $sm= 'Mensaje enviado correctamente';
            header("Location: registro.php?success=$sm");
        }
		exit;
	} catch (Exception $e) {
        // Si hay error en el envío de correo
        if ($skipDB) {
            echo json_encode(['error' => true, 'message' => "Error al enviar correo: {$mail->ErrorInfo}"]);
        } else {
            $em = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location: registro.php?error=$em");
        }
		exit;
	}

}