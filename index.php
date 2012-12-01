<?php	session_start();	?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>The Graphical Storyline App</title>
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
      .btn-primary 
      {
        font-size: 24px;
        padding: 16px 24px;
      }
      .btn-main 
      {
        font-size: 24px;
        padding: 16px 24px;
      }
      .btn-link 
      {
        padding: 0px 0px;
      }
	  .map-fixed 
	  {
		position: fixed;
		width : 400;
	  }
    </style>
    
    <link href="/twitter/css/docs.css" rel="stylesheet">
    <link href="/twitter/css/prettify.css" rel="stylesheet">
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
    function closealert(usrid)
	{	
		var pname = "profile" + usrid.toString();
		
		$("#hello").fadeOut();
		
		setTimeout(function ()
		{
			document.getElementById(pid).innerHTML == "";
			document.getElementById("pid").value = "";
		}, 1000);
	}
	
	function showprofile(usrid, prfid)
	{
		var pname = "profile" + prfid.toString();
		var pbody = "";
		
		var pid = document.getElementById("pid").value;
		
		if (pid != "")
		{
			if (document.getElementById(pid).innerHTML != "")
			{
				$("#hello").alert('close');
				document.getElementById(pid).innerHTML == "";
			}
		}
		
		$.ajax({  
            type:   "POST",  
            url:    "index.php",  
            data:   "OpenProfile=true&usrid=" + usrid,  
            success: function(data)
            {               
                pbody = $(data).find('#ProfileBody').attr("value");
                
				var htmlstring = 	"<div class='alert alert-block hide alert-info' id='hello'>" + 
									"<button type='button' class='close' onClick='closealert(" + usrid + ");'><strong>&times;</strong></button>" + 
									"<h5><center>Nano Profile</center></h5>" + 
									"<table align='center' cellpadding='4'>" + pbody + "</table><p align='center'>" + 
<?php
				if (isset($_SESSION['login']) && $_SESSION['login'] != "") 
				{
					print			"\" <a href='collaborators.php?uid=\" + usrid + \"' class='btn btn-success btn-mini' target='_blank'>Invite!</a> \" + ";
				}
?>		
				"<button type='button' class='btn btn-mini' onClick='closealert(" + usrid + ");'>Close</button></p></div>";
				
				document.getElementById(pname).innerHTML = htmlstring;
				document.getElementById("pid").value = pname;
				
				$("#hello").fadeIn();
            },  
            error: function(err)
            {  
                alert('Error: ' + err);  
            }  
        });
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
          <a class="brand" href="http://www.flickps.com">FlickPS.com</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li  class="active"><a href="index.php"><i class="icon-home"></i>&nbsp; Home</a></li>
              <li><a href="dashboard.php"><i class="icon-th"></i>&nbsp; Dashboard</a></li>
            </ul>
<?php
    if (!(isset($_SESSION['login']) && $_SESSION['login'] != "")) 
    {
        print "<form class='navbar-form pull-right' action='verify.php' method='post'>";
        print "<input class='span2' type='text' placeholder='Username' name='username'>&nbsp;";
        print "<input class='span2' type='password' placeholder='Password' name='password'>&nbsp;";
        print "<button type='submit' class='btn' name='LogIn'>Log In</button></form>";
    }
    else
    {
		print "<p class='navbar-text pull-right'>";
		print "<i class='icon-off'></i>&nbsp;<a href='verify.php?logout=yes' class='navbar-link'>Log Out</a></p>";
    }
?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>


<?php
    if (!(isset($_SESSION['login']) && $_SESSION['login'] != "")) 
    {
		print "<div style='background-image: url(/images/Background.jpg); margin-top:-10px; position: relative; padding: 32px 32px;'>";
		print "<div class='container'><h2><center>Space v Time - The Graphical Storyline App</center></h2>";
        print "<p>&nbsp;</p><p align='center'>";
        print "<a href='users.php#signup' class='btn btn-large btn-success btn-main'>Sign up for free !</a></p></div></div>";
    }
    else
    {
		print "<div class='container'><h3><center>Space v Time - The Graphical Storyline App</center></h3></div>";
    }
?>       

	<p>&nbsp;</p>
	
	<form class="form-inline" method="post" action=""><center>
	    <input type="text" class="span4" name="SText" id="SText">
	    <button type="submit" class="btn" name="Search">Search</button>
	    <a name="Listing">&nbsp;</a>
	</center></form>
       
	<div class="container"><div class="row">  	
	<div class="span12"><div class="row">
		
		<div class="span2 bs-docs-sidebar">
          <ul class="nav nav-list bs-docs-sidenav">
          <li <?php if ($_GET['genre'] == "History") print "class='active'"; ?>>
			<a href="?genre=History#Listing"><i class="icon-chevron-right"></i> History Study</a></li>
          <li <?php if ($_GET['genre'] == "Action") print "class='active'"; ?>>
			<a href="?genre=Action#Listing"><i class="icon-chevron-right"></i> Action</a></li>
          <li <?php if ($_GET['genre'] == "Adventure") print "class='active'"; ?>>
			<a href="?genre=Adventure#Listing"><i class="icon-chevron-right"></i> Adventure</a></li>
		  <li <?php if ($_GET['genre'] == "Children") print "class='active'"; ?>>
			<a href="?genre=Children#Listing"><i class="icon-chevron-right"></i> Children</a></li>
          <li <?php if ($_GET['genre'] == "Comedy") print "class='active'"; ?>>
			<a href="?genre=Comedy#Listing"><i class="icon-chevron-right"></i> Comedy</a></li>
          <li <?php if ($_GET['genre'] == "Fantasy") print "class='active'"; ?>>
			<a href="?genre=Fantasy#Listing"><i class="icon-chevron-right"></i> Fantasy</a></li>
          <li <?php if ($_GET['genre'] == "Manga") print "class='active'"; ?>>
			<a href="?genre=Manga#Listing"><i class="icon-chevron-right"></i> Manga/Anime</a></li>
		  <li <?php if ($_GET['genre'] == "Wuxia") print "class='active'"; ?>>
			<a href="?genre=Wuxia#Listing"><i class="icon-chevron-right"></i> Martial Arts</a></li>
          <li <?php if ($_GET['genre'] == "Mystery") print "class='active'"; ?>>
			<a href="?genre=Mystery#Listing"><i class="icon-chevron-right"></i> Mystery</a></li>
          <li <?php if ($_GET['genre'] == "Romance") print "class='active'"; ?>>
			<a href="?genre=Romance#Listing"><i class="icon-chevron-right"></i> Romance</a></li>
          <li <?php if ($_GET['genre'] == "Sci-Fi") print "class='active'"; ?>>
			<a href="?genre=Sci-Fi#Listing"><i class="icon-chevron-right"></i> Sci-Fi</a></li>
          <li <?php if ($_GET['genre'] == "Thriller") print "class='active'"; ?>>
			<a href="?genre=Thriller#Listing"><i class="icon-chevron-right"></i> Thriller</a></li>
          </ul>
		</div>		
		
		<div class="span7 offset1">
			<p>&nbsp;</p>
		  
<?php
		
	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	
		
	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);		
	  
	  $search  = array('\'', '\"', ';', '<', '>');
      $replace = array('&#39;', '', '', '', ''); 
	  
	  $currentuser = str_replace($search, $replace, $_SESSION['login']); 
		
	  if ($db_found) 
 	  {	     
			$genre = str_replace($search, $replace, $_GET['genre']);
			$search = str_replace($search, $replace, $_POST['SText']);
			
			if ($_GET['genre'] == "" && !isset($_POST['Search']))
			{
				print "<h4><center>Latest Projects</center></h4><p>&nbsp;</p>\n\n";
				
				$SQL = "SELECT * FROM groups WHERE (datestarted >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 2 MONTH)), INTERVAL 1 DAY)) AND (status = 'Public' OR status = 'Wiki') ORDER BY creator, name; ";
			}
			else if ($_GET['genre'] != "" && !isset($_POST['Search']))
			{
				$SQL = "SELECT * FROM groups WHERE genre = '" . $genre . "' AND (status = 'Public' OR status = 'Wiki') ORDER BY name; ";
			}
			else if ($_GET['genre'] == "" && isset($_POST['Search']))
			{
				print "<h4><center>Search Results</center></h4><p>&nbsp;</p>\n";
				print "<script> document.getElementById('SText').value = '" . $search . "'; </script>\n\n";
				
				$SQL = "SELECT *, MATCH (name) AGAINST ('" . $search . "') AS score FROM groups WHERE MATCH (name) AGAINST ('" . $search . "') ORDER BY score DESC; ";
			}
			else
			{
				print "<h4><center>Search Results</center></h4><p>&nbsp;</p>\n\n";
				print "<script> document.getElementById('SText').value = '" . $search . "'; </script>\n\n";
				
				$SQL = "SELECT *, MATCH (name) AGAINST ('" . $search . "') AS score FROM groups WHERE MATCH (name) AGAINST ('" . $search . "') ORDER BY score DESC; ";				
			}			
			
			$result = mysql_query($SQL);
			$number_of_rows = mysql_num_rows($result); 
			
			if ($number_of_rows > 0)
			{
				$profilecounter = 0;
				
				while ($db_field = mysql_fetch_assoc($result)) 
				{					
					$SQL = "SELECT id FROM users WHERE userid = '" . $db_field['creator'] . "'; ";
					$creatorresult = mysql_query($SQL);
					$row = mysql_fetch_array($creatorresult);
					  
					$creatorid = $row[0]; 
					$datestarted = date("d F Y", strtotime($db_field['datestarted']));
					
					print "<p id='profile" . $profilecounter . "'></p>";
					print "\n<table cellpadding='10'><tr><td width='400'>&nbsp;&nbsp; ";
					print "<a href='viewstory.php?gid=" . $db_field['id'] . "' rel='tooltip' title='Date started: <i>" . $datestarted . "</i>";
					print "<br/>Status: <strong>" . $db_field['progress'] . "</strong>' data-placement='top' target='_blank'>";
					print "<strong>". $db_field['name'] . "</strong></a> by ";
					print "<a href='#Listing' onClick=\"showprofile(" . $creatorid . ", " . $profilecounter . ");\"><strong>" . $db_field['creator'] . "</strong></a></td>";
					
					if ($db_field['status'] == "Wiki")
					{
						print "<td>&nbsp;</td></tr>";
						
						// print "<td><a href='#' rel='tooltip' title='Wiki Editing Enabled' data-placement='top'>
						// print "<i class='icon-edit'></i> Edit</a></td></tr>";
					}
					else
					{
						print "<td>&nbsp;</td></tr>";
					}
					
					print "<tr><td>&nbsp;&nbsp; " . $db_field['description'] . "</td><td>&nbsp;</td></tr>";
					print "<tr><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td></tr></table>\n\n";
					
					$profilecounter++;
				}
			}
			else
			{
				if (isset($_POST['Search']))
				{
					print "<center>Sorry, no results found. :( </center>\n\n";
				}
			}
			
			
			if (isset($_POST['OpenProfile']) && $_POST['usrid'] != "")
			{
				$uid = intval($_POST['usrid']);
				
				$SQL = "SELECT * FROM users WHERE id = " . $uid . "; ";
				$result = mysql_query($SQL);
				$db_field = mysql_fetch_assoc($result);
				
				if ($db_field['gravatar'] != "")
				{
					$currentbody = "<tr><td valign=top><img src=http://www.gravatar.com/avatar/" . $db_field['gravatar'] . "?r=PG&s=48&d=wavatar></img></td><td>";
				}
				else
				{
					$currentbody = "<tr><td>";
				}
				
				$currentbody .= "You are now looking at the profile of <u>" . $db_field['userid'] . "</u>. <br/>";
				
				$SQL = "SELECT * FROM groups WHERE creator = (SELECT userid FROM users WHERE id = " . $uid . "); ";
				$result = mysql_query($SQL);
				$group_rows = mysql_num_rows($result); 
				
				if ($group_rows > 0)
				{
					$currentbody .=	"This user has initiated " . 	$group_rows . " project(s). <br/>";
				}
				
				$SQL = "SELECT * FROM g2u WHERE uid = " . $uid . "; ";
				$result = mysql_query($SQL);
				$member_rows = mysql_num_rows($result); 
				
				if ($member_rows > 0 && $group_rows > 0)
				{
					$currentbody .=	"And this user is a member of " . 	($member_rows - $group_rows) . " other project(s).";
				}		
				else if ($member_rows > 0 && $group_rows == 0)
				{
					$currentbody .=	"This user is a member of " . 	$member_rows . " project(s).";
				}
				else
				{
					$currentbody .=	"This user has not taken part in any projects yet.";
				}
				
				$currentbody .= "<br/>&nbsp;</td></tr>";
				
				echo "<input type='hidden' id='ProfileBody'  value='" . $currentbody . "'>";
          	}
          	
			
			mysql_close($db_handle);
	  }
	  else 
 	  {
			print "Database NOT Found " . $db_handle;
	  }	
	  
	  print "<p>&nbsp;</p>";

?>		  

			<input type='hidden' name='pid' id='pid' value=''>
		</div>
		
		<div class="span1">	
			<p>&nbsp;</p>
			<div align="right" style="border-style:dotted; border-width:1px; width:168px; height:504px;">
				<!-- Advertisement -->
			</div>
		</div>
		
    </div></div>
    </div></div>
    
    <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
    
    <footer class="footer">
        <center>Hosted by <a href="https://www.singaporehost.sg/customers/aff.php?aff=235" target="_blank">Singapore Host</a>.</center>
        <center>&copy; 2012 FlickPS Software Singapore. All Rights Reserved. =)</center>
    </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/twitter/js/widgets.js"></script>
    <script src="/twitter/js/jquery.js"></script>
    <script src="/twitter/js/prettify.js"></script>
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
    $(function()
    {
      $('a[rel=tooltip]').tooltip();
    });
  </script>

</html>
