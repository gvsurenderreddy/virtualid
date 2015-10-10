<?php
session_start();
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);

include 'functions/fb-api.php';
include 'functions/islogged.php';
include 'functions/validate-fb-sub.php';
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <style type="text/css">
      /* add a little bottom space under the images */
      .thumbnail {
        margin-bottom:7px;
      }
    </style>
    <?php include_once "page_includes/header.php" ?>
    <link type="text/css" rel="stylesheet" media="screen" href="css/converse.css" />
    <![if gte IE 9]>
        <script src="js/converse.min.js"></script>
    <![endif]>
  </head>

  <body>
    <script>
    // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {
      // The response object is returned with a status field that lets the
      // app know the current login status of the person.
      // Full docs on the response object can be found in the documentation
      // for FB.getLoginStatus().
      if (response.status === 'connected') {
        // Logged into your app and Facebook.
      } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        window.location = "index.php";
      } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        //window.location = "index.php";
      }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }

    window.fbAsyncInit = function() {
    FB.init({
      appId      : '117561025264451',
      cookie     : true,  // enable cookies to allow the server to access
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.2' // use version 2.2
    });

    // Now that we've initialized the JavaScript SDK, we call
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/fr_FR/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function logout() {
      FB.logout(function(response) {
        converse.user.logout();
        // Person is now logged out
        $.ajax({
          type: "GET",
          url: "functions/logout.php",
          complete: function(response) {
            window.location = "index.php";
          }
        });
      });
      $.ajax({
        type: "GET",
        url: "functions/logout.php",
        complete: function(response) {
          window.location = "index.php";
        }
      });
    }
    </script>
  	<!-- NAVIGATION BAR -->
  	<div class="navbar-wrapper">
      <div class="container">
        <div class="navbar navbar-inverse navbar-static-top" style="border-radius: 4px;" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="stream.php">VirtualID</a>
            </div>
            <div class="navbar-collapse collapse">
              <!-- NAVBAR LEFT (Menu) -->
              <?php if(!$useFacebookConnect || $user->isFacebookLinked()){ ?>
      			  <ul class="nav navbar-nav navbar-left">
        				<li><a href="stream.php">Mon flux</a></li>
        				<li><a href="stream.php#messages">Messages privés<span class="badge">1</span></a></li>
      			  </ul>
              <?php } ?>
              <!-- NAVBAR RIGHT (Searchbar, Notifications, Account) -->
              <ul class="nav navbar-nav navbar-right">
              	<?php if(!$useFacebookConnect || $user->isFacebookLinked()){ ?>
                  <li>
                    <form class="navbar-form navbar-right">
                      <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                        <input style="width:200px;" id="searchFriendBar" class="form-control" type="text" placeholder="Rechercher des amis..." aria-describedby="basic-addon1">
                      </div>
                    </form>
                  </li>
                  <li id="notifPanel"></li>
                  <?php } ?>
                <li class="dropdown active" style="margin-right:50px;">
              			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="media-object" src="<?php echo 'avatars/'.$user->getId(); ?>" alt="no_avatar" style="float:left;width:32px;height:32px;background-color:white;margin-top:-5px;margin-right:5px;"><?php echo $user->getUsername() ?> <b class="caret"></b></a>
              			<ul class="dropdown-menu">
                      <?php if(!$useFacebookConnect || $user->isFacebookLinked()){ ?>
                			<li><a href="account.php">Mon compte</a></li>
                			<li><a href="account.php#settings">Paramètres</a></li>
                			<li class="divider"></li>
                      <?php } ?>
                			<li><a onclick="logout()" href="#">Déconnexion</a></li>
              			</ul>
              		</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php if(!$useFacebookConnect || $user->isFacebookLinked()){ ?>
    <div class="container">
      <div class="container-fluid">
        <!-- SIDEBAR -->
        <div style="width:20%;float:left;padding:10px;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="img-thumbnail" src="<?php echo 'avatars/'.$user->getId(); ?>" alt="no_avatar" style="width:128px;height:128px;background-color:white;margin-top:-5px;margin-right:5px;"></a>
          <br/>
          <form enctype="multipart/form-data">
              <input name="file" type="file" />
              <input type="button" value="Upload" />
          </form>
          <!--<progress></progress>-->
          <input value="<?php echo $user->getId(); ?>" id="userid" type="hidden" >
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Mon compte</h3>
            </div>
            <div class="panel-body">
            </div>
          </div>
        </div>
      	<!-- Main content -->
      	<div style="width:77%;float:left;">
          <div id="tabContent" class="tab-content">

          </div>
    </div>
  <?php } else { ?>
    <!-- Facebook subscription validation -->
    <div id="login-form" class="container">
      <form action="stream.php" class="form-signin" role="form" method="post">
        <h3 class="form-signin-heading">Validez vos informations</h3>
        <p>Ces informations proviennent de votre compte Facebook.</p>
        <p><em>Votre adresse E-mail ne sera pas visible des autres utilisateurs. Elle sera utilisée uniquement pour :<br/>
          <ul>
            <li> Vous connecter</li>
            <li> En cas de perte de votre mot de passe</li>
          </ul>
        </em></p>
        <input value="<?php echo $user->getUsername() ?>" name="username" type="text" placeholder="Nom d'utilisateur" class="form-control" required autofocus>
        <input value="<?php echo $user->getEmail() ?>" name="email" type="email" placeholder="E-mail" class="form-control" required>
        <br/><p>Créez un mot de passe pour votre compte VirtualID.</p>
        <input name="password" type="password" class="form-control" placeholder="Mot de passe" required>
        <input name="passwordcheck" type="password" class="form-control" placeholder="Vérification mot de passe" required>
        <p style="color:red;"> <?php if(isset($erreur))echo $erreur; ?> </p>
        <button name="validate-fb-sub" value="validate-fb-sub" class="btn btn-lg btn-primary btn-block" type="submit">Valider</button>
      </form>
    </div>
  <?php } ?>
	  <!-- BOOTSTRAP NAVIGATION SCRIPTS -->
    <script>
      require(['converse'], function (converse) {
          (function () {
              /* XXX: This function initializes jquery.easing for the https://conversejs.org
              * website. This code is only useful in the context of the converse.js
              * website and converse.js itself is NOT dependent on it.
              */
              var $ = converse.env.jQuery;
              $.extend( $.easing, {
                  easeInOutExpo: function (x, t, b, c, d) {
                      if (t==0) return b;
                      if (t==d) return b+c;
                      if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
                      return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
                  },
              });

              $(window).scroll(function() {
                  if ($(".navbar").offset().top > 50) {
                      $(".navbar-fixed-top").addClass("top-nav-collapse");
                  } else {
                      $(".navbar-fixed-top").removeClass("top-nav-collapse");
                  }
              });
              //jQuery for page scrolling feature - requires jQuery Easing plugin
              $('.page-scroll a').bind('click', function(event) {
                  var $anchor = $(this);
                  $('html, body').stop().animate({
                      scrollTop: $($anchor.attr('href')).offset().top
                  }, 700, 'easeInOutExpo');
                  event.preventDefault();
              });
          })();
          converse.initialize({
              bosh_service_url: 'https://octeau.fr:7443/http-bind/', // Please use this connection manager only for testing purposes
              keepalive: true,
              message_carbons: true,
              play_sounds: true,
              roster_groups: true,
              show_controlbox_by_default: false,
              xhr_user_search: false,
              allow_registration: false,
              jid: '<?php echo $user->getUsername(); ?>@octeau.fr',
              password: '<?php echo md5($user->getPasswordHash()); ?>',
              authentication: 'login',
              auto_login: true,
              auto_reconnect: true,
              hide_muc_server: true,
              message_archiving: true,
              cache_otr_key: true,
              auto_subscribe: true,
              auto_away: 30,
              allow_contact_requests: false,
              allow_contact_removal: false,
              hide_offline_users: true
          });
      });
  </script>
</body>