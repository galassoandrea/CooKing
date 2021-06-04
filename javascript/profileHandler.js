// Switching between user posts and saved posts
document.querySelector(".switch-btn-2").addEventListener("click", function() {
    if(!document.querySelector(".switch-btn-2").classList.contains("active")) {
        document.querySelector(".switch-btn-2").classList.add("active");
        document.querySelector(".switch-btn-1").classList.remove("active");
        document.querySelector(".user-posts").classList.add("hidden");
        document.querySelector(".saved-posts").classList.remove("hidden");
    }
});

document.querySelector(".switch-btn-1").addEventListener("click", function() {
    if(!document.querySelector(".switch-btn-1").classList.contains("active")) {
        document.querySelector(".switch-btn-1").classList.add("active");
        document.querySelector(".switch-btn-2").classList.remove("active");
        document.querySelector(".user-posts").classList.remove("hidden");
        document.querySelector(".saved-posts").classList.add("hidden");
    }
});

// Handling the post delete
function deletePost(x){
    x.parentElement.parentElement.parentElement.querySelector(".delete-form").classList.toggle("hidden");
}

