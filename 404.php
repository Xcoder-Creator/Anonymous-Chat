<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the 404 error page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="404 error occured!">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Webpage title -->
    <title>Anonymous-chat | 404 error</title>
	<!-- Css files to be used for the general styling of the 404 error page -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the 404 error page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/anonymous-message.png">
</head>
<body>
    <!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<!-- The div container containing the 404 error content -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content" style="background-color: #d4eeef;">
                    <div class="signup-form">
                        <div class="form-group" style="margin-top: 0px; margin-bottom: 0px; text-align: center;">
                            <div style="font-size: 5.0rem;"><b style="text-shadow: 1px 1px 2px #2e6abf, 0 0 25px #74ebd5, 0 0 5px #9face6;">404</b></div>
                        </div>
						
						<div class="form-group" style="    margin-top: -30px; margin-bottom: 0px; text-align: center;">
                            <div style="font-size: 1.0rem;"><b>PAGE NOT FOUND !</b></div>
                        </div>
						
						<!-- Login page button -->
						<div class="form-group">
                            <a class="msg_chk" style="border: none; margin-top: 15px;" href="login.php">Go Back <i style="margin-left: 4px;" class="fa fa-arrow-left"></i></a>
                        </div>
						<!----------------------->
						
						<p style="text-align: left; margin-top: 4px; font-size: 12px;">It looks like youv'e lost your way. Don't worry we have you covered, Just click on the button above to place you on the right path 👍</p>
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

	<!-- Javascript files to be used on the 404 error page -->
    <script src="vendor/jquery/jquery.min.js"></script>
	<script type="text/javascript">
	  //Page spinner loader
	  setTimeout(function(){
		$("#preloder").fadeOut();
		$(".loader").fadeOut();
	  }, 2000);
	  //--------------------
	  
	  var year = new Date();
	  document.getElementById("curr_year").innerHTML = year.getFullYear();
	</script>
	<!----------------------------------------------->
</body>
</html>