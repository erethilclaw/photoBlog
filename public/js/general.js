
//hamburger
$(function() {
    $(".hamburgerBtn").on("click", function() {
        document.getElementById("overlay").style.display = "block";
        if ($(".nav-links ").hasClass("active")) {
            $(".nav-links ").removeClass("active");
        } else {
            $(".nav-links ").addClass("active");
        }
    });
    $("#overlay").on("click", function() {
        document.getElementById("overlay").style.display = "none";
    })   
});