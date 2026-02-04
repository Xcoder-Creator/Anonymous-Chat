<?php
  session_start(); //Starting up sessions for the web application through the message page
  
  //Database connection details
  $servername = "localhost";
  $username_server = "root";
  $server_password = "";
  $database = "anonymous_chat";
  //---------------------------
  
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username_server, $server_password); //Activate the connection and store details in $conn variable
  
  //If connection was unsuccessfull
  if (!$conn){
	$_SESSION['load_err'] = 'Qas12bY'; //Session variable created incase of unsuccessfull database connection
	header("Location: website_error.php"); //Redirect to website_error.php
  }
  //------------------------------
  
  //Check if the message id is set 
  if (isset($_GET['msg_id'])){
	//If the condition above is met
	$msg_id = $_GET['msg_id']; //Message id value
	
	$_SESSION["message_load_key"] = 0;
	
	//This query selects all the rows in the user details table that has the same message id with the one stated above
	$query = $conn->prepare("
		SELECT * FROM user_details
		WHERE messageID = '$msg_id';
	");
	//-----------------------------------------------------
	
	$query->execute(); //Execute the query above
	$fetch_rows = $query->fetchAll(); //Fetch all the rows gotten from the user details table
	//------------------------------
	
	//Check the number of rows fetched from the user details table
	if (count($fetch_rows) > 0 && count($fetch_rows) == 1){
		$id_fullname = $fetch_rows[0][1]; //Get the full name of the user from the fetched details above
	} else {
		header("Location: index.php"); //Redirect the user to the index page
	}
	//-------------------------------------------
  } else {
	header("Location: index.php"); //Redirect the user to the index page
  }
  //------------------------------
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the message page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Hello, My name is <?php echo $id_fullname; ?>, Please send me an anonymous message. I won't know who sent it 😁👌😃😉">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Meta details for whatsapp, facebook, instagram and twitter link sharing through og -->
	<meta property="og:title" content="Anonymous-chat | User">
	<meta property="og:url" content="http://localhost/www.anonymous-chat.com/message.php">
	<meta property="og:description" content="Hi there friend ❤💖, Please send me a secret anonymous message. I won't know who sent it 😁👌😃😉">
	<meta property="og:image" content="http://localhost/www.anonymous-chat.com/images/anonymous-message.png">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">
	<meta property="og:locale:alternate" content="fr_FR">
	<meta property="og:locale:alternate" content="es_ES">
	<!-- Webpage title -->
    <title>Anonymous-chat | User(<?php echo $id_fullname; ?>)</title>
	<!-- Css files to be used for the general styling of the message page -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<!-- The image icon to be used on the browser tab for the message page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the message form -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content" style="background-color: #d4eeef;">
                    <div class="signup-form">
                        <h2 style="margin-bottom: 16px; text-transform: none;" class="form-title">Say Something...</h2>
						<div id="message_panel">
						
						  <!-- The message input bar used for recieving messages -->
						  <input type="text" id="msg_txt" required />
						  <!--------------------------------------------------------->
						  
						  <label id="label_band" for="msg_txt"><span id="grade">Say Something About Me...</span></label>
						</div>
						<p id="msg_send_err" style="color: red; font-size: 12px; text-align: center;"></p>
						
						<!-- Send message button -->
                        <div class="form-group" style="margin-top: 13px;">
                            <a class="msg_chk" style="cursor: pointer; border: none;" id="send_msg">Send Message <i style="margin-left: 4px;" class="fa fa-envelope-o"></i></a>
                        </div>
						<!------------------------->
						
						<!-- Login page button -->
						<div class="profile_lnk_group">
                            <a class="msg_chk" href="login.php" style="border: none;">Go Back <i style="margin-left: 4px;" class="fa fa-arrow-left"></i></a>
                        </div>
						<!----------------------->
						
						<p style="text-align: left; margin-top: 4px; font-size: 12px;">Say what you think about <?php echo $id_fullname; ?> using the form above. Thank You!!</p>
                        <p style="text-align: center; margin-top: 23px; font-size: 14px;">
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
	<!--------------------------------------------->
	
	<p id="msg_id" style="display: none;"><?php echo $msg_id; ?></p>

	<!-- Javascript files to be used on the message page -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/js/msg_handle.js"></script>
	<script type="text/javascript">
	  var year = new Date();
	  document.getElementById("curr_year").innerHTML = year.getFullYear();
	</script>
	<!----------------------------------------------->
</body>
</html>