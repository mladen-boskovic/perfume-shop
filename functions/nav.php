<?php
function nav(){
    global $konekcija;
    function navigacija($roditelj){
        global $konekcija;
        $upitNav = "SELECT * FROM navigacija WHERE roditelj = :roditelj";
        $pripremaNav = $konekcija->prepare($upitNav);
        $pripremaNav -> bindParam(":roditelj", $roditelj);
        try{
            $pripremaNav->execute();
            $rezultatNav = $pripremaNav->fetchAll();
            if ($pripremaNav->rowCount() > 0){
                echo "<ul>";
            }
            foreach($rezultatNav as $nav){
                echo "<li><a href='".$nav->href."'>".$nav->navigacija."</a>";
                navigacija($nav->navigacijaID);
                echo "</li>";
            }
            if ($pripremaNav->rowCount() > 0){
                echo "</ul>";
            }
        } catch (PDOException $e){
            echo "Došlo je do greške: " . $e->getMessage();
        }
    }


    $upitLinkovi = "SELECT * FROM navigacija WHERE roditelj = 0";
    $pripremaLinkovi = $konekcija->prepare($upitLinkovi);
    try{
        $pripremaLinkovi -> execute();
        $rezultatLinkovi = $pripremaLinkovi->fetchAll();

        echo "<ul id='navigacija'>";
        foreach($rezultatLinkovi as $link){
            if(isset($_SESSION['korisnik']) and ($link->navigacija == "PRIJAVI SE" or $link->navigacija == "REGISTRACIJA")){
                continue;
            }
            if(!isset($_SESSION['korisnik']) and $link->navigacija == "ODJAVI SE"){
                continue;
            }

            echo "<li><a href='".$link->href."'>".$link->navigacija."</a>";
            navigacija($link->navigacijaID);
            echo "</li>";
        }
        echo "</ul>";
    } catch (PDOException $e){
        echo "Došlo je do greške: " . $e->getMessage();
    }
}

