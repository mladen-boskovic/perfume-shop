<?php
include "functions/nav.php"
?>

<div id="nav2">
    <div id="nav_drzac">
        <a href="index.php"><img src="images/logo.png" alt="Logo"/></a>
        <div id="nav">
            <?php nav(); ?>



        </div>
    </div>
</div>



<?php if(isset($_SESSION['korisnik'])): ?>
    <?php if($_SESSION['korisnik']->uloga == "Admin"): ?>
        <a href="index.php?page=admin" id="adminLink">ADMIN</a>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($_SESSION['korisnik'])): ?>
<a href="index.php?page=shop" id="korpaLink"><img src="images/dodaj.png" alt="Korpa"/></a>
<?php endif; ?>



