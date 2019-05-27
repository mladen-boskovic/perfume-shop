<?php
if(!isset($_SESSION['korisnik'])){
    header("Location: http://localhost/php1sajt/index.php");
} else{
    if($_SESSION['korisnik']->uloga != "Admin"){
        header("Location: http://localhost/php1sajt/index.php");
    }
}

$adminPage = null;
if(isset($_GET['adminaction'])) {
    $adminPage = $_GET['adminaction'];
}

switch ($adminPage){
    case "adduser" : include "adminmenu.php"; include "adduser.php"; break;
    case "allusers" : include "adminmenu.php"; include "allusers.php"; break;
    case "updateuser" : include "adminmenu.php"; include "updateuser.php"; break;
    case "allproducts" : include "adminmenu.php"; include "allproducts.php"; break;
    case "addproduct" : include "adminmenu.php"; include "addproduct.php"; break;
    case "updateproduct" : include "adminmenu.php"; include "updateproduct.php"; break;
    default : include "adminhome.php"; break;
}
