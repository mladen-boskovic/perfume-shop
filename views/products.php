<!------------------------>
<?php
$proizvodiNiz = null;
$pol_filter = "";
if(isset($_GET['gender'])){
    $pol_filter = $_GET['gender'];
}


$limit = 16; //broj proizvoda po stranici
$no_list = 5;
$max = get_max();
$tmp = $max / $limit;
$br_strana = intval($tmp) + 1;
if($no_list > $br_strana){
    $no_list = $br_strana;
} else if($no_list < 3){
    $no_list = 3;
}
if(isset($_GET['strana'])){
    $str = $_GET['strana'];
    $of = ($str-1) * $limit;
} else{
    $str = 1;
    $of = 0;
}


$l1 = $str-1;
$l2 = $str+1;


ispisi($limit, $of);


function shift($strane, $str, $list){
    global $pol_filter;
    $n1 = (int)round($list/2);
    $n2 = (int)($list/2);
    $od = $str-$n1;
    $do = $str+$n2;
    if($od<0) {
        $od = 0;
        $do = $od +$list;
    }
    if($do>$strane) {
        $do=$strane;
        $od = $strane-$list;
    }
    for ($i = $od; $i<$do; $i++){
        $b = $i+1;
        if($b > $strane ){
            continue;
        }
        switch ($pol_filter){
            case "m" : echo "<a href=\"index.php?page=products&strana=$b&gender=m\">$b</a> &nbsp;"; break;
            case "z" : echo "<a href=\"index.php?page=products&strana=$b&gender=z\">$b</a> &nbsp;"; break;
            default : echo "<a href=\"index.php?page=products&strana=$b\">$b</a> &nbsp;"; break;
        }
    }
}


function get_max(){
    global $konekcija;
    global $pol_filter;
    switch ($pol_filter){
        case "m" : $upit = "SELECT proizvodID FROM proizvod WHERE pol = :pol_filter"; break;
        case "z" : $upit = "SELECT proizvodID FROM proizvod WHERE pol = :pol_filter"; break;
        default : $upit = "SELECT proizvodID FROM proizvod"; break;
    }
    $priprema = $konekcija->prepare($upit);
    switch ($pol_filter){
        case "m" : $priprema -> bindParam(":pol_filter", $pol_filter); break;
        case "z" : $priprema -> bindParam(":pol_filter", $pol_filter); break;
        default : break;
    }
    try{
        $priprema->execute();
        $nizProizvoda = $priprema->fetchAll();
        $count = count($nizProizvoda);
    } catch (PDOException $e){
        echo "Došlo je do greške: " . $e->getMessage();
    }
    return $count;
}


function ispisi($limit, $ofs){
    global $konekcija;
    global $pol_filter;
    switch ($pol_filter){
        case "m" : $upit = "SELECT * FROM proizvod p INNER JOIN brend b on p.brendID = b.brendID INNER JOIN militraza m
             ON p.militrazaID = m.militrazaID WHERE pol = :pol_filter LIMIT :limit OFFSET :ofs"; break;
        case "z" : $upit = "SELECT * FROM proizvod p INNER JOIN brend b on p.brendID = b.brendID INNER JOIN militraza m
             ON p.militrazaID = m.militrazaID WHERE pol = :pol_filter LIMIT :limit OFFSET :ofs"; break;
        default : $upit = "SELECT * FROM proizvod p INNER JOIN brend b on p.brendID = b.brendID INNER JOIN militraza m
             ON p.militrazaID = m.militrazaID LIMIT :limit OFFSET :ofs"; break;
    }
    $priprema = $konekcija->prepare($upit);
    $priprema -> bindParam(":limit", $limit, PDO::PARAM_INT);
    $priprema -> bindParam(":ofs", $ofs, PDO::PARAM_INT);
    switch ($pol_filter){
        case "m" : $priprema -> bindParam(":pol_filter", $pol_filter); break;
        case "z" : $priprema -> bindParam(":pol_filter", $pol_filter); break;
        default : break;
    }
    try{
        $priprema->execute();
        global $proizvodiNiz;
        $proizvodiNiz = $priprema->fetchAll();
    } catch (PDOException $e){
        echo "Došlo je do greške: " . $e->getMessage();
    }
}

?>
<!------------------------>






<!------------------------>
<?php
$proizvodNaslov = "";
switch ($pol_filter){
    case "m" : $proizvodNaslov = "Muški parfemi"; break;
    case "z" : $proizvodNaslov = "Ženski parfemi"; break;
    default : $proizvodNaslov = "Svi parfemi"; break;
}
?>


<div id="hr"></div>
<div id="products_slika">

</div>




<div id="register_naslov">
    <h1><?= $proizvodNaslov ?></h1>
</div>


<div class="stranice_sadrzaj">
    <div class="ss_drzac">
            <div class="stranice">
                <?php

                switch ($pol_filter){
                    case "m" : if($l1 < 1){
                        echo "<a><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2&gender=m\">></a>";
                    } else if($l2 > $br_strana){
                        echo "<a href=\"index.php?page=products&strana=$l1&gender=m\"><</a> &nbsp;";
                        shift($br_strana, $str, $no_list);
                        echo "<a>></a>";
                    } else{
                        echo "<a href=\"index.php?page=products&strana=$l1&gender=m\"><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2&gender=m\">></a>";
                    } break;

                    case "z" : if($l1 < 1){
                        echo "<a><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2&gender=z\">></a>";
                    } else if($l2 > $br_strana){
                        echo "<a href=\"index.php?page=products&strana=$l1&gender=z\"><</a> &nbsp;";
                        shift($br_strana, $str, $no_list);
                        echo "<a>></a>";
                    } else{
                        echo "<a href=\"index.php?page=products&strana=$l1&gender=z\"><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2&gender=z\">></a>";
                    } break;

                    default : if($l1 < 1){
                        echo "<a><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2\">></a>";
                    } else if($l2 > $br_strana){
                        echo "<a href=\"index.php?page=products&strana=$l1\"><</a> &nbsp;";
                        shift($br_strana, $str, $no_list);
                        echo "<a>></a>";
                    } else{
                        echo "<a href=\"index.php?page=products&strana=$l1\"><</a> &nbsp;";
                        shift($br_strana, $str,$no_list);
                        echo "<a href=\"index.php?page=products&strana=$l2\">></a>";
                    } break;
                }

                ?>
            </div>
    </div>
</div>


<div id="proizvodi_sadrzaj">
    <div id="proizvodi_drzac">


        <div id="proizvodi">
            <?php foreach ($proizvodiNiz as $proizvod): ?>
                <div class="proizvod">
                        <img src="images/<?= stripslashes($proizvod->src) ?>" alt="<?= stripslashes($proizvod->alt) ?>"/>
                    <div class="detaljnije2">
                        <p class="ps_naziv"><?= stripslashes($proizvod->proizvod) ?></p>
                        <p class="ps_cena"><?= $proizvod->cena ?> din.</p>
                        <a href="index.php?page=product&id=<?= $proizvod->proizvodID ?>" class="detaljnije">DETALJNIJE</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<div class="stranice_sadrzaj">
    <div class="ss_drzac">
        <div class="stranice">
            <?php

            switch ($pol_filter){
                case "m" : if($l1 < 1){
                    echo "<a><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2&gender=m\">></a>";
                } else if($l2 > $br_strana){
                    echo "<a href=\"index.php?page=products&strana=$l1&gender=m\"><</a> &nbsp;";
                    shift($br_strana, $str, $no_list);
                    echo "<a>></a>";
                } else{
                    echo "<a href=\"index.php?page=products&strana=$l1&gender=m\"><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2&gender=m\">></a>";
                } break;

                case "z" : if($l1 < 1){
                    echo "<a><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2&gender=z\">></a>";
                } else if($l2 > $br_strana){
                    echo "<a href=\"index.php?page=products&strana=$l1&gender=z\"><</a> &nbsp;";
                    shift($br_strana, $str, $no_list);
                    echo "<a>></a>";
                } else{
                    echo "<a href=\"index.php?page=products&strana=$l1&gender=z\"><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2&gender=z\">></a>";
                } break;

                default : if($l1 < 1){
                    echo "<a><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2\">></a>";
                } else if($l2 > $br_strana){
                    echo "<a href=\"index.php?page=products&strana=$l1\"><</a> &nbsp;";
                    shift($br_strana, $str, $no_list);
                    echo "<a>></a>";
                } else{
                    echo "<a href=\"index.php?page=products&strana=$l1\"><</a> &nbsp;";
                    shift($br_strana, $str,$no_list);
                    echo "<a href=\"index.php?page=products&strana=$l2\">></a>";
                } break;
            }

            ?>
        </div>
    </div>
</div>

<!------------------------>