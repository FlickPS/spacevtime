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
    <title>SvT Collaborators</title>
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
    function acceptinvitation(groupid)
    {
		document.getElementById("AcceptInvite").value = groupid;
		document.respondinvite.submit();
    }
    
    function declineinvitation(groupid)
    {
		var sureornot = confirm("Are you really going to decline this invitation?");
		
		if(sureornot == true)
		{
			document.getElementById("DeclineInvite").value = groupid;
			document.respondinvite.submit();
		}		
    }
    
    function kickmember(grpid, usrid)
    {
		var sureornot = confirm("Do you really want to remove this user from your project?");
		
		if(sureornot == true)
		{
			document.getElementById("grpid").value = grpid;
			document.getElementById("usrid").value = usrid;
			
			document.kickuser.submit();
		}	
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
            <p class="navbar-text pull-right">
              <i class="icon-off"></i>&nbsp;<a href="verify.php?logout=yes" class="navbar-link">Log Out</a>
            </p>
            <ul class="nav">
			  <li><a href="index.php"><i class="icon-home"></i>&nbsp; Home</a></li>
              <li><a href="dashboard.php"><i class="icon-th"></i>&nbsp; Dashboard</a></li>
              <li class="active"><a href="collaborators.php"><i class="icon-user"></i>&nbsp; Collaborators</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">   
    
      <div class="row">
      <div class="span12">
      
        <h4><center>Your Space v. Time Collaborators</center></h4>
        <p>&nbsp;<br/>&nbsp;</p>        	
	
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
	  $thisuserid = intval($_SESSION['userid']);
		
	  if ($db_found) 
 	  {	    
			if (isset($_GET['code']) && $_GET['code'] != "")
			{
				$responsecode = intval($_GET['code']);
				
				switch ($responsecode)
				{
				case 1:
					print "<center><span class='label label-success' style='font-size: 16px;'>Invitation sent!</span></center><p>&nbsp;</p>\n";
					break;
				case 2:
					print "<center><span class='label label-warning' style='font-size: 16px;'>Invitation already sent.</span></center><p>&nbsp;</p>\n";
					break;
				case 3:
					print "<center><span class='label label-warning' style='font-size: 16px;'>User is already on your team.</span></center><p>&nbsp;</p>\n";
					break;
				case 4:
					print "<center><span class='label label-success' style='font-size: 16px;'>Invitation accepted!</span></center><p>&nbsp;</p>\n";
					break;
				case 5:					
					print "<center><span class='label label-warning' style='font-size: 16px;'>Invitation declined.</span></center><p>&nbsp;<br/>&nbsp;</p>\n";
					break;
				case 6:
					print "<center><span class='label label-warning' style='font-size: 16px;'>Sorry, user not found. :(</span></center><p>&nbsp;</p>\n";
					break;
				case 7:
					print "<center><span class='label label-important' style='font-size: 16px;'>User removed. :(</span></center><p>&nbsp;</p>\n";
					break;
				case 8:
					print "<center><span class='label label-info' style='font-size: 16px;'>Invitation sent by email.</span></center><p>&nbsp;</p>\n";
					break;
				default:
					// Do nothing, I guess.
					break;
				}
			}
			
			
			if (isset($_GET['uid']) && $_GET['uid'] != "")
			{
				$uid = intval($_GET['uid']);
				
				$SQL = "SELECT userid FROM users WHERE id = " . $uid . "; ";
				$result = mysql_query($SQL);
				$row = mysql_fetch_array($result);
				
				$inviteuser = $row[0]; 
				
				$SQL = "SELECT * FROM groups WHERE creator = '" . $currentuser . "'; ";
				$result = mysql_query($SQL);
				$number_of_rows = mysql_num_rows($result);
				
				if ($number_of_rows > 0)
				{				
					print "<form name='GetUser' method='post' class='form-inline' action=''>";
					print "<input type='hidden' name='usrid' value='" . $uid . "'></input><h5><center>";
					print "You're inviting <font color='blue'>" . $inviteuser . "</font> to join your project &nbsp;";
					print "<select name='grpname' class='span3'>";
					
					while ($db_field = mysql_fetch_assoc($result))
					{
						print "<option value='" . $db_field['id'] . "'>" . $db_field['name'] . "</option>";
					}
					
					print "</select> <input type='submit' class='btn btn-info' value='Send' name='InviteByID'></input></center></h5></form>";
				}		
			}	
	     
			if (isset($_POST['InviteByID']) && $_POST['usrid'] != "" && $_POST['grpname'] != "") 
			{
				$gid = intval($_POST['grpname']);
				$uid = intval($_POST['usrid']);
				
				$SQL = "SELECT * FROM g2u WHERE gid = " . $gid . " AND uid = " . $uid . "; ";
				$result = mysql_query($SQL);
				$g2u_rows = mysql_num_rows($result);
				
				if ($g2u_rows == 0)
				{				
					$SQL = "SELECT invites FROM users WHERE id = " . $uid . "; ";
					$result = mysql_query($SQL);
					$row = mysql_fetch_array($result);
					
					$invites = $row[0]; 
					$invarray = explode("#", $invites);
					$invrows = 0;
					
					foreach ($invarray as $invid)
					{
						if (intval($invid) == $gid)
						{
							$invrows++;
						}
					}
					
					if ($invrows == 0 && $uid != $thisuserid)
					{
						if ($invites == "")
						{
							$updatedinv = $invites . $gid;
						}
						else
						{
							$updatedinv = $invites . "#" . $gid;
						}
						
						$SQL  = "UPDATE users SET invites='" . $updatedinv . "' WHERE id = " . $uid . "; ";
						$result = mysql_query($SQL);
						
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=1'; </script>";
					}
					else
					{
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=2'; </script>";
					}
				}
				else
				{
					mysql_close($db_handle);
					print "<script> window.location = 'collaborators.php?code=3'; </script>";
				}
			}     
			
			
			if (isset($_POST['AcceptInvite']) && $_POST['AcceptInvite'] != "") 
			{
				$gid = intval($_POST['AcceptInvite']);
					
				$SQL = "INSERT INTO g2u(gid, uid) VALUES (" . $gid . ", " . $thisuserid . "); ";
                $result = mysql_query($SQL);
                    
                $SQL = "SELECT invites FROM users WHERE id = " . $thisuserid . "; ";
				$result = mysql_query($SQL);
				$row = mysql_fetch_array($result);
				
				$invites = $row[0]; 
					
				$invarray = explode("#", $invites);
				$invrows = 0;
				$updatedinvites = "";
						
				foreach ($invarray as $invid)
				{
					if ($invid != $gid)
					{
						if ($updatedinvites == "")
						{
							$updatedinvites = "" . $invid;
						}
						else
						{
							$updatedinvites = $invites . "#" . $invid;
						}
					}
				}
				
				$SQL = "UPDATE users SET invites = '" . $updatedinvites . "' WHERE id = " . $thisuserid . "; ";
				$result = mysql_query($SQL);
				
				mysql_close($db_handle);
				print "<script> window.location = 'collaborators.php?code=4'; </script>";
			}
			
			
			if (isset($_POST['DeclineInvite']) && $_POST['DeclineInvite'] != "") 
			{
				$gid = intval($_POST['DeclineInvite']);                
                
                $SQL = "SELECT invites FROM users WHERE id = " . $thisuserid . "; ";
				$result = mysql_query($SQL);
				$row = mysql_fetch_array($result);
				
				$invites = $row[0]; 
					
				$invarray = explode("#", $invites);
				$invrows = 0;
				$updatedinvites = "";
						
				foreach ($invarray as $invid)
				{
					if ($invid != $gid)
					{
						if ($updatedinvites == "")
						{
							$updatedinvites = "" . $invid;
						}
						else
						{
							$updatedinvites = $invites . "#" . $invid;
						}
					}
				}
				
				$SQL = "UPDATE users SET invites = '" . $updatedinvites . "' WHERE id = " . $thisuserid . "; ";
				$result = mysql_query($SQL);
				
				mysql_close($db_handle);
				print "<script> window.location = 'collaborators.php?code=5'; </script>";
			}
			
			if (isset($_POST['SearchInvite']) && $_POST['invsearch'] != "" && $_POST['grpname'] != "") 
			{
				$gid = intval($_POST['grpname']);
				$searchname = str_replace($search, $replace, $_POST['invsearch']); 
				
				$SQL = "SELECT * FROM users WHERE userid = '" . $searchname . "' OR email = '" . $searchname . "'; ";
				$result = mysql_query($SQL);
				$number_of_rows = mysql_num_rows($result);
					
				if ($number_of_rows > 0)
				{
					$invites = "";
					$uid = 0;
						
					while ($db_field = mysql_fetch_assoc($result)) 
					{
						$invites = $db_field['invites'];
						$uid = $db_field['id'];
					}
					
					$SQL = "SELECT * FROM g2u WHERE gid = " . $gid . " AND uid = " . $uid . "; ";
					$result = mysql_query($SQL);
					$g2u_rows = mysql_num_rows($result);
					
					if ($g2u_rows == 0)
					{ 
						$invarray = explode("#", $invites);
						$invrows = 0;
						
						foreach ($invarray as $invid)
						{
							if (intval($invid) == $gid)
							{
								$invrows++;
							}
						}
						
						if ($invrows == 0 && $uid != $thisuserid)
						{
							if ($invites == "")
							{
								$updatedinv = $invites . $gid;
							}
							else
							{
								$updatedinv = $invites . "#" . $gid;
							}
							
							$SQL  = "UPDATE users SET invites='" . $updatedinv . "' WHERE id = " . $uid . "; ";
							$result = mysql_query($SQL);
							
							mysql_close($db_handle);
							print "<script> window.location = 'collaborators.php?code=1'; </script>";
						}
						else
						{
							mysql_close($db_handle);
							print "<script> window.location = 'collaborators.php?code=2'; </script>";
						}
					}
					else
					{
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=3'; </script>";
					}
				}
				else
				{
					$isvalidemail = filter_var($searchname, FILTER_VALIDATE_EMAIL);
						
					if ($isvalidemail)
					{
						$SQL = "SELECT name FROM groups WHERE id = " . $gid . "; ";
						$result = mysql_query($SQL);
						$row = mysql_fetch_array($result);
						
						$projectname = $row[0];	
						$invitecode = rand(10000000, 9999999999);
						
						$SQL = "INSERT INTO users (passcode, email, invites) VALUES ('" . md5($invitecode) . "', '" . $searchname . "', '" . $gid . "')";
						$result = mysql_query($SQL);
						
						$subject =  "FlickPS Invitation";
						
						// Remember to change back to http://storyline.flickps.com for production.
						
						$message =  "<html>
									<p>Good day,</p><p>You have been invited to participate in the following project: </p>
									<p><strong>" . $projectname . "</strong></p>
									<p>Please visit <a href='http://storyline.flickps.com/users.php?invite=" . $invitecode . "#signup'>this page</a> to sign up and respond to your invitation.</p>
									<p>Thank you! =)</p>
									<p>FlickPS SvT - The Graphical Storyline App</p>
									</html>";
						
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: FlickPS Admin <admin@flickps.com>' . "\r\n";
						
						mail($searchname, $subject, $message, $headers);  
						
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=8'; </script>";
					}
					else
					{
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=6'; </script>";
					}				
				}
			}
			
			if (!isset($_GET['uid']))
			{
				$SQL = "SELECT invites FROM users WHERE id = " . $thisuserid . "; ";
				$result = mysql_query($SQL);
				$row = mysql_fetch_array($result);
				
				$invites = $row[0]; 
				
				if ($invites != "")
				{
					print "\n<h4><center>Your Invitations</center></h4>\n";
					print "<form name='respondinvite' method='post' class='form-inline'><table cellpadding='12' align='center'>\n";
					print "<tr><td><b>Project Group</b></td><td><b>Group Admin</b></td><td><b>Your Response?</b></td></tr>";
					
					$invarray = explode("#", $invites);
					$invrows = 0;
						
					foreach ($invarray as $invid)
					{
						$SQL = "SELECT * FROM groups WHERE id = '" . $invid . "'; ";
						$result = mysql_query($SQL); 
						
						while ($db_field = mysql_fetch_assoc($result)) 
						{
							print "<tr><td>" . $db_field['name'] . "</td><td>" . $db_field['creator'] . "<td>";
							print "<a href='#' onClick=\"acceptinvitation(" . $db_field['id'] . ");\">Accept</a> / ";
							print "<a href='#' onClick=\"declineinvitation(" . $db_field['id'] . ");\">Decline</a></td></tr>";
						}
					}
					print "<input type='hidden' name='AcceptInvite' id='AcceptInvite' value=''>";
					print "<input type='hidden' name='DeclineInvite' id='DeclineInvite' value=''></table></form>";
				}
				
				
				$SQL = "SELECT * FROM groups WHERE creator = '" . $currentuser . "'; ";
				$result = mysql_query($SQL);
				$number_of_rows = mysql_num_rows($result);
				
				if ($number_of_rows > 0)
				{
					print "<p>&nbsp;</p><h4><center>Invite user to join your project!</center></h4>";
					print "<form name='searchinvite' method='post' class='form-inline'>";
					print "<table cellpadding='12' align='center'><tr><td>Name or Email</td>";
					print "<td><input type='text' class='span3' name='invsearch' rel='tooltip' title='Case sensitive!' data-placement='bottom'></td></tr>";
					print "<tr><td>Project Group</td><td><select name='grpname' class='span3'>";
					
					while ($db_field = mysql_fetch_assoc($result))
					{
						print "<option value='" . $db_field['id'] . "'>" . $db_field['name'] . "</option>";
					}
					
					print "</select></td></tr><tr><td>&nbsp;</td><td><input type='submit' class='btn btn-info' name='SearchInvite' value='Find User !'>";
					print "</tr></table></form>\n";
				}
			}	
			
			
			if ($_POST['grpid'] != "" && $_POST['usrid'] != "")
			{
				$gid = intval($_POST['grpid']);
				$uid = intval($_POST['usrid']);
				
				$SQL = "SELECT * FROM groups WHERE id = " . $gid . " AND creator = '" . $currentuser . "'; ";
				$result = mysql_query($SQL);
				$grprows = mysql_num_rows($result);
				
				if ($grprows > 0)
				{
					$SQL = "SELECT * FROM g2u WHERE gid = " . $gid . " AND uid = " . $uid . "; ";
					$result = mysql_query($SQL);
					$g2u_rows = mysql_num_rows($result);
					
					if ($g2u_rows > 0)
					{
						$SQL = "DELETE FROM g2u WHERE gid = " . $gid . " AND uid = " . $uid . "; ";
						$result = mysql_query($SQL); 
						
						mysql_close($db_handle);
						print "<script> window.location = 'collaborators.php?code=7'; </script>";
					}
				}
			}
			
			$SQL = "SELECT groups.name, g2u.gid, g2u.uid, users.userid FROM groups INNER JOIN g2u ON groups.id=g2u.gid RIGHT JOIN users ON g2u.uid=users.id WHERE groups.creator = '" . $currentuser . "' ORDER BY groups.name, users.userid; "; 
			$result = mysql_query($SQL);
			$num_rows = mysql_num_rows($result);
			
			print "\n<p>&nbsp;</p><p>&nbsp;</p><h4><center>Your Project Groupmates</center></h4><br/>\n";
			print "<form name='kickuser' class='form-inline' method='post'><table class='table table-bordered'>\n";
			print "<tr><td><b>#</b></td><td><b>Project Group</b></td><td><b>Member Name</b></td><td><b>Management</b></td></tr>\n";
			
			$counter = 1;
				
			while ($db_field = mysql_fetch_assoc($result)) 
			{
				print "<tr><td>" . $counter . "</td><td>" . $db_field['name'] . "</td><td>" . $db_field['userid'] . "</td><td>&nbsp;";
				
				if ($db_field['userid'] != $currentuser)
				{
					print "<a href='#' onClick=\"kickmember(" . $db_field['gid'] . ", " . $db_field['uid'] . ");\" >Kick Member?</a>";
				}
				else
				{
					print "Group Admin";
				}
				print "</td></tr>\n";
				
				$counter++;
			}	
			if ($num_rows == 0)
			{
				print "<tr><td> - </td><td> - </td><td> - </td><td> - </td></tr>\n";
			}
			print "<input type='hidden' name='grpid' id='grpid' value=''><input type='hidden' name='usrid' id='usrid' value=''></table></form>\n";
			
         
			mysql_close($db_handle);
	  }
	  else 
 	  {
			print "Database NOT Found " . $db_handle;
	  }	

?>
   
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    
    <footer class="footer">
        <center>Hosted by <a href="https://www.singaporehost.sg/customers/aff.php?aff=235" target="_blank">Singapore Host</a>.</center>
        <center>&copy; 2012 FlickPS Software Singapore. All Rights Reserved. =)</center><p>&nbsp;</p>
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

    </div> <!-- /container -->

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
