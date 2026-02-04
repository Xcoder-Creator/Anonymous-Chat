<?php 
  session_start(); //Starting up sessions for the web application through the view_msg page
  
  //Database connection details
  $servername = "localhost";
  $username_server = "root";
  $server_password = "";
  $database = "anonymous_chat";
  //----------------------------
  
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username_server, $server_password); //Activate the connection and store details in $conn variable
  
  //If connection was unsuccessfull
  if (!$conn){
	$_SESSION['load_err'] = 'Qas12bY'; //Session variable created incase of unsuccessfull database connection
	header("Location: website_error.php"); //Redirect to website_error.php
  }
  //--------------------------------
  
  //Check if session variables that are storing user credentials are set
  if (!isset($_SESSION['xyz_username']) || !isset($_SESSION['xyz_userpass']) || !isset($_SESSION['xyz_fullname']) || !isset($_SESSION['xyz_message_id'])){
	//If the condition above is met, run the code below
	echo "
	  <script>
		window.location.href = 'login.php';
	  </script>
	"; 
	//-------------------------------------------------
  } else {
	//Creating session variables storing values such as the users username, password, fullname and message id
	$username = $_SESSION['xyz_username'];
	$userpass = $_SESSION['xyz_userpass'];
	$fullname = $_SESSION['xyz_fullname'];
	$message_id = $_SESSION['xyz_message_id'];
	//-----------------------------------------------------------------
  }
  //--------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the view_msg page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Welcome to Anonymous-chat where anonymous messaging is fun and entertaining">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Webpage title -->
    <title>Anonymous-chat | Messages(<?php echo $fullname; ?>)</title>
	<!-- Css files to be used for the general styling of the view_msg -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the view_msg page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the main contents of the view_msg page -->
    <div class="main">
        <section class="profile">
            <div class="container">
                <div class="profile-content" style="background-color: #d4eeef;">
                    <div class="inner_profile-content">
                        <h2 style="margin-bottom: 1px; text-transform: none;" class="form-title">My Messages</h2>
						<p style="text-align: center; margin-top: 3px; margin-bottom: 10px;">Scroll down to check out the messages that you have recieved</p>
						<p id="msg_bar" style="font-size: 14px;">Messages: (<span id="row"></span>)</p>
						<div id="led_bar"></div>
						
						<!-- The div container holding all the users messages -->
						<div id="msg_recieved-block">
						  <div id="st_pt">* No messages found, Check back later! *</div>
						  <?php 
							  if (!isset($username) || !isset($userpass) || !isset($fullname) || !isset($message_id)){ //Check if the username, fullname and message id for the user is set
								//If not set, Take the user back to the view_msg page
								echo "
								  <script>
									window.location.href = 'login.php';
								  </script>
								";  
							  } else {
								//If set, Try to fetch the new messages and delete the old messages
								$query = $conn->prepare("
									SELECT * FROM user_details
									WHERE user_fullname = '$fullname' AND user_password = '$userpass' AND users_username = '$username' AND messageID = '$message_id';
								");
								$query->execute();
								$fetch_rows = $query->fetchAll();
								
								if (count($fetch_rows) > 0 && count($fetch_rows) == 1){
									//Delete messages from the database that are over ten days old or more
									$query = $conn->prepare("
										DELETE FROM `$message_id`
										WHERE DATEDIFF(NOW(), `$message_id`.`post_date`) > 10;
									");
									$query->execute();
									//---------------------------------------------
									
									//Select all the available messages and their registered time post
									$query = $conn->prepare("
										SELECT message, post_time FROM `$message_id`;
									");
									$query->execute();
									$fetch_msg = $query->fetchAll();
									//---------------------------------------------
									
									//Display all the fetched messages in html format
									foreach ($fetch_msg as $value){
										$msg = $value['message']; //The message
										$time = $value['post_time']; //The registered posted time for the message
										//Display messages
										echo "<div style=\"border-color: #4635e0; border-width: 1px; border-style: solid; border-radius: 10px; padding: 9px; margin-bottom: 13px;\" id=\"line_hook\"><b>Message:</b><div id=\"msg_post\">$msg</div><div id=\"time_set\">- Anonymous <span id=\"time_post\">[$time GMT]</span></div><a id=\"share_btn\" href=\"javascript:void(0)\"><i class=\"fa fa-share-square-o\"></i>   Share Response</a><a id=\"more_opt\" href=\"javascript:void(0)\">More Options    <i class=\"fa fa-caret-down\"></i></a></div>";
										//--------------------------------------------
									}
									//-------------------------------------------------------------------
								}
							  }
						  ?>
						</div>
						<!------------------------------------------------------>
						
						<div id="led_bar"></div>
						
						<!-- A link to the login page -->
						<div class="profile_lnk_group">
                            <a class="msg_chk" style="margin-top: 16px; border: none;" href="profile_page.php">Go Back <i style="margin-left: 4px;" class="fa fa-arrow-left"></i></a>
                        </div>
						<!------------------------------>
						
                        <p style="text-align: center; margin-top: 50px; font-size: 14px;">
                          <span style="font-weight: 653;">© <span id="curr_year"></span></span> - Anonymous-Chat
                        </p>
						<p style="text-align: center; margin-top: 3px; font-size: 14px;">
                          <a href="index.php" style="text-decoration: none; color: #222;">Home</a> | <a href="javascript:void(0)" style="text-decoration: none; color: #222;">Disclaimer</a> | <a href="javascript:void(0)" style="text-decoration: none; color: #222;">Contact Us</a>
                        </p>
					</div>	
                </div>
            </div>
        </section>
    </div>
	<!--------------------------------------------------->

	<!-- Javascript files to be used on the view_msg page -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/js/view_message.js"></script>
	<script type="text/javascript">
	  var year = new Date();
	  document.getElementById("curr_year").innerHTML = year.getFullYear();
	</script>
	<?php 
	    $count_val = count($fetch_msg);							
		if ($count_val > 0){
			//If the number of rows fetched is greater than zero, store the count of the rows in the span element having an id of 'row'
			echo "
			  <script>
				document.getElementById('row').innerHTML = '$count_val';
			  </script>
			";
			//---------------------------------------------------------------
		} else {
			//If the number of rows fetched is equals to zero, store the value of zero in the span element having an id of 'row'
			echo "
			  <script>
				document.getElementById('row').innerHTML = '0';
			  </script>
			";
			//---------------------------------------------------------------
		}
	?>
	<!------------------------------------------------>
</body>
</html>