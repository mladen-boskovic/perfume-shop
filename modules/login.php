<?php
session_start();
if(isset($_POST['loginDugme'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $loginKorIme = trim($_POST['loginKorIme']);
    $loginLozinka = md5($_POST['loginLozinka']);
    $reKorIme = "/^[\w\d\.\_]{5,15}$/";
    $loginGreske = [];

    if(!$loginKorIme){
        $loginGreske[] = "Polje za korisničko ime mora biti popunjeno";
    } else if(!preg_match($reKorIme, $loginKorIme)){
        $loginGreske[] = "Korisničko ime mora imati 5-15 karaktera";
    }
    if(!$_POST['loginLozinka']){
        $loginGreske[] = "Polje za lozinku mora biti popunjeno";
    } else if(strlen($_POST['loginLozinka']) < 6){
        $loginGreske[] = ("Lozinka mora imati bar 6 karaktera");
    }

    $korIme = addslashes($loginKorIme);

    if(count($loginGreske)){
        $code = 422;
        $data = $loginGreske;
    } else{
        $upit = "SELECT * FROM korisnik k INNER JOIN uloga u ON k.ulogaID=u.ulogaID WHERE korisnicko_ime = :korIme AND lozinka = :loginLozinka AND aktivan = 1";
        $priprema = $konekcija->prepare($upit);
        $priprema -> bindParam(":korIme", $korIme);
        $priprema -> bindParam(":loginLozinka", $loginLozinka);
        try{
            $priprema->execute();
            if($priprema -> rowCount() == 1){
                $korisnik = $priprema->fetch();
                $_SESSION['korisnik'] = $korisnik;
                $code = 200;
                $data = $_SESSION['korisnik']->uloga;
            } else{
                $code = 409;
            }
        } catch (PDOException $e){
            $code = 500;
        }
    }

    http_response_code($code);
    echo json_encode(['message' => $data]);
} else{
    header("Location: http://localhost/php1sajt/index.php");
}