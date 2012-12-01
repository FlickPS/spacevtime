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
            <h4>Log In</h4>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputuser">Username</label>
            <div class="controls">
                  <input type="text" id="inputuser" placeholder="Username" name="username" 
                      rel="tooltip" title="Case sensitive!" data-placement='bottom' >
            </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
                  <input type="password" id="inputPassword" placeholder="Password" name="password">
            </div>
    </div>    
    
<?php    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "1")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>Invalid username or password...</strong></span>\n";
            print "</div></div>\n";
        }
    }	
?>
	
    <div class="control-group">
        <div class="controls">
            <a href="recovery.php">Need to reset your password?</a>
        </div>
    </div>    
	
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-info btn-large" name="LogIn">Log in !</button>
        </div>
    </div>
	</form>
	
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	
	<a name="signup">&nbsp;</a>
	<form method="post" action="verify.php" name="form2" class="form-horizontal">
    <div class="control-group">
        <div class="controls">
            <h4>Or, Sign up!</h4>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputuser">Username</label>
            <div class="controls">
                  <input type="text" id="inputuser" placeholder="Username" name="nusername"
                      rel="tooltip" title="Case sensitive! Spaces allowed." data-placement='bottom' >
            </div>
    </div>
    
<?php    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "2")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>Username already taken...</strong></span>\n";
            print "</div></div>\n";
        }
    }	
?>

    <div class="control-group">
        <label class="control-label" for="inputPassword1">Password</label>
            <div class="controls">
                  <input type="password" id="inputPassword1" placeholder="Password" name="npassword" 
                      onkeyup ="chkPasswordStrength(this.value,document.getElementById('strendth'),document.getElementById('passError'))" 
                      rel="tooltip" title="Minimum 6 characters." data-placement='bottom' size="40" type="password" value="" />
                  <span id="passError"></span>        
                  &nbsp; <span id="strendth"></span><br/>                                   
            </div>
    </div>   
    <div class="control-group">
        <label class="control-label" for="inputPassword2">Password (again)</label>
            <div class="controls">
                  <input type="password" id="inputPassword2" placeholder="Re-enter Password" name="confirm">
            </div>
    </div>  
    <div class="control-group">
        <label class="control-label" for="inputemail">E-mail</label>
            <div class="controls">
                  <input type="text" id="inputemail" placeholder="Email Address" name="email"
                      rel="tooltip" title="Only used for password recovery." data-placement='bottom'>
            </div>
    </div>
    
<?php    
    if ($_GET["error"])
    {            
        if ($_GET["error"] == "3")
        {
            print "<div class='control-group'><div class='controls'>\n";
            print "<span class='label label-important'><strong>Please enter a valid username & password!</strong></span>\n";
            print "</div></div>\n";
        }
    }	
    
    if ($_GET["invite"] != "")
    {            
        print "<input type='hidden' name='InviteCode' value='" . intval($_GET["invite"]) . "'>";
    }
    else
    {
		print "<input type='hidden' name='InviteCode' value=''>";
    }
?>    

    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-success btn-large" name="Register">Register !</button>
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
      $('input[rel=tooltip]').tooltip();
    });
  </script>

</html>
