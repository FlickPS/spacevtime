<?php
    session_start();

    if (!(isset($_SESSION['login']) && $_SESSION['login'] != "")) 
    {
        header ("Location: /users.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SvT Timeline Events</title>
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
        
        function submitform(imgurl)
        {
			var day = document.getElementById("day").value;
			var month = document.getElementById("month").value;
			var year = document.getElementById("year").value;
			
			var days = 0;
			
			if (year != "")
				days += (parseInt(year)*416);
			if (month != "")
				days += (parseInt(month)*32);
			if (day != "")
				days += parseInt(day);
			
			document.getElementById("days").value = days.toString();
            document.getElementById("currentimg").value = imgurl;
            document.form.submit();
        }
        
        function updateevent()
        {
			var day = document.getElementById("day").value;
			var month = document.getElementById("month").value;
			var year = document.getElementById("year").value;
			
			var days = 0;
			
			if (year != "")
				days += (parseInt(year)*416);
			if (month != "")
				days += (parseInt(month)*32);
			if (day != "")
				days += parseInt(day);
				
			document.getElementById("days").value = days.toString();
			document.getElementById("UpdateEvent").value = "Yes";
			document.form.submit();
        }
        
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
          <a class="brand" href="dashboard.php">FlickPS SvT</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
<?php
	if ($_SESSION['newmessages'] > 0)
	{
		if ($_SESSION['newmessages'] == 1)
			print "<i class='icon-envelope'></i>&nbsp;<a href='messages.php?gid=" . intval($_GET["gid"]) . "' class='navbar-link'>1 New Message!</a> &nbsp;";
		else
			print "<i class='icon-envelope'></i>&nbsp;<a href='messages.php?gid=" . intval($_GET["gid"]) . "' class='navbar-link'>" . $_SESSION['newmessages'] . " New Messages!</a> &nbsp;";
	}
	else
	{
		print "<i class='icon-envelope'></i>&nbsp;<a href='messages.php?gid=" . intval($_GET["gid"]) . "' class='navbar-link'>Messages</a> &nbsp;";
	}
?>
              <i class="icon-off"></i>&nbsp;<a href="verify.php?logout=yes" class="navbar-link">Log Out</a>
            </p>
            <ul class="nav">
              <li><?php print "<a href='spacevtime.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-thumbs-up"></i>&nbsp; Storyline</a></li>
              <li><?php print "<a href='characters.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-user"></i>&nbsp; Characters</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <div class="row">
        <div class="span12">
	
	<p><h4><center>Manage Events on Timeline</center></h4></p>
	<p>&nbsp;</p>
	
	<form class='form-inline' method='post' action='timeline.php' name='form' enctype='multipart/form-data'>
        <center><strong>Change date? &nbsp;</strong><div class='input-append'>
    <?php
		$tdays = intval($_GET['edit']);
		
		$year 	= intval( $tdays/416  );
		$month 	= intval( ($tdays%416)/32 );
		$tday 	= intval( ($tdays%416)%32 );
		
        print "<input type='text' id='day'   class='span1' value='" . $tday . "'><span class='add-on'>day(s)</span>";
        print "<input type='text' id='month' class='span1' value='" . $month . "'><span class='add-on'>month(s)</span>";
        print "<input type='text' id='year'  class='span1' value='" . $year . "'><span class='add-on'>year(s)</span>";
    ?>
        </div></center>
        <input type='hidden' name='currentday' value='<?php print intval($_GET['edit']) ?>'>
        
    <?php    
		  $user_name = "flickpsc_svtmain";
		  $password = "";
		  $database = "flickpsc_svtgsi";
		  $server = "localhost";	
			
		  $db_handle = mysql_connect($server, $user_name, $password);
		  $db_found = mysql_select_db($database, $db_handle);	
		  
		  $search  = array('\'', '\"', ';', '<', '>');
		  $replace = array('&#39;', '', '', '', ''); 
		  
		  $SQL = "SELECT * FROM g2u WHERE gid = '" . intval($_GET['gid']) . "' AND uid = '" . intval($_SESSION['userid']) . "'; ";
		  $result = mysql_query($SQL);
		  $number_of_rows = mysql_num_rows($result);
		  
		  if ($number_of_rows == 0 || $_GET['edit'] == "")
		  {
				// Check if Public Wiki is available.
				
				mysql_close($db_handle);
				print "<script> window.location = '/index.php'; </script> \n";
		  }
		  
		  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . "; ";
		  $result = mysql_query($SQL);
		  $row = mysql_fetch_array($result);
		  
		  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	  
		  
		  $clearspaces  = array('\'', '\"', ';', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '\\', '/', '{', '}', '`', '~', ',', '?', '[', ']', ':', '|', '-', '_', '<', '>', '.');                  
		  $directory = intval($_GET['gid']) . "_" . str_replace($clearspaces, '', $row[0]) . "_";	
		  
			
		  if ($_GET["error"] && $_GET["error"] == "X")
		  {            
			print "<p>&nbsp;</p><p align=center><span class='label label-warning' style='font-size: 18px;'>";
			print "You did not enter a valid number!</span></p>"; 
		  }	
		  if ($_GET["error"] && $_GET["error"] == "1")
		  {            
			print "<p>&nbsp;</p><p align=center><span class='label label-warning' style='font-size: 18px;'>";
			print "This event already exists!</span></p>"; 
		  }	
		  
		  
		  if ($db_found) 
		  {	   
				$SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' AND days = '" . intval($_GET['edit']) . "'";
				$result = mysql_query($SQL);
				$db_field = mysql_fetch_assoc($result);
				
				if ($db_field['image'] == "")
				{
					print "<p>&nbsp;</p><h5><center>Attach a map: </center></h5>";
				}
				else
				{          
					print "<p>&nbsp;</p><h5><center>This event's current map: </center></h5>";
					print "  <p align='center'><img src='uploads/" . $directory . $db_field['image'] . "' width='320' height='240'>";
					print "  </p><p>&nbsp;</p>";
				}
				
				mysql_close($db_handle);
		  }
	?>        
	        
        <center><label for='file'><strong>New map? &nbsp;</strong></label>
        <input type='file' name='file' id='file' rel='tooltip' data-placement='bottom' 
			title='Formats supported: .jpg, .gif, .png <br> & maximum size is 500 kb.'></center>
			
	<?php    
		  if ($_GET["error"] && $_GET["error"] == "2")
		  {            
			print "<p>&nbsp;</p><p align=center><span class='label label-warning' style='font-size: 18px;'>";
			print "A strange file error occured...?</span></p>"; 
		  }	
		  if ($_GET["error"] && $_GET["error"] == "3")
		  {            
			print "<p>&nbsp;</p><p align=center><span class='label label-warning' style='font-size: 18px;'>";
			print "Hang on, you didn't upload any file!</span></p>"; 
		  }
		  if ($_GET["error"] && $_GET["error"] == "4")
		  {            
			print "<p>&nbsp;</p><p align=center><span class='label label-warning' style='font-size: 18px;'>";
			print "File type not supported or file size too large!</span></p>"; 
		  }	
	?> 
        
        <p>&nbsp;</p>
		<input type='hidden' name='days' id='days' value=''></input>
		<input type='hidden' name='UpdateEvent' id='UpdateEvent' value=''></input>
        <p align=center><button type='button' class='btn btn-large btn-success' onClick='updateevent();'>Update Event !</button></p>

<?php

	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	
		
	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);	
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', ''); 
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . "; ";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	  
	  
	  $clearspaces  = array('\'', '\"', ';', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '\\', '/', '{', '}', '`', '~', ',', '?', '[', ']', ':', '|', '-', '_', '<', '>', '.');                  
      $directory = intval($_GET['gid']) . "_" . str_replace($clearspaces, '', $row[0]) . "_";	
      
      
      print "<input type=hidden name='GroupID' id='GroupID' value='" . intval($_GET['gid']) . "'>";
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', '');  
		
	  if ($db_found) 
 	  {	              
			$SQL = "SELECT * FROM images WHERE project = '" . $groupname . "'";
			$result = mysql_query($SQL);
			$numimages = mysql_num_rows($result);         
			
			if ($numimages > 0)
			{
				$numimgmod = 1;
				
				print "\n<p>&nbsp;</p><p>&nbsp;</p><h5><center>Or, pick one you saved previously: </h5><br>&nbsp;<ul class='thumbnails'>\n";
				
				while ($db_field = mysql_fetch_assoc($result))
				{
					print "<li class='span3'><a href='#' class='thumbnail' onClick='submitform(\"" . $db_field['url'] . "\")'>";
					print "<img src='uploads/" . $directory . $db_field['url'] . "'></a></li>\n";     
					
					if ($numimgmod % 4 == 0)
					{
						print "</ul><ul class='thumbnails'>";
					}
					$numimgmod++;           
				}            
				print "</ul> \n";
			}
			
			mysql_close($db_handle);
	}
	
?>

	<input type='hidden' id='currentimg' name='currentimg' value=''>
	<p>&nbsp;</p></form>
	
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <footer class="footer">
        <center>Hosted by <a href="https://www.singaporehost.sg/customers/aff.php?aff=235" target="_blank">Singapore Host</a>.</center>
        <center>&copy; 2012 FlickPS Software Singapore. All Rights Reserved. =)</center>
    </footer>
  
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

    </div>
    <!-- /container -->

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

  </body>

  <script>
    $(function(){
        $('input[rel=tooltip]').tooltip();
    });
  </script>

</html>
