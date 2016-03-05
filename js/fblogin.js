function statusChangeCallback(response) {
  if (response.status === 'connected') {
    callAPI();
  }
}
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

window.fbAsyncInit = function() {
FB.init({
  appId      : '899354956847787',
  cookie     : true,
  xfbml      : true,
  version    : 'v2.5'
});

FB.getLoginStatus(function(response) {
  statusChangeCallback(response);
});

};

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function callAPI() {
  FB.api('/me?fields=name,email', function(response) {
    var name= response.name;
    var email=response.email;
    var fbid=response.id;
    var data={
         name:name,
         email:email,
         fbid:fbid
       };
    $.post("register.php",data)
      .done(function(data){
          var response=JSON.parse(data);
          if (response.status==="success"){
            loggedin=1;
            fblogin=1;
            loadUserContent();
          }
      });
  });
}
