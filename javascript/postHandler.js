// Handling the post form
document.querySelector(".new-post").addEventListener("click", function() {
  document.querySelector(".post").classList.toggle("hidden");
});

function validatePost() {
  var title = document.forms["post"]["title"].value;
  var description = document.forms["post"]["description"].value;
  if (title == "") {
    alert("Per favore, inserisci un titolo per la tua ricetta");
    return false;
  } else if(ingredients == "") {
    alert("Per favore, inserisci gli ingredienti per la tua ricetta");
    return false;
  } else if(description == "") {
    alert("Per favore, inserisci il procedimento per la tua ricetta");
    return false;
  }
}

// Checking if a file was selected
document.querySelector("#file-upload").addEventListener("change", function() {
  if(document.querySelector("#file-upload").files.length == 0 ){
      console.log("no files selected");
  } else {
    var src = document.getElementById("file-upload");
    var target = document.getElementById("target");
    showImage(src,target);
  }
});

function showImage(src,target) {
  var fr=new FileReader();
  // when image is loaded, set the src of the image where you want to display it
  fr.onload = function(e) { target.src = this.result; };
    // fill fr with image data
    fr.readAsDataURL(src.files[0]);
}

// Handling the post comment
function showComments(x){
  x.parentElement.parentElement.parentElement.querySelector(".comment-section").classList.toggle("hidden");
}

// Handling the post share
function sharePost(x){
  x.parentElement.parentElement.parentElement.querySelector(".share-form").classList.toggle("hidden");
}

// Handling the show more/less content
function showMore(x){
  var fullContent = x.parentElement.parentElement.firstElementChild;
  if(fullContent.classList.contains("hidden")) {
    x.parentElement.parentElement.lastElementChild.classList.add("hidden");
    x.parentElement.parentElement.firstElementChild.classList.remove("hidden");
  } else {
    x.parentElement.parentElement.lastElementChild.classList.remove("hidden");
    x.parentElement.parentElement.firstElementChild.classList.add("hidden");
  }
}
  


