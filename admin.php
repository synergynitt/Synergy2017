<?php
require 'functions.php';
require 'connect.php';
require 'events.php';
?>
<!doctype html>
<html>
<head>
  <title>Synergy 2016</title>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <script src="js/jquery.min.js"></script>
  <link rel="stylesheet" href="materialize/css/materialize.min.css">
  <script src="materialize/js/materialize.min.js"></script>

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/slider.css">
  <link rel="stylesheet" href="css/notebook.css" type="text/css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>


<body>

  <?php
  $loggedin = 0;
  if (isset($_POST['username']) && isset($_POST['password'])){
    if ($_POST['username']=="Synergy" && $_POST['password']=="Synergy@16"){
      $loggedin=1;
    }
  }
  if ($loggedin == 1){
    ?>
    <section class="navigationbar">
      <ul id="dropdown1" class="dropdown-content">
        <?php
        foreach ($events as $event => $eventName) {
          ?>
          <li><a href="#<?php echo $event ?>"><?php echo $eventName ?></a></li>
          <?php
        }
        ?>
      </ul>
      <ul id="dropdown2" class="dropdown-content">
        <?php
        foreach ($workshops as $workshop => $workshopName) {
          ?>
          <li><a href="#<?php echo $workshop ?>"><?php echo $workshopName ?></a></li>
          <?php
        }
        ?>
      </ul>
      <div class="navbar-fixed">
        <nav>
          <div class="nav-wrapper green darken-2">
            <ul class="left hide-on-med-and-down">
              <li><a class="dropdown-button" href="#!" data-activates="dropdown1" >Events Registration List<i class="material-icons right">arrow_drop_down</i></a></li>
               <li><a class="dropdown-button" href="#!" data-activates="dropdown2" >Workshops Registration List<i class="material-icons right">arrow_drop_down</i></a></li>
               <li><a href="#CARegistrationList">Campus Ambassadors</a></li>
              <li><a href="#accomodationregistrationlist">Accomodation</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </section>
    <section class="eventregistrationlist">
      <?php
      foreach ($events as $event => $eventName) {
        $sqlSelectEvent= "SELECT * FROM `events` WHERE `$event`='1' ";
        $selectEventResult = executeQuery($db,$sqlSelectEvent);
        if ($selectEventResult->num_rows>0){
          ?>
          <div class="header" id = "<?php echo $event; ?>"> <?php echo $eventName ?> (Total Registrations:<?php echo $selectEventResult->num_rows ?>)</div>
          <table class="striped highlight">
            <thead>
              <tr>
                <th>Group ID</th>
                <th>Group Name</th>
                <th>Members</th>
                <th>Emails</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while($groupRegisteredForEvent = $selectEventResult->fetch_assoc()){
                $groupid= $groupRegisteredForEvent['groupid'];
                $sqlSelectGroup = "SELECT * FROM `groups` WHERE `groupid` =\"$groupid\"";
                $selectGroupResult = executeQuery($db, $sqlSelectGroup);
                $group = $selectGroupResult->fetch_assoc();
                $groupName = str_replace('.',' ',$group['groupname']);
                $sqlSelectGroupMembers = "SELECT * FROM `usergroup` WHERE `groupid`=\"$groupid\"";
                $selectGroupMembersResult = executeQuery($db, $sqlSelectGroupMembers);
                $groupMembers="";
                $groupMembersEmails="";
                if ($selectGroupMembersResult->num_rows>0){
                  while ($groupMember = $selectGroupMembersResult->fetch_assoc()){
                    $userid=$groupMember['userid'];
                    $sqlSelectUser = "SELECT * FROM `users` WHERE  `userid`=\"$userid\"";
                    $selectUserResult = executeQuery($db, $sqlSelectUser);
                    $username = $selectUserResult->fetch_assoc();
                    $name = $username['name'];
                    $email = $username['email'];
                    $groupMembers .= $name . ", ";
                    $groupMembersEmails.= $email . ", ";
                  }
                }
                ?>
                <tr>
                  <td><?php echo $groupid ?></td>
                  <td><?php echo ucwords(strtolower($groupName)) ?></td>
                  <td><?php $groupMembers = str_replace('.',' ',trim($groupMembers, ", ")); echo ucwords(strtolower($groupMembers)) ?></td>
                  <td><?php $groupMembersEmails = trim($groupMembersEmails, ", "); echo strtolower($groupMembersEmails) ?></td>
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
        <?php
      }
      ?>
    </section>
    <div class="divider"></div>
    <section class="workshopRegistrationsList">
      <?php
      foreach ($workshops as $workshop => $workshopName) {
        $sqlSelectWorkshop= "SELECT * FROM `workshops` WHERE `$workshop`<>'0' ORDER BY `$workshop` ";
        $selectWorkshopResult = executeQuery($db,$sqlSelectWorkshop);
        if ($selectWorkshopResult->num_rows>0){
          ?>
          <div class="header" id = "<?php echo $workshop; ?>"> <?php echo $workshopName ?> (Total Registrations:<?php echo $selectWorkshopResult->num_rows ?>)</div>
          <table class="striped highlight">
            <thead>
              <tr>
                <th>Group ID</th>
                <th>Group Name</th>
                <th>Members</th>
                <th>Email</th>
                <th>Slot</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while($groupRegisteredForWorkshop = $selectWorkshopResult->fetch_assoc()){
                $groupid= $groupRegisteredForWorkshop['groupid'];
                $slot = $groupRegisteredForWorkshop[$workshop];
                $sqlSelectGroup = "SELECT * FROM `groups` WHERE `groupid` =\"$groupid\"";
                $selectGroupResult = executeQuery($db, $sqlSelectGroup);
                $group = $selectGroupResult->fetch_assoc();
                $groupName = str_replace('.',' ',$group['groupname']);
                $sqlSelectGroupMembers = "SELECT * FROM `usergroup` WHERE `groupid`=\"$groupid\"";
                $selectGroupMembersResult = executeQuery($db, $sqlSelectGroupMembers);
                $groupMembers="";
                $groupMembersEmails="";
                if ($selectGroupMembersResult->num_rows>0){
                  while ($groupMember = $selectGroupMembersResult->fetch_assoc()){
                    $userid=$groupMember['userid'];
                    $sqlSelectUser = "SELECT * FROM `users` WHERE  `userid`=\"$userid\"";
                    $selectUserResult = executeQuery($db, $sqlSelectUser);
                    $username = $selectUserResult->fetch_assoc();
                    $name = $username['name'];
                    $email = $username['email'];
                    $groupMembers .= $name . ", ";
                    $groupMembersEmails .= $email . ", ";
                  }
                }
                ?>
                <tr>
                  <td><?php echo $groupid ?></td>
                  <td><?php echo ucwords(strtolower($groupName)) ?></td>
                  <td><?php $groupMembers = str_replace('.',' ',trim($groupMembers, ", ")); echo ucwords(strtolower($groupMembers)) ?></td>
                  <td><?php $groupMembersEmails = trim($groupMembersEmails, ", "); echo strtolower($groupMembersEmails) ?></td>
                  <td><?php echo $slot ?></td>
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
        <?php
      }
      ?>
    </section>
    <div class="divider"></div>
    <section class="CARegistrationList" id="CARegistrationList">
      <?php
      $sqlCARegistration = "SELECT * FROM `ambassadors`";
      $CARegistrationResult = executeQuery($db, $sqlCARegistration);
      ?>
      <div class="header">Campus Ambassodors (Total Registration: <?php echo $CARegistrationResult->num_rows ?>)</div>
      <table class="striped highlight">
        <thead>
          <th>Name</th>
          <th>College</th>
          <th>FB Name</th>
          <th>Email</th>
          <th>Phone</th>
        </thead>
        <tbody>
          <?php
          while($row=$CARegistrationResult->fetch_assoc()){
            $name = str_replace('.',' ',$row['name']);
            $college = str_replace('.',' ',$row['college']);
            $fbname = $row['fbname'];
            $email = $row['email'];
            $phone = $row['phone'];
            ?>
            <tr>
              <td><?php echo ucwords(strtolower($name)) ?></td>
              <td><?php echo ucwords($college) ?></td>
              <td><?php echo $fbname ?></td>
              <td><?php echo $email ?></td>
              <td><?php echo $phone ?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
      <?php
      ?>
    </section>
    <div class="divider"></div>
    <section class="accomodationregistrationlist" id="accomodationregistrationlist">
      <?php
      $sqlAccomodationRegistration = "SELECT * FROM `accomodation`";
      $accomodationRegistrationResult = executeQuery($db, $sqlAccomodationRegistration);
      ?>
      <div class="header">Accomodation (Total Registration: <?php echo $accomodationRegistrationResult->num_rows ?>)</div>
      <table class="striped highlight">
        <thead>
          <th>User ID</th>
          <th>Name</th>
          <th>Rollno</th>
          <th>College</th>
          <th>Email</th>
          <th>Phone</th>
        </thead>
        <tbody>
          <?php
          while($row=$accomodationRegistrationResult->fetch_assoc()){
            $userid = $row['userid'];
            $name = str_replace('.',' ',$row['name']);
            $college = str_replace('.',' ',$row['college']);
            $rollno = $row['rollno'];
            $email = $row['email'];
            $phone = $row['phone'];
            ?>
            <tr>
              <td><?php echo $userid ?></td>
              <td><?php echo ucwords(strtolower($name)) ?></td>
              <td><?php echo $rollno ?></td>
              <td><?php echo ucwords($college) ?></td>
              <td><?php echo $email ?></td>
              <td><?php echo $phone ?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
      <?php
      ?>
    </section>
    <?php
  }else {
    ?>
    <section id="adminloginform" class="adminloginform">
      <div class="valign-wrapper fullheight ">
        <div class="valign center-align fullwidth">
          <div class="row">
            <div class="col s12 l4 m4 offset-m4 offset-l4">
              <form action="admin.php" method="post">
                <div class="row">
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="username" name="username" type="text" class="validate">
                      <label for="username">Admin Username</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="password" name="password" type="password" class="validate">
                      <label for="password">Password</label>
                    </div>
                  </div>
                  <div class="row">
                    <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <?php
  }
  ?>
  <script type="text/javascript">
  $("a:not(.dropdown-button)").click(function(e){
    // console.log(this);
    var link = $(this).attr('href');
    // console.log(link);
    if (link!="#!"){
      e.preventDefault();
      console.log($(link).attr("id"));
      $(link).animate
      $('html, body').animate({
        scrollTop: $(link).offset().top-70
      }, 800);

    }else{
      console.log("hello");
    }
  });
  </script>
</body>
</html>
