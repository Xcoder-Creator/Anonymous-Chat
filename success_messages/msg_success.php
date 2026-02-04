<?php 
  session_start(); //Starting up sessions for the web application through the msg_success page
  
  if (isset($_SESSION['msg_send_token'])){
	unset($_SESSION['msg_send_token']);
  } else {
	header("Location: ../login.php"); //Redirect the user to the login page, if the msg_send_token is not set  
  }
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the msg_success page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Message sent successfully!">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Webpage title -->
    <title>Anonymous-chat | Success Message</title>
	<!-- Css files to be used for the general styling of the msg_success page -->
	<link rel="stylesheet" href="../fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="../css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the msg_success page -->
	<link rel="icon" href="../images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the msg_success content -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content" style="background-color: #d4eeef;">
                    <div class="signup-form">
                        <h2 style="margin-bottom: 16px; text-transform: none; color: green;" class="form-title">Message sent successfully!</h2>
                        <div class="form-group" style="margin-top: 13px; text-align: center;">
                            <img style="width: 70px;" src="../images/avataaars.svg">
                        </div>
						
						<!-- Login page button -->
						<div class="form-group">
                            <a class="msg_chk" style="border: none;" href="../login.php">Go Back <i style="margin-left: 4px;" class="fa fa-arrow-left"></i></a>
                        </div>
						<!----------------------->
						
						<p style="text-align: left; margin-top: 4px; font-size: 12px;">Your message has been sent successfully, Now it's your own turn to recieve messages sent to you by your friends. Click the button above to go to the login page. Thank you! 😁😊✌😜</p>
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

	<!-- Javascript files to be used on the msg_success page -->
    <script src="../vendor/jquery/jquery.min.js"></script>
	<script type="text/javascript">
	  $(document).ready(function(){
		setTimeout(function(){
		  $("#preloder").fadeOut();
		  $(".loader").fadeOut();
	    }, 2000);
	  });
	
	  var year = new Date();
	  document.getElementById("curr_year").innerHTML = year.getFullYear();
	</script>
	<!----------------------------------------------->
</body>
</html>