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
  $("#campus-ambassador-reg").hide();
  $("#select-group-id").hide();
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
$("#select-group-id").css({
  "min-height": window.innerHeight
});
$(".fadingbackground").css({
  "min-height": window.innerHeight,
  "background":"#000",
});
$(".fadingbackground").children("div").css({
  "height": window.innerHeight,
  "width" : window.innerWidth,
  "position" :"absolute",
  "top" : 0
});
(function textanimation() {
    timedText("MECHANICAL DEPARTMENT, NIT TRICHY PRESENTS", "#textanimdata", function(){
        timedText("SYNERGY 2016".toUpperCase(), "#textanimdata", function(){
          timedText ("ON APRIL 14,15,16","#textanimdata",textanimation)
        });
    });
}());

(function setBackgorund() {
  var i = 0;
  var max = 5;
  for (i = 0; i <= max; i++){
    var divclass=".bg"+i;
    var background = "url(../images/bg"+i+".jpg) no-repeat center fixed";
    var backgroundSize = "100% 100%";
    $(divclass).css({
      "background": background,
      "background-size": backgroundSize
    });
  }
}());
function changeBackground(i){
  var divclass=".bg"+i;
  $(divclass).fadeTo(1000 , 1);
  $(".fadingbackground").children("div:not("+divclass+")").fadeTo( 1000 , 0.05);
}

(function changingbackground(){
  var i = 0;
  var max = 5;
  var timelimit = 5000;
  for (i=0;i<=max;i++){
    if (i === max) {
      setTimeout(changingbackground, (max+1)*timelimit);
    }
    var callback = (function(i){
      return function () {
        changeBackground(i);
      }
    })(i);
    setTimeout(callback, i*timelimit );
  }
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
