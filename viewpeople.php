<?php	session_start();	?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SvT Story Characters</title>
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
        
      .modal-body 
      {
          max-height:300px;
          overflow-y: auto;
      }
      
      .tooltip-inner 
      {
			max-width: 400px;
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
    
    <script src="/twitter/js/jquery.js"></script>

  <script>

    function OpenHistory(cname, cid)  
    {  
        $('#CharHistory').modal(); 
        
        var ModalTitle = "Story Arc for " + cname;      
        
        $.ajax({  
            type:   "POST",  
            url:    "viewpeople.php",  
            data:   "OpenHistory=true&cid=" + cid,  
            success: function(data)
            {                  
                document.getElementById("CharacterName").innerHTML =  ModalTitle;
                
                var ArcBody = $(data).find('#ArcBody').attr("value");
                document.getElementById("History").innerHTML = ArcBody;   
                 
                $('#CharHistory').modal();            
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
          <a class="brand" href="index.php">FlickPS SvT</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
<?php
	if (!(isset($_SESSION['login']) && $_SESSION['login'] != "")) 
    {
        print "<i class='icon-thumbs-up'></i>&nbsp;<a href='users.php' class='navbar-link'>Log In</a>";
    }
	else
	{
		print "<i class='icon-off'></i>&nbsp;<a href='verify.php?logout=yes' class='navbar-link'>Log Out</a>";
	}
?>
            </p>
            <ul class="nav">
              <li><?php print "<a href='viewstory.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-thumbs-up"></i>&nbsp; Storyline</a></li>
              <li class="active"><?php print "<a href='viewpeople.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-user"></i>&nbsp; Characters</a></li>
            </ul>            
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>    

    <div class="container">

      <div class="row">
		<div class="span12">

	<p><h4><center>Story Characters</center></h4></p>
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
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . ";";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	  
		
	  if ($db_found) 
 	  {	     
		 $SQL = "SELECT status FROM groups WHERE id = " . intval($_GET['gid']) . ";";
		 $result = mysql_query($SQL);
		 $row = mysql_fetch_array($result);
		 
		 $viewstatus = $row[0]; 
		 
		 if (!($viewstatus == "Public" || $viewstatus == "Wiki"))
		 {
			if (isset($_SESSION['userid']))
			{
				$SQL = "SELECT * FROM g2u WHERE gid = " . intval($_GET['gid']) . " AND uid = " . intval($_SESSION['userid']) . "; ";
				$result = mysql_query($SQL);
				$userrows = mysql_num_rows($result);
				
				if ($userrows == 0)
				{
					print "<script> window.location = 'index.php'; </script>";
				}
			}
			else
			{
				print "<script> window.location = 'index.php'; </script>";
			}
		 }
		 
		 
	     if (isset($_POST['OpenHistory']) && $_POST['cid'] != "")
	     {
          $cid = intval($_POST['cid']);
			
          // $SQL = "SELECT * FROM characters WHERE project = '" . $groupname . "' AND id = " . $cid . ";";
          $SQL = "SELECT * FROM characters WHERE id = " . $cid . ";";
          $result = mysql_query($SQL);
          $db_field = mysql_fetch_assoc($result);
          
          $currentname = $db_field['name'];
          $currentbody = "";
          
          $SQL = "SELECT time.days, location.name, location.hour, l2c.lid FROM time INNER JOIN location ON time.id=location.Tid RIGHT JOIN l2c ON location.id=l2c.lid WHERE l2c.cid= " . $cid . " ORDER BY time.days; "; 
          $result = mysql_query($SQL);
          $storyrows = mysql_num_rows($result);
          
          if ($storyrows < 1)
          {
              $currentbody = "<center>Character hasn&#39;t appeared in the story yet.</center><br/>";
          }
			
          $currentbody .= "<table align=center border=0 cellpadding=4>";
          
          $days = 0;		   
		  $year = 0;
		  $month = 0;
		  $day = 0;
		  $counter = 0;
          
          while ($db_field = mysql_fetch_assoc($result)) 
          {  
			  $days = $db_field['days'];
			  
			  $thisyear = intval($days/416);
			  $thismonth = intval(($days%416)/32);
			  $thisday = intval(($days%416)%32);
			  
			  if ($thisyear != $year)
			  {
					if ($counter != 0) 
					{
						$currentbody .= "</td><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
					}
					$currentbody .= "</table><center><strong>Year " . $thisyear . "</strong></center><br/>";
					$currentbody .= "<table align=center border=0 cellpadding=4>";
			  }  
			  if ($thismonth != $month)
			  {
					$currentbody .= "</td><tr><td><strong>Month " . $thismonth . "</strong></td><td>&nbsp;</td><td>&nbsp;</td></tr>";
					$currentbody .= "</td><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			  }
			  
			  if ($thisday != $day || ($thismonth != $month && $thisday == $day))
			  {
					$currentbody .= "<tr><td><strong>Day " . $thisday . "</strong></td><td>&nbsp; &rsaquo; &nbsp;</td>";
			  }
			  else
			  {
					if ($counter == 0)
					{
						$currentbody .= "<tr><td><strong>Day " . $thisday . "</strong></td><td>&nbsp; &rsaquo; &nbsp;</td>";
					}
					else
					{
						$currentbody .= "<tr><td>&nbsp;</td><td>&nbsp; &rsaquo; &nbsp;</td>";
					}					
			  }              
              $currentbody .= "<td width=300>" . $db_field['name'] . "</td></tr>";
              
              if ($db_field['hour'] > 0)
              {
					$timetext = "<tr><td>&nbsp;</td><td>&nbsp;</td><td><i>" . $db_field['hour'] . " Hours</i></td>";
					
					if ($db_field['hour'] < 1000)
						$timetext = "<tr><td>&nbsp;</td><td>&nbsp;</td><td><i>0" . $db_field['hour'] . " Hours</i></td>";
					if ($db_field['hour'] < 100)
						$timetext = "<tr><td>&nbsp;</td><td>&nbsp;</td><td><i>00" . $db_field['hour'] . " Hours</i></td>";
					if ($db_field['hour'] < 10)
						$timetext = "<tr><td>&nbsp;</td><td>&nbsp;</td><td><i>000" . $db_field['hour'] . " Hours</i></td>";
					if ($db_field['hour'] >= 2400)
						$timetext = "<tr><td>&nbsp;</td><td>&nbsp;</td><td><i>0000 Hours</i></td>";
					
					$currentbody .= $timetext;
              }
              
              $SQL = "SELECT l2c.lid, characters.name FROM l2c INNER JOIN characters ON characters.id = l2c.cid WHERE l2c.lid = " . $db_field['lid'] . "; ";
              $people = mysql_query($SQL);
              $pplrows = mysql_num_rows($people); 
              
              $currentbody .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>";
              
              if ($pplrows > 1)
              {        
                  $currentbody .= "Character was with ";
                  $pplcount = 2;
                  
                  while ($ppl_field = mysql_fetch_assoc($people))
                  {
                      if ($ppl_field['name'] != $currentname)
                      {
                          if ($pplcount == $pplrows)
                          {
                              $currentbody .= $ppl_field['name'] . ".";
                          }
                          else if ($pplcount == ($pplrows-1))
                          {
                              $currentbody .= $ppl_field['name'] . " and ";
                          }
                          else
                          {
                              $currentbody .= $ppl_field['name'] . ", ";
                          }                      
                          $pplcount++;
                      }
                  } 
              }
              else
              {
                  $currentbody .= "Character was alone at that time.";
              }
              $counter++;
              $day = $thisday;
              $month = $thismonth;
              $year = $thisyear;              
              
              $currentbody .= "</td><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
          }
          
          $currentbody .= "</table><br/>\n";
                            
          echo "<input type='hidden' id='ArcBody'  value='" . $currentbody . "'>";
	     }
			
			
	     print "<p>&nbsp;</p>";
	     print "<table class='table table-bordered'>";	
	     print "<tr><th># ID</th><th>Character</th>";
	     print "<th>Status</th></tr>\n";
	     
	     $search1  = array('&#39');
         $replace1 = array('\\\'');
         $search2  = array('&#39');
         $replace2 = array('&#39;');
	     
	     $SQL = "SELECT * FROM characters WHERE project = '" . $groupname . "' ORDER BY name; ";
	     $result = mysql_query($SQL);     
			
         $counter = 1;
			
 	     while ($db_field = mysql_fetch_assoc($result)) 
	     {
          $character = str_replace($search1, $replace1, $db_field['name']);
          $description = str_replace($search1, $replace1, $db_field['description']);
          
          // $character = $db_field['name'];
          // $description = $db_field['description'];
          
          print "<tr>";
          print "<td>" . $counter . "</td>";
          print "<td><a href='#' rel='popover' data-placement='right' ";
            
          if ($description == "")
          {
              print "title='<i>No description.</i>' ";
          }
          else
          {
              print "title='" . str_replace($search2, $replace2, $db_field['description']) . "' ";
          }		          
          print "onClick=\"OpenHistory('" . $character . "', " . $db_field['id'] . ");\">";
          print str_replace($search2, $replace2, $db_field['name']) . "</a></td>";
          
          print "<td>" . $db_field['status'] . "</td></tr>\n";
          
          $counter++;
       }	
			
	     print "</table>";
	     
	     
	     mysql_close($db_handle);
	  }
	  else 
 	  {
        print "Database NOT Found " . $db_handle;
	  }	

?>

	<div class="modal hide" id="CharHistory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 
		class="modal hide fade in" aria-hidden="true">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	    <h4 id="CharacterName">Story Arc</h4>
	  </div>
	  <div class="modal-body">	 
      <p>&nbsp;</p> 
	    <p id="History">Loading...</p>	
	    <p>&nbsp;</p> 	    
	  </div>
	  <div class="modal-footer">
	    <button class="btn" data-dismiss="modal" aria-hidden="true" onClick="ClearModal();"><strong>OK &nbsp;</strong>
          <i class="icon-ok"></i></button>
	  </div>
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
