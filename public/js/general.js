// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the navbar
//var navbar = document.getElementById("navbar");
var navbar = $("#navbar")[0];
// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky")
    } else {
        navbar.classList.remove("sticky");
    }
}
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