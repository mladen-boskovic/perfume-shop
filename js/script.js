$(document).ready(function(){
    $('#regDugme').on('click', regProvera);
    $('#loginDugme').on('click', loginProvera);
    $('#contactDugme').on('click', contactProvera);


    $('#addUserDugme').on('click', addUserProvera);
    $('#updateUserDugme').on('click', updateUserProvera);
    $('.obrisiKorisnika').on('click', obrisiKorisnika);
    $('.obrisiProizvod').on('click', obrisiProizvod);

    $('.dodaj').on('click', dodajUKorpu);
    $('.izbaciIzKorpe').on('click', izbaciIzKorpe);
    $('.kupiDugme').on('click', kupiDugme);

    $("#selectAnketa").on('change', selectAnketa);
    $('#glasajDugmeDiv').hide();
    $('#glasaj').on("click", glasaj);
    $('#rezultatiDugme').on('click', dohvatiRezultate);


    $('#nav li').hover(
        function() {
            $('ul', this).stop().slideDown(200);
        },
        function() {
            $('ul', this).stop().slideUp(200);
        }
    );


    slajder();

    $('.vecaSlika').simpleLightbox();

    $('#loginKorIme').focus();

    $('#loginDugme, #regDugme, #formaProizvodDugme, #formaProizvodDugmeUp, #addUserDugme, #updateUserDugme, #glasaj, #rezultatiDugme, #contactDugme').hover(
        function(){
            $(this).css({backgroundColor : 'darkolivegreen'});
        },
        function(){
            $(this).css({backgroundColor : '#DCDCDC'});
        }
    );





});




function regProvera() {
    var regIme = $.trim($('#regIme').val());
    var regPrezime = $.trim($('#regPrezime').val());
    var regEmail = $.trim($('#regEmail').val());
    var regKorIme = $.trim($('#regKorIme').val());
    var regLozinka = $('#regLozinka').val();
    var regLozinka2 = $('#regLozinka2').val();

    var reImePrezime = /^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/;
    var reEmail = /^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/;
    var reKorIme = /^[\w\d\.\_]{5,15}$/;
    var regGreske = [];

    if(regIme === ""){
        regGreske.push("Polje za ime mora biti popunjeno");
    } else if(!reImePrezime.test(regIme)){
        regGreske.push("Ime nije u dobrom formatu");
    }
    if(regPrezime === ""){
        regGreske.push("Polje za prezime mora biti popunjeno");
    } else if(!reImePrezime.test(regPrezime)){
        regGreske.push("Prezime nije u dobrom formatu");
    }
    if(regEmail === ""){
        regGreske.push("Polje za email mora biti popunjeno");
    } else if(!reEmail.test(regEmail)){
        regGreske.push("Email nije u dobrom formatu");
    }
    if(regKorIme === ""){
        regGreske.push("Polje za korisničko ime mora biti popunjeno");
    } else if(!reKorIme.test(regKorIme)){
        regGreske.push("Korisničko ime mora imati 5-15 karaktera");
    }
    if(regLozinka === ""){
        regGreske.push("Polje za lozinku mora biti popunjeno");
    } else if(regLozinka.length < 6){
        regGreske.push("Lozinka mora imati bar 6 karaktera");
    }
    if(regLozinka2 === ""){
        regGreske.push("Polje za ponovljenu lozinku mora biti popunjeno");
    } else if(regLozinka2.length < 6){
        regGreske.push("Ponovljena lozinka mora imati bar 6 karaktera");
    }
    if(regLozinka !== regLozinka2){
        regGreske.push("Lozinka i ponovljena lozinka se ne poklapaju");
    }

    if(regGreske.length){
        var regGreskeIspis = "<ul>";
        for(let i=0; i<regGreske.length; i++){
            regGreskeIspis += "<li><h4>"+ regGreske[i] +"</h4></li>";
        }
        regGreskeIspis += "</ul>";
        $('#reg_greske').html(regGreskeIspis);
    } else{
        var data = {
            regIme : regIme,
            regPrezime : regPrezime,
            regEmail : regEmail,
            regKorIme : regKorIme,
            regLozinka : regLozinka,
            regLozinka2 : regLozinka2,
            regDugme : "ok"
        };
        $.ajax({
            url : "modules/register.php",
            method : "POST",
            dataType : "json",
            data : data,
            success : function (data) {
                $('#reg_greske').html("<h2>Uspešno ste se registrovali!</h2><h4>Proverite email i aktivirajte nalog</h4>");
                setTimeout(function() {
                    window.location.href = "http://localhost/php1sajt/index.php";
                }, 10000);
            },
            error : function (xhr, status, error) {
                let greska = JSON.parse(xhr.responseText).message;
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguća registracija novih korisnika, pokušajte kasnije";
                        break;
                    case 409:
                        poruka = "Korisničko ime ili email već postoje";
                        break;
                    case 422:
                        poruka = "<ul>";
                        for(let i=0; i<greska.length; i++){
                            poruka += "<li>"+ greska[i] +"</li>";
                        }
                        poruka += "</ul>";
                        break;
                    case 500:
                        poruka = "Greska pri registraciji, pokušajte kasnije";
                        break;
                }
                $('#reg_greske').html("<h4>"+ poruka +"</h4>");
            }
            
        });
    }
}



function loginProvera() {
    var loginKorIme = $.trim($('#loginKorIme').val());
    var loginLozinka = $('#loginLozinka').val();
    var reLoginKorIme = /^[\w\d\.\_]{5,15}$/;
    var loginGreske = [];

    if(loginKorIme === ""){
        loginGreske.push("Polje za korisničko ime mora biti popunjeno");
    } else if(!reLoginKorIme.test(loginKorIme)){
        loginGreske.push("Korisničko ime mora imati 5-15 karaktera");
    }
    if(loginLozinka === ""){
        loginGreske.push("Polje za lozinku mora biti popunjeno");
    } else if(loginLozinka.length < 6){
        loginGreske.push("Lozinka mora imati bar 6 karaktera");
    }

    if(loginGreske.length){
        var loginGreskeIspis = "<ul>";
        for(let i=0; i<loginGreske.length; i++){
            loginGreskeIspis += "<li><h4>"+ loginGreske[i] +"</h4></li>";
        }
        loginGreskeIspis += "</ul>";
        $('#login_greske').html(loginGreskeIspis);
    } else{
        var data = {
            loginKorIme : loginKorIme,
            loginLozinka : loginLozinka,
            loginDugme : "ok"
        };

        $.ajax({
            url : "modules/login.php",
            method : "POST",
            dataType : "json",
            data : data,
            success : function (data) {
                var uloga = data.message;
                if(uloga === "Admin"){
                    window.location.href = "http://localhost/php1sajt/index.php?page=admin";
                } else{
                    window.location.href = "http://localhost/php1sajt/index.php";
                }
            },
            error : function (xhr, status, error) {
                let greska = JSON.parse(xhr.responseText).message;
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće prijavljivanje, pokušajte kasnije";
                        break;
                    case 409:
                        poruka = "Pogrešno korisničko ime ili lozinka";
                        break;
                    case 422:
                        poruka = "<ul>";
                        for(let i=0; i<greska.length; i++){
                            poruka += "<li>"+ greska[i] +"</li>";
                        }
                        poruka += "</ul>";
                        break;
                    case 500:
                        poruka = "Greska pri prijavljivanju, pokušajte kasnije";
                        break;
                }
                $('#login_greske').html("<h4>"+ poruka +"</h4>");
            }
        });
    }
}



function contactProvera() {
    var contactEmail = $.trim($('#contactEmail').val());
    var poruka= $('#poruka').val();
    var reContactEmail = /^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/;
    var contactGreske = [];

    if(contactEmail === ""){
        contactGreske.push("Polje za email mora biti popunjeno");
    } else if(!reContactEmail.test(contactEmail)){
        contactGreske.push("Email nije u dobrom formatu");
    }
    if(poruka === ""){
        contactGreske.push("Polje za poruku mora biti popunjeno");
    } else if(poruka.length < 15 || poruka.length > 200){
        contactGreske.push("Poruka mora imati 15-200 karaktera");
    }

    if(contactGreske.length){
        var contactGreskeIspis = "<ul>";
        for(let i=0; i<contactGreske.length; i++){
            contactGreskeIspis += "<li><h4>"+ contactGreske[i] +"</h4></li>";
        }
        contactGreskeIspis += "</ul>";
        $('#contact_greske').html(contactGreskeIspis);
    } else{
        var data = {
            contactEmail : contactEmail,
            poruka : poruka,
            contactDugme : "ok"
        };

        $.ajax({
            url : "modules/mailadmin.php",
            method : "POST",
            dataType : "json",
            data : data,
            success : function (data) {
                $('#contact_greske').html("<h2>Poruka uspešno poslata!</h2>");
                setTimeout(function() {
                    window.location.href = "http://localhost/php1sajt/index.php";
                }, 5000);
            },
            error : function (xhr, status, error) {
                let greska = JSON.parse(xhr.responseText).message;
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće postaviti pitanje, pokušajte kasnije";
                        break;
                    case 422:
                        poruka = "<ul>";
                        for(let i=0; i<greska.length; i++){
                            poruka += "<li>"+ greska[i] +"</li>";
                        }
                        poruka += "</ul>";
                        break;
                    case 500:
                        poruka = "Greska pri slanju poruke, pokušajte kasnije";
                        break;
                }
                $('#contact_greske').html("<h4>"+ poruka +"</h4>");
            }
        });
    }
}

function addUserProvera(){
    var regIme = $.trim($('#regIme').val());
    var regPrezime = $.trim($('#regPrezime').val());
    var regEmail = $.trim($('#regEmail').val());
    var regKorIme = $.trim($('#regKorIme').val());
    var regLozinka = $('#regLozinka').val();
    var regLozinka2 = $('#regLozinka2').val();
    var uloga = $('#add_uloga').val();
    var aktivan = $('#add_aktivan').val();

    var reImePrezime = /^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/;
    var reEmail = /^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/;
    var reKorIme = /^[\w\d\.\_]{5,15}$/;
    var regGreske = [];


    if(regIme === ""){
        regGreske.push("Polje za ime mora biti popunjeno");
    } else if(!reImePrezime.test(regIme)){
        regGreske.push("Ime nije u dobrom formatu");
    }
    if(regPrezime === ""){
        regGreske.push("Polje za prezime mora biti popunjeno");
    } else if(!reImePrezime.test(regPrezime)){
        regGreske.push("Prezime nije u dobrom formatu");
    }
    if(regEmail === ""){
        regGreske.push("Polje za email mora biti popunjeno");
    } else if(!reEmail.test(regEmail)){
        regGreske.push("Email nije u dobrom formatu");
    }
    if(regKorIme === ""){
        regGreske.push("Polje za korisničko ime mora biti popunjeno");
    } else if(!reKorIme.test(regKorIme)){
        regGreske.push("Korisničko ime mora imati 5-15 karaktera");
    }
    if(regLozinka === ""){
        regGreske.push("Polje za lozinku mora biti popunjeno");
    } else if(regLozinka.length < 6){
        regGreske.push("Lozinka mora imati bar 6 karaktera");
    }
    if(regLozinka2 === ""){
        regGreske.push("Polje za ponovljenu lozinku mora biti popunjeno");
    } else if(regLozinka2.length < 6){
        regGreske.push("Ponovljena lozinka mora imati bar 6 karaktera");
    }
    if(regLozinka !== regLozinka2){
        regGreske.push("Lozinka i ponovljena lozinka se ne poklapaju");
    }
    if(uloga === "0"){
        regGreske.push("Morate odabrati ulogu");
    }
    if(aktivan === "2"){
        regGreske.push("Morate odabrati aktivnost korisnika");
    }

    if(regGreske.length){
        var regGreskeIspis = "<ul>";
        for(let i=0; i<regGreske.length; i++){
            regGreskeIspis += "<li><h4>"+ regGreske[i] +"</h4></li>";
        }
        regGreskeIspis += "</ul>";
        $('#reg_greske').html(regGreskeIspis);
    } else{
        var data = {
            regIme : regIme,
            regPrezime : regPrezime,
            regEmail : regEmail,
            regKorIme : regKorIme,
            regLozinka : regLozinka,
            regLozinka2 : regLozinka2,
            add_uloga : uloga,
            add_aktivan : aktivan,
            addUserDugme : "ok"
        };
        $.ajax({
            url : "modules/admin.php",
            method : "POST",
            dataType : "json",
            data : data,
            success : function (data) {
                $('#reg_greske').html("<h2>Uspešno ste dodali korisnika!</h2><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim korisnicima</h4>");
                setTimeout(function() {
                    window.location.href = "http://localhost/php1sajt/index.php?page=admin&adminaction=allusers";
                }, 5000);
            },
            error : function (xhr, status, error) {
                let greska = JSON.parse(xhr.responseText).message;
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće dodavanje novog korisnika, pokušajte kasnije";
                        break;
                    case 409:
                        poruka = "Korisničko ime ili email već postoje";
                        break;
                    case 422:
                        poruka = "<ul>";
                        for(let i=0; i<greska.length; i++){
                            poruka += "<li>"+ greska[i] +"</li>";
                        }
                        poruka += "</ul>";
                        break;
                    case 500:
                        poruka = "Greska pri dodavanju korisnika, pokušajte kasnije";
                        break;
                }
                $('#reg_greske').html("<h4>"+ poruka +"</h4>");
            }

        });
    }
}

function obrisiKorisnika() {
    if(confirm("Da li želite da obrišete korisnika?")){
        var id = $(this).data('id');
        $.ajax({
            url : "modules/admin.php",
            method : "POST",
            data : {
                idDelete : id
            },
            success : function (data) {
                alert('Korisnik uspešno obrisan!');
                window.location.href = "http://localhost/php1sajt/index.php?page=admin&adminaction=allusers";
            },
            error : function (xhr,status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće obrisati korisnika, pokušajte kasnije";
                        break;
                    case 409 :
                        poruka = "Greška pri brisanju korisnika, pokušajte kasnije";
                        break;
                    case 500 :
                        poruka = "Greška pri brisanju korisnika, pokušajte kasnije";
                        break;
                }
            }
        });
    }
}





function updateUserProvera(){
    var regIme = $.trim($('#regIme').val());
    var regPrezime = $.trim($('#regPrezime').val());
    var regEmail = $.trim($('#regEmail').val());
    var regKorIme = $.trim($('#regKorIme').val());
    var uloga = $('#add_uloga').val();
    var aktivan = $('#add_aktivan').val();
    var idUpdate = $('#idUpdate').val();

    var reImePrezime = /^[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}(\s[A-ZŠĐČĆŽ][a-zšđčćž]{2,12}){0,1}$/;
    var reEmail = /^[\w]+[\.\w\d]*[\w\d]+\@[\w]+([\.][\w]+)+$/;
    var reKorIme = /^[\w\d\.\_]{5,15}$/;
    var regGreske = [];


    if(regIme === ""){
        regGreske.push("Polje za ime mora biti popunjeno");
    } else if(!reImePrezime.test(regIme)){
        regGreske.push("Ime nije u dobrom formatu");
    }
    if(regPrezime === ""){
        regGreske.push("Polje za prezime mora biti popunjeno");
    } else if(!reImePrezime.test(regPrezime)){
        regGreske.push("Prezime nije u dobrom formatu");
    }
    if(regEmail === ""){
        regGreske.push("Polje za email mora biti popunjeno");
    } else if(!reEmail.test(regEmail)){
        regGreske.push("Email nije u dobrom formatu");
    }
    if(regKorIme === ""){
        regGreske.push("Polje za korisničko ime mora biti popunjeno");
    } else if(!reKorIme.test(regKorIme)){
        regGreske.push("Korisničko ime mora imati 5-15 karaktera");
    }
    if(uloga === "0"){
        regGreske.push("Morate odabrati ulogu");
    }
    if(aktivan === "2"){
        regGreske.push("Morate odabrati aktivnost korisnika");
    }

    if(regGreske.length){
        var regGreskeIspis = "<ul>";
        for(let i=0; i<regGreske.length; i++){
            regGreskeIspis += "<li><h4>"+ regGreske[i] +"</h4></li>";
        }
        regGreskeIspis += "</ul>";
        $('#reg_greske').html(regGreskeIspis);
    } else{
        var data = {
            regIme : regIme,
            regPrezime : regPrezime,
            regEmail : regEmail,
            regKorIme : regKorIme,
            add_uloga : uloga,
            add_aktivan : aktivan,
            idUpdate : idUpdate,
            updateUserDugme : "ok"
        };
        $.ajax({
            url : "modules/admin.php",
            method : "POST",
            dataType : "json",
            data : data,
            success : function (data) {
                $('#reg_greske').html("<h2>Uspešno ste izmenili korisnika!</h2><h4>Uskoro ćete biti prebačeni na admin stranicu sa svim korisnicima</h4>");
                setTimeout(function() {
                    window.location.href = "http://localhost/php1sajt/index.php?page=admin&adminaction=allusers";
                }, 5000);
            },
            error : function (xhr, status, error) {
                let greska = JSON.parse(xhr.responseText).message;
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće izmeniti korisnika, pokušajte kasnije";
                        break;
                    case 409:
                        poruka = "Korisničko ime ili email već postoje";
                        break;
                    case 422:
                        poruka = "<ul>";
                        for(let i=0; i<greska.length; i++){
                            poruka += "<li>"+ greska[i] +"</li>";
                        }
                        poruka += "</ul>";
                        break;
                    case 500:
                        poruka = "Greska pri izmeni korisnika, pokušajte kasnije";
                        break;
                }
                $('#reg_greske').html("<h4>"+ poruka +"</h4>");
            }

        });
    }
}


function obrisiProizvod() {
    if(confirm("Da li želite da obrišete proizod?")){
        var id = $(this).data('id');
        $.ajax({
            url : "modules/admin.php",
            method : "POST",
            data : {
                idDeleteP : id
            },
            success : function (data) {
                alert('Proizvod uspešno obrisan!');
                window.location.href = "http://localhost/php1sajt/index.php?page=admin&adminaction=allproducts";
            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće obrisati korisnika, pokušajte kasnije";
                        break;
                    case 409 :
                        poruka = "Greška pri brisanju korisnika, pokušajte kasnije";
                        break;
                    case 500 :
                        poruka = "Greška pri brisanju korisnika, pokušajte kasnije";
                        break;
                }
            }
        });
    }

}




function dodajUKorpu() {
    var idPr = $(this).data('id');
    var idKor = $('#sessionIdKor').val();

    if(idKor == ""){
        alert("Morate se prijaviti da bi dodali proizvod u korpu");
    } else{
        $.ajax({
            url : "modules/shop.php",
            method : "post",
            data : {
                idPr : idPr,
                idKor : idKor
            },
            success : function (data) {
                alert("Proizvod je dodat u korpu!");
            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće dodavati u korpu, pokušajte kasnije";
                        break;
                    case 409:
                        poruka = "Greška pri dodavanju u korpu, pokušajte kasnije";
                        break;
                    case 500:
                        poruka = "Greška pri dodavanju u korpu, pokušajte kasnije";
                        break;
                }
                alert(poruka);
            }

        });
    }
}

function izbaciIzKorpe() {
    if(confirm("Da li želite da izbacite proizvod iz korpe?")){
        var idP = $(this).data('id');
        var idK = $('#idKorIzbaci').val();
        $.ajax({
            url : "modules/shop.php",
            method : "POST",
            data : {
                idK : idK,
                idP : idP
            },
            success : function (data) {
                window.location.href = "http://localhost/php1sajt/index.php?page=shop";
            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće izbaciti proizvod iz korpe, pokušajte kasnije";
                        break;
                    case 409 :
                        poruka = "Greška pri izbacivanju proizvoda iz korpe, pokušajte kasnije";
                        break;
                    case 500 :
                        poruka = "Greška pri izbacivanju proizvoda iz korpe, pokušajte kasnije";
                        break;
                }
                alert(poruka);
            }
        });
    }

}


function kupiDugme() {
    if(confirm("Da li želite da izvršite kupovinu?")){
        var id = $(this).data('id');
        $.ajax({
            url : "modules/shop.php",
            method : "POST",
            data : {
                idKupovina : id
            },
            success : function (data) {
                alert("Kupovina uspešno izvršena!");
                window.location.href = "http://localhost/php1sajt/index.php?page=shop";
            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće izvršiti kupovinu, pokušajte kasnije";
                        break;
                    case 409 :
                        poruka = "Greška pri izvršavanju kupovine, pokušajte kasnije";
                        break;
                    case 500 :
                        poruka = "Greška pri izvršavanju kupovine, pokušajte kasnije";
                        break;
                }
                alert(poruka);
            }
        });
    }
}



function slajder(){
    var trenutna = $('.trenutna');
    var sledeca = trenutna.next().length ? trenutna.next() : trenutna.parent().children(':first');
    trenutna.hide().removeClass('trenutna');
    sledeca.fadeIn('slow').addClass('trenutna');
    setTimeout(slajder, 4000);
}





function selectAnketa() {
    var id = $('#selectAnketa').val();
    $('#izaberiteOdgovor').html("");
    if(id != "0"){
        $.ajax({
            url : "modules/survey.php",
            method : "POST",
            dataType : "json",
            data : {
                idPitanja : id
            },
            success : function (data) {
                let podaci = data.message;


                let ispisPodaci = "<ul>";
                for(let i=0; i<podaci.length; i++){
                    ispisPodaci += "<li><input type='radio' value='"+ podaci[i].odgovorID +"' id='glasajOdgovori' name='glasajOdgovori'/>"+ podaci[i].odgovor +"</li>";
                }

                ispisPodaci += "</ul>";

                $('#odgovori').html(ispisPodaci);
                $('#glasajDugmeDiv').show();

            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nema odgovora za izabrano pitanje";
                        break;
                    case 409:
                        poruka = "Trenutno nema odgovora za izabrano pitanje";
                        break;
                    case 500:
                        poruka = "Greska, pokušajte kasnije";
                        break;
                }
                $('#odgovori').html("<h4>"+ poruka +"</h4>");
            }
        });
    } else{
        $('#odgovori').html("");
        $('#glasajDugmeDiv').hide();
    }
}


function glasaj() {
    var odgovori = document.getElementsByName("glasajOdgovori");
    var id = "";
    var idPitanja = $('#selectAnketa').val();
    for(let i=0; i<odgovori.length; i++){
        if(odgovori[i].checked){
            id = odgovori[i].value;
        }
    }

    if(id === ""){
        $('#izaberiteOdgovor').html("<h4>Izaberite odgovor</h4>");
    } else{
        
        $.ajax({
            url : "modules/survey.php",
            dataType : "json",
            method : "post",
            data : {
                idOdgovora2 : id,
                idPitanja2 : idPitanja,
                glasaj : "ok"
            },
            success : function (data) {
                $('#izaberiteOdgovor').html("<h4>Uspešno ste glasali!</h4>");
            },
            error : function (xhr, status, error) {
                let poruka = "";
                switch (xhr.status){
                    case 404 :
                        poruka = "Trenutno nije moguće glasati na anketu";
                        break;
                    case 409:
                        poruka = "Trenutno nije moguće glasati na anketu";
                        break;
                    case 422:
                        poruka = "Na ovu anketu ste već glasali";
                        break;
                    case 500:
                        poruka = "Greska, pokušajte kasnije";
                        break;
                }
                $('#izaberiteOdgovor').html("<h4>"+ poruka +"</h4>");
            }
        });

    }
}



function dohvatiRezultate() {
    var id = $('#selectAnketa').val();
    $.ajax({
        url : "modules/survey.php",
        method : "post",
        dataType : "json",
        data : {
            pitanjeID : id,
            dohvatiRezultate : "ok"
        },
        success : function (data) {
            let podaci = data.message;

            let ispisPodaci = "<ul>";
            for(let i=0; i<podaci.length; i++){
                ispisPodaci += "<li><h4>"+ podaci[i].odgovor + " - " + podaci[i].broj_glasova + " glasova" +"</h4></li>";
            }

            ispisPodaci += "</ul>";

            $('#izaberiteOdgovor').html(ispisPodaci);
        },
        error : function (xhr, status, error) {
            let poruka = "";
            switch (xhr.status){
                case 404 :
                    poruka = "Trenutno nije moguće videti rezultate";
                    break;
                case 409:
                    poruka = "Trenutno nije moguće videti rezultate";
                    break;
                case 422:
                    poruka = "Trenutno nema rezltata za izabranu anketu";
                    break;
                case 500:
                    poruka = "Greška, pokušajte kasnije";
                    break;
            }
            $('#izaberiteOdgovor').html("<h4>"+ poruka +"</h4>");
        }
    });
}
