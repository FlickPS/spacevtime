<?php	session_start();	?>
<!DOCTYPE html>
<html lang="en">

  <head>
  
    <meta charset="utf-8">
    <title>SvT Graphical Storyline</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/twitter/css/bootstrap.css" rel="stylesheet">
    <link href="/tooltip/style.css" rel="stylesheet">
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
      
      .div-msg 
      {
		overflow-x: hidden;
      } 
      
      .char-text 
      {
		  cursor: default;
		  text-decoration: none;
          color: #AAAAAA;
      }  
      .char-text:hover
      {
		  cursor: default;
		  text-decoration: none;
          color: #000000;
      } 
      
      .tooltip-inner 
      {
			max-width: 300px;
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
    
    <script src="/KinectJS/dist/kinetic-core.js"></script>   

<script type="text/javascript">

	var added = new Array();
	var days = new Array();
	var maps = new Array();
	var bars = new Array();
	var boxes = new Array();
	
	var places = new Array();
	var toolshow = false;
	var locationid = 0;
	var enabled = true;
	
<?	

	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	
	  
	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);		
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', ''); 
	  
	  
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
					print "\n window.location = 'index.php'; \n\n";
				}
			}
			else
			{
				print "\n window.location = 'index.php'; \n\n";
			}
	  }
	  
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . "; ";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	  
	  
	  $clearspaces  = array('\'', '\"', ';', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '\\', '/', '{', '}', '`', '~', ',', '?', '[', ']', ':', '|', '-', '_', '<', '>', '.');                  
      $directory = intval($_GET['gid']) . "_" . str_replace($clearspaces, '', $row[0]) . "_";
	  
	  $search1  = array('&#39');
      $replace1 = array('\\\'');
	  
	  if ($db_found) 
 	  {	
	     $SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' ORDER BY days";
	     $result = mysql_query($SQL);
	     $index = 0;
			
 	     while ($db_field = mysql_fetch_assoc($result)) 
	     {
          print "days[" . $index . "] = " . $db_field['days'] . ";\n";
          print "maps[" . $index . "] = '" . $db_field['image'] . "';\n";
          $index++;
	     }	
	     print "\n";	     
	     
	     $SQL = "SELECT * FROM time INNER JOIN location ON time.id=location.Tid WHERE time.project = '" . $groupname . "' ORDER BY time.days";
	     $result = mysql_query($SQL);
	     $index = 0;
			
 	     while ($db_field = mysql_fetch_assoc($result)) 
	     {                   
          $SQL = "SELECT DISTINCT characters.name, characters.id AS charlist FROM characters, location, l2c WHERE l2c.lid = " . $db_field['id'] . " AND l2c.cid = characters.id";
          $people = mysql_query($SQL);
          $numppl = 0;
          
          print "var charlist" . $index . " = new Array();\n";
          
          while ($ppl_field = mysql_fetch_assoc($people))
          {
              $person = str_replace($search1, $replace1, $ppl_field['name']);
              print "charlist" . $index . "[" . $numppl . "] = '" . $person . "'; \t ";
              $numppl++;
          } 
          print "\n";
          
          $description = str_replace($search1, $replace1, $db_field['name']);
          
          print "places[" . $index . "] = { days: "		. $db_field['days']		. ", "     	;
          print						  "   lid: "		. $db_field['id']		. ", "     	;
          print                       "   xcoord: "		. $db_field['xcoord']		. ", "     	;
          print                       "   ycoord: "		. $db_field['ycoord']		. ", "     	;
          print                       "   name: '"		. $description			. "', " 		;  
          print						  "   hour: " 		. $db_field['hour'] 		. ","		;
          print                       "   people: charlist" . $index . " };   \n\n"	;
          
          $index++;
	     }	
	     print "\n";
	     
	     mysql_close($db_handle);
	  }
	  
    print "\tvar datenow = days[0]; \n";
    print "\tvar mapnow = maps[0]; \n";
    print "\tvar mapindex = 0; \n";
	  
	if (!$_GET["tunit"])
    {            
        if ($_GET["tunit"] == "0")
        {
            print "\n\n for (n = 0; n < days.length; n++) \n";
			print " { \n\t if (days[n] == 0) \n";
			print " \t { \n\t\t datenow = days[n]; \n\t\t mapnow = maps[n]; \n\t\t mapindex = n; ";
			print " \n\t } \n } \n\n";
        }
    }
    else
    {            
        print "\n\n for (n = 0; n < days.length; n++) \n";
        print " { \n\t if (days[n] == " . intval($_GET["tunit"]) . ") \n";
        print " \t { \n\t\t datenow = days[n]; \n\t\t mapnow = maps[n]; \n\t\t mapindex = n; ";
        print " \n\t } \n } \n\n";
    }
    
    print "\tvar uploaded_dir = 'uploads/" . $directory . "'; \n";
	
?>
	
	var countchar = 0;
	var mousePos = { x:0, y:0 };
	var maplimit = days.length - 1;	

	for (i = 0; i < maplimit+1; i++)
	{
		bars[i] = ((days[i]-days[0])/(days[maplimit]-days[0]))*624+7;
	}
	
	
	function getMousePos(canvas, evt) 
	{
        var rect = canvas.getBoundingClientRect();
        var mx = evt.clientX - rect.left;
        var my = evt.clientY - rect.top;
        var mPos = { x:mx, y:my };
        
        return mPos;
	}
	
	function writeMessage(messageLayer, message, barnum) 
	{
        var context = messageLayer.getContext();
        var xcoord = 0;
        
        messageLayer.clear();
        context.font = '12pt Helvetica';
        context.fillStyle = 'white';
        
        if (barnum != maplimit)     
        {
            context.textAlign = "left";
            xcoord = bars[barnum]+2;
        }
        else
        {
            context.textAlign = "right"; 
            xcoord = bars[barnum]-2; 
        }          
        context.fillText(message, xcoord, 16);
	}
	
	function addHovers(shape, easing, messageLayer, barnum) 
	{
		var tdays 	= days[barnum]; 
		var year 	= parseInt( ( tdays/416 ).toString() );
		var month 	= parseInt( ( (tdays%416)/32 ).toString() );
		var day 	= parseInt( ( (tdays%416)%32 ).toString() );
			
		var textmsg = "Day " + (day).toString() + " ";
		
		if (month != 0)
			textmsg += "Month " + (month).toString() + " ";
		if (year != 0)
			textmsg += "Year " + (year).toString() + " ";  
        
        shape.on('mouseover touchstart', function() 
        {
          document.body.style.cursor = 'pointer';          
          this.transitionTo({
            scale: {
              x: 2,
              y: 1
            },
            duration: 0.3,
            easing: easing
          });
          
          // writeMessage(messageLayer, textmsg, barnum);
          toolshow = true;
          tooltip.show(textmsg);
        });
        
        shape.on('mouseout touchend', function() 
        {
          if (toolshow)
          {
              tooltip.hide();
          }
          // writeMessage(messageLayer, '', barnum);
          
          this.transitionTo({
            scale: {
              x: 1,
              y: 1
            },
            duration: 0.3,
            easing: easing
          });
          document.body.style.cursor = 'default';          
        });
        
        shape.on('mousedown', function()
        {
            changeimage(maps[barnum], days[barnum]);
            document.getElementById("currentday").value = (days[barnum]).toString();
            document.getElementById("currentmap").value = barnum.toString();
        });
	}
    

	function winloaded() 
	{			
		$('body').on('contextmenu', '#Main', function(e){ return false; });
		$('body').on('contextmenu', '#NavTree', function(e){ return false; });
		  
		var stage = new Kinetic.Stage({container: 'NavTree', width: 640, height: 30});
		var layer = new Kinetic.Layer();
		var messageLayer = new Kinetic.Layer();
		
		var smallrect = new Kinetic.Rect({x: 0, y: 0, width: 640, height: 30, fill: '#222222', stroke: '#222222', strokeWidth: 0});
		layer.add(smallrect);
		    
		for (j = 0; j < maplimit+1; j++)
		{
			boxes[j] = new Kinetic.Rect(
			{
			  x: bars[j],
			  y: 0,
			  width: 4,
			  height: 30,
			  fill: 'white',
			  stroke: '#222222',
			  strokeWidth: 0,
			  offset: {x: 2, y: 0}
			});
			
			addHovers(boxes[j], 'ease-in', messageLayer, j);
			layer.add(boxes[j]);
		}    
		
		stage.add(layer);
		stage.add(messageLayer);  
		
		var elem = document.getElementById('Main');
		var context = elem.getContext('2d');
		var img = new Image(); 
		
		if (maplimit < 0)
		{        
			var caption = "There are currently no events on the timeline.";
			document.getElementById('EditEvent').disabled = true;
			enabled = false;
			
	    	context.fillStyle = "rgba(0, 0, 0, 0.5)";
	    	context.rect(0, 0, 640, 30);
	    	context.fill();	    	
	    	context.fillStyle = "white"; 
			context.font = "bold 18px Helvetica"; 
			context.fillText(caption, 8, 20);
		}
		else
		{
			var year 	= parseInt( ( datenow/416 ).toString() );
			var month 	= parseInt( ( (datenow%416)/32 ).toString() );
			var day 	= parseInt( ( (datenow%416)%32 ).toString() );
			
			var caption = "Day " + (day).toString() + " ";
			
			if (month != 0)
				caption += "Month " + (month).toString() + " "; 
			if (year != 0)
				caption += "Year " + (year).toString() + " ";
			
			document.getElementById('EditEvent').disabled = false;
			enabled = true;
			
			if (mapnow != "")
			{	
				context.fillStyle = "rgba(0, 0, 0, 0.5)";
				context.fillRect(0, 0, 640, 480);
				context.fillStyle = "white"; 
  	    		context.font = "bold 18px Helvetica"; 
  	    		context.fillText("Loading...", 280, 250);
  	    		
				img.addEventListener('load', function ()
				{
	      			context.fillStyle = "white";
	      			context.fillRect(0, 0, 640, 480);
					context.drawImage(this, 0, 0, 640, 480);
	         	 
	      			context.fillStyle = "rgba(0, 0, 0, 0.5)";
	      			context.rect(0, 0, 640, 30);
	      			context.fill();
	      			context.fillStyle = "white"; 
					context.font = "bold 18px Helvetica"; 
					context.fillText(caption, 8, 20);   
					
					for (k = 0; k < places.length; k++)
					{                  
						if (places[k].days == datenow)
						{
							if (places[k].hour > 0)
							{
								timetext = (places[k].hour).toString() + " Hours";
								
								if (places[k].hour < 1000)
									timetext = "0" + (places[k].hour).toString() + " Hours";
								if (places[k].hour < 100)
									timetext = "00" + (places[k].hour).toString() + " Hours";
								if (places[k].hour < 10)
									timetext = "000" + (places[k].hour).toString() + " Hours";
								if (places[k].hour >= 2400)
									timetext = "0000 Hours";
								
								var xwing = 0;
								
								if (places[k].xcoord > 560)
									xwing = places[k].xcoord-90;
								else
									xwing = places[k].xcoord+16;
								
								context.font = "bold 12px Helvetica"; 
								context.strokeStyle = "white";
								context.lineWidth = 2;
								context.strokeText(timetext, xwing+4, places[k].ycoord+4);
								context.fillStyle = "black"; 								
								context.fillText(timetext, xwing+4, places[k].ycoord+4); 
							}
							
							context.beginPath();
							context.fillStyle = "black";
							context.arc(places[k].xcoord, places[k].ycoord, 12, Math.PI*2, 0, true);
							context.closePath();
							context.fill();        
							  
							context.beginPath();
							context.fillStyle = "red";
							context.arc(places[k].xcoord, places[k].ycoord, 10, Math.PI*2, 0, true);
							context.closePath();
							context.fill();
						}
					}
				}, false);
				
				img.src = uploaded_dir + mapnow; 
             
				context.beginPath();
				context.fillStyle = "black";
				context.arc(720, 540, 12, Math.PI*2, 0, true);
				context.closePath();
				context.fill();      
	  		}
			else
			{
				caption = "No map attached for Day " + (datenow).toString() + "!";
				context.fillStyle = "rgba(0, 0, 0, 0.5)";
				context.rect(0, 0, 640, 30);
				context.fill();
				context.fillStyle = "white"; 
  	    		context.font = "bold 18px Helvetica"; 
  	    		context.fillText(caption, 8, 20);
			}
			
			document.getElementById("currentmap").value = (mapindex).toString();
			document.getElementById("currentday").value = (datenow).toString();
			
			elem.addEventListener('mousemove', function(evt) 
			{
				mousePos = getMousePos(elem, evt);           
				var today = parseInt(document.getElementById("currentday").value);
				document.body.style.cursor = 'default'; 
				   
				if (toolshow)
				{
					tooltip.hide();
				}
				
				for (k = 0; k < places.length; k++)
				{                  
					if (places[k].days == today)
					{
						if (mousePos.x > (places[k].xcoord-8) && mousePos.x < (places[k].xcoord+8) && mousePos.y > (places[k].ycoord-8) && mousePos.y < (places[k].ycoord+8))
						{                      
							document.body.style.cursor = 'pointer';
							
							if (places[k].name != "")
							{
								var toolstring = "<strong>" + places[k].name + "</strong>";
								
								for (n = 0; n < places[k].people.length; n++)
								{
									toolstring += "<br>- " + places[k].people[n];
								}
								
								toolshow = true;
								tooltip.show(toolstring);
							}
							else
							{
								var toolstring = "<i>Character(s)</i> :";
								
								for (n = 0; n < places[k].people.length; n++)
								{
									toolstring += "<br>- " + places[k].people[n];
								}
								
								toolshow = true;
								tooltip.show(toolstring);
							}
						}
					}
				}                 
			}, false);	  
		}
	}

 	window.onload = winloaded; 
 	
	function changeimage(imgurl, day)
	{
      var elem = document.getElementById('Main');
      var context = elem.getContext('2d');
      var img = new Image();
      
      var year 	= parseInt( ( day/416 ).toString() );
	  var month = parseInt( ( (day%416)/32 ).toString() );
	  var tday 	= parseInt( ( (day%416)%32 ).toString() );
		
	  var caption = "Day " + (tday).toString() + " ";
	  
	  if (month != 0)
		  caption += "Month " + (month).toString() + " "; 
	  if (year != 0)
		  caption += "Year " + (year).toString() + " ";
     
	  if (imgurl != "")
	  {
	    context.fillStyle = "rgba(0, 0, 0, 0.5)";
	    context.fillRect(0, 0, 640, 480);
	    context.fillStyle = "white"; 
		context.font = "bold 18px Helvetica"; 
		context.fillText("Loading...", 280, 250);
		
		img.addEventListener('load', function ()
		{
          context.fillStyle = "white";
          context.fillRect(0, 0, 640, 480);
          context.drawImage(this, 0, 0, 640, 480);
          
          context.fillStyle = "rgba(0, 0, 0, 0.5)";
          context.rect(0, 0, 640, 30);
          context.fill();
          context.fillStyle = "white"; 
  	      context.font = "bold 18px Helvetica"; 
  	      context.fillText(caption, 8, 20);
  	      
          for (k = 0; k < places.length; k++)
          {                  
              if (places[k].days == day)
              {
				  if (places[k].hour > 0)
				  {
					timetext = (places[k].hour).toString() + " Hours";
					
					if (places[k].hour < 1000)
						timetext = "0" + (places[k].hour).toString() + " Hours";
					if (places[k].hour < 100)
						timetext = "00" + (places[k].hour).toString() + " Hours";
					if (places[k].hour < 10)
						timetext = "000" + (places[k].hour).toString() + " Hours";						
					if (places[k].hour >= 2400)
						timetext = "0000 Hours";
						
					var xwing = 0;
					
					if (places[k].xcoord > 560)
						xwing = places[k].xcoord-90;
					else
						xwing = places[k].xcoord+16;
					
					context.font = "bold 12px Helvetica";
					context.strokeStyle = "white";
					context.lineWidth = 2;
					context.strokeText(timetext, xwing+4, places[k].ycoord+4);
					context.fillStyle = "black"; 					 
					context.fillText(timetext, xwing+4, places[k].ycoord+4);  
				  }
					
                  context.beginPath();
                  context.fillStyle = "black";
                  context.arc(places[k].xcoord, places[k].ycoord, 12, Math.PI*2, 0, true);
                  context.closePath();
                  context.fill();
                  
                  context.beginPath();
                  context.fillStyle = "red";
                  context.arc(places[k].xcoord, places[k].ycoord, 10, Math.PI*2, 0, true);
                  context.closePath();
                  context.fill();
              }
          }          
		}, false);
		
	    img.src = uploaded_dir + imgurl;
	    
	    context.beginPath();
		context.fillStyle = "black";
		context.arc(720, 540, 12, Math.PI*2, 0, true);
		context.closePath();
		context.fill();
	  }
	  else
	  {
        caption = "No map attached for Day " + day.toString() + "!";
        
        img.addEventListener('load', function ()
      	{
            context.fillStyle = "white";
            context.fillRect(0, 0, 640, 480);
            context.drawImage(this, 0, 0, 640, 480);
            
            context.fillStyle = "rgba(0, 0, 0, 0.5)";
            context.fillRect(0, 0, 640, 30);
            context.fillStyle = "white"; 
            context.font = "bold 18px Helvetica"; 
            context.fillText(caption, 8, 20);
      	}, false);
      	
        img.src = 'images/Blank.png';
	  }
    }

	  function back()
	  {
		  var temp = parseInt(document.getElementById("currentmap").value);
		  var counter = temp - 1;
		  
		  if (counter >= 0)
		  {
				changeimage(maps[counter], days[counter]);
				document.getElementById("currentday").value = (days[counter]).toString();
				document.getElementById("currentmap").value = counter.toString();
		  }
	  }
	  
	  function next()
	  {
		  var temp = parseInt(document.getElementById("currentmap").value);
		  var counter = temp + 1;
		  
		  if (counter <= maplimit)
		  {
				changeimage(maps[counter], days[counter]);
				document.getElementById("currentday").value = (days[counter]).toString();
				document.getElementById("currentmap").value = counter.toString();
		  }
	  }
	
	function LoadFrame(daycode)
	{
		var daycounter = 0;
		
		for (ij = 0; ij < days.length; ij++)
		{
			if (days[ij] == daycode)
			{
				daycounter = ij;
				break;
			}
		}
		
		changeimage(maps[daycounter], daycode);
        document.getElementById("currentday").value = daycode.toString();
        document.getElementById("currentmap").value = daycounter.toString();
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
              <li class="active"><? print "<a href='viewstory.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-thumbs-up"></i>&nbsp; Storyline</a></li>
              <li><? print "<a href='viewpeople.php?gid=" . intval($_GET["gid"]) . "'>"; ?><i class="icon-user"></i>&nbsp; Characters</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">    
       
      <div class="row">
      <div class="span12">      
      
      <a name="SVTGSI"></a>
      
	<p id="MainTitle"><!-- Space v. Time Graphical Storyline Interface --></p>	
      	
	<center>
	<table cellpadding="8">	
	<tr>
	  <td valign=center>&nbsp;<button class="btn btn-large btn-inverse" name="ButtonBack" id="ButtonBack" onclick="back();" >
		<i class="icon-arrow-left icon-white"></button>&nbsp;</td>
	  <td>	  
	    <canvas id="Main" width="640" height="480" style="border:1px solid #AAAAAA;"></canvas>
	    <br>
	    <div id='NavTree'></div>
	  </td>
	  <td valign=center>&nbsp;<button class="btn btn-large btn-inverse" name="ButtonNext" id="ButtonNext" onclick="next();" >
		<i class="icon-arrow-right icon-white"></button>&nbsp;</td>
	</tr>
	
	<tr><td></td><td>
	    <form class="form-inline" method="post" name="editsubmit" action=""> 
		<input type="hidden" value="" id="currentmap" name="currentmap">
		<input type="hidden" value="" id="currentday" name="currentday">
		<input type="hidden" value="" id="GroupID" name="GroupID">
		<input type="hidden" value="" id="EditEvent" name="EditEvent"></form> 
	</td><td></td></tr>
	</table>
	</center> 
	
	<p>&nbsp;</p><a name="TimeLine"></a>
	<h4><center>EVENT TIMELINE</center></h4>	
	
	<div id="TimeLine" class="div-msg"><center>Loading...</center></div>
	<input type="hidden" name="TempTimelineData" id="TempTimelineData" value=""> 	
	<p>&nbsp;</p>	
	
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
    
    <script src="/tooltip/script.js"></script>

  </body>
  
<script> 
  
    var characters = new Array();
    var timeline = new Array();
    var singleyearflag = false;
    
<?	
	
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
	  
	  print "var TitleText = '" . $row[0] . "';\n";
	  print "var GroupName = '" . $groupname . "';\n\n";
	  
	  if ($db_found) 
 	  {	     
	     $search  = array('&#39');
         $replace = array('\\\'');
         	
 	     while ($db_field = mysql_fetch_assoc($result)) 
	     {
          $character = str_replace($search, $replace, $db_field['name']);          
          print "characters[" . $index . "] = '" . $character . "';\n";
          $index++;
	     }	
	     print "\n";	 
	     
	     
	     $SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' ORDER BY days;";
	     $result = mysql_query($SQL);
	     $index = 0;
	     
	     while ($db_field = mysql_fetch_assoc($result)) 
	     {
          $days = $db_field['days']; 
          $thisyear = intval($days/416);
		  
		  if ($thisyear != 0)
		  {
			print "timeline[" . $index . "] = '" . $thisyear . "';\n";
			$index++;
		  }		         
	     }		     	
	     
	     if ($index == 0)
	     {
			print "singleyearflag = true;";
	     }
	     print "\n";
		 
		 mysql_close($db_handle);
	  }
	  
?>
	
	var ThisGroupName = GroupName.replace(/&/g, "and");	
		
	document.getElementById('MainTitle').innerHTML = "<h4><center>Space v. Time - " + TitleText +  "</center></h4>";
	
	var kosong = "";
   
	function LoadYear(year, yearid)
	{
		var yearid = "Year" + yearid.toString();
		
		$.ajax({  
            type:   "POST",  
            url:    "svtdata.php",  
            data:   "SpecificYear=true&Group=\"" + ThisGroupName + "\"&Year=" + year,  
            success: function(data)
            {                  
                var HistoryTimeline = $(data).filter('#SpecificYearContent').html();
				
                document.getElementById(yearid).innerHTML = "<div class='accordion-inner'>" + HistoryTimeline + "</div"; 
				
                $('a[rel=tooltip]').tooltip();           
            },  
            error: function(err)
            {  
                // alert('Error: ' + err);  
            }  
        });	
	}
	
	if (singleyearflag == true)
	{
		$.ajax({  
            type:   "POST",  
            url:    "svtdata.php",  
            data:   "SingleYear=true&Group=\"" + ThisGroupName + "\"",  
            success: function(data)
            {                  
                var HistoryTimeline = $(data).filter('#SingleYearContent').html();
				
                document.getElementById("TimeLine").innerHTML = HistoryTimeline; 
                
                $('a[rel=tooltip]').tooltip();            
            },  
            error: function(err)
            {  
                // alert('Error: ' + err);  
            }  
        });
	}
	else
	{
		var AccordianCode = 		"<p>&nbsp;</p>";
		
		AccordianCode += 			"<div class='accordion' id='MultiYearline'><div class='accordion-group'><div class='accordion-heading'><center>" + 
									"<a class='accordion-toggle' data-toggle='collapse' data-parent='#MultiYearline' href='#Year0' " + 
									"onClick=\"LoadYear(" + timeline[0] + ", 0);\">Year " + 
									timeline[0] + "</a></center></div><div id='Year0' class='accordion-body collapse in'><div class='accordion-inner'>" + 
									"<center>Loading...</center>" + "</div></div></div>";
		
		var previousyear = timeline[0];
		
		for (yr = 1; yr < timeline.length; yr++)
		{
			if (timeline[yr] != previousyear)
			{
				AccordianCode += 	"<div class='accordion-group'><div class='accordion-heading'><center>" + 
									"<a class='accordion-toggle' data-toggle='collapse' data-parent='#MultiYearline' href='#Year" + yr + "' " + 
									"onClick=\"LoadYear(" + timeline[yr] + ", " + yr + ");\">Year " + 
									timeline[yr] + "</a></center></div><div id='Year" + yr + "' class='accordion-body collapse'><div class='accordion-inner'>" + 
									"<center>Loading...</center>" + "</div></div></div>";
			}
			previousyear = timeline[yr];
		}
		
        AccordianCode +=      		"</div></center>";
		
		document.getElementById("TimeLine").innerHTML = AccordianCode;  
		
		$.ajax({  
            type:   "POST",  
            url:    "svtdata.php",  
            data:   "SpecificYear=true&Group=\"" + ThisGroupName + "\"&Year=" + timeline[0],  
            success: function(data)
            {                  
                var HistoryTimeline = $(data).filter('#SpecificYearContent').html();
				
                document.getElementById("Year0").innerHTML = "<div class='accordion-inner'>" + HistoryTimeline + "</div"; 
				
                $('a[rel=tooltip]').tooltip();           
            },  
            error: function(err)
            {  
                // alert('Error: ' + err);  
            }  
        });		
	}
	
</script>
  
</html>
