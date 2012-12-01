<?php
    	
      $user_name = "flickpsc_svtmain";
	  $password = "";
	  $database = "flickpsc_svtgsi";
	  $server = "localhost";		  

	  $db_handle = mysql_connect($server, $user_name, $password);
	  $db_found = mysql_select_db($database, $db_handle);		  
		
	  if ($db_found) 
 	  {	     
         $search  = array('\'', '\"', ';', '<', '>');
         $replace = array('', '', '', '', '');
          
	     if (isset($_POST['LogIn'])) 
	     { 
          $username = str_replace($search, $replace, $_POST['username']);
          $password = $_POST['password'];          
          
          $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND passcode = '" . sha1(md5($password)) . "'";
          $result = mysql_query($SQL);
          $number_of_rows = mysql_num_rows($result);           
          
          if ($number_of_rows > 0) 
          {
              session_start();      
              
              $_SESSION['login'] = $username;
              $_SESSION['newmessages'] = 0;
              $_SESSION['newinvites'] = 0; 
              
              while ($db_field = mysql_fetch_assoc($result)) 
              {
					$_SESSION['userid'] = $db_field['id'];
              }
              
              mysql_close($db_handle);
              header ("Location: /dashboard.php");
          }
          else 
          {
              mysql_close($db_handle);  
              header("Location: /users.php?error=1"); 
          }
       }
       
       if (isset($_POST['Register'])  && $_POST['InviteCode'] == "") 
	     { 
          $username = str_replace($search, $replace, $_POST['nusername']);
          $email = str_replace($search, $replace, $_POST['email']);
          
          $password = $_POST['npassword'];
          $confirm = $_POST['confirm'];
          
          if ( $username != "" && $username[0] != " " && $password == $confirm && strlen($password) >= 6 && $email != "" && $email[0] != " " )
          {
              $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' OR email = '" . $email . "'";
              $result = mysql_query($SQL);
              $num_rows = mysql_num_rows($result);
              
              if ($num_rows > 0) 
              {
                  mysql_close($db_handle);  
                  header("Location: /users.php?error=2");
              } 
              else               
              {
                  $SQL = "INSERT INTO users (userid, passcode, email) VALUES ('" . $username . "', '" . sha1(md5($password)) . "', '" . $email . "')";
                  $result = mysql_query($SQL);
                  
                  $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND email = '" . $email . "'";
                  $result = mysql_query($SQL);
                  
                  session_start();
                  
                  $_SESSION['login'] = $username;   
                  $_SESSION['newmessages'] = 0;
                  $_SESSION['newinvites'] = 0;   
                  
                  while ($db_field = mysql_fetch_assoc($result)) 
				  {
					$_SESSION['userid'] = $db_field['id'];
				  }
                  
                  mysql_close($db_handle);
                  header ("Location: /dashboard.php");
              }
          }
          else 
          {
              mysql_close($db_handle);  
              header("Location: /users.php?error=3"); 
          }
       }
       
       if (isset($_POST['Register']) && $_POST['InviteCode'] != "") 
       {
			$invitecode = intval($_POST['InviteCode']);
			
			$username = str_replace($search, $replace, $_POST['nusername']);
			$email = str_replace($search, $replace, $_POST['email']);
          
			$password = $_POST['npassword'];
			$confirm = $_POST['confirm'];
			
			$SQL = "SELECT * FROM users WHERE passcode = '" . md5($invitecode) . "' AND email = '" . $email . "'; ";
            $result = mysql_query($SQL);
            $num_rows = mysql_num_rows($result);
            
            if ($num_rows > 0);
            {
				$SQL = "UPDATE users SET userid = '" . $username . "', passcode = '" . sha1(md5($password)) . "' WHERE email = '" . $email . "' AND passcode = '" . md5($invitecode) . "'; ";
				$result = mysql_query($SQL);
				
				$SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND email = '" . $email . "'";
                $result = mysql_query($SQL);
                  
                session_start();
                  
                $_SESSION['login'] = $username;   
                $_SESSION['newmessages'] = 0;
                $_SESSION['newinvites'] = 0;   
                  
                while ($db_field = mysql_fetch_assoc($result)) 
				{
					$_SESSION['userid'] = $db_field['id'];
				}
                  
                mysql_close($db_handle);
                header ("Location: /dashboard.php");
            }            
       }
       
       if (isset($_POST['SendPassword'])) 
	     { 
          $email = str_replace($search, $replace, $_POST['SendEmail']); 
          $username = str_replace($search, $replace, $_POST['Username']);          
          
          $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND email = '" . $email . "'";
          $result = mysql_query($SQL);
          $number_of_rows = mysql_num_rows($result); 
          
          if ($number_of_rows > 0) 
          {
              $randompassword = rand(10000000, 9999999999);
              
              $SQL = "UPDATE users SET passcode = '" . sha1(md5($randompassword)) . "' WHERE email = '" . $email . "'";
              $result = mysql_query($SQL);
              
              $subject =  "FlickPS Password Recovery";
              
              $message =  "<html>
                            <p><strong>Your temporary password: " . $randompassword . "</strong></p>
                            <p>Please visit <a href=http://storyline.flickps.com/recovery.php>this page</a> to change your password.</p>
                            </html>";
                
              $headers  = 'MIME-Version: 1.0' . "\r\n";
              $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              $headers .= 'From: FlickPS Admin <admin@flickps.com>' . "\r\n";
              
              mail($email, $subject, $message, $headers);             
              
              mysql_close($db_handle);
              header ("Location: /recovery.php?success=1");
          }
          else 
          {
              mysql_close($db_handle);  
              header("Location: /recovery.php?error=1"); 
          }
       }
       
       if (isset($_POST['ChangePassword'])) 
	   {  
          $username = str_replace($search, $replace, $_POST['UserID']);
          
          $oldpass = $_POST['OldPass'];
          $password = $_POST['NewPass'];
          $confirm = $_POST['confirmPass'];
          
          if ( $username != "" && $username[0] != " " && $password == $confirm && strlen($password) >= 6 )
          {
              $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND passcode = '" . sha1(md5($oldpass)) . "'";
              $result = mysql_query($SQL);
              $num_rows = mysql_num_rows($result);
              
              if ($num_rows > 0) 
              {                  
                  $SQL = "UPDATE users SET passcode = '" . sha1(md5($password)) . "' WHERE userid = '" . $username . "'";
                  $result = mysql_query($SQL);
                   
                  $SQL = "SELECT * FROM users WHERE userid = '" . $username . "' AND passcode = '" . sha1(md5($password)) . "'";
                  $result = mysql_query($SQL);                 
                  
                  session_start();
                  
                  $_SESSION['login'] = $username;  
                  $_SESSION['newmessages'] = 0;
                  $_SESSION['newinvites'] = 0;       
                  
                  while ($db_field = mysql_fetch_assoc($result)) 
				  {
					$_SESSION['userid'] = $db_field['id'];
				  }
				    
                  mysql_close($db_handle);                    
                  header ("Location: /dashboard.php");
              } 
              else               
              {
                  mysql_close($db_handle);
                  header("Location: /recovery.php?error=2");
              }
          }
          else 
          {
              mysql_close($db_handle);  
              header("Location: /recovery.php?error=3"); 
          }
       }

       mysql_close($db_handle);  
	  }
	  else 
 	  {
        print "Database NOT Found " . $db_handle;
	  }	
	  
	  if ($_GET["logout"])
      {            
        if ($_GET["logout"] == "yes")
        {
            session_start();
            session_destroy();
            header("Location: /index.php");
        }
      }	  

?>
