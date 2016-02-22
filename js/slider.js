$("#synergy-slider").hide();

function hideslider() {
    $("document").on("click", function () {
        $("#synergy-slider").hide(200);
        $("document").off();
    });
}
$("#nav-bar-button").click(function () {
    $("#synergy-slider").toggle(200);
    setTimeout(function () {
        $("body").on("click", function (e) {
            if (e.pageX > 300) {
                $("#synergy-slider").hide(200);
                $("body").off();
            }
        });
    }, 200);
});
