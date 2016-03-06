(function hideAllContents(){
  $("section, footer,a").hide();
})();
(function showLoadingScreen(){
  $(".loading-screen").show();
})();

$(document).ready(function showAllContents(){
  $("section, footer,a").show();
  $(".loading-screen").hide();
  $("#synergy-user-content").hide();
  // $("#campus-ambassador-reg").hide();
});

function timedText(text, id, callback) {
        var i = 0;
        (function animate() {
            i = i + 1;
            if (i <= text.length) {
                $(id).html(text.substring(0,i)+"_");
                setTimeout(animate, 100);
            } else {
                setTimeout(callback,1000);
            }
        }());
}
$("#textanimation").css({
   "height": window.innerHeight
});
$("section").css({
  "min-height": window.innerHeight
});
$("#campus-ambassador-reg").css({
  "min-height": window.innerHeight
});
$("#campus-ambassador-reg .contain").css({
  "min-width": window.innerWidth
});
(function textanimation() {
    timedText("SYNERGY 2016", "#textanimdata", function(){
        timedText("MECHANICAL ENGINEERING SYMPOSIUM, NIT TRICHY".toUpperCase(), "#textanimdata", function(){
          timedText ("APRIL 14-16","#textanimdata",textanimation)
        });
    });
}());

$("#workshops").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-workshops").position().top
    });
});
$("#goto-workshops").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-workshops").position().top
    });
});
$("#events").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-events").position().top
    });
});
$("#goto-events").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-events").position().top
    });
});
$("#registration").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-reg").position().top
    });
});
$("#goto-registration").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-reg").position().top
    });
});
$("#guestlectures").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-gl").position().top
    });
});
$("#sponsors").on('click',function() {
    $('html, body').animate({
        'scrollTop' : $("#synergy-sponsors").position().top
    });
});
