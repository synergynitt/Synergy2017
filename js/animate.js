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

$(".synergy-project-info").css({
    "min-height" : window.innerHeight - 200
});

(function textanimation() {
    timedText("SYNERGY 2016", "#textanimdata", function(){
        timedText("SYMPOSIUM OF MECHANICAL ENGINEERING DEPARTMENT , NIT TRICHY".toUpperCase(), "#textanimdata", function(){
          timedText ("APRIL 14-16","#textanimdata",textanimation)
        });
    });
}());

 $(document).ready(function(){
    $('.scrollspy').scrollSpy();
  });
