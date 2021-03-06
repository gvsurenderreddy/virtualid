<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
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
    <title>VirtualID - Profil de </title>
    <link type="text/css" rel="stylesheet" media="screen" href="css/converse.min.css" />
  </head>

  <body>
      <input id="myid" value="<?php echo (string)$_SESSION['user']['_id']; ?>" type="hidden" >
    <?php include_once 'page_includes/navbar.php'; ?>
    <div class="container" style="position:relative;top:50px;">
      <div class="container-fluid">
        <!-- SIDEBAR -->
        <div style="width:20%;float:left;padding:10px;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img id="identity-avatar" class="img-thumbnail" src="" alt="no_avatar" style="width:128px;height:128px;background-color:white;margin-top:-5px;margin-right:5px;"></a>
          <br/><h3 id="identity-name"></h3>
          <?php if(isset($_SESSION['user']))
          { ?>
          <div id="identity-actions">
          <div id="identity-actions-buttons" style="margin-top:15px;margin-bottom:15px;" class="btn-group" role="group" aria-label="Identity action group">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></button>
          </div>
          </div>
          <?php
          } ?>
          <input id="identity-id" value="<?php echo $_GET['userid']; ?>" type="hidden" >
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Ses amis</h3>
            </div>
            <div class="panel-body">
              <div id="his-user-panel" class="row">
              </div>
            </div>
          </div>
        </div>
      	<!-- Main content -->
      	<div style="width:77%;float:left;">
          <!-- Identity tabs -->
          <ul style="margin-bottom: 15px;margin-left: 5px;" role="tablist" class="nav nav-pills">
            <li role="presentation" class="active"><a id="hisStream" aria-controls="stream" role="tab" data-toggle="tab" href="#stream">Son flux</a></li>
            <li role="presentation"><a id="hisPhotos" aria-controls="photos" role="tab" data-toggle="tab" href="#photos">Ses photos</a></li>
            <li role="presentation"><a id="hisFriends" aria-controls="friends" role="tab" data-toggle="tab" href="#friends">Ses amis</a></li>
          </ul>
          <div id="tabContent" class="tab-content">
            <!-- stream content -->
            <div class="tab-pane fade in active" role="tabpanel" id="stream">
              <!-- Stream posts list -->
              <div id="posts-stream">
                <div id="loadingAnimation"><canvas id="c"></canvas></div>
              </div>
            </div>
            <!-- Messages content -->
            <div class="tab-pane fade" role="tabpanel" id="messages">
            </div>
            <!-- Photos gallery -->
            <div role="tabpanel" class="tab-pane fade" id="photos">
              <div class="row">
                <!--<div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                 <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>
                <div class="col-lg-3 col-sm-5 col-xs-6">
                    <a href="#">
                         <img src="http://placehold.it/200x200" class="thumbnail img-responsive">
                    </a>
                </div>-->
              </div>
              <hr>
              <br><br>
            </div>
            <!-- Friends gallery -->
            <div style="position:absolute;top:200px;width:53%;" role="tabpanel" class="tab-pane fade" id="friends">
            </div>
          </div>
    </div>
    <!-- SCRIPTS -->
    <?php require_once 'page_includes/footer.php'; ?>
    <script type="text/javascript">
        //now some variables for canvas and math
        var canvas = document.getElementById('c');
        var context = canvas.getContext('2d');
        var x = canvas.width / 2; //the center on X axis
        var y = canvas.height / 2; //the center on Y axis
        $(document).ready(function() {
        showLoadingAnimation( distanceArrows, arrowStrength );
        setTimeout(function(){
            var searchFriendBar = $('#searchFriendBar').magicSuggest({
                allowFreeEntries: false,
                data: 'functions/get-all-users.php',
                valueField: 'id',
                displayField: 'userresult'
            });
            $(searchFriendBar).on('selectionchange', function(e,m){
              showIdentity(this.getValue());
            });
            $('[data-toggle="tooltip"]').tooltip();
        }, 500);
        loadIdentity();
        <?php if(isset($_SESSION['user']))
        { ?>
        updateNotifications();
        setInterval(updateNotifications, 30000);
        <?php
        } ?>
      });
    </script>
  </body>
  <?php include_once 'page_includes/instant-message-module.php'; ?>
</html>
