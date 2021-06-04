// Handling the change of Password
document.querySelector(".pass").addEventListener("click", function() {
    document.querySelector(".change-pass").classList.toggle("hidden");
  });
  
  function validatePassword() {
    var oldPass = document.forms["change-pass"]["old-pass"].value;
    var newPass = document.forms["change-pass"]["new-pass"].value;
    if (oldPass == "") {
      alert("Per favore, inserisci la password corrente");
      return false;
    } else if(newPass == "") {
      alert("Per favore, inserisci la nuova password");
      return false;
    }
  }
  
  // Handling the account delete
  document.querySelector(".delete").addEventListener("click", function() {
    document.querySelector(".delete-account").classList.toggle("hidden");
  });
  
  function validateDelete() {
    var pass = document.forms["delete-account"]["password"].value;
    if (pass == "") {
      alert("Per favore, inserisci la tua password");
      return false;
    }
  }
  