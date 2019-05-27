<?php
$proizvod = null;
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

$korID = "";
if(isset($_SESSION['korisnik'])){
    $korID = $_SESSION['korisnik']->korisnikID;
}


?>

<div id="product_sadrzaj">
    <div id="product_drzac">
        <a href="images/<?= stripslashes($proizvod->src) ?>" class="vecaSlika"><img src="images/<?= stripslashes($proizvod->src) ?>" alt="<?= stripslashes($proizvod->alt) ?>"/></a>
        <div id="o_proizvodu">
            <p id="p_naziv"><?= stripslashes($proizvod->proizvod) ?></p>
            <p id="p_opis"><?= stripslashes($proizvod->opis) ?></p>
            <p id="p_dodatno"><?php echo $proizvod->pol=="m" ? "Muški parfem" : "Ženski perfem"; ?> - <?= $proizvod->brend ?> <?= $proizvod->militraza ?>ml</p>
            <p id="p_cena"><?= $proizvod->cena ?> din.</p>
            <?php if(isset($_SESSION['korisnik'])):
                if($_SESSION['korisnik']->uloga == "Admin"): ?>
                    <a href="index.php?page=admin&adminaction=updateproduct&idupdate=<?= $proizvod->proizvodID ?>" class="p_opcije">IZMENI PROIZVOD</a>
                    <?php else: ?>
                        <a href="#" data-id="<?= $proizvod->proizvodID ?>" class="p_opcije dodaj">DODAJ U KORPU</a>
                    <?php endif; ?>
            <?php else: ?>
                <a href="#" data-id="<?= $proizvod->proizvodID ?>" class="p_opcije dodaj">DODAJ U KORPU</a>
            <?php endif; ?>
            <input type="hidden" id="sessionIdKor" name="sessionIdKor" value="<?= $korID ?>"/>
        </div>

    </div>
</div>