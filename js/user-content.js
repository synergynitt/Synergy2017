(function hideusercontent(){
  $("#synergy-user-content").hide();
})();

function checkConfirmationPassword(){
    var matching=1;
    password=$("#password").val();
    confirmPassword=$("#confirm-password").val();
    if (password!=confirmPassword){
      matching=0;
    }
    if (matching){
      $("#confirm-password").removeClass("invalid");
      $("#confirm-password").addClass("valid");
    }else {
      $("#confirm-password").removeClass("valid");
      $("#confirm-password").addClass("invalid");
    }
    return matching;
}

function checkPassword(){
    var password=$("#password").val();
    if (password.length<8){
      $("#password").removeClass("valid");
      $("#password").addClass("invalid");
      return 0;
    }
    if (password.length>=8){
      $("#password").removeClass("invalid");
      $("#password").addClass("valid");
    }
    var valid=checkConfirmationPassword();
    return valid;
}

(function confirmationPasswordFieldChange(){
  $("#confirm-password").on("change",function(){
  checkConfirmationPassword();
  });
})();

(function passwordFieldChange(){
  $("#password").on("change",function(){
    checkPassword();
  });
})();


(function processRegistration(){
  $("#registration-submit").on("click", function(e){
    e.preventDefault();
    console.log("Registration Button Clicked");
    var name=$("#first_name").val()+ " " +$("#last_name").val();
    var college=$("#college").val();
    var email=$("#email").val();
    var password=$("#password").val();
    var isPasswordValid=checkPassword();
    var isEmailValid=$("#email").hasClass("valid");
    if (isPasswordValid && isEmailValid){
      var data={
           name:name,
           college:college,
           email:email,
           password:password
         };
         console.log(data);
      $.post("register.php",data)
        .done(function(data){
          console.log(data);
          //Add Code Here
        });
    }
  });
})();


(function processLogin(){
  $("#login-submit").on("click", function(e){
    e.preventDefault();
    console.log("Login Button Clicked");
    var email=$("#login-email").val();
    var password=$("#login-password").val();
    var isEmailValid=$("#login-email").hasClass("valid");
    if (isEmailValid){
      var data={
           email:email,
           password:password
         };
      $.post("login.php",data)
        .done(function(data){
          console.log(data);
          //Add Code Here
        });
      };
  });
})();