<?php
session_start();
include "modules/connection.php";
$page = "";
if(isset($_GET['page'])){
    $page = $_GET['page'];
}



include "views/head.php";
include "views/nav.php";
switch ($page){
    case "home" : include "views/home.php"; break;
    case "admin" : if($_SESSION['korisnik']->uloga == "Admin"){
        include "views/admin/admin.php"; break;
    } else{
        include "views/home.php"; break;
    }
    case "author" : include "views/author.php"; break;
    case "contact" : include "views/contact.php"; break;
    case "product" : include "views/product.php"; break;
    case "products" : include "views/products.php"; break;
    case "shop" : include "views/shop.php"; break;
    case "404" : include "views/404.php"; break;
    case "register" : if(isset($_SESSION['korisnik'])){
        include "views/home.php";
    } else {
        include "views/register.php";
    } break;
    case "login" : if(isset($_SESSION['korisnik'])){
        include "views/home.php";
    } else {
        include "views/login.php";
    } break;
    default : include "views/home.php"; break;
}
include "views/footer.php";