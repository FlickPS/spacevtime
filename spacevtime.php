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
	  
	  $SQL = "SELECT * FROM g2u WHERE gid = '" . intval($_GET['gid']) . "' AND uid = '" . intval($_SESSION['userid']) . "'; ";
      $result = mysql_query($SQL);
      $number_of_rows = mysql_num_rows($result);
      
      if ($number_of_rows == 0)
      {
			// Check if Public Wiki is available.
			
			print "\n window.location = '/index.php'; \n ";
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
		 $search  = array('\\\'', '\'', '\"', ';', '<', '>');
         $replace = array('&#39', '&#39', '', '', '', '');
         
	     if (isset($_POST['LocationSubmit']))
	     {
          $xcoord = intval($_POST['xcoord']);
          $ycoord = intval($_POST['ycoord']);
          $name = str_replace($search, $replace, $_POST['description']);
          $today = intval($_POST['today']);
          $hour = intval($_POST['hour']);
          
          if ($_POST['hour'] == "0000")
          {
				$hour = 2400;
          }
          
          $SQL = "INSERT INTO location(project, Tid, xcoord, ycoord, name, hour) VALUES ('" . $groupname . "', (SELECT id FROM time WHERE project = '" . $groupname . "' AND days = " . $today . "), " . $xcoord . ", " . $ycoord . ", '" . $name . "', " . $hour . "); ";
          $result = mysql_query($SQL);          
          
          $lid = 0;
          $SQL = "SELECT * FROM location WHERE project = '" . $groupname . "' AND xcoord = " . $xcoord . " AND ycoord = " . $ycoord . " AND Tid = (SELECT id FROM time WHERE project = '" . $groupname . "' AND days = " . $today . "); ";
          $result = mysql_query($SQL); 
          
          $row = mysql_fetch_array($result); 
          $lid = $row[0];          
          
          // Add characters.
          
          $characters = explode("#", $_POST['added']);          
          $index = 0;
          
          while($characters[$index])
          {   
              $character = str_replace($search, $replace, $characters[$index]);
              $SQL = "INSERT INTO l2c(lid, cid) VALUES (" . $lid . ", (SELECT id FROM characters WHERE project = '" . $groupname . "' AND name = '" . $character . "')); ";
              $result = mysql_query($SQL); 
              
              $index++;
          }
          print "\n";
	     }
	     
	     
	     if (isset($_POST['LocationEdit']))
	     {
          $xcoord = intval($_POST['excoord']);
          $ycoord = intval($_POST['eycoord']);
          $name = str_replace($search, $replace, $_POST['edescription']);
          $today = intval($_POST['etoday']);
          $hour = intval($_POST['ehour']);
          
          if ($_POST['ehour'] == "0000")
          {
				$hour = 2400;
          }
          
          $SQL = "UPDATE location SET name = '" . $name . "', hour = " . $hour . " WHERE project = '" . $groupname . "' AND Tid = (SELECT id FROM time WHERE project = '" . $groupname . "' AND days = " . $today . ") AND xcoord = " . $xcoord . " AND ycoord = " . $ycoord . ";";
          $result = mysql_query($SQL);    
          
          $lid = 0;
          $SQL = "SELECT id FROM location WHERE project = '" . $groupname . "' AND xcoord = " . $xcoord . " AND ycoord = " . $ycoord . " AND Tid = (SELECT id FROM time WHERE project = '" . $groupname . "' AND days = " . $today . "); ";
          $result = mysql_query($SQL);  
          
          $row = mysql_fetch_array($result); 
          $lid = $row[0];
          
          $SQL = "DELETE FROM l2c WHERE lid = " . $lid;
          $result = mysql_query($SQL);        
          
          // Update characters.
          
          $characters = explode("#", $_POST['eadded']);         
          $index = 0;          
           
          while($characters[$index])
          {   
              $character = str_replace($search, $replace, $characters[$index]);
              $SQL = "INSERT INTO l2c(lid, cid) VALUES (" . $lid . ", (SELECT id FROM characters WHERE project = '" . $groupname . "' AND name = '" . $character . "')); ";
              $result = mysql_query($SQL); 
              
              $index++;
          }
          print "\n";
	     }
	     
	     if (isset($_POST['DeleteLocation']) && $_POST['DeleteLocation'] != "")
	     {
			$lid = intval($_POST['DeleteLocation']);
			$today = intval($_POST['dtoday']);
			
			$SQL = "DELETE FROM l2c WHERE lid = " . $lid;
			$result = mysql_query($SQL);  
			
			$SQL = "DELETE FROM location WHERE id = " . $lid;
			$result = mysql_query($SQL);  
			
			print "\n"; 
	     }
	     
	     
	     $search  = array('\'', '\"', ';', '<', '>');
		 $replace = array('&#39;', '', '', '', ''); 
	     
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
		    
		/*
		var smallLine = new Kinetic.Line({
			  points: [2, 16, 638, 16],
			  stroke: '#AAAAAA',
			  strokeWidth: 1,
			  lineJoin: 'round',
			  dashArray: [10, 4]
			});
		layer.add(smallLine);
		*/
		    
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
			
			elem.addEventListener('dblclick', function()
			{       
				// Check if map exists??
				countchar = 0;
				added = []; 				
				
				document.getElementById("description").value = "";
				document.getElementById("chars").value = "";          
				document.getElementById("characters").innerHTML = "";
				document.getElementById("None").innerHTML = "<i>None so far.</i>";
				
				$('#ModalAdd').modal();          
			});	
			
			elem.addEventListener('click', function()
			{                
				var today = parseInt(document.getElementById("currentday").value);
				
				for (k = 0; k < places.length; k++)
				{                  
					if (places[k].days == today)
					{
						if (mousePos.x > (places[k].xcoord-8) && mousePos.x < (places[k].xcoord+8) && mousePos.y > (places[k].ycoord-8) && mousePos.y < (places[k].ycoord+8))
						{             
							document.body.style.cursor = 'default';
							
							if (toolshow)
							{
								tooltip.hide();
							}                      
							
							var currentchars = window['charlist' + k];                      
							countchar = currentchars.length;
							
							added = [];
							added = currentchars;
							
							locationid = places[k].lid;
							
							document.getElementById("edescription").value = places[k].name;
							document.getElementById("echars").value = "";          
							document.getElementById("echaracters").innerHTML = "";
							
							if (places[k].hour > 0)
							{
								document.getElementById("ehour").value = places[k].hour;
								
								if (places[k].hour < 1000)
									document.getElementById("ehour").value = "0" + places[k].hour;
								if (places[k].hour < 100)
									document.getElementById("ehour").value = "00" + places[k].hour;
								if (places[k].hour < 10)
									document.getElementById("ehour").value = "000" + places[k].hour;
								if (places[k].hour >= 2400)
									document.getElementById("ehour").value = "0000";
							}
							
							if (countchar == 0)
							{
								document.getElementById("eNone").innerHTML = "<i>None so far.</i>";
							}
							else
							{
								document.getElementById("eNone").innerHTML = ""; 
								
								for (p = 0; p < countchar; p++)
								{   
									var nrow = document.getElementById('echaracters').insertRow(-1);
									var cell1 = nrow.insertCell(0);
									cell1.innerHTML = (p+1).toString() + ". ";
									var cell2 = nrow.insertCell(1);
									cell2.innerHTML = "&nbsp; " + currentchars[p] + " &nbsp;";
									var cell2 = nrow.insertCell(2);
									cell2.innerHTML = "<a href='#' class='btn btn-mini btn-warning' onclick='RemoveEdit(" + p + ");'>" + 
													  "<strong>X</strong></a>";
								}
							}   
							
							document.getElementById('placenum').value = k; 
							
							$('#ModalEdit').modal();
							
							break;
						}
					}
				}            
			});	
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

	  function RemoveChar(id)
	  {
		  var tempchar = new Array();      
		  var index = 0;
		  
		  for (k = 0; k < countchar; k++)
		  {
			  if (k != id)
			  {
				  tempchar[index] = added[k];
				  index++;
			  }
		  }
		  
		  countchar = index;
		  added = [];
		  added = tempchar;
		  
		  document.getElementById("characters").innerHTML = "";
		  
		  for (k = 0; k < countchar; k++)
		  {      
			  var nrow = document.getElementById('characters').insertRow(-1);
			  var cell1 = nrow.insertCell(0);
			  cell1.innerHTML = (k+1).toString() + ". ";
			  var cell2 = nrow.insertCell(1);
			  cell2.innerHTML = "&nbsp; " + added[k] + " &nbsp;";
			  var cell2 = nrow.insertCell(2);
			  cell2.innerHTML = "<a href='#' class='btn btn-mini btn-warning' onclick='RemoveChar(" + k + ");'>" + 
								"<strong>X</strong></a>";
		  }
		  
		  document.getElementById("None").innerHTML = "";
	  }
	  
	  function AddChars(result)
	  {
		  var toadd = true;
		  
		  for (k = 0; k < countchar; k++)
		  {
			  if (added[k] == result)
			  {
				  alert('Already added!');
				  toadd = false;
			  }
		  }
		  
		  if (toadd == true)
		  {
			  added[countchar] = result;
			  countchar++;
		  }
		  
		  document.getElementById("characters").innerHTML = "";
		  
		  for (k = 0; k < countchar; k++)
		  {      
			  var nrow = document.getElementById('characters').insertRow(-1);
			  var cell1 = nrow.insertCell(0);
			  cell1.innerHTML = (k+1).toString() + ". ";
			  var cell2 = nrow.insertCell(1);
			  cell2.innerHTML = "&nbsp; " + added[k] + " &nbsp;";
			  var cell2 = nrow.insertCell(2);
			  cell2.innerHTML = "<a href='#' class='btn btn-mini btn-warning' onclick='RemoveChar(" + k + ");'>" + 
								"<strong>X</strong></a>";
		  }
		  
		  document.getElementById("None").innerHTML = "";
	  }

	  function RemoveEdit(id)
	  {
		  var tempchar = new Array();      
		  var index = 0;
		  
		  for (k = 0; k < countchar; k++)
		  {
			  if (k != id)
			  {
				  tempchar[index] = added[k];
				  index++;
			  }
		  }
		  
		  countchar = index;
		  added = [];
		  added = tempchar;
		  
		  document.getElementById("echaracters").innerHTML = "";
		  
		  for (k = 0; k < countchar; k++)
		  {      
			  var nrow = document.getElementById('echaracters').insertRow(-1);
			  var cell1 = nrow.insertCell(0);
			  cell1.innerHTML = (k+1).toString() + ". ";
			  var cell2 = nrow.insertCell(1);
			  cell2.innerHTML = "&nbsp; " + added[k] + " &nbsp;";
			  var cell2 = nrow.insertCell(2);
			  cell2.innerHTML = "<a href='#' class='btn btn-mini btn-warning' onclick='RemoveEdit(" + k + ");'>" + 
								"<strong>X</strong></a>";
		  }
		  
		  document.getElementById("eNone").innerHTML = "";
	  }
	  
	  function EditChars(result)
	  {
		  var toadd = true;
		  
		  for (k = 0; k < countchar; k++)
		  {
			  if (added[k] == result)
			  {
				  alert('Already added!');
				  toadd = false;
			  }
		  }
		  
		  if (toadd == true)
		  {
			  added[countchar] = result;
			  countchar++;
		  }
		  
		  document.getElementById("echaracters").innerHTML = "";
		  
		  for (k = 0; k < countchar; k++)
		  {      
			  var nrow = document.getElementById('echaracters').insertRow(-1);
			  var cell1 = nrow.insertCell(0);
			  cell1.innerHTML = (k+1).toString() + ". ";
			  var cell2 = nrow.insertCell(1);
			  cell2.innerHTML = "&nbsp; " + added[k] + " &nbsp;";
			  var cell2 = nrow.insertCell(2);
			  cell2.innerHTML = "<a href='#' class='btn btn-mini btn-warning' onclick='RemoveEdit(" + k + ");'>" + 
								"<strong>X</strong></a>";
		  }
		  
		  document.getElementById("eNone").innerHTML = "";
	  }
  
	function AddLocation()
	{
      var today = document.getElementById("currentday").value;      
      var thechars = "";      
      
      for (m = 0; m < countchar; m++)
      {
          thechars += added[m] + "#";
      }      
      
      document.getElementById("today").value = today;
      document.getElementById("xcoord").value = mousePos.x;
      document.getElementById("ycoord").value = mousePos.y;
      document.getElementById("added").value = thechars;
      
      document.form1.submit();
	}
	
	function EditLocation()
	{
      var today = document.getElementById("currentday").value;
      var placenum = parseInt(document.getElementById("placenum").value);      
      var thechars = "";      
      
      for (m = 0; m < countchar; m++)
      {
          thechars += added[m] + "#";
      }      
      
      document.getElementById("etoday").value = today;
      document.getElementById("excoord").value = places[placenum].xcoord;
      document.getElementById("eycoord").value = places[placenum].ycoord;
      document.getElementById("eadded").value = thechars;
      
      document.form2.submit();
	}
	
	function LocationCancel()
	{
		var today = document.getElementById("currentday").value;
		<? print "window.location = '/spacevtime.php?gid=" . intval($_GET["gid"]) . "&tunit=' + today; "; ?>
	}
	
	function LocationDelete()
	{
		if (enabled == true)
		{
			var sureornot = confirm("Are you really sure you want to delete this location??");
			
			if(sureornot == true)
			{
				document.getElementById("DeleteLocation").value = locationid;
				document.getElementById("dtoday").value = document.getElementById("currentday").value;
				document.deletesubmit.submit();
			}			
		}
	}
	
	function EditSubmit()
	{
		if (enabled == true)
		{
			var today = document.getElementById("currentday").value;
			<? print "window.location = '/eventedit.php?gid=" . intval($_GET["gid"]) . "&edit=' + today.toString(); \n"; ?>
		}
	}
	
	function DeleteSubmit()
	{
		if (enabled == true)
		{
			var sureornot = confirm("Are you really REALLY sure you want to delete this event??");
			
			if(sureornot == true)
			{
				document.getElementById("EditEvent").value = "Delete";
				document.getElementById("GroupID").value = "<? print intval($_GET["gid"]); ?>";
				document.editsubmit.submit(); 
			}			
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
          <a class="brand" href="dashboard.php">FlickPS SvT</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
<?
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
              <li class="active"><? print "<a href='spacevtime.php?gid=" . intval($_GET["gid"]) . "'>"; ?>
					<i class="icon-thumbs-up"></i>&nbsp; Storyline</a></li>
              <li><? print "<a href='characters.php?gid=" . intval($_GET["gid"]) . "'>"; ?><i class="icon-user"></i>&nbsp; Characters</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="icon-list-alt"></i>&nbsp; Events <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><? print "<a href='eventadd.php?gid=" . intval($_GET["gid"]) . "'>"; ?>Add New Event</a></li>
                  <li><a href="#" onClick="EditSubmit();">Edit This Event</a></li>
                  <li class="divider"></li>
                  <li><a href="#" onClick="DeleteSubmit();">Delete This Event</a></li>
                </ul>
              </li>
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
	    <form class="form-inline" method="post" name="editsubmit" action="timeline.php"> 
		<input type="hidden" value="" id="currentmap" name="currentmap">
		<input type="hidden" value="" id="currentday" name="currentday">
		<input type="hidden" value="" id="GroupID" name="GroupID">
		<input type="hidden" value="" id="EditEvent" name="EditEvent"></form> 
		<form class="form-inline" method="post" name="deletesubmit"> 
		<input type='hidden' name='dtoday' id='dtoday' value=''>
		<input type="hidden" value="" id="DeleteLocation" name="DeleteLocation"></form>    
	</td><td></td></tr>
	</table>
	</center> 
	
	<p>&nbsp;</p><a name="TimeLine"></a>
	<h4><center>EVENT TIMELINE</center></h4>	
	
	<div id="TimeLine" class="div-msg"><center>Loading...</center></div>
	<input type="hidden" name="TempTimelineData" id="TempTimelineData" value=""> 	
	<p>&nbsp;</p>	

  <div class="modal hide" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="ModalAddLabel" 
		class="modal hide fade in" aria-hidden="true">
	  <form method="post" class="form-inline" name="form1">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	    <h4 id="ModalAddLabel">Add Location</h4>
	  </div>
	  <div class="modal-body">	  
	    <p align=center><table cellpadding="10" align="center">
		<input type="hidden" name="cid" id="cid" value="">				
		<tr><td>Add description? (Optional) &nbsp;</td><td><input type="text" name="description" id="description" value=""></td></tr>
		<tr><td>Time of Incident? (Optional) &nbsp;</td><td><input type="text" name="hour" id="hour" value=""></td></tr>
		<tr><td>Add characters : </td>
        <td><input type="text" name="chars" id="chars" data-provide="typeahead" data-items="4" value=""></td></tr>
	    </table></p>	
	    <p>&nbsp;</p>
	    <h5><center>Characters Added:</center></h5> 	    
      <table id='characters' align='center' cellpadding='4'></table>
      <p id='None' align='center'><i>None so far.</i></p>        
	  </div>
	  <div class="modal-footer">
      <input type='hidden' name='LocationSubmit' value='true'>
      <input type='hidden' name='xcoord' id='xcoord' value=''>
      <input type='hidden' name='ycoord' id='ycoord' value=''>
      <input type='hidden' name='today' id='today' value=''>
      <input type='hidden' name='added' id='added' value=''>
	    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
	    <button class="btn btn-info" type="button" name="Location" 
          data-dismiss="modal" aria-hidden="true" onClick='AddLocation();'>SAVE !</button>
	  </div>
	  </form>
	</div>
	
	<div class="modal hide" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="ModalEditLabel" 
		class="modal hide fade in" aria-hidden="true">
	  <form method="post" class="form-inline" name="form2">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
	    <h4 id="ModalAddLabel">Edit Location</h4>
	  </div>
	  <div class="modal-body">	  
	    <p align=center><table cellpadding="10" align="center">
		<input type="hidden" name="cid" id="cid" value="">				
		<tr><td>Change description? (Optional) &nbsp;</td><td><input type="text" name="edescription" id="edescription" value=""></td></tr>
		<tr><td>Time of Incident? (Optional) &nbsp;</td><td><input type="text" name="ehour" id="ehour" value=""></td></tr>
		<tr><td>Change characters : </td>
        <td><input type="text" name="echars" id="echars" data-provide="typeahead" data-items="4" value=""></td></tr>
	    </table></p>	
	    <p>&nbsp;</p>
	    <h5><center>Characters Added:</center></h5> 	    
      <table id='echaracters' align='center' cellpadding='4'></table>
      <p id='eNone' align='center'><i>None so far.</i></p>        
	  </div>
	  <div class="modal-footer">
      <input type='hidden' name='placenum' id='placenum' value=''>
      <input type='hidden' name='LocationEdit' value='true'>
      <input type='hidden' name='excoord' id='excoord' value=''>
      <input type='hidden' name='eycoord' id='eycoord' value=''>
      <input type='hidden' name='etoday' id='etoday' value=''>
      <input type='hidden' name='eadded' id='eadded' value=''>
		<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true" onClick='LocationDelete();'>Delete</button>
	    <button class="btn" data-dismiss="modal" aria-hidden="true" onClick='LocationCancel();'>Cancel</button>
	    <button class="btn btn-info" type="button" name="eLocation" 
          data-dismiss="modal" aria-hidden="true" onClick='EditLocation();'>SAVE !</button>
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
      
      $SQL = "SELECT * FROM g2u WHERE gid = '" . intval($_GET['gid']) . "' AND uid = '" . intval($_SESSION['userid']) . "'; ";
      $result = mysql_query($SQL);
      $number_of_rows = mysql_num_rows($result);
      
      if ($number_of_rows == 0)
      {
			// Check if Public Wiki is available.
			
			print "\n window.location = '/index.php'; \n ";
      }
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_GET['gid']) . ";";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_GET['gid']) . "_" . $row[0]; 	 		  
	  
	  print "var TitleText = '" . $row[0] . "';\n";
	  print "var GroupName = '" . $groupname . "';\n\n";
	  
	  if ($db_found) 
 	  {	     
	     $SQL = "SELECT * FROM characters WHERE project = '" . $groupname . "' ORDER BY name;";
	     $result = mysql_query($SQL);
	     $index = 0;
	     
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
    
    $('#chars').typeahead(
    { 
        source: characters, 
        updater:function (item) 
        { 
            AddChars(item);
            return kosong; 
        } 
    });
    
    $('#echars').typeahead(
    { 
        source: characters, 
        updater:function (item) 
        { 
            EditChars(item);
            return kosong; 
        } 
    });
    
   
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
