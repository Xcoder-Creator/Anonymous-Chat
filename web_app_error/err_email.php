<?php 
  session_start(); //Starting up sessions for the web application through the err_email page
  
  if (isset($_SESSION['mail_error_key'])){
	unset($_SESSION['mail_error_key']);
  } else {
	header("Location: ../login.php"); //Redirect the user to the login page, if the err_msg_token is not set  
  }
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the err_email page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="An error has occured, Wrong email used!">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Webpage title -->
    <title>Anonymous-chat | Mail Error Message</title>
	<!-- Css files to be used for the general styling of the err_email page -->
	<link rel="stylesheet" href="../fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="../css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the err_email page -->
	<link rel="icon" type="image/png" sizes="16x16" href="../images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the err_email content -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content" style="background-color: #d4eeef;">
                    <div class="signup-form">
                        <h2 style="margin-bottom: 16px; text-transform: none; color: red; font-size: 23px;" class="form-title">Email Doesn't Exist!</h2>
                        <div class="form-group" style="margin-top: 13px; text-align: center;">
                            <img style="width: 50px;" src="../images/Error-icon.png">
                        </div>
						
						<!-- Login page button -->
						<div class="form-group">
                            <a class="msg_chk" href="../login.php" style="border: none;">Go Back <i style="margin-left: 4px;" class="fa fa-arrow-left"></i></a>
                        </div>
						<!----------------------->
						
						<p style="text-align: left; margin-top: 4px; font-size: 12px;">The email which you entered might have not been registered with us. Click the button above to go back to the login page.</p>
                        <p style="text-align: center; margin-top: 23px; font-size: 14px;">
                          <span style="font-weight: 653;">© <span id="curr_year"></span></span> - Anonymous-Chat
                        </p>
						<p style="text-align: center; margin-top: 3px; font-size: 14px;">
                          <a href="../index.php" style="text-decoration: none; color: #222;">Home</a> | <a href="javascript:void(0)" style="text-decoration: none; color: #222;">Disclaimer</a> | <a href="javascript:void(0)" style="text-decoration: none; color: #222;">Contact Us</a>
                        </p>
					</div>	
                </div>
            </div>
        </section>
    </div>
	<!--------------------------------------------->

	<!-- Javascript files to be used on the err_email page -->
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