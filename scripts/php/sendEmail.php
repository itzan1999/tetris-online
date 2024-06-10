<?php
  use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};
  
  // Clase que se encarga de enviar un correo electronico
  // Recibe la direccion de correo a la que enviar el correo, el asunto y el cuerpo de correo
  class Mailer {
    function sendEmail($email, $asunto, $cuerpo) {
      require_once $_SERVER['DOCUMENT_ROOT'] . "/tetris_online/config/config.php";
      require PROJECT_ROUTE . "lib/PHPMailer/src/PHPMailer.php";
      require PROJECT_ROUTE . "lib/PHPMailer/src/SMTP.php";
      require PROJECT_ROUTE . "lib/PHPMailer/src/Exception.php";

      $mail = new PHPMailer(true);
    
      try {
        // * Configuracion de cuenta email
        $mail -> SMTPDebug = SMTP::DEBUG_OFF; //SMTP::DEBUG_SERVER;
        $mail -> isSMTP();
        $mail -> Host = MAIL_HOST;
        $mail -> SMTPAuth = true;
        $mail -> Username = MAIL_USER;
        $mail -> Password = MAIL_PASS;
        $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail -> Port = MAIL_PORT;
    
        // * Correo del emisor y nombre
        $mail -> setFrom(MAIL_USER, 'TETRIS ONLINE');
        // Correo del receptor
        $mail -> addAddress($email);
    
        // * Estructura del corro
        $mail -> isHTML(true); // Formato del correo en HTML
        // * Asunto del correo
        $mail -> Subject = mb_convert_encoding($asunto, 'ISO-8859-1', 'UTF-8');
        // * Cueropo del correo
        $mail -> Body = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');
        
        // * Establecer lenguaje a PHPMailer
        $mail -> setLanguage('es', "../../lib/PHPMailer/language/phpmailer.lang-es.php");
        // * Enviar corrro
        if ($mail -> send()) {
          return true;
        } else {
          return false;
        }
      } catch(Exception $e) {
        echo "Error al enviar el correo electronico de activacion: {$mail -> ErrorInfo}";
        return false;
      }
      
    }
  }
?>