
//hamburger
$(function() {
    $(".hamburgerBtn").on("click", function() {
        if ($(".nav-links ").hasClass("active")) {
            $(".nav-links ").removeClass("active");
            
        } else {
            $(".nav-links ").addClass("active");
        }
    }); 
});