<?php
$rezultatAllUsers = null;
$upitAllUsers = "SELECT * FROM korisnik k INNER JOIN uloga u ON k.ulogaID = u.ulogaID";
$pripremaAllUsers = $konekcija->prepare($upitAllUsers);
try{
    $pripremaAllUsers -> execute();
    $rezultatAllUsers = $pripremaAllUsers->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

?>

<div id="register_naslov">
    <h1>Svi korisnici</h1>
</div>


<?php if(count($rezultatAllUsers)): ?>
<div id="allusers_sadrzaj">
    <table class="tabela">
        <tr>
            <th>IME</th>
            <th>PREZIME</th>
            <th>EMAIL</th>
            <th>KORISNIČKO IME</th>
            <th>ULOGA</th>
            <th>AKTIVAN</th>
            <th>IZMENI</th>
            <th>OBRIŠI</th>
        </tr>
        <?php foreach ($rezultatAllUsers as $allusers): ?>
            <tr>
                <td><?= stripslashes($allusers->ime) ?></td>
                <td><?= stripslashes($allusers->prezime) ?></td>
                <td><?= stripslashes($allusers->email) ?></td>
                <td><?= stripslashes($allusers->korisnicko_ime) ?></td>
                <td><?= $allusers->uloga ?></td>
                <td><?= $allusers->aktivan == 0 ? "Ne" : "Da"; ?></td>
                <td><a href="index.php?page=admin&adminaction=updateuser&idupdate=<?= $allusers->korisnikID ?>" class="detaljnijeD">Izmeni</a></td>
                <td><a href="#" data-id="<?= $allusers->korisnikID ?>" class="obrisiKorisnika obrisi">Obriši</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php else: ?>
<div id="register_naslov">
    <h1>Trenutno nema korisnika</h1>
</div>
<?php endif; ?>
