$("#synergy-slider").hide();

$("#nav-bar-button").click(function () {
    $("#synergy-slider").toggle(200);
    setTimeout(function () {
        $("body").on("click", function (e) {
            if(e.target.id == "nav-bar-button"){
              return;
            }
            if (e.pageX > 300) {
                $("#synergy-slider").hide(200);
                $("body").off();
            }
        });
    }, 200);
});
