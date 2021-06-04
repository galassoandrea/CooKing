// Handling user registration
function validateRegistration() {
    var name = document.forms["register"]["name"].value;
    var surname = document.forms["register"]["surname"].value;
    var username = document.forms["register"]["username"].value;
    var email = document.forms["register"]["email"].value;
    var password = document.forms["register"]["password"].value;
    var file = document.forms["register"]["file"].value;

    if(name =="") {
        alert("Per favore, inserisci il tuo nome");
        return false;
    } else if(surname == "") {
        alert("Per favore, inserisci il tuo cognome");
        return false;
    } else if(username == "") {
        alert("Per favore, inserisci il tuo username");
        return false;
    } else if(email == "") {
        alert("Per favore, inserisci la tua email");
        return false;
    } else if(password == "") {
        alert("Per favore, inserisci la tua password");
        return false;
    } else if(file == "") {
        alert("Per favore, inserisci la tua immagine del profilo");
        return false;
    }
}

// Handling user login
function validateLogin() {
    var email = document.forms["login"]["email"].value;
    var password = document.forms["login"]["password"].value;

    if(email == "") {
        alert("Per favore, inserisci la tua email");
        return false;
    } else if(password == "") {
        alert("Per favore, inserisci la tua password");
        return false;
    }
}