// Checking if a file was selected
document.querySelector("#file-upload").addEventListener("change", function() {
    if(document.querySelector("#file-upload").files.length == 0 ){
        console.log("no files selected");
    } else {
      var src = document.getElementById("file-upload");
      var target = document.getElementById("target");
      document.querySelector("#target").classList.remove("hidden");
      document.querySelector("#target").classList.add("block");
      document.querySelector(".empty").classList.add("hidden");
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

