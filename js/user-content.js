var loggedin=0;
var fblogin=0;
var eventcodes;
var workshopcodes;
var email;
var owngroup;

(function getEventMemberCount(){
  $.get("getEventCodes.php")
    .done(function(data){
      eventcodes = JSON.parse(data);
      // console.log(eventcodes);
    });
})();

(function getWorkshopMemberCount(){
  $.get("getWorkshopCodes.php")
    .done(function(data){
      workshopcodes = JSON.parse(data);
      // console.log(eventcodes);
    });
})();

(function hideUserContent(){
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

(function onConfirmationPasswordFieldChange(){
  $("#confirm-password").on("change",function(){
  checkConfirmationPassword();
  });
})();

(function onPasswordFieldChange(){
  $("#password").on("change",function(){
    checkPassword();
  });
})();

(function processRegistration(){
  $("#registration-submit").on("click", function(e){
    e.preventDefault();
    var name=$("#first_name").val()+ " " +$("#last_name").val();
    var college=$("#college").val();
    var email=$("#email").val();
    var rollno=$("#rollno").val();
    var password=$("#password").val();
    var phone = $("#phone").val();
    var isPasswordValid=checkPassword();
    var isEmailValid=$("#email").hasClass("valid");
    if (isPasswordValid && isEmailValid){
      var data={
           "name":name,
           "college":college,
           "rollno":rollno,
           "email":email,
           "phone":phone,
           "password":password
         };
      console.log(data);
      $.post("register.php",data)
        .done(function(data){
          console.log(data);
            var response=JSON.parse(data);
            console.log(response);
            if (response.status==="success"){
              loggedin=1;
              loadUserContent();
            }else {
              $("#synergy-registration-status").empty();
              $("#synergy-registration-status").html(response.description);
            }
        });
    }
  });
})();

(function processLogin(){
  $("#login-submit").on("click", function(e){
    e.preventDefault();
    var email=$("#login-email").val();
    var password=$("#login-password").val();
    var isEmailValid=$("#login-email").hasClass("valid");
    if (isEmailValid){
      var data={
           "email":email,
           "password":password
         };
      $.post("login.php",data)
        .done(function(data){
          var response=JSON.parse(data);
          // console.log(response);
          if (response.status==="success"){
            loggedin=1;
            $(".reg-result").empty();
            loadUserContent();
          }else {
            loggedin=0;
            $("#synergy-registration-status").empty();
            $("#synergy-registration-status").html(response.description);
          }
        });
      };
  });
})();

(function processLogout(){
  $("#logout").on("click", function(e){
    e.preventDefault();
    if (fblogin==1){
      FB.logout(function(response) {
        $.post("logout.php")
          .done(function(data){
              var response=JSON.parse(data);
              // console.log(response);
              if (response.status==="logout"){
                fblogin = 0;
                logout();
              }
          });
      });
    }else{
      $.post("logout.php")
        .done(function(data){
            var response=JSON.parse(data);
            // console.log(response);
            if (response.status==="logout"){
              logout();
            }
        });
    }
  });
})();

function loadUserContent(){
  $("#synergy-reg").hide();
  $("#synergy-user-content").show();
  var url="getusercontent.php";
  $.get(url)
  .done(function(data){
    var response=JSON.parse(data);
    if (response.status=="success"){
      loggedin = 1;
      $("#registeredEvents").empty();
      email = response.email;
      owngroup = response.owngroup;
      var registeredEvents=response.registeredEvents;
      var registeredEventsCode=response.registeredEventsCode;
      var registeredEventGroupName = response.registeredEventGroupName;
      var registeredEventGroupId = response.registeredEventGroupId;
      var i;
      for (i=0;i<registeredEvents.length;i++){
        var id="deregister_"+registeredEventsCode[i];

        var tag = '<tr><td>' + registeredEvents[i] + '</td><td>'+registeredEventGroupName[i]+'</td><td><div class="chip right green deregister" id="'+id+'"">Unregister    <i class="material-icons">close</i></div></td></tr>';
        $("#registeredEvents").append(tag);

        $("#"+id).on("click", (function(eventcode, groupid){
          return function(){
            deregisterEvent(eventcode, groupid);
          }
        })(registeredEventsCode[i], registeredEventGroupId[i]) );

      }

      $("#registeredWorkshops").empty();
      var registeredWorkshops =response.registeredWorkshops;
      var registeredWorkshopsSlots = response.registeredWorkshopsSlots;
      var registeredWorkshopsGroupName = response.registeredWorkshopsGroupName;
      for (i = 0; i < registeredWorkshops.length; i++){
        var tag = '<tr><td>' + registeredWorkshops[i] + '</td><td class="center-align">' + registeredWorkshopsSlots[i] + '</td><td class="center-align">' + registeredWorkshopsGroupName[i] + '</td></tr>';
        $("#registeredWorkshops").append(tag);
      }



    }else {
      logout();
    }

  });
}

function logout(){
  loggedin=0;
  $("#synergy-reg").show();
  $("#synergy-user-content").hide();
  $(".reg-result").empty();
}

function selectGroupId(callback, maxmembers){
  var groupId = 0;
  var url = 'getuserdetails.php?email='+email;
  $.get(url)
    .done(function(data){
      var userDetails = JSON.parse(data);
      if (userDetails.status==="error"){
        console.log(userDetails);
        return;
      }
      var noofgroups = userDetails.user.noofgroups;
      var groups = userDetails.user.groups;
      $("#selectFromList").empty();
      $("#selectFromList").show();
      $("#registerNewGroupWindow").hide();
      $("#select-group-id").show();
      for (var i = 0 ; i < noofgroups; i++){
        if (groups[i].id == owngroup || groups[i].members > maxmembers){
          continue;
        }
        var tag = '<tr><td>' + groups[i].name + '</td><td>' + groups[i].members + ' Members</td><td><div class="chip right green register" id="register-' + groups[i].id + '"">Register</div></td></tr>';
        $("#selectFromList").append(tag);

        $("#register-"+groups[i].id).on("click", (function(callback , groupId){
          return function(){
            callback(groupId);
            $("#select-group-id").hide();
          }
        })(callback, groups[i].id));

      }
      var tag = '<tr><td colspan="3" class="center-align"><div class="chip right green register" id="registerNewGroup">Register New Group </div></td></tr>';
      $("#selectFromList").append(tag);

      $("#registerNewGroup").on("click", function(){

        console.log("registerNewGroup");
        $("#selectFromList").empty();
        $("#selectFromList").hide();
        $("#select-group-id").show();
        $("#registerNewGroupWindow > *").hide();
        $("#selectGroupName").show();

        $("#registerNewGroupWindow").show();
        $("#noofmembers").attr("max", maxmembers);
        $("#groupNameRegistrationSubmit").on("click",function () {
          var members=$("#noofmembers").val();
          var groupName = $("#groupName").val();
          console.log(members, groupName);
          if (!(groupName != "" && members != "")){
            console.log("Cancelling");
            $("#select-group-id").hide();
            return;
          }

          $("#selectGroupName").hide();
          $("#getUsersData").empty();

          var tag = '<div class="col s12 m12 l6 sub-header center-align">Group Name : '+ groupName  +'</div><div class="col s12 m12 l6 sub-header center-align">Members : '+ members +'</div>';
          $("#getUsersData").append(tag);
          var groupUsers={};
          var tag = '<div class="col s12 l12 m12 center-align"><div class="row fullwidth "><div class="validate center-align input-field col s12 m12 l3"><input disabled  class="validate" id="member-1-email" type="email" ></div><div class="validate center-align input-field col s12 m12 l3"><input disabled  class="validate" id="member-1-name" type="text" ></div><div class="validate center-align input-field col s12 m12 l3"><input disabled  class="validate" id="member-1-rollno" type="text" ></div><div class="validate center-align input-field col s12 m12 l3"><input disabled  class="validate" id="member-1-college" type="text" ></div></div></div>';
          $("#getUsersData").append(tag);
          $("#member-1-email").val(userDetails.user.email);
          $("#member-1-name").val(userDetails.user.name);
          $("#member-1-rollno").val(userDetails.user.rollno);
          $("#member-1-college").val(userDetails.user.college);
          for (var i = 2; i <= members ; i++){
            var tag = '<div class="col s12 l12 m12 center-align"><div class="row fullwidth ">';
            tag +='<div class=" center-align input-field col s12 m12 l3"><input  id="member-' + i + '-email" type="email" class="validate" ><label for="member-' + i + '-email">Member ' + i + ' Email </label> </div>';
            tag +='<div class=" center-align input-field col s12 m12 l3"><input  id="member-' + i + '-name" type="text" class="validate" ><label for="member-' + i + '-name">Member ' + i + ' Name </label> </div>';
            tag +='<div class=" center-align input-field col s12 m12 l3"><input  id="member-' + i + '-rollno" type="text" class="validate" ><label for="member-' + i + '-rollno">Member ' + i + ' Roll Number </label> </div>';
            tag +='<div class=" center-align input-field col s12 m12 l3"><input  id="member-' + i + '-college" type="text" class="validate" ><label for="member-' + i + '-college">Member ' + i + ' College </label> </div>';
            tag+='</div></div>';
            $("#getUsersData").append(tag);
          }
          var tag ='<div class="col s12 m12 l12 center-align"><button class="btn waves-effect waves-light green darken-3" type="submit" name="action" id="getUserDataEmailSubmit">Next<i class="material-icons right">send</i></button></div>';
          $("#getUsersData").append(tag);

          $("#getUsersData").show();
          $("#getUserDataEmailSubmit").on('click',function(){
            console.log("get email");
            var groupUserData=[];
            for (var i=0; i < members; i++){
              var memberid=i+1;
              memberid="#member-"+memberid;
              var userData={};
              userData.email = $(memberid+"-email").val();
              userData.name = $(memberid+"-name").val();
              userData.rollno = $(memberid+"-rollno").val();
              userData.college = $(memberid+"-college").val();
              if (userData.email===""){
                alert ("Email Can't be Empty");
                $("#select-group-id").hide();
                return;
              }
              groupUserData.push(userData);
            }
            var groupjsondata=JSON.stringify(groupUserData);
            var data ={
              groupjsondata:groupjsondata,
              groupName:groupName,
              members : members
            }
            $.post("registergroup.php",data)
              .done(function(data){
                var response=JSON.parse(data);
                if (response.status=="success"){
                  var groupId = response.groupid;
                  callback(groupId);
                  $("#select-group-id").hide();
                }else{
                  alert("Registration Failed");
                  $("#select-group-id").hide();
                  return;
                }
              });
          });
        });
      });
    });
}

function registerEvent(event){
  console.log(event);
  function callback(groupId){
    if (groupId == 0){
      console.log("Selection of GroupId failed")
      return;
    }
    var url="eventreg.php?event=" + event + "&groupid=" + groupId;
    $.get(url)
    .done(function(data){
      // console.log(data);
      var response=JSON.parse(data);
      if (response.status==="success"){
        $("#"+event+" .reg-result").html(response.description);
        loadUserContent();
      }else if (response.status==="fail"){
        $("#"+event+" .reg-result").html(response.description);
      }else if (response.status==="logout"){
        $("#"+event+" .reg-result").html(response.description);
        logout();
      }
    });
  }
  if (loggedin === 1){
    if (eventcodes[event] == 1){
      groupId = owngroup;
      callback(groupId);
    }else{
      var maxmembers = eventcodes[event];
      console.log(maxmembers);
      selectGroupId(callback, maxmembers);
    }
  }else{
      $("#"+event+" .reg-result").html("You need to login to register");
  }
}

function deregisterEvent(eventcode, groupid){
  // console.log(eventcode);
  if (loggedin === 1){
    var url="eventreg.php?event=" + eventcode + "&deregister=1&groupid="+groupid;
    $.get(url);
    loadUserContent();
  }else{
    logout();
  }
}

(function eventRegistrations(){
    $("#fixemup-register").on("click",function(e){
      registerEvent("fixemup");
    });
    $("#engineerofthefuture-register").on("click",function(e){
      e.preventDefault();
      registerEvent("engineerofthefuture");
    });
    $("#techyhunt-register").on("click",function(e){
      e.preventDefault();
      registerEvent("techyhunt");
    });
    $("#junkyardwars-register").on("click",function(e){
      e.preventDefault();
      registerEvent("junkyardwars");
    });
    $("#paperpresentation-register").on("click",function(e){
      e.preventDefault();
      registerEvent("paperpresentation");
    });
    $("#waterrocketry-register").on("click",function(e){
      e.preventDefault();
      registerEvent("waterrocketry");
    });
    $("#sanrachana-register").on("click",function(e){
      e.preventDefault();
      registerEvent("sanrachana");
    });
    $("#paperplane-register").on("click",function(e){
      e.preventDefault();
      registerEvent("paperplane");
    });
    $("#selfpropellingvehicle-register").on("click",function(e){
      e.preventDefault();
      registerEvent("selfpropellingvehicle");
    });
    $("#cadmodelling-register").on("click",function(e){
      e.preventDefault();
      registerEvent("cadmodelling");
    });
    $("#mcquiz-register").on("click",function(e){
      e.preventDefault();
      registerEvent("mcquiz");
    });
})();

function registerWorkshop(workshop){
  console.log(workshop);
  function callback(groupId){
    if (groupId == 0){
      console.log("Selection of GroupId failed")
      return;
    }

    var url="getAvailableSlots.php?workshop=" + workshop;
    $.get(url)
    .done(function(data){
      console.log(data);
      var slots = JSON.parse(data).slots;
      console.log(slots);
      $("#selectSlotform").empty();
      for (var i = 0; i < slots.length; i++){
        var tag = '<div class="input-field col s12 l12 m12"><input type="radio" id=' + slots[i] + ' name="selectSlotRadio" value ="' + slots[i] + '"><label for="' + slots[i] + '">' + slots[i] + '</label></div>';
        $("#selectSlotform").append(tag);
      }

      $("#selectSlotModal").openModal();
      $("#selectSlotSubmit").on("click",function (){
        console.log("asdf");
        var selectedRadio = $("#selectSlotform div input[type='radio']:checked");
        var selectedSlot = 0;
        if (selectedRadio.length >0){
          selectedSlot = $("#selectSlotform div input[type='radio']:checked").val();
        }
        console.log(selectedSlot);
        var url = "registerWorkshop.php?slot="+selectedSlot+"&groupid="+groupId;
        window.location.href=url;
      });
    });

  }
  if (loggedin === 1){
    if (workshopcodes[workshop] == 1){
      groupId = owngroup;
      callback(groupId);
    }else{
      var maxmembers = workshopcodes[workshop];
      console.log(maxmembers);
      selectGroupId(callback, maxmembers);
    }
  }else{
      $("." + workshop + ".reg-result").html("You need to login to register");
  }

}

(function workshopRegistrations(){
  $("#automobile-register").on("click", function(){
    registerWorkshop("automobile");
  });
  $("#3dprinting-register").on("click", function(){
    registerWorkshop("3dprinting");
  });
  $("#ornithopter-register").on("click", function(){
    registerWorkshop("ornithopter");
  });
  $("#robotics-register").on("click", function(){
    registerWorkshop("robotics");
  });
  $("#aeromodelling-register").on("click", function(){
    registerWorkshop("aeromodelling");
  });
  $("#autocad-register").on("click", function(){
    registerWorkshop("autocad");
  });
  $("#creo-register").on("click", function(){
    registerWorkshop("creo");
  });
  $("#solidworks-register").on("click", function(){
    registerWorkshop("solidworks");
  });
})();

$("#campus-ambassador").on('click',function(e){
  e.preventDefault();
  $('html, body').animate({
      'scrollTop' : $("#campus-ambassador-reg").position().top
  });
  $("#campus-ambassador-reg").show();

});
$("#campus-ambassador-reg-hide").on('click', function(){
  // console.log("hide");
  $("#campus-ambassador-reg").hide();
});
$("#select-group-hide").on('click',function(){
  $("#select-group-id").hide();
  $("#select-from-list").empty();
});

(function processCARegistration(){
  $("#CA-registration-submit").on("click", function(e){
    e.preventDefault();
    $(".ca-status").empty();
    var name=$("#CA_first_name").val()+ " " +$("#CA_last_name").val();
    var college=$("#CA_college").val();
    var email=$("#CA_email").val();
    var fbname=$("#CA_fbname").val();
    var phone=$("#CA_phone").val();
    var password=$("#CA_password").val();
    var confirmPassword=$("#CA_confirm_password").val();
    if (confirmPassword!=password || password===""){
      $("#CA_confirm_password").removeClass("valid");
      $("#CA_confirm_password").addClass("invalid");
      $("#CA_password").removeClass("valid");
      $("#CA_password").addClass("invalid");
      return;
    }else{
      $("#CA_confirm_password").removeClass("invalid");
      $("#CA_confirm_password").addClass("valid");
      $("#CA_password").removeClass("invalid");
      $("#CA_password").addClass("valid");
    }
    var data={
         "name":name,
         "college":college,
         "email":email,
         "fbname":fbname,
         "phone":phone,
         "password":password
       };
    // console.log(data);
    $.post("caregister.php",data)
      .done(function(data){
          var response=JSON.parse(data);
          if (response.status==="success"){
            $(".ca-status").empty();
            $(".ca-status").addClass("success");
            $(".ca-status").removeClass("error");
            $(".ca-status").html(response.description);
          }else if (response.status==="fail"){
            $(".ca-status").empty();
            $(".ca-status").removeClass("success");
            $(".ca-status").addClass("error");
            $(".ca-status").html(response.description);
          }
      });
  });
})();

$(document).on("ready",loadUserContent);

$("#accomodation").on("click",function(){
  $("#registerAccomodation").off("click");
  console.log("accomodation");
  $("#registerAccomodationModal").openModal();
  if (loggedin===0){
    $("#registerAccomodationModal p").empty();
    $("#registerAccomodationModal p").html("You need to login to register for Accomodation");
    $("#registerAccomodation").hide();
  }else{
    $("#registerAccomodation").show();
    $("#registerAccomodationModal p").empty();
    $("#registerAccomodationModal p").html("Click on Confirm to register for Accomodation. Payment will be collected on site.");
    $("#registerAccomodation").on("click",function(){
      $("#registerAccomodation").hide();
      $.get("registerAccomodation.php")
        .done(function(data){
          console.log(data);
          var response = JSON.parse(data);
          if (response.status === "success"){
            $("#registerAccomodationModal p").empty();
            $("#registerAccomodationModal p").html("Registered For Accomodation");
          }else{
            $("#registerAccomodationModal p").empty();
            $("#registerAccomodationModal p").html(response.description);
          }
        });
    });

  }
});

$("#contacts").on("click",function(){
  $("#contactsModal").openModal();
});
