<!doctype html>
<html lang="fr">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 
        <title>Authentification</title>
 
<!-- Bootstrap 4 CSS and custom CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="css/custom.css" /> 
<link rel="stylesheet" type="text/css" href="css/principal.css" />
<link rel="stylesheet" type="text/css" href="css/Mprincipal.css" />
    </head>
<body>
<div class="test1">
            <div class="content">
                <ul class="bmenu">
                    <li><a href="#" id='home'>Acceuil</a></li>
                    <li><a href="#" id='update_account'>Votre compte</a></li>
                    <li><a href="#" id='logout'>Se deconnecté</a></li>
                    <li><a href="#" id='login'>Se connecter</a></li>
                    <li><a href="#" id='sign_up'>Inscription</a></li>
                </ul>
            </div>
    </div>

<!-- container -->
<main role="main" class="container starter-template">
 
    <div class="row">
        <div class="col">
 
            <!-- where prompt / messages will appear -->
            <div id="response"></div>
 
            <!-- where main content will appear -->
            <div id="content"></div>
        </div>
    </div>
 
</main>
<!-- /container --><!-- script links will be here -->
 
<!-- jQuery & Bootstrap 4 JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 
<script>
    // jQuery codes
    $(document).ready(function(){
        // show sign up / registration form
        $(document).on('click', '#sign_up', function(){
     
            var html = `
                <div class="Insform">
                <form id='sign_up_form'>
                        <h2>Inscription</h2>
     
                    <div class="form-group">
                        <label for="name">Nom Prenom</label>
                        <input type="text" class="form-control" name="name" id="name" required />
                    </div>
     
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required />
                    </div>
     
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="password" required />
                    </div>
     
                    <button type='submit' class='btn btn-primary'>S'inscrire</button>
                </form>
                </div>
                `;
     
            clearResponse();
            $('#content').html(html);
        });
     
// trigger when registration form is submitted
$(document).on('submit', '#sign_up_form', function(){
 
 // get form data
 var sign_up_form=$(this);
 var form_data=JSON.stringify(sign_up_form.serializeObject());

 // submit form data to api
 $.ajax({
     url: "api/create_user.php",
     type : "POST",
     contentType : 'application/json',
     data : form_data,
     success : function(result) {
         // if response is a success, tell the user it was a successful sign up & empty the input boxes
         $('#response').html("<div class='alert alert-success'>Inscription réussis. Merci de vous connectez.</div>");
         sign_up_form.find('input').val('');
     },
     error: function(xhr, resp, text){
         // on error, tell the user sign up failed
         $('#response').html("<div class='alert alert-danger'>Impossible de s'inscrire. Merci de contacter un admin si le problème persiste .</div>");
     }
 });

 return false;
});     
        // show login form
$(document).on('click', '#login', function(){
    showLoginPage();
});
 
// trigger when login form is submitted
$(document).on('submit', '#login_form', function(){
 
 // get form data
 var login_form=$(this);
 var form_data=JSON.stringify(login_form.serializeObject());

 // submit form data to api
$.ajax(
{
    url: "api/login.php",
    type : "POST",
    contentType : 'application/json',
    data : form_data,
    success : function(result)
    {
 
        // store jwt to cookie
        setCookie("jwt", result.jwt, 1);
 
        // show home page & tell the user it was a successful login
        showHomePage();
        $('#response').html("<div class='alert alert-success'>Vous êtes connecté.</div>");
 
    },
    error: function(xhr, resp, text){
    // on error, tell the user login has failed & empty the input boxes
    $('#response').html("<div class='alert alert-danger'>Connexion échoué. Email ou Mot de passe incorrect.</div>");
    login_form.find('input').val('');
}
});

 return false;
});

// show home page
$(document).on('click', '#home', function(){
    showHomePage();
    clearResponse();
});
 
// show update account form
$(document).on('click', '#update_account', function(){
    showUpdateAccountForm();
});
 
// trigger when 'update account' form is submitted
$(document).on('submit', '#update_account_form', function(){
 
 // handle for update_account_form
 var update_account_form=$(this);

 // validate jwt to verify access
 var jwt = getCookie('jwt');

 // get form data
var update_account_form_obj = update_account_form.serializeObject()
 
 // add jwt on the object
 update_account_form_obj.jwt = jwt;
  
 // convert object to json string
 var form_data=JSON.stringify(update_account_form_obj);
  
// submit form data to api
$.ajax({
    url: "api/update_user.php",
    type : "POST",
    contentType : 'application/json',
    data : form_data,
    success : function(result) {
 
        // tell the user account was updated
        $('#response').html("<div class='alert alert-success'>Compte mis a jour</div>");
 
        // store new jwt to coookie
        setCookie("jwt", result.jwt, 1);
    },
 
    // show error message to user
error: function(xhr, resp, text){
    if(xhr.responseJSON.message=="Unable to update user."){
        $('#response').html("<div class='alert alert-danger'>Impossible de mettre le compte a jour.</div>");
    }
 
    else if(xhr.responseJSON.message=="Access denied."){
        showLoginPage();
        $('#response').html("<div class='alert alert-success'>Accès refusé , veuillez vous connectez</div>");
    }
}
});

 return false;
});

// logout the user
$(document).on('click', '#logout', function(){
    showLoginPage();
    $('#response').html("<div class='alert alert-info'>Vous êtes connecté.</div>");
});
     
// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}
 
// show login page
function showLoginPage(){
 
 // remove jwt
 setCookie("jwt", "", 1);

 // login page html
 var html = `
     <form id='login_form'>
            <h2>Connexion</h2>
         <div class='form-group'>
             <label for='email'>Adresse email</label>
             <input type='email' class='form-control' id='email' name='email' placeholder='Votre email'>
         </div>

         <div class='form-group'>
             <label for='password'>Mot de passe</label>
             <input type='password' class='form-control' id='password' name='password' placeholder='Mot de passe'>
         </div>

         <button type='submit' class='btn btn-primary'>Se connecté</button>
     </form>
     `;

 $('#content').html(html);
 clearResponse();
 showLoggedOutMenu();
}

// function to set cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// if the user is logged out
function showLoggedOutMenu(){
    // show login and sign up from navbar & hide logout button
    $("#login, #sign_up").show();
    $("#logout").hide();
}
 
// show home page
function showHomePage(){
 
 // validate jwt to verify access
 var jwt = getCookie('jwt');
 $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

     // if valid, show homepage
var html = `
    <div class="card">
        <div class="card-header">Bienvenue dans l'acceuil!</div>
        <div class="card-body">
            <h5 class="card-title">Vous êtes connecter.</h5>
            <p class="card-text">Vous pouvez maintenant accédé a la rubrique "Votre compte"</p>
        </div>
    </div>
    `;
 
$('#content').html(html);
showLoggedInMenu();
 })

 // show login page on error
.fail(function(result){
    showLoginPage();
    $('#response').html("<div class='alert alert-danger'>Veuillez vous connecté pour accédé a la rubrique Acceuil.</div>");
})
}

// get or read cookie
function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }
 
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// if the user is logged in
function showLoggedInMenu(){
    // hide login and sign up from navbar & show logout button
    $("#login, #sign_up").hide();
    $("#logout").show();
}
 
function showUpdateAccountForm(){
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post("api/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {
 
       // if response is valid, put user details in the form
var html = `
        <form id='update_account_form'>
        <h2>Modifié votre profil</h2>
            <div class="form-group">
                <label for="name">Nom Prenom</label>
                <input type="text" class="form-control" name="name" id="name" required value="` + result.data.name + `" />
            </div>
 
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" required value="` + result.data.email + `" />
            </div>
 
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" />
            </div>
 
            <button type='submit' class='btn btn-primary'>
                Save Changes
            </button>
        </form>
    `;
 
clearResponse();
$('#content').html(html);
    })
 
   // on error/fail, tell the user he needs to login to show the account page
.fail(function(result){
    showLoginPage();
    $('#response').html("<div class='alert alert-danger'>Veuillez vous connecté pour accédé a la rubrique VOTRE COMPTE.</div>");
});
}


 
// function to make form values to json format
$.fn.serializeObject = function(){
 
 var o = {};
 var a = this.serializeArray();
 $.each(a, function() {
     if (o[this.name] !== undefined) {
         if (!o[this.name].push) {
             o[this.name] = [o[this.name]];
         }
         o[this.name].push(this.value || '');
     } else {
         o[this.name] = this.value || '';
     }
 });
 return o;
};    });
    </script>









</body>
</html>