<?php 
  session_start(); //Starting up sessions for the web application through the profile page
  
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

  //Creating session variables storing values such as the users username, password, fullname and message id
  $username = $_SESSION['xyz_username'];
  $userpass = $_SESSION['xyz_userpass'];
  $fullname = $_SESSION['xyz_fullname'];
  $message_id = $_SESSION['xyz_message_id'];
  //-----------------------------------------------------------------
  
  //If user credentials are not set, Redirect the user back to the login page
  if (!isset($username) || !isset($userpass) || !isset($fullname) || !isset($message_id)){
	header("Location: login.php"); //Redirect to login.php
  }
  //----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the profile page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Welcome to Anonymous-chat where anonymous messaging is fun and entertaining">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Meta details for whatsapp, facebook, instagram and twitter link sharing through og -->
	<meta property="og:title" content="Anonymous-chat | Profile">
	<meta property="og:url" content="http://localhost/www.anonymous-chat.com/profile_page.php">
	<meta property="og:description" content="Welcome to Anonymous-chat where anonymous messaging is fun and entertaining">
	<meta property="og:image" content="http://localhost/www.anonymous-chat.com/images/anonymous-message.png">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">
	<meta property="og:locale:alternate" content="fr_FR">
	<meta property="og:locale:alternate" content="es_ES">
	<!-- Webpage title -->
    <title>Anonymous-chat | Profile(<?php echo $fullname; ?>)</title>
	<!-- Css files to be used for the general styling of the profile page -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the profile page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the main contents of the profile page -->
    <div class="main">
        <section class="profile">
            <div class="container">
                <div class="profile-content" style="background-color: #d4eeef;">
                    <div class="inner_profile-content">
                        <h2 style="margin-bottom: 16px;" class="form-title"><?php echo $fullname; ?>'<span style="font-size: 18px;">s</span> Profile</h2>
						<p style="text-align: center; margin-top: 4px; font-size: 13px;"><a id="cpylnk" style="text-decoration: none; color: #222;" href="javascript:void(0)" onclick="cpy_lnk(this)"><?php echo "http://localhost/www.anonymous-chat.com/message.php?msg_id=$message_id"; ?></a> <i class="fa fa-file-o"></i></p>
						<p style="font-size: 13px; padding-bottom: 20px;"><b>Share the profile link</b> above with your friends. Go to <b>"View Messages"</b> to check out the responses.</p>
                        <div class="profile_lnk_group">
                            <a class="msg_chk" style="border: none;" href="view_msg.php">View Messages <i style="margin-left: 4px;" class="fa fa-arrow-right"></i></a>
                        </div>
						
						<!-- Whatsapp Sharing link -->
                        <div class="profile_lnk_group">
                            <a class="profile_btn" data-clipboard-demo data-clipboard-action="copy" data-clipboard-text="Hi there friend ❤💖, Please send me a secret anonymous message. I won't know who sent it 😁👌😃😉👉 <?php echo "http://localhost/www.anonymous-chat.com/message.php?msg_id=$message_id"; ?>" style="background-image: linear-gradient(to left, #25d366, #23ece4); border: none; border-radius: 9px; cursor: pointer;"><i style="margin-left: 4px; font-size: 1.0rem; position: relative; top: 1px;" class="fa fa-whatsapp"></i>&nbsp; Share On WhatsApp</a>
                        </div>
						<!------------------->
						
						<!-- Facebook Sharing link -->
						<div class="profile_lnk_group">
                            <a class="profile_btn" style="background-image: linear-gradient(to right, #34b7f1, #23ef); border: none; border-radius: 9px;" href="javascript:void(0)"><i style="margin-left: 4px; font-size: 1.0rem; position: relative; top: 1px;" class="fa fa-facebook"></i>&nbsp; Share On Facebook</a>
                        </div>
						<!------------------->
						
						<!-- Instagram Sharing link -->
						<div class="profile_lnk_group">
                            <a class="profile_btn" style="background-image: linear-gradient(to right, #fd1d1d, #de664c); border: none; border-radius: 9px;" href="javascript:void(0)"><i style="margin-left: 4px; font-size: 1.0rem; position: relative; top: 1px;" class="fa fa-instagram"></i>&nbsp; Share On Instagram</a>
                        </div>
						<!------------------->
						
						<!-- Logout button -->
						<div class="profile_lnk_group">
                            <a class="profile_btn" id="log_out_btn" style="background-image: linear-gradient(345deg, #311c31, #786578); border: none;" href="#log_out">Log Out <i style="margin-left: 4px;" class="fa fa-sign-out"></i></a>
                        </div>
						<!------------------->
						
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

	<!-- Javascript files to be used on the profile page -->
    <script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/js/clipboard.min.js"></script>
	<script src="vendor/js/profile_page.js"></script>
    <script type="text/javascript">
	  var year = new Date();
	  document.getElementById("curr_year").innerHTML = year.getFullYear();
	</script>
	<!------------------------------------------------------->
</body>
</html>