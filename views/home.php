<?php
$upitSlajder = "SELECT * FROM slajder";
$pripremaSlajder = $konekcija->prepare($upitSlajder);
try{
    $pripremaSlajder->execute();
    $slajder = $pripremaSlajder->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}
$br = 1;


$upitHomePr = "SELECT COUNT(k.proizvodID) as kupljen_broj, p.proizvod, p.cena, p.src, p.alt, p.proizvodID FROM korpa k
               INNER JOIN proizvod p ON k.proizvodID = p.proizvodID GROUP BY k.proizvodID ORDER BY kupljen_broj DESC LIMIT 0,4";
$pripremaHomePr = $konekcija->prepare($upitHomePr);
try{
    $pripremaHomePr->execute();
    $homePr = $pripremaHomePr->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}





$upitPitanje = "SELECT * FROM pitanje WHERE aktivno = 1";
$pripremaPitanje = $konekcija->prepare($upitPitanje);
try{
    $pripremaPitanje->execute();
    $rezPitanje = $pripremaPitanje->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}



$upitOdgovor = "SELECT * FROM odgovor";
$pripremaOdgovor = $konekcija->prepare($upitOdgovor);
try{
    $pripremaOdgovor->execute();
    $rezOdgovor = $pripremaOdgovor->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}
?>


<div id="hr"></div>


<div id="slajder">
    <img src="images/slajder1.jpg" alt="Slajder1" class="trenutna"/>
    <?php foreach ($slajder as $s): ?>
        <img src="images/<?= $s->src ?>.jpg" alt="Slajder<?= ++$br ?>"/>
    <?php endforeach; ?>
</div>

<div id="home_naslov">
    <h1>Najprodavaniji parfemi</h1>
</div>

<div id="proizvodi_sadrzaj">
    <div id="proizvodi_drzac">


        <div id="proizvodi">
            <?php foreach ($homePr as $proizvod): ?>
                <div class="proizvod">
                    <img src="images/<?= stripslashes($proizvod->src) ?>" alt="<?= stripslashes($proizvod->alt) ?>"/>
                    <div class="detaljnije2">
                        <p class="ps_naziv"><?= stripslashes($proizvod->proizvod) ?></p>
                        <p class="ps_cena"><?= $proizvod->cena ?> din.</p>
                        <a href="index.php?page=product&id=<?= $proizvod->proizvodID ?>" class="detaljnije">DETALJNIJE</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<div id="home_muski_zenski">
    <table>
        <tr>
            <td>
                <a href="index.php?page=products&gender=z"><img src="images/woman.jpg"/></a>
            </td>
            <td>
                <a href="index.php?page=products&gender=m"><img src="images/man.jpg"/></a>
            </td>
        </tr>
    </table>
</div>



<!-- ANKETA -->
<?php if(isset($_SESSION['korisnik'])): ?>
    <?php if(count($rezPitanje)): ?>
        <div id="ankete">
            <h3>Kratka anketa</h3><br/>
            <p>
                <select id="selectAnketa">
                    <option value="0">Izaberite pitanje</option>
                    <?php foreach ($rezPitanje as $pitanje): ?>
                        <option value="<?= $pitanje->pitanjeID ?>"><?= $pitanje->pitanje ?></option>
                    <?php endforeach; ?>
                </select>
            </p>



        </div>
    <?php endif; ?>
<?php endif; ?>



<div id="odgovori">

</div>

<div id="glasajDugmeDiv">
    <p><input type='button' value='Glasaj' name='glasaj' id='glasaj'/></p>
    <p><input type="button" name="rezultatiDugme" id="rezultatiDugme" value="Rezultati"/></p>
</div>

<div id="izaberiteOdgovor">

</div>