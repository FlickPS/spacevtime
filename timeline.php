<?php 

	  $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";	

	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);
	  
	  session_start();
	  
	  if (!(isset($_SESSION['login']) && $_SESSION['login'] != "")) 
      {
			header ("Location: /users.php");
      }
	  
	  if (!isset($_POST['GroupID']) || $_POST['GroupID'] == "")
	  {
		    mysql_close($db_handle);
		    header("Location: /index.php"); 
	  }		
	  
	  $search  = array('\'', '\"', ';', '<', '>');
	  $replace = array('&#39;', '', '', '', ''); 
	  
	  $SQL = "SELECT * FROM g2u WHERE gid = '" . intval($_POST['GroupID']) . "' AND uid = '" . intval($_SESSION['userid']) . "'; ";
      $result = mysql_query($SQL);
      $number_of_rows = mysql_num_rows($result);
      
      if ($number_of_rows == 0)
      {
			// Check if Public Wiki is available.
			
			mysql_close($db_handle);
			header("Location: /index.php"); 
      }
	  
	  $SQL = "SELECT name FROM groups WHERE id = " . intval($_POST['GroupID']) . "; ";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
	  $groupname = intval($_POST['GroupID']) . "_" . $row[0]; 	  
	  
	  $clearspaces  = array('\'', '\"', ';', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '\\', '/', '{', '}', '`', '~', ',', '?', '[', ']', ':', '|', '-', '_', '<', '>', '.');                  
      $directory = intval($_POST['GroupID']) . "_" . str_replace($clearspaces, '', $row[0]) . "_";	
      
	  
	  $search  = array('\'', '\"', ';', '<', '>', ' ');
	  $replace = array('', '', '', '', '', '');  
		
	  if ($db_found) 
 	  {	             
		  if (( isset($_POST['AddEvent']) || isset($_POST['imgurl']) ) && isset($_POST['days']))
		  {
			$days = $_POST['days'];	
			
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$extension = end(explode(".", $_FILES["file"]["name"]));
			
			if ($days[0] == "0" || $days[0] == "1" || $days[0] == "2" || $days[0] == "3" || $days[0] == "4" ||
				$days[0] == "5" || $days[0] == "6" || $days[0] == "7" || $days[0] == "8" || $days[0] == "9" || $days[0] == "-")
			{
				$SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' AND days = '" . intval($days) . "'; ";
				$result = mysql_query($SQL);
				$number_of_rows = mysql_num_rows($result); 
				
				if ($number_of_rows > 0) 
				{ 
					// Event already exists.		
					
					$url = "Location: /eventadd.php?gid=" . intval($_POST['GroupID']) . "&error=1";
					
					mysql_close($db_handle);
					header($url); 
				} 
				else
				{	
					if ($_POST['imgurl'] != "")
					{
						$SQL = "INSERT INTO time(project, days, image) VALUES ('" . $groupname . "', '" . intval($days) . "', '" . str_replace($search, $replace, $_POST['imgurl']) . "'); ";
						$result = mysql_query($SQL);
						
						$url = "Location: /spacevtime.php?gid=" . intval($_POST['GroupID']) . "&tunit=" . intval($days);
						
						// An existing image is selected.
						
						mysql_close($db_handle);
						header($url);
					}          
					
					if ((($_FILES["file"]["type"] == "image/gif")
						  || ($_FILES["file"]["type"] == "image/jpeg")
						  || ($_FILES["file"]["type"] == "image/png")
						  || ($_FILES["file"]["type"] == "image/pjpeg"))
						  && ($_FILES["file"]["size"] < 500000)
						  && in_array($extension, $allowedExts)
						  && $_FILES["file"]["name"] != "")
					{
						if ($_FILES["file"]["error"] > 0)
						{
							// Strange file error.		
							
							$url = "Location: /eventadd.php?gid=" . intval($_POST['GroupID']) . "&error=2";
							
							mysql_close($db_handle);
							header($url); 
						}
						else
						{				
							$filename =  str_replace($search, $replace, $_FILES["file"]["name"]); 
							$uploadedfile = "uploads/" . $directory . $filename;         
							
							if (!file_exists($uploadedfile))
							{ 
							  move_uploaded_file($_FILES["file"]["tmp_name"],	$uploadedfile);
							  
							  $SQL = "INSERT INTO images(project, url) VALUES ('" . $groupname . "', '" . $filename . "')";
							  $result = mysql_query($SQL);
							}							
							
							$SQL = "INSERT INTO time(project, days, image) VALUES ('" . $groupname . "', '" . intval($days) . "', '" . $filename . "'); ";	
							$result = mysql_query($SQL);
							
							// Upload ok, saved successfully.
							
							$url = "Location: /spacevtime.php?gid=" . intval($_POST['GroupID']) . "&tunit=" . intval($days);
							
							mysql_close($db_handle);							
							header($url);
						}
					}
					else
					{
						if ($_FILES["file"]["name"] == "")
						{
							// No file uploaded.		
							
							$url = "Location: /eventadd.php?gid=" . intval($_POST['GroupID']) . "&error=3";
							
							mysql_close($db_handle);
							header($url);
						}
						else
						{
							// Invalid file type or file too big.		
							
							$url = "Location: /eventadd.php?gid=" . intval($_POST['GroupID']) . "&error=4";
							
							mysql_close($db_handle);
							header($url);               
						}
					}
				}
			}
			else
			{
				// Invalid number.		
				
				$url = "Location: /eventadd.php?gid=" . intval($_POST['GroupID']) . "&error=X";
				
				mysql_close($db_handle);
				header($url); 
			}  
		  }
          
          
		  if (( isset($_POST['UpdateEvent']) || isset($_POST['currentimg']) ) && isset($_POST['days']))
		  {  
			$days = $_POST['days'];
			$currentday = intval($_POST['currentday']);	
			
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$extension = end(explode(".", $_FILES["file"]["name"]));
			
			$errorcounter = 0;
			
			if ($days[0] == "0" || $days[0] == "1" || $days[0] == "2" || $days[0] == "3" || $days[0] == "4" ||
				$days[0] == "5" || $days[0] == "6" || $days[0] == "7" || $days[0] == "8" || $days[0] == "9" || $days[0] == "-")
			{
				$SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' AND days = '" . intval($days) . "'; ";
				$result = mysql_query($SQL);
				$number_of_rows = mysql_num_rows($result); 
				
				if ($number_of_rows > 0 && intval($days) != $currentday) 
				{ 
					$errorcounter++;
					
					// Event already exists.		
					
					$url = "Location: /eventedit.php?gid=" . intval($_POST['GroupID']) . "&edit=" . $currentday . "&error=1";
					
					mysql_close($db_handle);
					header($url);  
				} 
			}
			else 
			{
				// Invalid number.		
				
				$url = "Location: /eventedit.php?gid=" . intval($_POST['GroupID']) . "&edit=" . $currentday . "&error=X";
				
				mysql_close($db_handle);
				header($url); 
			}
				
			if ($errorcounter == 0 && $_POST['currentimg'] != "")
			{
				  $search  = array('\'', '\"', ';', '<', '>');
				  $replace = array('', '', '', '', '');   
					
				  $SQL = "UPDATE time SET days = '" . intval($days) . "' WHERE project = '" . $groupname . "' AND days = " . $currentday;
				  $result = mysql_query($SQL);
				  
				  $SQL = "UPDATE time SET image = '" . str_replace($search, $replace, $_POST['currentimg']) . "' WHERE project = '" . $groupname . "' AND days = " . intval($days);		
				  $result = mysql_query($SQL);
				  
				  $url = "Location: /spacevtime.php?gid=" . intval($_POST['GroupID']) . "&tunit=" . intval($days);
				  
				  // An existing image is selected.
				  
				  mysql_close($db_handle);
				  header($url); 
			}
			
			$SQL = "SELECT * FROM time WHERE project = '" . $groupname . "' AND days = '" . intval($currentday) . "'";
			$result = mysql_query($SQL);
			$db_field = mysql_fetch_assoc($result);    
				
			if ((($_FILES["file"]["type"] == "image/gif")
				  || ($_FILES["file"]["type"] == "image/jpeg")
				  || ($_FILES["file"]["type"] == "image/png")
				  || ($_FILES["file"]["type"] == "image/pjpeg"))
				  && ($_FILES["file"]["size"] < 500000)
				  && in_array($extension, $allowedExts))
			{
				if ($_FILES["file"]["error"] > 0)
				{
					$errorcounter++;
					
					// Strange file error.		
					
					$url = "Location: /eventedit.php?gid=" . intval($_POST['GroupID']) . "&edit=" . $currentday . "&error=2";
					
					mysql_close($db_handle);
					header($url); 
				}
				else
				{
					if ($errorcounter == 0)
					{
						  $search  = array('\'', '\"', ';', '<', '>', ' ');
						  $replace = array('', '', '', '', '', '');      
							
						  $filename =  str_replace($search, $replace, $_FILES["file"]["name"]); 
						  $uploadedfile = "uploads/" . $directory . $filename;         
							
						  if (!file_exists($uploadedfile))
						  { 
								move_uploaded_file($_FILES["file"]["tmp_name"],	$uploadedfile);
								
								$SQL = "INSERT INTO images(project, url) VALUES ('" . $groupname . "', '" . $filename . "')";
								$result = mysql_query($SQL);
						  }
						  
						  $SQL = "UPDATE time SET image = '" . $filename . "' WHERE project = '" . $groupname . "' AND days = " . intval($currentday);		
						  $result = mysql_query($SQL);	
					}
				}
			}
			else
			{
			  // Error 3 - no file uploaded - not required for edits.
			  
			  if ($_FILES["file"]["name"] != "")
			  {
				$errorcounter++;
				
				// Invalid file type or file too big.		
				
				$url = "Location: /eventedit.php?gid=" . intval($_POST['GroupID']) . "&edit=" . $currentday . "&error=4";
				
				mysql_close($db_handle);
				header($url);  
			  }		 
			}
			
			if ($errorcounter == 0)
			{
				$SQL = "UPDATE time SET days = '" . intval($days) . "' WHERE project = '" . $groupname . "' AND days = " . intval($currentday);
				$result = mysql_query($SQL);
				
				// Upload ok, saved successfully.
				
				$url = "Location: /spacevtime.php?gid=" . intval($_POST['GroupID']) . "&tunit=" . intval($days);
				
				mysql_close($db_handle);
				header($url);
			}		
		  }
			
			
		  if (isset($_POST['EditEvent']) && $_POST['EditEvent'] == "Delete")
		  {
				// Delete event here.
				
				$time = intval($_POST['currentday']);
				
				$SQL = "SELECT id FROM time WHERE project = '" . $groupname . "' AND days = " . $time . "; "; 
				$result = mysql_query($SQL);
				$row = mysql_fetch_array($result);
				
				$tid = $row[0];
				
				$SQL = "SELECT * FROM location WHERE Tid = " . $tid . "; "; 
				$result = mysql_query($SQL);
				
				while ($db_field = mysql_fetch_assoc($result))
				{
					$SQL = "DELETE FROM l2c WHERE lid = " . $db_field['id'];
					$l2c = mysql_query($SQL);
				}				  
				
				$SQL = "DELETE FROM location WHERE Tid = " . $tid;
				$result = mysql_query($SQL);  
				
				$SQL = "DELETE FROM time WHERE id = " . $tid;
				$result = mysql_query($SQL);  
				
				$url = "Location: /spacevtime.php?gid=" . intval($_POST['GroupID']) . "";
				
				mysql_close($db_handle);
				header($url);
		  }
		
      mysql_close($db_handle);
	}

?>

