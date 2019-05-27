<?php
$rezultatUloga = null;
$upitUloga = "SELECT * FROM uloga";
$pripremaUloga = $konekcija->prepare($upitUloga);
try{
    $pripremaUloga -> execute();
    $rezultatUloga = $pripremaUloga->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

$idUpdate = $_GET['idupdate'];
$rezultatUpdateUser = null;
$upitUpdateUser = "SELECT * FROM korisnik WHERE korisnikID = :idUpdate";
$pripremaUpdateUser = $konekcija->prepare($upitUpdateUser);
$pripremaUpdateUser -> bindParam(":idUpdate", $idUpdate);
try{
    $pripremaUpdateUser -> execute();
    $rezultatUpdateUser = $pripremaUpdateUser->fetch();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

?>

<div id="register_naslov">
    <h1>Izmenite korisnika</h1>
</div>

<div id="register_sadrzaj">
    <div id="register_drzac">
        <form id="forma_register" name="forma_register">
            <table id="tabela_register">
                <tr>
                    <td colspan="2">
                        <a href="#" data-id="<?= $rezultatUpdateUser->korisnikID ?>" class="obrisiKorisnika obrisi">Obriši</a>
                    </td>
                </tr>
                <tr>
                    <td>Ime</td>
                    <td>Prezime</td>
                </tr>
                <tr>
                    <td><input type="text" id="regIme" name="regIme" value="<?= stripslashes($rezultatUpdateUser->ime); ?>" placeholder="" autocomplete="on" class="inputi"/></td>
                    <td><input type="text" id="regPrezime" name="regPrezime" value="<?= stripslashes($rezultatUpdateUser->prezime); ?>" placeholder="" autocomplete="on" class="inputi"/></td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td>Korisničko ime</td>
                </tr>
                <tr>
                    <td><input type="text" id="regEmail" name="regEmail" value="<?= stripslashes($rezultatUpdateUser->email); ?>" placeholder="" autocomplete="on" class="inputi"/></td>
                    <td><input type="text" id="regKorIme" name="regKorIme" value="<?= stripslashes($rezultatUpdateUser->korisnicko_ime); ?>" placeholder="" autocomplete="on" class="inputi"/></td>
                </tr>

                <tr>
                    <td>Uloga</td>
                    <td id="add_aktivan_t">Aktivan</td>
                </tr>
                <tr>
                    <td>
                        <select id="add_uloga" name="add_uloga">
                            <option value="0">Izaberite</option>
                            <?php foreach($rezultatUloga as $uloga): ?>
                                <?php if($rezultatUpdateUser->ulogaID == $uloga->ulogaID): ?>
                                <option value="<?= $uloga->ulogaID ?>" selected><?= $uloga->uloga ?></option>
                                <?php else: ?>
                                <option value="<?= $uloga->ulogaID ?>"><?= $uloga->uloga ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select id="add_aktivan" name="add_aktivan">
                            <?php
                            $nizOption = ['Izaberite', 'Da', 'Ne'];
                            $br = 2;
                                foreach($nizOption as $option): ?>
                                    <?php if($rezultatUpdateUser->aktivan == $br): ?>
                                        <option value="<?= $br ?>" selected><?= $option ?></option>
                                    <?php else: ?>
                                        <option value="<?= $br ?>"><?= $option ?></option>
                                    <?php endif; ?>
                                    <?php $br--;
                                    endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" id="updateUserDugme" name="updateUserDugme" value="IZMENI KORISNIKA" class="inputi"/></td>
                </tr>

            </table>
            <input type="hidden" value="<?= $idUpdate ?>" name="idUpdate" id="idUpdate"/>
        </form>
    </div>
</div>

<div id="reg_greske">

</div>