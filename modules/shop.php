<?php
if(isset($_POST['idPr'])){
    include "connection.php";
    $code = 404;


    $idPr = $_POST['idPr'];
    $idKor = $_POST['idKor'];
    $upitDodaj = "INSERT INTO korpa (korisnikID, proizvodID, kupljeno) VALUES (:idKor, :idPr, 0)";
    $pripremaDodaj = $konekcija->prepare($upitDodaj);
    $pripremaDodaj -> bindParam(":idKor", $idKor);
    $pripremaDodaj -> bindParam(":idPr", $idPr);

    try{
        $code = $pripremaDodaj->execute() ? 201 : 500;
    } catch (PDOException $e){
        $code = 409;
    }

    http_response_code($code);
}


if(isset($_POST['idP'])){
    include "connection.php";
    $code = 404;


    $idPr = $_POST['idP'];
    $idKor = $_POST['idK'];
    $upitIzbaci = "DELETE FROM korpa WHERE korisnikID = :idKor AND proizvodID = :idPr AND kupljeno = 0 LIMIT 1";
    $pripremaIzbaci = $konekcija->prepare($upitIzbaci);
    $pripremaIzbaci -> bindParam(":idKor", $idKor);
    $pripremaIzbaci -> bindParam(":idPr", $idPr);

    try{
        $code = $pripremaIzbaci->execute() ? 204 : 500;
    } catch (PDOException $e){
        $code = 409;
    }

    http_response_code($code);
}


if(isset($_POST['idKupovina'])){
    include "connection.php";
    $code = 404;


    $idKupovina = $_POST['idKupovina'];
    $upitKupovina = "UPDATE korpa SET kupljeno = 1 WHERE korisnikID = :idKupovina";
    $pripremaKupovina = $konekcija->prepare($upitKupovina);
    $pripremaKupovina -> bindParam(":idKupovina", $idKupovina);

    try{
        $code = $pripremaKupovina->execute() ? 204 : 500;
    } catch (PDOException $e){
        $code = 409;
    }

    http_response_code($code);
}



if(empty($_POST['idPr']) and empty($_POST['idP']) and empty($_POST['idKupovina'])){
    header("Location: http://localhost/php1sajt/index.php");
}