<?php
	  session_start();
	  
	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	
		
	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);	
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', ''); 
	  
	  // $currentuser = str_replace($search, $replace, $_SESSION['login']);  
	  // $thisuserid = intval($_SESSION['userid']); 
	  
	  if ($db_found) 
 	  {	     
		if (isset($_POST['message']) && $_POST['message'] != "" && $_POST['gid'] != "") 
	    { 
			$gid = intval($_POST['gid']);
			$thisuserid = intval($_POST['uid']); 
			
			$search  = array('\'', '\"', ';', '<', '>');
			$replace = array('&#39', '&quot;', '', '', '');
          
			$message = str_replace($search, $replace, $_POST['message']);
			$currentuser = str_replace($search, $replace, $_POST['username']); 
			
            $readstatus = "";
            $readcounter = 0;
            
            $SQL = "SELECT * FROM g2u WHERE gid = " . $gid . " AND uid != " . $thisuserid . "; ";
			$result = mysql_query($SQL);	
			
			while ($db_field = mysql_fetch_assoc($result)) 
			{
				if ($readcounter == 0)
					$readstatus .= $db_field['uid'];
				else
					$readstatus .= "#" . $db_field['uid'];
				$readcounter++;
			}
			
			$SQL = "INSERT INTO messages(gid, username, message, readstatus) VALUES (" . $gid . ", '" . $currentuser . "', '" . $message . "', '" . $readstatus . "'); ";
            $result = mysql_query($SQL);
	    }
	    
	    if (isset($_POST['UpdateChat']) && $_POST['enddate'] != "" && $_POST['gid'] != "") 
	    { 
			$gid = intval($_POST['gid']);
			$uid = intval($_POST['uid']); 
			
			$search  = array('\'', '\"', ';', '<', '>');
			$replace = array('&#39', '&quot;', '', '', '');
          
			$message = str_replace($search, $replace, $_POST['message']);
			$currentuser = str_replace($search, $replace, $_POST['username']); 
			
			$enddate = str_replace($search, $replace, $_POST['enddate']);  
			$newenddate = "";
			
			$SQL = "SELECT * FROM messages WHERE gid = " . $gid . "; ";
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
					if (intval($readreceipt) != $uid)
					{
						if ($readcounter == 0)
							$readupdate .= $readreceipt;
						else
							$readupdate .= "#" . $readreceipt;
					}
					$readcounter++;
				}
				
				$SQL  = "UPDATE messages SET readstatus = '" . $readupdate . "' WHERE id = " . $msgid . "; ";
				$msgupdate = mysql_query($SQL);
			}
				
			$_SESSION['newmessages'] = 0;
				
			$chatbody = "<table cellpadding='10'>";
				
			$search2  = array('&#39');
			$replace2 = array('&#39;');
			 
			$SQL = "SELECT * FROM messages WHERE gid = '" . $gid . "' AND datetime > '" . $enddate . "' ORDER BY datetime; ";
			$result = mysql_query($SQL);  
			$number_of_rows = mysql_num_rows($result);
			  
			while ($db_field = mysql_fetch_assoc($result)) 
			{
				$msguser = str_replace($search2, $replace2, $db_field['username']);
				$message = str_replace($search2, $replace2, $db_field['message']);
				$datetime = date("d/m/Y @ H:i", strtotime($db_field['datetime']));
				
				$chatbody .= "<tr><td class='span2'>" . $msguser . "</td>";
				$chatbody .= "<td class='span6' rel='tooltip' title='" . $datetime . "' data-placement='right'>" . $message . "</td></tr>";
				
				$newenddate = $db_field['datetime'];
			}			
			
			if ($number_of_rows > 0)
			{
				$chatbody .= "</table>\n";
				
				print "<div id='NewEndDate'>" . $newenddate . "</div>";
			}
			else
			{
				$chatbody .= "</table>";
				
				print "<div id='NewEndDate'>None</div>";
			}
          
			print "<div id='ChatUpdate'>" . $chatbody . "</div>";
	    }
	    
	    
	    if (isset($_POST['SingleYear']) && $_POST['Group'] != "")
	    {
			$search  = array('\'', '\"', ';', '<', '>');
			$replace = array('&#39', '', '', '', ''); 
			
			$groupname = str_replace($search, $replace, $_POST['Group']); 
			
			$SQL = 	"SELECT time.days, location.name AS event, location.hour, characters.name, characters.description 
					FROM time INNER JOIN location ON time.id=location.Tid 
					RIGHT JOIN l2c ON location.id=l2c.lid RIGHT JOIN characters ON l2c.cid=characters.id 
					WHERE time.project = '" . $groupname . "' ORDER BY time.days, location.hour; ";	
			
			$result = mysql_query($SQL);
			$number_of_rows = mysql_num_rows($result);
			
			$previousmonth = 0;
			$previousday = -1;
			$previousevent = "";
			
			$content = "<table cellpadding='10' align='center'>";
			
			$counter = 0;
			
			while ($db_field = mysql_fetch_assoc($result))
			{
				$days = $db_field['days']; 
				
				$thisyear = intval($days/416);
				$thismonth = intval(($days%416)/32);
				$thisday = intval(($days%416)%32);
				
				if ($counter == 0)
				{
					$content .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;";
				}
				
				if ($thismonth != $previousmonth)
				{
					$content .= "</td></tr><tr><td>&nbsp;<br/><strong>Month " . $thismonth . "</strong></td><td>&nbsp;</td><td>&nbsp;";
				}
				
				if ($thisday != $previousday  || ($thismonth != $previousmonth && $thisday == $previousday))
				{
					$content .= "</td></tr><tr><td valign='top'><strong><a href='#SVTGSI' onClick=\"LoadFrame(" . $days . ");\">Day " . $thisday . "</a></strong></td>";
					$content .= "<td valign='top'>&nbsp; &rsaquo; &nbsp;</td><td><strong>" . $db_field['event'] . "</strong><br/>";
					
					if ($db_field['hour'] > 0)
					{
						$timetext = "<i>" . $db_field['hour'] . " Hours</i><br/>";
						
						if ($db_field['hour'] < 1000)
							$timetext = "<i>0" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] < 100)
							$timetext = "<i>00" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] < 10)
							$timetext = "<i>000" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] >= 2400)
							$timetext = "<i>0000 Hours</i><br/>";
						
						$content .= $timetext;
					}
					
					if ($db_field['name'] != "")
					{
						$content .= "With ";
					}
				}
				else
				{
					if ($db_field['event'] != $previousevent)
					{
						$content .= "</td></tr><tr><td valign='top'&nbsp;</td>";
						$content .= "<td valign='top'>&nbsp; &rsaquo; &nbsp;</td><td><strong>" . $db_field['event'] . "</strong><br/>";
						
						if ($db_field['hour'] > 0)
						{
							$timetext = "<i>" . $db_field['hour'] . " Hours</i><br/>";
							
							if ($db_field['hour'] < 1000)
								$timetext = "<i>0" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] < 100)
								$timetext = "<i>00" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] < 10)
								$timetext = "<i>000" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] >= 2400)
								$timetext = "<i>0000 Hours</i><br/>";
							
							$content .= $timetext;
						}						
						
						if ($db_field['name'] != "")
						{
							$content .= "With ";
						}
					}
				}
				
				if ($db_field['name'] != "")
				{
					$content .= "<a href='#TimeLine' class='char-text' rel='tooltip' data-placement='bottom' title='" . $db_field['description'] . "'>" . $db_field['name'] . "</a> ";
				}
				
				$counter++;
				
				$previousevent = $db_field['event'];				
				$previousday = $thisday;
				$previousmonth = $thismonth;
			}
			$content .= "</td></tr></table><p>&nbsp;</p>";
			
			if ($number_of_rows == 0)
			{
				$content .= "<p>&nbsp;</p><p align='center'><strong>Nothing has happened in this story yet.</strong></p><p>&nbsp;</p>";
			}
			
			$finalcontent = str_replace("a> <a", "a>, <a", $content); 
			
			print "<div id='SingleYearContent'>" . $finalcontent . "</div>";
	    }
	    
	    if (isset($_POST['SpecificYear']) && $_POST['Group'] != "" && $_POST['Year'] != "")
	    {
			$search  = array('\'', '\"', ';', '<', '>');
			$replace = array('&#39;', '', '', '', ''); 
			
			$theyear = intval($_POST['Year']);
			$groupname = str_replace($search, $replace, $_POST['Group']);  
			
			$content = "";
			
			$SQL = 	"SELECT time.days, location.name AS event, location.hour, characters.name, characters.description 
					FROM time INNER JOIN location ON time.id=location.Tid 
					RIGHT JOIN l2c ON location.id=l2c.lid RIGHT JOIN characters ON l2c.cid=characters.id 
					WHERE time.project = '" . $groupname . "' ORDER BY time.days, location.hour; ";	
			
			$result = mysql_query($SQL);
			$number_of_rows = mysql_num_rows($result);
			
			$previousmonth = 0;
			$previousday = -1;
			$previousevent = "";
			
			$content = "<table cellpadding='10' align='center'>";
			
			$counter = 0;
			
			while ($db_field = mysql_fetch_assoc($result))
			{
				$days = $db_field['days']; 
				
				$thisyear = intval($days/416);
				$thismonth = intval(($days%416)/32);
				$thisday = intval(($days%416)%32);
				
				if ($thisyear != $theyear)
				{
					continue;
				}
				
				if ($counter == 0)
				{
					$content .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;";
				}
				
				if ($thismonth != $previousmonth)
				{
					$content .= "</td></tr><tr><td>&nbsp;<br/><strong>Month " . $thismonth . "</strong></td><td>&nbsp;</td><td>&nbsp;";
				}
				
				if ($thisday != $previousday || ($thismonth != $previousmonth && $thisday == $previousday))
				{
					$content .= "</td></tr><tr><td valign='top'><strong><a href='#SVTGSI' onClick=\"LoadFrame(" . $db_field['days'] . ");\">Day " . $thisday . "</a></strong></td>";
					$content .= "<td valign='top'>&nbsp; &rsaquo; &nbsp;</td><td><strong>" . $db_field['event'] . "</strong><br/>";
					
					if ($db_field['hour'] > 0)
					{
						$timetext = "<i>" . $db_field['hour'] . " Hours</i><br/>";
						
						if ($db_field['hour'] < 1000)
							$timetext = "<i>0" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] < 100)
							$timetext = "<i>00" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] < 10)
							$timetext = "<i>000" . $db_field['hour'] . " Hours</i><br/>";
						if ($db_field['hour'] >= 2400)
							$timetext = "<i>0000 Hours</i><br/>";
						
						$content .= $timetext;
					}
					
					if ($db_field['name'] != "")
					{
						$content .= "With ";
					}
				}
				else
				{
					if ($db_field['event'] != $previousevent)
					{
						$content .= "</td></tr><tr><td valign='top'&nbsp;</td>";
						$content .= "<td valign='top'>&nbsp; &rsaquo; &nbsp;</td><td><strong>" . $db_field['event'] . "</strong><br/>";
						
						if ($db_field['hour'] > 0)
						{
							$timetext = "<i>" . $db_field['hour'] . " Hours</i><br/>";
							
							if ($db_field['hour'] < 1000)
								$timetext = "<i>0" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] < 100)
								$timetext = "<i>00" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] < 10)
								$timetext = "<i>000" . $db_field['hour'] . " Hours</i><br/>";
							if ($db_field['hour'] >= 2400)
								$timetext = "<i>0000 Hours</i><br/>";
							
							$content .= $timetext;
						}						
						
						if ($db_field['name'] != "")
						{
							$content .= "With ";
						}
					}
				}
				
				if ($db_field['name'] != "")
				{
					$content .= "<a href='#TimeLine' class='char-text' rel='tooltip' data-placement='bottom' title='" . $db_field['description'] . "'>" . $db_field['name'] . "</a> ";
				}
				
				$counter++;
				
				$previousevent = $db_field['event'];				
				$previousday = $thisday;
				$previousmonth = $thismonth;
			}
			$content .= "</td></tr> <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr> </table>";
			
			if ($number_of_rows == 0 || $counter == 0)
			{
				$content .= "<p align='center'><strong>Nothing has happened in this year yet.</strong></p><p>&nbsp;</p>";
			}
			
			$finalcontent = str_replace("a> <a", "a>, <a", $content); 
			
			print "<div id='SpecificYearContent'>" . $finalcontent . "</div>";
	    }
	    
	    
	    mysql_close($db_handle);
	  }
	  else 
 	  {
        print "Database NOT Found " . $db_handle;
	  }
?>
