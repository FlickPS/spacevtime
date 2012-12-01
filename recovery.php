<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SvT User Log-in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/twitter/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body 
      {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="/twitter/css/bootstrap-responsive.css" rel="stylesheet">   
    <link href="/password/analyzer.css" rel="stylesheet"/> 

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/twitter/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/twitter/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/twitter/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/twitter/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/twitter/ico/apple-touch-icon-57-precomposed.png">

    <script>

    </script>

  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="http://www.flickps.com">FlickPS.com</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="index.php"><i class="icon-home"></i>&nbsp; Home</a></li>
            </ul>
            <p class="navbar-text pull-right"><strong> :) </strong></p>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <div class="row">
      <div class="span9">	

  <form method="post" action="verify.php" name="form1" class="form-horizontal">
    <div class="control-group">
        <div class="controls">
            <h4>Password Recovery</h4>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="Username">Your Username</label>
            <div class="controls">
                  <input type="text" id="Username" placeholder="Your Username" name="Username">
            </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="SendEmail">Your Email</label>
            <div class="controls">
                  <input type="text" id="SendEmail" placeholder="Your Email" name="SendEmail">
            </div>
    </div>  
    
  <?php
    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "1")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>No such username or email address...</strong></span>\n";
            print "</div></div>\n";
        }
    }
    if ($_GET["success"])
    {            
        if ($_GET["success"] == "1")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<font color=green><strong>Password sent! ^_^</strong></font>\n";
            print "</div></div>\n";
        }
    }
	
	?>
	
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-info btn-large" name="SendPassword">Send Password !</button>
        </div>
    </div>
	</form>
	
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	
	<form method="post" action="verify.php" name="form2" class="form-horizontal">
    <div class="control-group">
        <div class="controls">
            <h4>Change Password</h4>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputuser">Your Username</label>
            <div class="controls">
                  <input type="text" id="inputuser" placeholder="Username" name="UserID">
            </div>
    </div>
    
  <?php
    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "2")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>Wrong username or password.</strong></span>\n";
            print "</div></div>\n";
        }
    }
	
	?>
	
    <div class="control-group">
        <label class="control-label" for="inputPassword3">Old Password</label>
            <div class="controls">
                  <input type="password" placeholder="Re-enter Password" name="OldPass" id="OldPass">
            </div>
    </div> 
    <div class="control-group">
        <label class="control-label" for="inputPassword1">New Password</label>
            <div class="controls">
                  <input type="password" id="inputPassword1" placeholder="Password" name="NewPass" onkeyup ="chkPasswordStrength(this.value,document.getElementById('strendth'),document.getElementById('passError'))" size="40" type="password" value="" />
                  <span id="passError"></span>        
                  &nbsp; <span id="strendth"></span>
            </div>
    </div>   
    <div class="control-group">
        <label class="control-label" for="inputPassword2">Password (again)</label>
            <div class="controls">
                  <input type="password" placeholder="Re-enter Password" name="confirmPass" id="confirmPass">
            </div>
    </div>  

  <?php
    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "3")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>Please check the passwords again!</strong></span>\n";
            print "</div></div>\n";
        }
    }
	
	?>    
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-success btn-large" name="ChangePassword">Change Password !</button>
        </div>
    </div>
	</form>

      </div>
            
      <!--          
      <div class="span3">	
          <p>&nbsp;</p>
          <p align="right">
            Advertisement
          </p>
      </div>
      -->
      
      </div>

    </div> <!-- /container -->

    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <footer class="footer">
        <center>Hosted by <a href="https://www.singaporehost.sg/customers/aff.php?aff=235" target="_blank">Singapore Host</a>.</center>
        <center>&copy; 2012 FlickPS Software Singapore. All Rights Reserved. =)</center>
    </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/twitter/js/jquery.js"></script>
    <script src="/twitter/js/bootstrap-transition.js"></script>
    <script src="/twitter/js/bootstrap-alert.js"></script>
    <script src="/twitter/js/bootstrap-modal.js"></script>
    <script src="/twitter/js/bootstrap-dropdown.js"></script>
    <script src="/twitter/js/bootstrap-scrollspy.js"></script>
    <script src="/twitter/js/bootstrap-tab.js"></script>
    <script src="/twitter/js/bootstrap-tooltip.js"></script>
    <script src="/twitter/js/bootstrap-popover.js"></script>
    <script src="/twitter/js/bootstrap-button.js"></script>
    <script src="/twitter/js/bootstrap-collapse.js"></script>
    <script src="/twitter/js/bootstrap-carousel.js"></script>
    <script src="/twitter/js/bootstrap-typeahead.js"></script>
    <script src="/password/analyzer.js"></script>

  </body>

  <script> 
    $(function(){
      $('a[rel=popover]').tooltip();
    });
  </script>

</html>
