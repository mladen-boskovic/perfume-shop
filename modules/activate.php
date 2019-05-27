<?php
if(isset($_GET['activate'])){
    $token = $_GET['activate'];
    include "connection.php";
    $upitSelect = "SELECT * FROM korisnik WHERE token = :token";
    $pripremaSelect = $konekcija->prepare($upitSelect);
    $pripremaSelect -> bindParam(":token", $token);

    try{
        $pripremaSelect -> execute();
        if($pripremaSelect -> rowCount() == 1){
            $korisnik = $pripremaSelect->fetch();
            if($korisnik->aktivan){
                echo "<h3>Nalog je već aktiviran</h3><h4><a href='http://localhost/php1sajt/index.php'>Početna stranica</a></h4>";
            } else{
                $upitUpdate = "UPDATE korisnik SET aktivan = 1 WHERE token = :token";
                $pripremaUpdate = $konekcija->prepare($upitUpdate);
                $pripremaUpdate -> bindParam(":token", $token);

                try{
                    $rezultatUpdate = $pripremaUpdate->execute();
                    if($rezultatUpdate){
                        echo "<h3>Uspešna aktivacija naloga!</h3><h4><a href='http://localhost/php1sajt/index.php?page=login'>Prijavite se</a></h4>";
                    } else {
                        echo "<h3>Došlo je do greške</h3><h4><a href='http://localhost/php1sajt/index.php'>Početna stranica</a></h4>";
                    }
                } catch (PDOException $e){
                    echo "<h3>Došlo je do greške: </h3>" . $e->getMessage() . "<h4><a href='http://localhost/php1sajt/index.php'>Početna stranica</a></h4>";
                }
            }
        } else{
            echo "<h3>Niste registrovani</h3><h4><a href='http://localhost/php1sajt/index.php&page=register'>Registrujte se</a></h4>";
        }
    } catch (PDOException $e){
        echo "<h3>Došlo je do greške: </h3>" . $e->getMessage() . "<h4><a href='http://localhost/php1sajt/index.php'>Početna stranica</a></h4>";
    }
} else{
    header("Location: http://localhost/php1sajt/index.php");
}