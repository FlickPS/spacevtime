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
    <title>SvT Messages</title>
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
      
      label 
      {
		  font-size: 12px;
          color:#AAAAAA;
      }  
      label:hover
      {
		  font-size: 12px;
          color:#000000;
      } 
      
      .tooltip-inner 
      {
			max-width: 300px;
      }
      
      .div-msg 
      {
			height:360px;
			overflow-y: scroll;
			overflow-x: hidden;
			
			scrollbar-track-color:#FFFFFF;
			scrollbar-face-color:#EEEEEE;
			scrollbar-3d-light-color:#EEEEEE;
			scrollbar-dark-shadow-color:#FFFFFF;
			scrollbar-shadow-color:#FFFFFF;
			scrollbar-arrow-color:#AAAAAA;
      }      
      
      .div-msg::-webkit-scrollbar{width:9px;height:9px;}
	  .div-msg::-webkit-scrollbar-button:start:decrement,#doc ::-webkit-scrollbar-button:end:increment{display:block;height:0;background-color:transparent;}
	  .div-msg::-webkit-scrollbar-track-piece{background-color:#FAFAFA;-webkit-border-radius:0;-webkit-border-bottom-right-radius:8px;-webkit-border-bottom-left-radius:8px;}
	  .div-msg::-webkit-scrollbar-thumb:vertical{height:50px;background-color:#999;-webkit-border-radius:8px;}
	  .div-msg::-webkit-scrollbar-thumb:horizontal{width:50px;background-color:#999;-webkit-border-radius:8px;}
      
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
 
    function UpdateChat()  
    {  
		var theenddate = $("#enddate").text();
		
		var gid = <?php print intval($_GET["gid"]); ?>;			
		var uid = <?php print $_SESSION['userid']; ?>;		
		var uname = "<?php print $_SESSION['login']; ?>";
		var username = uname.replace(/&/g, "and");
		
		var msgdatedata = "UpdateChat=1&enddate=" + theenddate + "&gid=" + gid + "&uid=" + uid + "&username='" + username + "'";
		
        $.ajax({  
            type:   "POST",  
            url:    "svtdata.php",  
            data:   msgdatedata,  
            success: function(data)
            {                 
				var updatedenddate = $(data).filter('#NewEndDate').text(); 
				
                var chatbody = $(data).filter('#ChatUpdate').html(); 
                
                String.prototype.parseURL = function() 
				{
					return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) 
					{
						return url.link(url);
					});
				};
				
				var ParsedChatBody = chatbody.parseURL();
                
                $("#newmessages").append(ParsedChatBody);                   
                $('#newmessages a').attr('target', '_blank');
				
				if (updatedenddate != "None")
				{				
					$("#enddate").text(updatedenddate);
					
					var NscrollHeight = $("#newmessages").prop("scrollHeight");   
					$("#newmessages").animate({ scrollTop: NscrollHeight }, 'normal');
				}
				
				$('td[rel=tooltip]').tooltip(); 
            },  
            error: function(err)
            {  
                // alert('Error: ' + err);  
            }  
        });
    } 
    
    window.setInterval(function()
    {
		UpdateChat();
	}, 800);
	
	window.onload = function() 
	{ 
		var NscrollHeight = $("#newmessages").prop("scrollHeight");   
		$("#newmessages").animate({ scrollTop: NscrollHeight }, 'normal');
	};
    
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
			  <?php print "<i class='icon-envelope'></i>&nbsp;<a href='messages.php?gid=" . intval($_GET["gid"]) . "' class='navbar-link'>Messages</a> &nbsp;"; ?>
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

<?php

	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	
		
	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);	
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', ''); 
	  
	  $SQL = "SELECT * FROM g2u WHERE gid = '" . intval($_GET['gid']) . "' AND uid = '" . intval($_SESSION['userid']) . "';";
      $result = mysql_query($SQL);
      $number_of_rows = mysql_num_rows($result);
      
      if ($number_of_rows == 0 && !isset($_POST['OpenHistory']))
      {
			// Check if Public Wiki is available.
			
			mysql_close($db_handle);
			print "<script>window.location = '/index.php';</script>";
      }
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . ";";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	
	  $currentuser = str_replace($search, $replace, $_SESSION['login']);  
	  $thisuserid = intval($_SESSION['userid']); 
	  
	  print "<p><h4><center>Messages for " . $row[0] . "</center></h4></p><p>&nbsp;</p>\n\n";
	  
	  
	  if ($db_found) 
 	  {	     
		$SQL = "SELECT * FROM messages WHERE gid = " . intval($_GET['gid']) . "; ";
		$result = mysql_query($SQL);
	    
		while ($db_field = mysql_fetch_assoc($result)) 
		{
			$newmsgs = $db_field['readstatus']; 
			$msgid = $db_field['id'];
			$readarray = explode("#", $newmsgs);
			
			$readupdate = "";
			$readcounter = 0;
			
			foreach ($readarray as $readreceipt)
			{
				if (intval($readreceipt) != $thisuserid)
				{
					if ($readcounter == 0)
						$readupdate .= $readreceipt;
					else
						$readupdate .= "#" . $readreceipt;
				}
				$readcounter++;
			}
			
			$SQL  = "UPDATE messages SET readstatus = '" . $readupdate . "' WHERE id = '" . $msgid . "'; ";
			$msgupdate = mysql_query($SQL);
		}
			
		$_SESSION['newmessages'] = 0;
		 
			
	    print "<div id='newmessages' class='div-msg'><table cellpadding='10'>";
	     
        $search2  = array('&#39');
        $replace2 = array('&#39;');
	     
	    $SQL = "SELECT * FROM messages WHERE gid = " . intval($_GET['gid']) . " ORDER BY datetime; ";
	    $result = mysql_query($SQL);   
	    $number_of_rows = mysql_num_rows($result);
	     
	    $msgcounter = 0; 			
	    $startdate = "";
	    $enddate = "";
			
 	    while ($db_field = mysql_fetch_assoc($result)) 
	    {
			$msguser = str_replace($search2, $replace2, $db_field['username']);
			$message = str_replace($search2, $replace2, $db_field['message']);
			$datetime = date("d/m/Y @ H:i", strtotime($db_field['datetime']));
			
			print "<tr><td class='span2'>" . $msguser . "</td>";
			print "<td class='span6' rel='tooltip' title='" . $datetime . "' data-placement='right'>" . $message . "</td></tr>";
			
			if ($msgcounter == 0)
			{
				$startdate = $db_field['datetime'];
			}
			else
			{
				$enddate = $db_field['datetime'];
			}
			$msgcounter++;
		}			
	    
	    if ($number_of_rows > 0)
	    {
			print "</table><p>&nbsp;</p></div>\n\n";
			
			print "<div id='startdate' class='hide'>" . $startdate . "</div>";
			print "<div id='enddate' class='hide'>" . $enddate . "</div>";			 
	    }
	    else
	    {
			print "</table><p>&nbsp;</p>";
			print "<p>&nbsp;</p><p>&nbsp;</p></div>\n\n";
			
			print "<div id='startdate' class='hide'>2000-01-01 00:00:00</div>";
			print "<div id='enddate' class='hide'>2000-01-01 00:00:00</div>";	
	    }
	    
	    mysql_close($db_handle);
	  }
	  else 
 	  {
        print "Database NOT Found " . $db_handle;
	  }	

?>
    
    <a name='chatbox'>&nbsp;</a>
    
	<!-- Remember to change the Span when adding advertisements! -->
	
	<form name='form' method='post' class='form-inline' action='svtdata.php'>
		<textarea name='message' id='message' rows='4' rel='tooltip' data-placement='top' 
			title='Hit Enter to Send ! Maximum 256 characters.' class='span12'></textarea>
		<input type='hidden' name='gid' id='gid' value='<?php print intval($_GET["gid"]); ?>'>
	</form>
	    
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
    $(function()
    {
		String.prototype.parseURL = function() 
		{
			return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) 
			{
				return url.link(url);
			});
		};
		
		var TempChatBody = document.getElementById("newmessages").innerHTML;
		var ParsedChatBody = TempChatBody.parseURL();
        
        $("#newmessages").html(ParsedChatBody);           
        $('#newmessages a').attr('target', '_blank');
        
        $('td[rel=tooltip]').tooltip();
        $('textarea[rel=tooltip]').tooltip();
    });
    
    $("textarea").keypress(function(event) 
    {
		if (event.which == 13) 
		{
			event.preventDefault();
			
			var msgtext = document.getElementById("message").value;
			var messagetext = msgtext.replace(/&/g, "and");
			
			var gid = <?php print intval($_GET["gid"]); ?>;			
			var uid = <?php print $_SESSION['userid']; ?>;
			
			var uname = "<?php print $_SESSION['login']; ?>";
			var username = uname.replace(/&/g, "and");
			
			var msgdatedata = "message=" + messagetext + "&gid=" + gid + "&uid=" + uid + "&username=" + username;
			
			$.ajax({  
				type:   "POST",  
				url:    "svtdata.php",  
				data:   msgdatedata,  
				success: function(data)
				{  
					document.getElementById("message").value = "";
					
					var NscrollHeight = $("#newmessages").prop("scrollHeight");   
					$("#newmessages").animate({ scrollTop: NscrollHeight }, 'normal');
				},  
				error: function(err)
				{  
					// alert('Error: ' + err);  
				}  
			}); 
		}
	});
  </script>

</html>
