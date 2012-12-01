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
    <title>SvT Dashboard</title>
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
      .btn-link 
      {
        padding: 0px 0px;
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
    function openmodal(gid, genre, status, description, progress)
    {
        document.getElementById("gid").value = gid;
        
        for (i = 0; i < document.getElementById("genre").length; i++)
        {
			if (document.getElementById("genre").options[i].value == genre)
			{
				document.getElementById("genre").options[i].selected = true;
				break;
			}
		}		
		for (i = 0; i < document.getElementById("status").length; i++)
        {
			if (document.getElementById("status").options[i].value == status)
			{
				document.getElementById("status").options[i].selected = true;
				break;
			}
		}  
		for (i = 0; i < document.getElementById("progress").length; i++)
        {
			if (document.getElementById("progress").options[i].value == progress)
			{
				document.getElementById("progress").options[i].selected = true;
				break;
			}
		}       
        
        document.getElementById("description").value = description;
        
        $('#myModal').modal();
    }
    
    function showgroup(groupid)
    {
		document.getElementById("GroupID").value = groupid;
        document.groupsession.submit();
    }
    
    function leavegroup(groupid)
    {
		var sureornot = confirm("Are you really REALLY sure you want to leave this group??");
		
		if(sureornot == true)
		{
			document.getElementById("LeaveGroup").value = groupid;
			document.exitgroup.submit();
		}	
    }
    
	function closealert()
	{
		$("#hello").fadeOut();
	}

	function showalert()
	{
		$("#hello").fadeIn();
	}
	
	function setgravatar()
	{
		var htmlstring = 	"<strong>Your Gravatar Email: </strong>" + 
							"<input type='text' class='span3' name='gravataremail'>&nbsp;" + 
							"<button type='submit' name='GravatarSubmit' class='btn'>" + 
							"<strong>Save</strong>  <i class='icon-ok'></i></button>" + 
							"&nbsp;<a href='dashboard.php' class='btn'>Cancel</a>";
		
		document.getElementById("gravatar").innerHTML = htmlstring;
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
              <li class="active"><a href="dashboard.php"><i class="icon-th"></i>&nbsp; Dashboard</a></li>
              <li><a href="collaborators.php"><i class="icon-user"></i>&nbsp; Collaborators</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">   
    
      <div class="row">
      <div class="span12">
      
        <h4><center>Your Space v. Time Dashboard</center></h4>
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
	  $thisuserid = intval($_SESSION['userid']);
		
	  if ($db_found) 
 	  {	     
		 if (isset($_POST['GroupID']) && $_POST['GroupID'] != "") 
		 {
			  $gid = intval($_POST['GroupID']);
			  $messagecounter = 0;
			  
			  $SQL = "SELECT * FROM messages WHERE gid = " . $gid . "; ";
			  $result = mysql_query($SQL);
			  
			  while ($db_field = mysql_fetch_assoc($result)) 
			  {
					$newmsgs = $db_field['readstatus']; 
					$readarray = explode("#", $newmsgs);
					
					foreach ($readarray as $readreceipt)
					{
						if (intval($readreceipt) == $thisuserid)
						{
							$messagecounter++;
						}
					}
			  }
			  
			  if ($messagecounter > 0)
			  {
					$_SESSION['newmessages'] = $messagecounter;
			  }
			  else
			  {
					$_SESSION['newmessages'] = 0;
			  }
			  
			  print "<script> window.location = 'spacevtime.php?gid=" . $gid . "'; </script>";
			  mysql_close($db_handle);
		 }
		 
		 $SQL = "SELECT invites FROM users WHERE id = " . $thisuserid . "";
         $result = mysql_query($SQL);
         $row = mysql_fetch_array($result);
			
		 $invites = $row[0]; 
		 
		 if ($invites != "")
		 {
			print "<div class='alert alert-block alert-error' id='hello'>";
			print "<button type='button' class='close' onClick='closealert();'><strong>&times;</strong></button>";
			print "<h4 class='alert-heading'><center>You have new invitations!</center></h4>";
			print "<p>&nbsp;</p><p align='center'><a class='btn btn-danger' href='collaborators.php'>View Invites</a>";
			print "&nbsp; <a class='btn' href='#' onClick='closealert();'>Dismiss</a></p></div>\n\n";
		 }		  
			
		 print "<div class='hero-unit'>\n\n<form method='post' action='' name='form1' class='form-inline'>";
		 print "<input type='text' class='input-xlarge' placeholder='Group name?' name='Group'>&nbsp;";	
		 print "<button type='submit' class='btn btn-info' name='Submit'>Create New Group !</button></form>\n\n";
			
		 
	     if (isset($_POST['Submit']) && $_POST['Group'] != "") 
	     {           
          $group = str_replace($search, $replace, $_POST['Group']);
          
          if ($group[0] != " ")
          {
              $SQL = "SELECT * FROM groups WHERE name = '" . $group . "' AND creator = '" . $currentuser . "'";
              $result = mysql_query($SQL);
              $number_of_rows = mysql_num_rows($result); 
               
              if ($number_of_rows > 0) 
              { 
                  print "<p><span class='label label-warning' style='font-size: 18px;'>";
                  print "This group already exists!</span></p>"; 
              } 
              else
              {	 
                  // mkdir($directory, 0644, true);
                  
                  $SQL = "INSERT INTO groups(name, creator, status, genre) VALUES ('" . $group . "', '" . $currentuser . "', 'Private', 'Action')";
                  $result = mysql_query($SQL);
                  
                  $SQL = "INSERT INTO g2u(gid, uid) VALUES ( (SELECT id FROM groups WHERE name = '" . $group . "' AND creator = '" . $currentuser . "'), " . $thisuserid . ")";
                  $result = mysql_query($SQL);
              }
          }
	     }
	     
	     if (isset($_POST['SaveGroup']) && $_POST['gid'] != "") 
	     {
			$gid = intval($_POST['gid']);
			$status = str_replace($search, $replace, $_POST['status']);
			$description = str_replace($search, $replace, $_POST['description']);
			$genre = str_replace($search, $replace, $_POST['genre']);
			$progress = str_replace($search, $replace, $_POST['progress']);
			
			$SQL  = "UPDATE groups SET status='" . $status . "', description='" . $description . "', genre='" . $genre . "', progress='" . $progress . "' WHERE id = " . $gid . "; ";
            $result = mysql_query($SQL);
	     }
	     
	     if (isset($_POST['LeaveGroup']) && $_POST['LeaveGroup'] != "") 
	     {
			$gid = intval($_POST['LeaveGroup']);
			
			$SQL = "DELETE FROM g2u WHERE gid = " . $gid . " AND uid = " . $thisuserid . "; ";
			$result = mysql_query($SQL); 
	     }	   
	     	
			
	     $SQL = "SELECT * FROM groups WHERE creator = '" . $currentuser . "'; ";
	     $result = mysql_query($SQL);
	     
	     $search1  = array('&#39');
		 $replace1 = array('\\\'');
         $search2  = array('&#39');
         $replace2 = array('&#39;');
         	
 	     while ($db_field = mysql_fetch_assoc($result)) 
	     {
          $groupname = str_replace($search1, $replace1, $db_field['name']);
          $displayname = str_replace($search1, $replace2, $db_field['name']);
          $description = str_replace($search1, $replace1, $db_field['description']);
          
          print "<br/><div class='row-fluid'><div class='span4'>";
          print "<strong>" . $displayname . "</strong>";
          print "</div><!--/span--><div class='span2'>";
          print "<button type='button' class='btn btn-success' onClick=\"showgroup(" . $db_field['id'] . ");\">Let's Go !</button>";
          print "</div><!--/span--><div class='span2'>";
          print "<button type='button' class='btn' onClick=\"openmodal(" . $db_field['id'] . ", '" . $db_field['genre'] . "', '" . $db_field['status'] . "', '" . $description . "', '" . $db_field['progress'] . "');\">Settings</button>";
          print "</div><!--/span--></div><!--/row-->\n";
         }	
         print "</div><!-- Hero Unit -->\n\n";
         
         
         if (isset($_POST['GravatarSubmit'])) 
	     {
			if ($_POST['gravataremail'] != "")
			{
				$gravataremail = md5(strtolower(preg_replace('/\s+/', '', $_POST['gravataremail'])));
			}
			else
			{
				$gravataremail = "";
			}
			
			$SQL  = "UPDATE users SET gravatar = '" . $gravataremail . "' WHERE id = " . $thisuserid . "; ";
            $result = mysql_query($SQL);
	     }	 
	     
	     print "<div class='hero-unit'>";
         
         $SQL = "SELECT gravatar FROM users WHERE id = " . $thisuserid . "; ";
		 $userresult = mysql_query($SQL);
		 $row = mysql_fetch_array($userresult);
		 
		 $gravatar = $row[0]; 
		 
		 if ($gravatar == "")
		 {
			print "<form name='gravatarset' method='post' class='form-inline'>";
			print "<div id='gravatar'><button type='button' class='btn btn-link' onClick=\"setgravatar();\"><strong>Would you like to set a Gravatar?</strong></button>";
			print "&nbsp;<a href='http://www.gravatar.com/' target='_blank'><i class='icon-question-sign'></i></a></div></form>\n\n";
		 }
		 else
		 {
			print "<form name='gravataredit' method='post' class='form-inline'><a name='gravatar'></a><div id='gravatar'>";
			print "<table><tr><td class='span4'><strong>Your current Gravatar :</strong></td><td class='span2'>";
			print "<img src='http://www.gravatar.com/avatar/" . $gravatar . "?r=PG&s=48&d=wavatar'></img></td><td class='span2'>";
			print "<button type='button' class='btn' onClick=\"setgravatar();\">Change?</button></td></tr></table></div></form>\n\n";
		 }
		 
		 print "</div>";
         
         
         print "<form name='exitgroup' method='post'>";
         
         $SQL = "SELECT * FROM g2u WHERE uid = " . $thisuserid . "; ";
	     $result = mysql_query($SQL);
	     $number_of_rows = mysql_num_rows($result);
	     
	     $counter = 0;
	     
	     if ($number_of_rows > 0)
	     {
			while ($db_field = mysql_fetch_assoc($result)) 
			{
				$SQL = "SELECT * FROM groups WHERE id = " . $db_field['gid'] . " AND creator != '" . $currentuser . "'; ";
				$groupresult = mysql_query($SQL);
				$group_rows = mysql_num_rows($groupresult);
				
				if ($group_rows > 0)
				{
					if ($counter == 0)
						print "<div class='hero-unit'><h5><font color='blue'>GROUPS YOU PARTICIPATE IN</font></h5>\n\n";
					$counter++;				
					
					while ($group_field = mysql_fetch_assoc($groupresult)) 
					{
						$displayname = str_replace($search1, $replace2, $group_field['name']);
						
						print "<br/><div class='row-fluid'><div class='span4'>";
						print "<strong>" . $displayname . "</strong>";
						print "</div><!--/span--><div class='span2'>";
						print "<button type='button' class='btn btn-success' onClick=\"showgroup(" . $group_field['id'] . ");\">Let's Go !</button>";
						print "</div><!--/span--><div class='span2'>";
						print "<button type='button' class='btn' onClick=\"leavegroup(" . $group_field['id'] . ");\">Leave Group</button>";
						print "</div><!--/span--></div><!--/row-->\n\n";
					}
				}
			}			
	     }
	     
	     print "<input type='hidden' name='LeaveGroup' id='LeaveGroup' value=''></div><!-- Hero Unit --></form>";
         
         
         mysql_close($db_handle);
	  }
	  else 
 	  {
        print "Database NOT Found " . $db_handle;
	  }	

?>    
	
	<form name='groupsession' method='post'>
	<input type='hidden' name='GroupID' id='GroupID' value=''></form>

	<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
		class="modal hide fade in" aria-hidden="true">
	  <form method="post" class="form-inline" name="EditGroup">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	    <h4 id="myModalLabel">Project Options</h4>
	  </div>
	  <div class="modal-body">	 
      <p>&nbsp;</p> 
	    <p align=center><table cellpadding="16" align="center">
		<input type="hidden" name="gid" id="gid" value="">		
		<tr><td>Genre / Type</td><td>
		  <select name="genre" id="genre" class="span3">
		    <option value="History">History Study</option>
		    <option value=""> - </option>
		    <option value="Action">Action</option>
		    <option value="Adventure">Adventure</option>
		    <option value="Children">Children</option>
		    <option value="Comedy">Comedy</option>
		    <option value="Fantasy">Fantasy</option>
		    <option value="Manga">Manga/Anime</option>
		    <option value="Wuxia">Martial Arts</option>
		    <option value="Mystery">Mystery</option>
		    <option value="Romance">Romance</option>
		    <option value="Sci-Fi">Sci-Fi</option>
		    <option value="Thriller">Thriller</option>
		  </select>
		</td></tr>
		<tr><td>Project Status</td><td>
		  <select name="status" id="status" class="span3">
		    <option value="Private">Private Group</option>
		    <option value="Public">Public Viewing Allowed</option>
		    <option value="Wiki">Public Editing Allowed</option>
		  </select>
		</td></tr>
		<tr><td>Description</td><td>
		  <input type="text" name="description" id="description" value="" class="span3">	
		</td></tr>
		<tr><td>Project Completion</td><td>
		  <select name="progress" id="progress" class="span3">
		    <option value="In Progress">Work In Progress</option>
		    <option value="Completed">Project Completed</option>
		  </select>
		</td></tr>
	    </table></p>	
	    <p>&nbsp;</p> 	    
	  </div>
	  <div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	    <button class="btn btn-info" type="submit" name="SaveGroup">SAVE !</button>
	  </div>
	  </form>
	</div>	
    
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
      $('a[rel=popover]').tooltip();
    });
  </script>

</html>
