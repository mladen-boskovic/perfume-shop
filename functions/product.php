<?php
function product(){
    $proizvod = null;
    global $konekcija;
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $upit = "SELECT * FROM proizvod p INNER JOIN brend b on p.brendID = b.brendID INNER JOIN militraza m
             ON p.militrazaID = m.militrazaID WHERE proizvodID = :id";
        $priprema = $konekcija->prepare($upit);
        $priprema -> bindParam(":id", $id);
        try{
            $priprema -> execute();
            if($priprema->rowCount()){
                $proizvod = $priprema->fetch();
            } else{
                header("Location: http://localhost/php1sajt/index.php");
            }




        } catch (PDOException $e){
            echo "Došlo je do greške: " . $e->getMessage();
        }
    } else{
        header("Location: http://localhost/php1sajt/index.php");
    }
}
