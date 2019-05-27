<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if(isset($_POST['contactDugme'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $contactEmail = trim($_POST['contactEmail']);
    $poruka = $_POST['poruka'];
    $reEmail = "/^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/";
    $contactGreske = [];


    if(!$contactEmail){
        $contactGreske[] = "Polje za email mora biti popunjeno";
    } else if(!preg_match($reEmail, $contactEmail)){
        $contactGreske[] = "Email nije u dobrom formatu";
    }

    if(!$poruka){
        $contactGreske[] = "Polje za poruku mora biti popunjeno";
    } else if(strlen($poruka)<15 or strlen($poruka)>200){
        $contactGreske[] = "Poruka mora imati 15-200 karaktera";
    }


    if(count($contactGreske)){
        $code = 422;
        $data = $contactGreske;
    } else{
        $mail = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                 // Enable verbose debug output
            /*$mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );*/
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'auditorne.php@gmail.com';            // SMTP username
            $mail->Password = 'Sifra123';                    // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('auditorne.php@gmail.com', $contactEmail);
            $mail->addAddress("mladenregistracije@gmail.com");     // Add a recipient
            //$mail->addAddress('ellen@example.com');                           // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Poruka za admina';
            $mail->Body    = "$poruka<br/><br/>Email: $contactEmail";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
            $code = 200;
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            $code = 500;
        }

    }

    http_response_code($code);
    echo json_encode(['message' => $data]);
} else{
    header("Location: http://localhost/php1sajt/index.php");
}
