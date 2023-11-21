<?php

namespace Classes;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }


    public function enviarConfirmacion()
    {

        // crear el objeto de email
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['USER']; 
            $mail->Password = $_ENV['PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('wiernatimoteoweb@gmail.com', 'AppSalon');
            $mail->addAddress($this->email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirma tu Cuenta';

            // importante en el <a> caundo tengamos el dominio hay que colocarle el dominio
            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Has Creado tu cuenta en Appsalon, solo debes confirmarla en el siguiente enlace:</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignonar este mensaje.</p>";
            $contenido .= '</html>';

            $mail->Body = $contenido;

            $mail->send();
            echo 'El correo ha sido enviado con éxito';
        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }

    }

    public function enviarInstrucciones()
    {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['USER'];  
            $mail->Password = $_ENV['PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('wiernatimoteoweb@gmail.com', 'AppSalon');
            $mail->addAddress($this->email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Restablecer Contraseña';

            $contenido = "<html>";
            $contenido .= "<p><b>Hola " . $this->nombre . "</b> Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Restablecer Password</a> </p>";
            $contenido .= "<p>Si tu no solicistaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= "</html>";

            $mail->Body = $contenido;

            $mail->send();
            echo 'El correo ha sido enviado con éxito';
        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
            
        }
    }
}
