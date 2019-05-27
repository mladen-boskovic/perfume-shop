<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';


if(isset($_POST['regDugme'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $regIme = trim($_POST['regIme']);
    $regPrezime = trim($_POST['regPrezime']);
    $regEmail = trim($_POST['regEmail']);
    $regKorIme = trim($_POST['regKorIme']);
    $regLozinka = md5($_POST['regLozinka']);
    $regLozinka2 = md5($_POST['regLozinka2']);

    $reImePrezime = "/^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/";
    $reEmail = "/^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/";
    $reKorIme = "/^[\w\d\.\_]{5,15}$/";
    $regGreske = [];

    if(!$regIme){
        $regGreske[] = "Polje za ime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regIme)){
        $regGreske[] = "Ime nije u dobrom formatu";
    }
    if(!$regPrezime){
        $regGreske[] = "Polje za prezime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regPrezime)){
        $regGreske[] = "Prezime nije u dobrom formatu";
    }
    if(!$regEmail){
        $regGreske[] = "Polje za email mora biti popunjeno";
    } else if(!preg_match($reEmail, $regEmail)){
        $regGreske[] = "Email nije u dobrom formatu";
    }
    if(!$regKorIme){
        $regGreske[] = "Polje za korisničko ime mora biti popunjeno";
    } else if(!preg_match($reKorIme, $regKorIme)){
        $regGreske[] = "Korisničko ime mora imati 5-15 karaktera";
    }
    if(!$_POST['regLozinka']){
        $regGreske[] = "Polje za lozinku mora biti popunjeno";
    } else if(strlen($_POST['regLozinka']) < 6){
        $regGreske[] = "Lozinka mora imati bar 6 karaktera";
    }
    if(!$_POST['regLozinka2']){
        $regGreske[] = "Polje za ponovljenu lozinku mora biti popunjeno";
    } else if(strlen($_POST['regLozinka2']) < 6){
        $regGreske[] = "Ponovljena lozinka mora imati bar 6 karaktera";
    }
    if($_POST['regLozinka'] !== $_POST['regLozinka2']){
        $regGreske[] = "Lozinka i ponovljena lozinka se ne poklapaju";
    }

    $ime = addslashes($regIme);                 // ADDSLASHES
    $prezime = addslashes($regPrezime);
    $email = addslashes($regEmail);
    $korIme = addslashes($regKorIme);


    if(count($regGreske)){
        $code = 422;
        $data = $regGreske;
    } else{
        $token = md5(sha1($email . $korIme . time()));
        $datum_registracije = time();
        $upit = "INSERT INTO korisnik (ime, prezime, email, korisnicko_ime, lozinka, ulogaID, token, datum_registracije)
                 VALUES (:ime, :prezime, :email, :korIme, :lozinka, (SELECT ulogaID FROM uloga WHERE uloga = 'Korisnik'), :token, :datum_registracije)";
        $priprema = $konekcija->prepare($upit);
        $priprema -> bindParam(":ime", $ime);
        $priprema -> bindParam(":prezime", $prezime);
        $priprema -> bindParam(":email", $email);
        $priprema -> bindParam(":korIme", $korIme);
        $priprema -> bindParam(":lozinka", $regLozinka);
        $priprema -> bindParam(":token", $token);
        $priprema -> bindParam(":datum_registracije", $datum_registracije);

        try{
            $code = $priprema->execute() ? 201 : 500;
            if($code == 201){
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
                    $mail->setFrom('auditorne.php@gmail.com', 'Perfume Shop');
                    $mail->addAddress($email, $ime . " " . $prezime);     // Add a recipient
                    //$mail->addAddress('ellen@example.com');                           // Name is optional
                    //$mail->addReplyTo('info@example.com', 'Information');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Aktivacija naloga';
                    $mail->Body    = 'Verifikacija email-a i aktivacija naloga: <a href="http://localhost/php1sajt/modules/activate.php?activate='. $token .'" target="_blank">KLIKNITE OVDE</a>';
                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                    //echo 'Message has been sent';
                    $code = 200;
                } catch (Exception $e) {
                    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    $code = 500;
                }
            }
        } catch (PDOException $e){
            $code = 409;
        }
    }

    http_response_code($code);
    echo json_encode(['message' => $data]);
} else{
    header("Location: http://localhost/php1sajt/index.php");
}