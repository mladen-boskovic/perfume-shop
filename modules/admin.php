<?php
/*-----INSERT USER-----*/
if(isset($_POST['addUserDugme'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $regIme = trim($_POST['regIme']);
    $regPrezime = trim($_POST['regPrezime']);
    $regEmail = trim($_POST['regEmail']);
    $regKorIme = trim($_POST['regKorIme']);
    $regLozinka = md5($_POST['regLozinka']);
    $regLozinka2 = md5($_POST['regLozinka2']);
    $add_uloga = $_POST['add_uloga'];
    $add_aktivan = $_POST['add_aktivan'];

    $reImePrezime = "/^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/";
    $reEmail = "/^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/";
    $reKorIme = "/^[\w\d\.\_]{5,15}$/";
    $regGreske = [];

    if(!$regIme){
        $regGreske[] = "Polje za ime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regIme)){
        $regGreske[] = "Ime nije u dobrom formatu";
    }
    if(!$regPrezime){
        $regGreske[] = "Polje za prezime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regPrezime)){
        $regGreske[] = "Prezime nije u dobrom formatu";
    }
    if(!$regEmail){
        $regGreske[] = "Polje za email mora biti popunjeno";
    } else if(!preg_match($reEmail, $regEmail)){
        $regGreske[] = "Email nije u dobrom formatu";
    }
    if(!$regKorIme){
        $regGreske[] = "Polje za korisničko ime mora biti popunjeno";
    } else if(!preg_match($reKorIme, $regKorIme)){
        $regGreske[] = "Korisničko ime mora imati 5-15 karaktera";
    }
    if(!$_POST['regLozinka']){
        $regGreske[] = "Polje za lozinku mora biti popunjeno";
    } else if(strlen($_POST['regLozinka']) < 6){
        $regGreske[] = ("Lozinka mora imati bar 6 karaktera");
    }
    if(!$_POST['regLozinka2']){
        $regGreske[] = "Polje za ponovljenu lozinku mora biti popunjeno";
    } else if(strlen($_POST['regLozinka2']) < 6){
        $regGreske[] = "Ponovljena lozinka mora imati bar 6 karaktera";
    }
    if($_POST['regLozinka'] !== $_POST['regLozinka2']){
        $regGreske[] = "Lozinka i ponovljena lozinka se ne poklapaju";
    }
    if($add_uloga == "0"){
        $regGreske[] = "Morate odabrati ulogu";
    }
    if($add_aktivan == "2"){
        $regGreske[] = "Morate odabrati aktivnost naloga";
    }

    $ime = addslashes($regIme);
    $prezime = addslashes($regPrezime);
    $email = addslashes($regEmail);
    $korIme = addslashes($regKorIme);
    $uloga = $add_uloga;
    $aktivan = $add_aktivan;

    if(count($regGreske)){
        $code = 422;
        $data = $regGreske;
    } else {
        $token = md5(sha1($email . $korIme . time()));
        $datum_registracije = time();
        $upit = "INSERT INTO korisnik (ime, prezime, email, korisnicko_ime, lozinka, ulogaID, token, aktivan, datum_registracije)
             VALUES (:ime, :prezime, :email, :korIme, :lozinka, :uloga, :token, :aktivan, :datum_registracije)";
        $priprema = $konekcija->prepare($upit);
        $priprema->bindParam(":ime", $ime);
        $priprema->bindParam(":prezime", $prezime);
        $priprema->bindParam(":email", $email);
        $priprema->bindParam(":korIme", $korIme);
        $priprema->bindParam(":lozinka", $regLozinka);
        $priprema->bindParam(":token", $token);
        $priprema->bindParam(":uloga", $uloga);
        $priprema->bindParam(":aktivan", $aktivan);
        $priprema->bindParam(":datum_registracije", $datum_registracije);
        try{
            $code = $priprema->execute() ? 201 : 500;
        } catch (PDOException $e){
            $code = 409;
        }
    }
http_response_code($code);
echo json_encode(['message' => $data]);
}




/*-----DELETE USER-----*/
if(isset($_POST['idDelete'])){
    include "connection.php";
    $code = 404;
    $data = null;

    $idDelete = $_POST['idDelete'];
    $upitDeleteUser = "DELETE FROM korisnik WHERE korisnikID = :idDelete";
    $pripremaDeleteUser = $konekcija->prepare($upitDeleteUser);
    $pripremaDeleteUser -> bindParam(":idDelete", $idDelete);
    try{
        $code = $pripremaDeleteUser->execute() ? 204 : 500;
    } catch (PDOException $e){
        $code = 409;
    }

    http_response_code($code);
}






/*-----UPDATE USER-----*/
if(isset($_POST['updateUserDugme'])){
    include "connection.php";
    header("Content-Type: application/json");
    $code = 404;
    $data = null;

    $regIme = trim($_POST['regIme']);
    $regPrezime = trim($_POST['regPrezime']);
    $regEmail = trim($_POST['regEmail']);
    $regKorIme = trim($_POST['regKorIme']);
    $add_uloga = $_POST['add_uloga'];
    $add_aktivan = $_POST['add_aktivan'];
    $idUpdate = $_POST['idUpdate'];

    $reImePrezime = "/^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/";
    $reEmail = "/^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/";
    $reKorIme = "/^[\w\d\.\_]{5,15}$/";
    $regGreske = [];

    if(!$regIme){
        $regGreske[] = "Polje za ime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regIme)){
        $regGreske[] = "Ime nije u dobrom formatu";
    }
    if(!$regPrezime){
        $regGreske[] = "Polje za prezime mora biti popunjeno";
    } else if(!preg_match($reImePrezime, $regPrezime)){
        $regGreske[] = "Prezime nije u dobrom formatu";
    }
    if(!$regEmail){
        $regGreske[] = "Polje za email mora biti popunjeno";
    } else if(!preg_match($reEmail, $regEmail)){
        $regGreske[] = "Email nije u dobrom formatu";
    }
    if(!$regKorIme){
        $regGreske[] = "Polje za korisničko ime mora biti popunjeno";
    } else if(!preg_match($reKorIme, $regKorIme)){
        $regGreske[] = "Korisničko ime mora imati 5-15 karaktera";
    }
    if($add_uloga == "0"){
        $regGreske[] = "Morate odabrati ulogu";
    }
    if($add_aktivan == "2"){
        $regGreske[] = "Morate odabrati aktivnost naloga";
    }

    $ime = addslashes($regIme);
    $prezime = addslashes($regPrezime);
    $email = addslashes($regEmail);
    $korIme = addslashes($regKorIme);
    $uloga = $add_uloga;
    $aktivan = $add_aktivan;

    if(count($regGreske)){
        $code = 422;
        $data = $regGreske;
    } else {
        $upit = "UPDATE korisnik SET ime = :ime, prezime = :prezime, email = :email, korisnicko_ime = :korIme, ulogaID = :uloga, aktivan = :aktivan
                 WHERE korisnikID = :idUpdate";
        $priprema = $konekcija->prepare($upit);
        $priprema->bindParam(":ime", $ime);
        $priprema->bindParam(":prezime", $prezime);
        $priprema->bindParam(":email", $email);
        $priprema->bindParam(":korIme", $korIme);
        $priprema->bindParam(":uloga", $uloga);
        $priprema->bindParam(":aktivan", $aktivan);
        $priprema->bindParam(":idUpdate", $idUpdate);
        try{
            $code = $priprema->execute() ? 204 : 500;
        } catch (PDOException $e){
            $code = 409;
        }
    }
    http_response_code($code);
    echo json_encode(['message' => $data]);
}




/*-----INSERT PRODUCT-----*/
if(isset($_POST['formaProizvodDugme'])){
    include "connection.php";



    $formaProizvodNaziv = trim($_POST['formaProizvodNaziv']);
    $formaProizvodOpis = trim($_POST['formaProizvodOpis']);
    $formaProizvodSlika = $_FILES['formaProizvodSlika'];
    $formaProizvodCena = $_POST['formaProizvodCena'];
    $formaProizvodBrend = $_POST['formaProizvodBrend'];
    $formaProizvodPol = $_POST['formaProizvodPol'];
    $formaProizvodMilitraza = $_POST['formaProizvodMilitraza'];

    $regGreske = [];

    if(!$formaProizvodNaziv){
        $regGreske[] = "Polje za naziv mora biti popunjeno";
    } else if(strlen($formaProizvodNaziv) > 60){
        $regGreske[] = "Naziv ne sme biti duži od 60 karaktera";
    }
    if(!$formaProizvodOpis){
        $regGreske[] = "Polje za opis mora biti popunjeno";
    } else if(strlen($formaProizvodOpis) > 150){
        $regGreske[] = "Opis ne sme biti duži od 150 karaktera";
    }
    if(!$formaProizvodCena){
        $regGreske[] = "Polje za cenu mora biti popunjeno";
    }
    if($formaProizvodBrend == "0"){
        $regGreske[] = "Morate odabrati brend";
    }
    if($formaProizvodPol == "0"){
        $regGreske[] = "Morate odabrati pol";
    }
    if($formaProizvodMilitraza == "0"){
        $regGreske[] = "Morate odabrati militražu";
    }
    if(!$formaProizvodSlika){
        $regGreske[] = "Morate odabrati sliku";
    }



    $uploadFolder = "../images/";
    $tmpName = $formaProizvodSlika['tmp_name'];
    $velicinaSlike = $formaProizvodSlika['size'];
    $maxVelicina = 2048000;
    $nazivSlike = time() . $formaProizvodSlika['name'];
    $tip = $formaProizvodSlika['type'];
    $putanja = $uploadFolder . $nazivSlike;


    if($velicinaSlike > $maxVelicina){
        $regGreske[] = "Slika ne sme biti veća od 2MB";
    }


    $reSlika = "/image\/jpg|image\/jpeg|image\/png/i";

    if(!preg_match($reSlika, $tip)){
        $regGreske[] = "Slika mora biti jpg, jpeg ili png formata";
    }

    $src = addslashes($nazivSlike);
    $alt = addslashes($formaProizvodNaziv);
    $naziv = addslashes($formaProizvodNaziv);
    $opis = addslashes($formaProizvodOpis);


    $prebacivanjeSlike = move_uploaded_file($tmpName, $putanja);

    if(!$prebacivanjeSlike){
        $regGreske[] = "Greška pri dodavanju slike";
    }


    if(count($regGreske)){
        foreach ($regGreske as $greska){
            echo "<h3>$greska</h3>";
        }

    } else {
        $upit = "INSERT INTO proizvod (proizvod, opis, cena, pol, brendID, militrazaID, src, alt)
                 VALUES (:naziv, :opis, :formaProizvodCena, :formaProizvodPol, :formaProizvodBrend, :formaProizvodMilitraza, :src, :alt)";
        $priprema = $konekcija->prepare($upit);
        $priprema->bindParam(":naziv", $naziv);
        $priprema->bindParam(":opis", $opis);
        $priprema->bindParam(":formaProizvodCena", $formaProizvodCena);
        $priprema->bindParam(":formaProizvodPol", $formaProizvodPol);
        $priprema->bindParam(":formaProizvodBrend", $formaProizvodBrend);
        $priprema->bindParam(":formaProizvodMilitraza", $formaProizvodMilitraza);
        $priprema->bindParam(":src", $src);
        $priprema->bindParam(":alt", $alt);
        //$priprema->bindParam(":srcMala", $srcMala);
        try{
            $priprema->execute();
            echo "<h3>Uspešno ste dodali proizvod!</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodima</h4>";
            header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
        } catch (PDOException $e){
            echo "<h3>Greška pri dodavanju proizvoda, pokušajte kasnije.</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodima</h4>";
            header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
        }
    }

}




/*-----DELETE PRODUCT-----*/
if(isset($_POST['idDeleteP'])){
    include "connection.php";
    $code = 404;
    $data = null;

    $idDeleteP = $_POST['idDeleteP'];
    $upitDeleteProduct = "DELETE FROM proizvod WHERE proizvodID = :idDeleteP";
    $pripremaDeleteProduct = $konekcija->prepare($upitDeleteProduct);
    $pripremaDeleteProduct -> bindParam(":idDeleteP", $idDeleteP);
    try{
        $code = $pripremaDeleteProduct->execute() ? 204 : 500;
    } catch (PDOException $e){
        $code = 409;
    }

    http_response_code($code);
}











/*-----UPDATE PRODUCT-----*/
if(isset($_POST['formaProizvodDugmeUp'])){
    include "connection.php";

    $formaProizvodNaziv = trim($_POST['formaProizvodNazivUp']);
    $formaProizvodOpis = trim($_POST['formaProizvodOpisUp']);
    $formaProizvodSlika = $_FILES['formaProizvodSlika'];
    $formaProizvodCena = $_POST['formaProizvodCena'];
    $formaProizvodBrend = $_POST['formaProizvodBrend'];
    $formaProizvodPol = $_POST['formaProizvodPol'];
    $formaProizvodMilitraza = $_POST['formaProizvodMilitraza'];
    $idUpdateP = $_POST['idUpdateP'];

    $regGreske = [];


    if(!$formaProizvodNaziv){
        $regGreske[] = "Polje za naziv mora biti popunjeno";
    } else if(strlen($formaProizvodNaziv) > 60){
        $regGreske[] = "Naziv ne sme biti duži od 60 karaktera";
    }
    if(!$formaProizvodOpis){
        $regGreske[] = "Polje za opis mora biti popunjeno";
    } else if(strlen($formaProizvodOpis) > 150){
        $regGreske[] = "Opis ne sme biti duži od 150 karaktera";
    }
    if(!$formaProizvodCena){
        $regGreske[] = "Polje za cenu mora biti popunjeno";
    }
    if($formaProizvodBrend == "0"){
        $regGreske[] = "Morate odabrati brend";
    }
    if($formaProizvodPol == "0"){
        $regGreske[] = "Morate odabrati pol";
    }
    if($formaProizvodMilitraza == "0"){
        $regGreske[] = "Morate odabrati militražu";
    }



    //AKO NIJE UPLOAD-ovao SLIKU
    if($formaProizvodSlika['name'] == ""){
        $naziv = addslashes($formaProizvodNaziv);
        $opis = addslashes($formaProizvodOpis);
        $alt = addslashes($formaProizvodNaziv);



        if(count($regGreske)){
            foreach ($regGreske as $greska){
                echo "<h3>$greska</h3>";
            }

        } else {
            $upit = "UPDATE proizvod SET proizvod = :naziv, opis = :opis, cena = :formaProizvodCena, pol = :formaProizvodPol, brendID = :formaProizvodBrend, 
                     militrazaID = :formaProizvodMilitraza, alt = :alt WHERE proizvodID = :idUpdateP";
            $priprema = $konekcija->prepare($upit);
            $priprema->bindParam(":naziv", $naziv);
            $priprema->bindParam(":opis", $opis);
            $priprema->bindParam(":formaProizvodCena", $formaProizvodCena);
            $priprema->bindParam(":formaProizvodPol", $formaProizvodPol);
            $priprema->bindParam(":formaProizvodBrend", $formaProizvodBrend);
            $priprema->bindParam(":formaProizvodMilitraza", $formaProizvodMilitraza);
            $priprema->bindParam(":alt", $alt);
            $priprema->bindParam(":idUpdateP", $idUpdateP);

            try{
                $priprema->execute();
                echo "<h3>Uspešno ste izmenili proizvod!</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodima</h4>";
                header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
            } catch (PDOException $e){
                echo "<h3>Greška pri izmeni proizvoda.</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodima</h4>";
                header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
            }
        }

    // AKO JESTE UPLOAD-ovao SLIKU
    } else {
        $uploadFolder = "../images/";
        $tmpName = $formaProizvodSlika['tmp_name'];
        $velicinaSlike = $formaProizvodSlika['size'];
        $maxVelicina = 2048000;
        $nazivSlike = time() . $formaProizvodSlika['name'];
        $tip = $formaProizvodSlika['type'];
        $putanja = $uploadFolder . $nazivSlike;



        if($velicinaSlike > $maxVelicina){
            $regGreske[] = "Slika ne sme biti veća od 2MB";
        }

        $reSlika = "/image\/jpg|image\/jpeg|image\/png/i";

        if(!preg_match($reSlika, $tip)){
            $regGreske[] = "Slika mora biti jpg, jpeg ili png formata";
        }

        $src = addslashes($nazivSlike);
        $alt = addslashes($formaProizvodNaziv);
        $naziv = addslashes($formaProizvodNaziv);
        $opis = addslashes($formaProizvodOpis);


        $prebacivanjeSlike = move_uploaded_file($tmpName, $putanja);


        if(!$prebacivanjeSlike){
            $regGreske[] = "Greška pri dodavanju slike";
        }


        if(count($regGreske)){
            foreach ($regGreske as $greska){
                echo "<h3>$greska</h3>";
            }
        } else {
            $upit = "UPDATE proizvod SET proizvod = :naziv, opis = :opis, cena = :formaProizvodCena, pol = :formaProizvodPol, brendID = :formaProizvodBrend, 
                     militrazaID = :formaProizvodMilitraza, src = :src, alt = :alt WHERE proizvodID = :idUpdateP";
            $priprema = $konekcija->prepare($upit);
            $priprema->bindParam(":naziv", $naziv);
            $priprema->bindParam(":opis", $opis);
            $priprema->bindParam(":formaProizvodCena", $formaProizvodCena);
            $priprema->bindParam(":formaProizvodPol", $formaProizvodPol);
            $priprema->bindParam(":formaProizvodBrend", $formaProizvodBrend);
            $priprema->bindParam(":formaProizvodMilitraza", $formaProizvodMilitraza);
            $priprema->bindParam(":src", $src);
            $priprema->bindParam(":alt", $alt);
            $priprema->bindParam(":idUpdateP", $idUpdateP);

            try{
                $priprema->execute();
                echo "<h3>Uspešno ste izmenili proizvod!</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodima</h4>";
                header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
            } catch (PDOException $e){
                echo "<h3>Greška pri izmeni proizvoda.</h3><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim proizvodi</h4>";
                header('Refresh: 5; URL=http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts');
            }
        }
    }

}

if(empty($_POST['addUserDugme']) and empty($_POST['idDelete']) and empty($_POST['updateUserDugme']) and empty($_POST['formaProizvodDugme']) and empty($_POST['idDeleteP']) and empty($_POST['formaProizvodDugmeUp'])){
    header("Location: http://localhost/php1sajt/index.php");
}