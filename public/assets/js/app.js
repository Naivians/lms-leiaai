$(".sidebar-toggle").on("click", function () {
    $("#sidebar").toggleClass("sidebar-collapsed");
    $(".main-content").toggleClass("expanded");
});
