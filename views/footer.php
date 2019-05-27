<?php
$rezultatFooter = null;
$upitFooter = "SELECT * FROM footer_navigacija";
$pripremaFooter = $konekcija->prepare($upitFooter);
try{
    $pripremaFooter -> execute();
    $rezultatFooter = $pripremaFooter->fetchAll();
} catch (PDOException $e){
    echo "Došlo je do greške: " . $e->getMessage();
}
?>

<div id="footer">
    <ul>
        <?php foreach ($rezultatFooter as $footer): ?>
            <?php if($footer->href == "index.php?page=author"): ?>
            <li><a href="<?= $footer->href ?>"><span class="<?= $footer->class ?>" aria-hidden="true"></span></a></li>
            <?php else: ?>
            <li><a href="<?= $footer->href ?>" target="_blank"><span class="<?= $footer->class ?>" aria-hidden="true"></span></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <p>Copyright &copy; Perfume Shop</p>
</div>

<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/simpleLightbox.min.js"></script>
</body>
</html>