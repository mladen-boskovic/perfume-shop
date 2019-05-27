<?php
session_start();
if(isset($_POST['idPitanja'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $idPitanja = $_POST['idPitanja'];
    $upitOdgovor = "SELECT * FROM odgovor WHERE pitanjeID = :idPitanja";
    $pripremaOdgovor = $konekcija->prepare($upitOdgovor);
    $pripremaOdgovor -> bindParam(":idPitanja", $idPitanja);
    try{
        $code = $pripremaOdgovor->execute() ? 200 : 500;
        $data = $pripremaOdgovor->fetchAll();
    } catch (PDOException $e){
        $code = 409;
    }




    http_response_code($code);
    echo json_encode(['message' => $data]);
}






if(isset($_POST['glasaj'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;


    $idOdgovora = $_POST['idOdgovora2'];
    $idPitanja = $_POST['idPitanja2'];
    $idKorisnika = $_SESSION['korisnik']->korisnikID;

    $upitProveri = "SELECT * FROM anketa WHERE pitanjeID = :idPitanja AND korisnikID = :idKorisnika";
    $pripremaProveri = $konekcija->prepare($upitProveri);
    $pripremaProveri -> bindParam(":idPitanja", $idPitanja);
    $pripremaProveri -> bindParam(":idKorisnika", $idKorisnika);
    try{
        $code = $pripremaProveri->execute() ? 200 : 500;
        if($pripremaProveri->rowCount() > 0){
            $code = 422;
        } else{
            $upitAnketa = "INSERT INTO anketa (pitanjeID, odgovorID, korisnikID) VALUES (:idPitanja, :idOdgovora, :idKorisnika)";
            $pripremaAnketa = $konekcija->prepare($upitAnketa);
            $pripremaAnketa -> bindParam(":idPitanja", $idPitanja);
            $pripremaAnketa -> bindParam(":idOdgovora", $idOdgovora);
            $pripremaAnketa -> bindParam(":idKorisnika", $idKorisnika);

            try{
                $code = $pripremaAnketa->execute() ? 201 : 500;
            } catch (PDOException $e){
                $code = 409;
            }
        }
    } catch (PDOException $e){
        $code = 409;
    }


    http_response_code($code);
    echo json_encode(['message' => $data]);
}


if(isset($_POST['dohvatiRezultate'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $pitanjeID = $_POST['pitanjeID'];
    $upitRezultati = "SELECT COUNT(a.korisnikID) AS broj_glasova, o.odgovor FROM anketa a INNER JOIN odgovor o ON a.odgovorID = o.odgovorID
                      WHERE a.pitanjeID = :pitanjeID GROUP BY o.odgovor";
    $pripremaRezultati = $konekcija->prepare($upitRezultati);
    $pripremaRezultati -> bindParam(":pitanjeID", $pitanjeID);

    try{
        $code = $pripremaRezultati->execute() ? 200 : 500;
        $rezultati = $pripremaRezultati->fetchAll();
        if($pripremaRezultati->rowCount() > 0){
            $data = $rezultati;
            $code = 200;
        } else{
            $code = 422;
        }
    } catch (PDOException $e){
        $code = 409;
    }






    http_response_code($code);
    echo json_encode(['message' => $data]);
}

if(empty($_POST['idPitanja']) and empty($_POST['glasaj']) and empty($_POST['dohvatiRezultate'])){
    header("Location: http://localhost/php1sajt/index.php");
}