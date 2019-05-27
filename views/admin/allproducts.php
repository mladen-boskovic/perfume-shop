<?php
$rezultatAllProducts = null;
$upitAllProducts = "SELECT * FROM proizvod p INNER JOIN brend b on p.brendID = b.brendID INNER JOIN militraza m ON p.militrazaID = m.militrazaID";
$pripremaAllProducts = $konekcija->prepare($upitAllProducts);
try{
    $pripremaAllProducts -> execute();
    $rezultatAllProducts = $pripremaAllProducts->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}

?>

<div id="register_naslov">
    <h1>Svi proizvodi</h1>
</div>

<?php if(count($rezultatAllProducts)): ?>
<div id="allproducts_sadrzaj">
    <table class="tabela">
        <tr>
            <th>SLIKA</th>
            <th>NAZIV</th>
            <th>OPIS</th>
            <th>CENA</th>
            <th>POL</th>
            <th>BREND</th>
            <th>MILITRAŽA</th>
            <th>IZMENI</th>
            <th>OBRIŠI</th>
        </tr>
        <?php foreach ($rezultatAllProducts as $allproducts): ?>
            <tr>
                <td><a href="index.php?page=product&id=<?= $allproducts->proizvodID ?>"><img src="images/<?= stripslashes($allproducts->src) ?>" alt="<?= stripslashes($allproducts->alt) ?>" class="malaSlika"/></a></td>
                <td><?= stripslashes($allproducts->proizvod) ?></td>
                <td><?= stripslashes($allproducts->opis) ?></td>
                <td><?= $allproducts->cena ?> din.</td>
                <td><?= $allproducts->pol == 'm' ? "Muški" : 'Ženski'; ?></td>
                <td><?= $allproducts->brend ?></td>
                <td><?= $allproducts->militraza ?>ml</td>
                <td><a href="index.php?page=admin&adminaction=updateproduct&idupdate=<?= $allproducts->proizvodID ?>" class="detaljnijeD">Izmeni</a></td>
                <td><a href="#" data-id="<?= $allproducts->proizvodID ?>" class="obrisiProizvod obrisi">Obriši</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php else: ?>
    <div id="register_naslov">
        <h1>Trenutno nema proizvoda</h1>
    </div>
<?php endif; ?>
