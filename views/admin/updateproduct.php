<?php
$rezultatBrend = null;
$upitBrend = "SELECT * FROM brend";
$pripremaBrend = $konekcija->prepare($upitBrend);
try{
    $pripremaBrend->execute();
    $rezultatBrend = $pripremaBrend->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

$rezultatMili = null;
$upitMili = "SELECT * FROM militraza";
$pripremaMili = $konekcija->prepare($upitMili);
try{
    $pripremaMili->execute();
    $rezultatMili = $pripremaMili->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

$idUpdateP = $_GET['idupdate'];
$rezultatProizvod = null;
$upitProizvod = "SELECT * FROM proizvod WHERE proizvodID = :idUpdateP";
$pripremaProizvod = $konekcija->prepare($upitProizvod);
$pripremaProizvod ->bindParam(":idUpdateP", $idUpdateP);
try{
    $pripremaProizvod->execute();
    $rezultatProizvod = $pripremaProizvod->fetch();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}
?>


<div id="register_naslov">
    <h1>Izmenite proizvod</h1>
</div>

<div id="register_sadrzaj">
    <div id="register_drzac">
        <form id="formaProizvod" name="formaProizvod" enctype="multipart/form-data" method="post" action="modules/admin.php">
            <table>
                <tr>
                    <td colspan="3">
                        <a href="#" data-id="<?= $rezultatProizvod->proizvodID ?>" class="obrisiProizvod obrisi">Obriši</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2"><img src="images/<?= stripslashes($rezultatProizvod->src) ?>" alt="<?= stripslashes($rezultatProizvod->alt) ?>"/></td>
                    <td>
                        <p>Naziv</p>
                        <textarea id="formaProizvodNazivUp" name="formaProizvodNazivUp" maxlength="60"><?= stripslashes($rezultatProizvod->proizvod) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Opis</p>
                        <textarea id="formaProizvodOpisUp" name="formaProizvodOpisUp" maxlength="150"><?= stripslashes($rezultatProizvod->opis) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Slika</p>
                        <input type="file" id="formaProizvodSlika" name="formaProizvodSlika"/>
                    </td>
                    <td>
                        <p id="formaProizvodCena_t">Cena</p><br/>
                        <input type="number" id="formaProizvodCena" name="formaProizvodCena" value="<?= $rezultatProizvod->cena ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Brend</p>
                        <select id="formaProizvodBrend" name="formaProizvodBrend">
                            <option value="0">Izaberite</option>
                            <?php foreach ($rezultatBrend as $brend): ?>
                                <?php if($rezultatProizvod->brendID == $brend->brendID): ?>
                                    <option value="<?= $brend->brendID ?>" selected><?= $brend->brend ?></option>
                                <?php else: ?>
                                    <option value="<?= $brend->brendID ?>"><?= $brend->brend ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <p id="formaProizvodPol_t">Pol</p><br/>
                        <select id="formaProizvodPol" name="formaProizvodPol">
                            <?php
                                $nizOptionP = ["Izaberite"=>"0", "Muški"=>"m", "Ženski"=>"z"];
                                foreach ($nizOptionP as $in => $vr): ?>
                                    <?php if($rezultatProizvod->pol == $vr): ?>
                                    <option value="<?= $vr ?>" selected><?= $in ?></option>
                                    <?php else: ?>
                                    <option value="<?= $vr ?>"><?= $in ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <p id="formaProizvodMilitraza_t">Militraža</p><br/>
                        <select id="formaProizvodMilitraza" name="formaProizvodMilitraza">
                            <option value="0">Izaberite</option>
                            <?php foreach ($rezultatMili as $mili): ?>
                                <?php if($rezultatProizvod->militrazaID == $mili->militrazaID): ?>
                                    <option value="<?= $mili->militrazaID ?>" selected><?= $mili->militraza ?>ml</option>
                                <?php else: ?>
                                    <option value="<?= $mili->militrazaID ?>"><?= $mili->militraza ?>ml</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><input type="submit" value="IZMENI PROIZVOD" id="formaProizvodDugmeUp" name="formaProizvodDugmeUp"/></td>
                </tr>
            </table>
            <input type="hidden" value="<?= $idUpdateP ?>" name="idUpdateP" id="idUpdateP"/>
        </form>
    </div>
</div>

<div id="reg_greske">

</div>