<?php
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './mail/PHPMailer.php';
require './mail/Exception.php';
require './mail/SMTP.php';

$para = 'soluciones2000@gmail.com';
$nombre = 'Luis Rodríguez';
$asunto = "Asunto de prueba";
$cuerpo = "Cuerpo de prueba";

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer;
try {   
   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;              // Enable verbose debug output
   // $mail->SMTPDebug = 2;              // Enable verbose debug output
   $mail->isSMTP();                                    // Send using SMTP
   $mail->CharSet    = 'utf-8';           // Set the SMTP server to send through
   $mail->Host       = 'cash-flag.com';           // Set the SMTP server to send through
   $mail->SMTPAuth   = true;                           // Enable SMTP authentication
   $mail->SMTPKeepAlive = true;                        // SMTP connection will not close after each email sent
   $mail->Username   = 'info@cash-flag.com';             // SMTP username
   $mail->Password   = 'cash-flag123456**';                     // SMTP password
   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption;
   $mail->SMTPSecure = 'ssl'; // Enable TLS encryption;
   $mail->Port       = 465;                            // TCP port to connect to, use 465 for 

   //Recipients
   $mail->setFrom('info@cash-flag.com', 'Cash-Flag');
   $mail->addAddress($para, $nombre); // Add a recipient
   // $mail->addAddress('ellen@example.com');                 // Name is optional
   // $mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

   // Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

   // Content
   $mail->isHTML(true);                                  // Set email format to HTML
   $mail->Subject = $asunto;
   $mail->Body    = $cuerpo;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
   $mail->send();
   echo '<pre>';
   var_dump($mail);
   echo '</pre>';
} catch (Exception $e) {
   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}    // if (!$mail->send()) {
echo "The email message was sent.";



// if (cashflagemail($correo, 'Luis Rodríguez', $asunto, $mensaje)) {
//   echo "Éxito";
// } else {
//   echo "Fallo";
// }

?>
