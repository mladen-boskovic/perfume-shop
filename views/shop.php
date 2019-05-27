<?php
if(!isset($_SESSION['korisnik'])){
    header("Location: http://localhost/php1sajt/index.php");
} else{
    $idKor = $_SESSION['korisnik']->korisnikID;
    $uloga = $_SESSION['korisnik']->uloga;
    $upitDohvatiKorpu = "";
    switch ($uloga){
        case "Korisnik" : $upitDohvatiKorpu = "SELECT * FROM korisnik k INNER JOIN korpa krp ON k.korisnikID = krp.korisnikID
                         INNER JOIN proizvod p ON krp.proizvodID = p.proizvodID WHERE k.korisnikID = :idKor AND kupljeno = 0"; break;
        case "Admin" : $upitDohvatiKorpu = "SELECT * FROM korisnik k INNER JOIN korpa krp ON k.korisnikID = krp.korisnikID
                         INNER JOIN proizvod p ON krp.proizvodID = p.proizvodID WHERE kupljeno = 0"; break;
    }

    $pripremaDohvatiKorpu = $konekcija->prepare($upitDohvatiKorpu);
    $pripremaDohvatiKorpu -> bindParam(":idKor", $idKor);
    try{
        $pripremaDohvatiKorpu->execute();
        $korpa = $pripremaDohvatiKorpu->fetchAll();
    } catch (PDOException $e){
        echo "Došlo je do greške: " . $e->getMessage();
    }

}

if(!isset($_SESSION['korisnik'])){
    header("Location: http://localhost/php1sajt/index.php");
} else{
    $idKor2 = $_SESSION['korisnik']->korisnikID;
    $uloga2 = $_SESSION['korisnik']->uloga;
    $upitDohvatiKorpu2 = "";

    switch ($uloga2){
        case "Korisnik" :  $upitDohvatiKorpu2 = "SELECT * FROM korisnik k INNER JOIN korpa krp ON k.korisnikID = krp.korisnikID
                         INNER JOIN proizvod p ON krp.proizvodID = p.proizvodID WHERE k.korisnikID = :idKor2 AND kupljeno = 1"; break;
        case "Admin" : $upitDohvatiKorpu2 = "SELECT * FROM korisnik k INNER JOIN korpa krp ON k.korisnikID = krp.korisnikID
                         INNER JOIN proizvod p ON krp.proizvodID = p.proizvodID WHERE kupljeno = 1"; break;
    }

    $pripremaDohvatiKorpu2 = $konekcija->prepare($upitDohvatiKorpu2);
    $pripremaDohvatiKorpu2 -> bindParam(":idKor2", $idKor2);
    try{
        $pripremaDohvatiKorpu2->execute();
        $korpa2 = $pripremaDohvatiKorpu2->fetchAll();
    } catch (PDOException $e){
        echo "Došlo je do greške: " . $e->getMessage();
    }
}


$idKor3 = $_SESSION['korisnik']->korisnikID;
$upitSuma = "SELECT SUM(cena) as suma FROM korisnik k INNER JOIN korpa krp ON k.korisnikID = krp.korisnikID
             INNER JOIN proizvod p ON krp.proizvodID = p.proizvodID WHERE k.korisnikID = :idKor3 AND kupljeno = 0";
$pripremaSuma = $konekcija->prepare($upitSuma);
$pripremaSuma->bindParam(":idKor3", $idKor3);
try{
    $pripremaSuma->execute();
    $suma = $pripremaSuma->fetch();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

?>




    <div id="hr"></div>

<div id="register_naslov">
    <h1>U korpi</h1>
</div>


<?php if(count($korpa)): ?>
    <div id="shop_sadrzaj">
        <table class="tabela_shop">
            <tr>
                <th>SLIKA</th>
                <th>NAZIV</th>
                <th>CENA</th>
                <?php if($uloga == "Korisnik"): ?>
                    <th>IZBACI IZ KORPE</th>
                <?php endif;?>
            </tr>


            <?php foreach ($korpa as $k): ?>
                <tr>
                    <td><a href="index.php?page=product&id=<?= $k->proizvodID ?>"><img src="images/<?= $k->src ?>" alt="<?= $k->alt ?>" class="malaSlika"/><a/></td>
                    <td><?= $k->proizvod ?></td>
                    <td><?= $k->cena ?> din.</td>
                    <?php if($uloga == "Korisnik"): ?>
                        <td><a href="#" data-id="<?= $k->proizvodID ?>" class="izbaciIzKorpe obrisi">Izbaci iz korpe</a></td>
                    <?php endif; ?>
                    <input type="hidden" name="idKorIzbaci" id="idKorIzbaci" value="<?= $k->korisnikID ?>"/>
                </tr>
            <?php endforeach; ?>
            <?php if($uloga == "Korisnik"): ?>
                <tr>
                    <td colspan="4">
                        <h3>Ukupno: <?= $suma->suma ?> din.</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <a href="#" data-id="<?= $k->korisnikID ?>" class="kupiDugme">KUPI</a>
                    </td>
                </tr>
            <?php endif;?>
        </table>
    </div>
<?php else: ?>
    <div id="register_naslov">
        <?php if($uloga == "Korisnik"): ?>
            <h3>Vaša korpa je prazna</h3><br/>
        <?php else: ?>
            <h3>Korpa je prazna</h3><br/>
        <?php endif; ?>
        <img src="images/korpa.png" alt="Prazna korpa" class="korpaS"/>
        <img src="images/korpa2.png" alt="Prazna korpa" class="korpaS"/>
        <br/><br/>
    </div>
<?php endif; ?>



<div id="register_naslov">
    <h1>Kupljeno</h1>
</div>


<?php if(count($korpa2)): ?>
    <div id="shop_sadrzaj2">
        <table class="tabela_shop">
            <tr>
                <th>SLIKA</th>
                <th>NAZIV</th>
                <th>CENA</th>
            </tr>


            <?php foreach ($korpa2 as $k2): ?>
                <tr>
                    <td><a href="index.php?page=product&id=<?= $k2->proizvodID ?>"><img src="images/<?= $k2->src ?>" alt="<?= $k2->alt ?>" class="malaSlika"/><a/></td>
                    <td><?= $k2->proizvod ?></td>
                    <td><?= $k2->cena ?> din.</td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php else: ?>
    <div id="register_naslov">
        <?php if($uloga == "Korisnik"): ?>
            <h3>Ništa niste kupili za sada</h3><br/><br/><br/>
        <?php else: ?>
            <h3>Ništa nije kupljeno za sada</h3><br/><br/><br/>
        <?php endif; ?>
    </div>
<?php endif; ?>