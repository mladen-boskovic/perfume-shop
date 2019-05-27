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
?>



<div id="register_naslov">
    <h1>Dodajte proizvod</h1>
</div>



<div id="register_sadrzaj">
    <div id="register_drzac">
        <form id="formaProizvod" name="formaProizvod" enctype="multipart/form-data" method="post" action="modules/admin.php">
            <table>
                <tr>
                    <td colspan="3">
                        <p>Naziv</p>
                        <textarea id="formaProizvodNaziv" name="formaProizvodNaziv" maxlength="60"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p>Opis</p>
                        <textarea id="formaProizvodOpis" name="formaProizvodOpis" maxlength="150"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Slika</p>
                        <input type="file" id="formaProizvodSlika" name="formaProizvodSlika"/>
                    </td>
                    <td>
                        <p id="formaProizvodCena_t">Cena</p><br/>
                        <input type="number" id="formaProizvodCena" name="formaProizvodCena"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Brend</p>
                        <select id="formaProizvodBrend" name="formaProizvodBrend">
                            <option value="0">Izaberite</option>
                            <?php foreach ($rezultatBrend as $brend): ?>
                            <option value="<?= $brend->brendID ?>"><?= $brend->brend ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <p id="formaProizvodPol_t">Pol</p><br/>
                        <select id="formaProizvodPol" name="formaProizvodPol">
                            <option value="0">Izaberite</option>
                            <option value="m">Muški</option>
                            <option value="z">Ženski</option>

                        </select>
                    </td>
                    <td>
                        <p id="formaProizvodMilitraza_t">Militraža</p><br/>
                        <select id="formaProizvodMilitraza" name="formaProizvodMilitraza">
                            <option value="0">Izaberite</option>
                            <?php foreach ($rezultatMili as $mili): ?>
                                <option value="<?= $mili->militrazaID ?>"><?= $mili->militraza ?>ml</option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><input type="submit" value="DODAJ PROIZVOD" id="formaProizvodDugme" name="formaProizvodDugme"/></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="reg_greske">

</div>