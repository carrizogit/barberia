<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();

        //configurar SMPT
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls'; //viajen seguros  los email Trasnport leg segurity 
        $mail->Port = $_ENV['EMAIL_PORT'];

        //configurar el contenido del msj
        $mail->setFrom('admin@bienes.com'); //quien envia el email
        $mail->addAddress($this->email, 'Appsalon'); //a qu email va llegar ese corrreo
        $mail->Subject = 'Tienes un nuevo mensaje'; //el mensaje que vallega en el tituolo del mjs
        
        //habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> creaste tu cuenta en AppSalon, presiona el siguinte enlace para confirmarla</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['DOMINIO_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
        
    }

    public function enviarInstrucciones() {
        
        $mail = new PHPMailer(); //crea el objeto de email

        //configurar SMPT
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls'; //viajen seguros  los email Trasnport leg segurity 
        $mail->Port = $_ENV['EMAIL_PORT'];

        //configurar el contenido del msj
        $mail->setFrom('admin@bienes.com'); //quien envia el email
        $mail->addAddress($this->email, 'Appsalon'); //a qu email va llegar ese corrreo
        $mail->Subject = 'Restablece tu contraseña'; //el mensaje que vallega en el tituolo del mjs
        
        //habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Solicitaste restablecer tu contraseña, sigue el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['DOMINIO_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer Contraseña</a></p>";
        $contenido .= "<p>Si no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }
}