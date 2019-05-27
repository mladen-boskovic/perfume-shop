<!DOCTYPE html>
<html>
<head>
    <title>
        <?php
        switch ($page){
            case "home" : echo "Početna"; break;
            case "products" : echo "Parfemi"; break;
            case "product" : echo "Proizvod"; break;
            case "contact" : echo "Kontakt"; break;
            case "login" : echo "Prijava"; break;
            case "register" : echo "Registracija"; break;
            case "admin" : echo "Admin Panel"; break;
            case "author" : echo "Autor"; break;
            case "shop" : echo "Korpa"; break;
            case "404" : echo "Stranica ne postoji"; break;
            default : echo "Početna"; break;
        }
        ?>
    </title>
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <meta charset="UTF-8">
    <meta name="author" content="Mladen Bošković"/>
    <meta name="description" content="Perfume Shop"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/simpleLightbox.min.css"/>

</head>
<body>