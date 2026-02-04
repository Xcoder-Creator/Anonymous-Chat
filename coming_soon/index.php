<?php
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
	  header("Location: error_pages/website_error.php"); //Redirect to website_error.php
	}
	//------------------------------
	
	//SMTP functions to be used in sending mail in PHPMailer
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	//---------------------------------

	//Files needed in order for PHPMailer to work
	require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
	require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
	require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';
	//------------------------------------------

	$mail = new PHPMailer(true); //Passing true in constructor enables exceptions in PHPMailer



	if (isset($_SESSION["data_id"])){
		unset($_SESSION["data_id"]);
	}

	if (isset($_SESSION["visitor_id"])){
		unset($_SESSION["visitor_id"]);
	}

	if (isset($_SESSION["visitor_block_id"])){
		unset($_SESSION["visitor_block_id"]);
	}
	
	if (isset($_SESSION["user_mail_id"])){
		unset($_SESSION["user_mail_id"]);
	}

	if (isset($_SESSION["delete_user_id"])){
		unset($_SESSION["delete_user_id"]);
	}
	
	if (isset($_SESSION["delete_cookie_id"])){
		unset($_SESSION["delete_cookie_id"]);
	}

	function get_user_ip_address(){
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			//checks for ip from the internet
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			// Check if the user is using Proxy
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}
	
		return $ip; //Get user actual ip_address
	}

	$user_ip_address = get_user_ip_address();

	if (filter_var($user_ip_address, FILTER_VALIDATE_IP)){
		$query = $conn->prepare("
			SELECT * FROM visitor_table
			WHERE ip_address = :ip;
		");

		$query->bindParam(":ip", $real_ip);

		$real_ip = $user_ip_address;

		$query->execute();

		$rows = $query->fetchAll();

		if (count($rows) > 0){
			$status = $rows[0][3];
			if ($status == "Unblocked"){
				echo "";
			} else if ($status == "Blocked"){
				header("Location: blocked_page.php");
			}
		} else {
			$query = $conn->prepare("
				INSERT INTO visitor_table(ip_address, date_visited, user_status) VALUES(:ip, NOW(), 'Unblocked');
			");

			$query->bindParam(":ip", $real_ip);

			$real_ip = $user_ip_address;

			if ($query->execute()){
				$query = $conn->prepare("
					UPDATE no_of_visitors
					SET total_visitors = total_visitors + 1
					WHERE id = 1;
				");

				$query->execute();
			}
		}
	} else {
		echo "";
	}
?>
<!DOCTYPE html>
<html lang="en-Us">
  <head>
	<!-- All meta information for the index page -->
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred Afia">
	<meta name="description" content="Anonymous-chat is an online anonymous messaging system and its coming soon!">
	<meta name="keywords" content="chat, Anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Details for whatsapp, facebook, instagram and twitter link sharing through og -->
	<meta property="og:title" content="Anonymous-chat | Coming Soon">
	<meta property="og:url" content="http://localhost/www.anonymous-chat.com/register.php">
	<meta property="og:description" content="Anonymous-chat is an online anonymous messaging system and its coming soon!">
	<meta property="og:image" content="http://localhost/www.anonymous-chat.com/images/anonymous-message.png">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">
	<meta property="og:locale:alternate" content="fr_FR">
	<meta property="og:locale:alternate" content="es_ES">
	<!-- Webpage title -->
    <title>Anonymous-chat | Coming Soon</title>
	<!-- Css files to be used for the general styling of the index page -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/Custom-Styles.css" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/materialize.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the index page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/Anonymous-chat.png">
  </head>
  <body>
    <!-- The Main content of the index page -->
    <div class="content_pack">
		<div class="cont_block">
			<div class="coming_soon_cont element-animate">
				<form class="notify_me_block" method="POST" action="<?php echo htmlentities(htmlspecialchars($_SERVER['PHP_SELF'])); ?>">
				    <div class="app_logo">
					  <img src="Images/anonymous-chat.png" class="element-animate" style="width: 33px; padding-bottom: 6px;"></img>
					</div>
					<span class="notify_me_block_title element-animate">
						Anonymous-chat coming soon!
					</span>
					<p class="err_msg element-animate"></p>
					
					<!-- Name field -->
					<div class="name-holder">
						<input class="input-val element-animate" type="text" name="fullname" placeholder="Your Name" required>
					</div>
					<!---------------->
					
					<!-- Email field -->
					<div class="email-holder">
						<input class="input-val element-animate" type="email" name="email" placeholder="Your Email" required>
					</div>
					<!----------------->
					
					<div class="notify_me_btn" style="margin-top: 0px;">
						<button class="notify_submit element-animate" name="submit" type="submit">
							Notify Me!
						</button>
					</div>
					<div style="padding-bottom: 6px; font-size: 13px;" class="text-center element-animate">
						<b>Note:</b> Type your name and email above so that we can notify you when the website has been finally hosted online. 
					</div>
					<div class="social-class" style="text-align: center; padding-top: 20px;">
					  <div class="ig-box element-animate">
					    <a href="javascript:void(0)"><img src="Images/instagram.png" id="ig-icon"></img></a>
					  </div>  
					  <div class="fb-box element-animate">
					    <a href="javascript:void(0)"><img src="Images/FB.png" id="fb-icon"></img></a>
					  </div>
					  <div class="tw-box element-animate">
					    <a href="javascript:void(0)"><img src="Images/twitter.png" id="tw-icon"></img></a>
					  </div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Core Js files -->
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/custom.js"></script>
	<?php 
	  //Check if the submit button has been clicked
	  if (isset($_POST['submit'])){
		$err_name = $err_email = ""; //Error messages
		$fullname = trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['fullname'])))), '"'); //Users fullname
		$email = trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['email'])))), '"'); //Users email
		
		//Validate fullname
		if (strlen($fullname) < 8 || strlen($fullname) > 40){
			$err_name = "Error in name!";
			echo "
			  <script>
			    $('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
			    $('.err_msg').text('* Error in fullname *');
			  </script>
			";  
		} else {
			$strip_space = preg_replace('/\s+/', '', $fullname);
			if (strlen($strip_space) < 8 || strlen($strip_space) > 40){
				$err_name = "Error in name!";
				echo "
			      <script>
				    $('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
			        $('.err_msg').text('* Error in fullname *');
			      </script>
			    ";  
			}
		}
		//----------------------------------------
		
		//Validate email
		if (!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email) > 30 || $email == ""){
			$err_email = "Error in email!";
		    echo "
			  <script>
			    $('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
			    $('.err_msg').text('* Error in email *');
			  </script>
			";  
		} else {
			if($email == "alfredmichael819@gmail.com"){
				$err_email = "This email cannot be used!";
				echo "
			      <script>
				    $('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
			        $('.err_msg').text('* This email cannot be used! *');
			      </script>
			    "; 
			}
		}
		//----------------------------------------
		
		if (empty($err_name) && empty($err_email)){
			//Write a query to select all the emails in the database based on the email that the user entered
			$query = $conn->prepare("
			  SELECT * FROM subscription_line
			  WHERE user_email = :email;
			");
			//------------------------------------------------------------
			
			$query->bindParam(":email", $x_email); //Bind the email placeholder with the email that the user entered
			
			$x_email = $email;
			
			$query->execute(); //Execute the sql query above
			
			$rows = $query->fetchAll(); //Fetch all the data gotten and place them into a variable
			
			if (count($rows) == 0){
				//Write a query to insert the users fullname and email into the database 
				$query = $conn->prepare("
			      INSERT INTO subscription_line(user_fullname, user_email, date_subscribed) VALUES(:fullname, :email, NOW());
			    ");
				//--------------------------------------------------
				
				//Bind the fullname and email placeholders with the fullname and email that the user entered
				$query->bindParam(":fullname", $x_fullname);
				$query->bindParam(":email", $x_email);
				//-------------------------------------------------------
				
				$x_fullname = $fullname;
				$x_email = $email;
				
				if ($query->execute()){
					//If the sql query above was executed successfully then try to connect to the SMTP server in order to send a mail message to the user based on the users email
					try {
						// SMTP server settings
						$mail->isSMTP();
						$mail->Host = 'smtp.gmail.com';
						$mail->SMTPAuth = true;
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
						$mail->Port = 587;
						//----------------------------
						
						//Senders email credentials both email address and password
						$mail->Username = 'anonymouschat456@gmail.com'; //The email address to be used in sending the mail
						$mail->Password = 'ywue172uwh9'; //The password for the email address
						//----------------------------------------

						//Sender and recipient settings
						$mail->setFrom('anonymouschat456@gmail.com', 'Anonymous-chat'); //The email address to be used in sending the mail
						$mail->addAddress("$email", "$fullname"); //The users name and email address
						$mail->addReplyTo('anonymouschat456@gmail.com', 'Anonymous-chat'); // to set the reply to
						//---------------------------------

						//Setting the email content
						$mail->IsHTML(true); //Setting up the mail to be of an html format
						$mail->Subject = "Welcome To Anonymous-chat"; //The subject of the mail to be sent
						//-------------------------------
						
						//Body of the html mail to be sent to the user
						$mail->Body = '
						  <!DOCTYPE html>
						  <html>
							  <head>
								<meta charset="UTF-8">
								<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
								<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
								<meta http-equiv="X-UA-Compatible" content="IE=edge">
								<meta name="author" content="Michael Alfred">
								<meta name="description" content="Anonymous-chat is an online anonymous messaging system and its coming soon!">
								<meta name="keywords" content="chat, anonymous-chat, message, ping">
								<title>Anonymous-chat | Welcome</title>
								<link rel="stylesheet" href="http://anonymous-chat.6te.net/css/Custom-Styles.css">
								<link rel="stylesheet" href="http://anonymous-chat.6te.net/css/font-awesome.min.css">
								<link rel="icon" type="image/png" sizes="16x16" href="http://anonymous-chat.6te.net/Images/anonymous-chat.png">
							  </head>
							  <body style="background-color: #fff; padding-top: 60px; padding-bottom: 0px;">
								<div style="text-align: center;">
								  <img style="width: 34px;" src="http://anonymous-chat.6te.net/Images/anonymous-chat.png">
								</div>
								<p style="text-align: center; color: #000; font-weight: 700; font-size: 20px;">Ready to have fun?</p>
								<p style="text-align: center; padding-left: 10px; padding-right: 10px; margin-bottom: 25px;">Explore the world of anonymous-chat as you send anonymous messages to your friends and also get to recieve one as well.<div style="text-align: center;">Coming Soon!</div></p>
								<img style="width: 100%; height: 160px; object-fit: cover;" src="http://anonymous-chat.6te.net/Images/work-3.jpg">
								<div style="text-align: center; margin-top: 29px;">
								  <a style="width:100%; border-radius:30px; -moz-border-radius:30px; -webkit-border-radius:30px; -o-border-radius:30px; -ms-border-radius:30px; padding:15px 20px; box-sizing:border-box; font-size:14px; font-weight:700; color:#fff; text-transform:uppercase; border:none; background-image:-moz-linear-gradient(to left,#74ebd5,#9face6); background-image:-ms-linear-gradient(to left,#74ebd5,#9face6); background-image:-o-linear-gradient(to left,#74ebd5,#9face6); background-image:-webkit-linear-gradient(to left,#74ebd5,#9face6); background-image:linear-gradient(to left,#74ebd5,#9face6); text-decoration:none;" href="javascript:void(0)">EXPLORE</a>
								</div>
								<div style="text-align: center; margin-top: 36px;">
								  <img style="width: 26px;" src="http://anonymous-chat.6te.net/Images/FB.png">
								  <img style="width: 26px; margin-left: 20px;" src="http://anonymous-chat.6te.net/Images/twitter.png">
								  <img style="width: 26px; margin-left: 20px;" src="http://anonymous-chat.6te.net/Images/instagram.png">
								</div>
								<p style="text-align: center; font-size: 12px; padding-left: 10px; padding-right: 10px;">Registered under the platform brinx_S.A. 2020 All Rights Reserved By Anonymous-chat.6te.net</p>
								<div style="width: 100%; padding: 3px 10px; box-sizing: border-box; font-size: 14px; font-weight: 700; color: #fff; text-transform: uppercase; border: none; margin-top: 17px; border-radius: 100px; background-image: -moz-linear-gradient(to left,#74ebd5,#9face6); background-image: -ms-linear-gradient(to left,#74ebd5,#9face6); background-image: -o-linear-gradient(to left,#74ebd5,#9face6); background-image: -webkit-linear-gradient(to left,#74ebd5,#9face6); background-image: linear-gradient(to left,#74ebd5,#9face6);"></div>
							  </body>
						  </html>
						';
						//-----------------------------------------------------
						
						$mail->AltBody = 'Please make use of a compatible HTML mail viewer to read the contents of this mail!'; //Alternate body to replace the main body above if a non html mail viewer is used to access the sent mail
						
						$mail->send(); //Send the prepared mail to the user
					} catch (Exception $e) {
						echo ""; //If an error occurs when trying to send the mail, Print out an empty space
					}	
					
					//Display a bootstrap alert box to signify a success message
					echo "
					  <script>
					    $('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
					    $('.err_msg').html('<div style=\"padding-right: 15px; font-size: 12px;\" class=\"alert alert-success alert-dismissible fade in\"><strong>Success!</strong> - Thank you for subscribing with us!</div>');
					  </script>
					";
					//-------------------------------------------------------------
				}
			} else {
				//Display an error message 
				echo "
					<script>
						$('.err_msg').css({'padding-bottom': '6px', 'display': 'block'});
						$('.err_msg').text('* Email already exists! *');
					</script>
				";  
				//----------------------------
			}
		}
	  }
	  //---------------------------------------------
	?>
	<!----------------------------------------------->
  </body>
</html>