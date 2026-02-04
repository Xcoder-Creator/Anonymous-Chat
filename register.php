<?php 
  session_start(); //Starting up sessions for the web application through the sign up page
  
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
  //--------------------------------
  
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
  
  //Check if there is a cookie with the key id_unique in the users browser
  if (isset($_COOKIE["id_unique"])){
	//Sql query to select all the rows in the cookie table based on the cookie id gotten from the users browser stored in the catch_cookie_val variable
	$exec_run = $conn->prepare("
	  SELECT * FROM cookie_table
	  WHERE cookieID = :catch_cookie_val;
	");
	//------------------------------------------------
	
	$exec_run->bindParam(":catch_cookie_val", $catch_cookie_val); //Bind the :catch_cookie_val placeholder with the $catch_cookie_val variable
	
	$catch_cookie_val = filter_var($_COOKIE["id_unique"], FILTER_SANITIZE_STRING); //If the cookie is set and available, Store it in a variable called catch_cookie_val
	
	$exec_run->execute(); //Execute the sql query above
	
	$array = $exec_run->fetchAll(); //Fetch all the rows if any, gotten from the execution of the sql query above on the server
	
	//Check if all the rows gotten is not equals to zero and not more than one
	if (count($array) > 0 && count($array) == 1){
		//If the condition above is met, Get the username and password from the row gotten and store them in a variable
		$auth_name = $array[0][2]; //Grabing the username found on the cookie table and storing it in the auth_name variable
		$auth_pass = $array[0][3]; //Grabing the password found on the cookie table and storing it in the auth_pass variable
		//-----------------------------------------------------
		
		//Sql query to select all the rows in the user_details table based on the username and password gotten from the users browser stored in the $auth_name and $auth_pass variables
		$exec_run = $conn->prepare("
		  SELECT * FROM user_details
		  WHERE users_username = '$auth_name' AND user_password = '$auth_pass';
		");
		//-----------------------------------------------------
		
		$exec_run->execute(); //Execute the sql query above
	
		$list_row = $exec_run->fetchAll(); //Fetch all the rows if any, gotten from the execution of the sql query above on the server
		
		//Check if all the rows gotten is not equals to zero and not more than one
		if (count($list_row) > 0 && count($list_row) == 1){
			//If condition above is met, Create session variables to store values such as the users username, password, fullname and message id
			$_SESSION['xyz_username'] = $list_row[0][4]; //Username session
			$_SESSION['xyz_userpass'] = $list_row[0][2]; //Password session
			$_SESSION['xyz_fullname'] = $list_row[0][1]; //Fullname session
			$_SESSION['xyz_message_id'] = $list_row[0][5]; //Message Id session
			header("Location: profile_page.php"); //After creating sessions, Redirect the user to the users profile page
		}
		//-------------------------------------------------------------------------
	}
  }
  
  $err_name = $err_username = $err_useremail = $err_pass = $success = ""; //Message variables to be used to print out errors and success messages
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
    <!-- All meta information for the sign up page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Welcome to Anonymous-chat where anonymous messaging is fun and entertaining">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Details for whatsapp, facebook, instagram and twitter link sharing through og -->
	<meta property="og:title" content="Anonymous-chat | Sign-up">
	<meta property="og:url" content="http://localhost/www.anonymous-chat.com/register.php">
	<meta property="og:description" content="Sign up with Anonymous-chat. It is fun and entertaining">
	<meta property="og:image" content="http://localhost/www.anonymous-chat.com/images/anonymous-message.png">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">
	<meta property="og:locale:alternate" content="fr_FR">
	<meta property="og:locale:alternate" content="es_ES">
	<!-- Webpage title -->
    <title>Anonymous-chat | Sign-up</title> 
	<!-- Css files to be used for the general styling of the sign up page -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- The image icon to be used on the browser tab for the sign up page -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/anonymous-message.png">
</head>
<body>
	<!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>
	
	<?php 
	  //Check if the user clicked the submit form button
	  if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
		//Check if the fullname, username, useremail, password and message id is set
		if (isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['useremail']) && isset($_POST['userpass'])){
			//If the condition above is met, Sanitize the data collected and store them in individual variables
			$fullname = filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['fullname'])))), '"'), FILTER_SANITIZE_STRING);
			$username =  filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['username'])))), '"'), FILTER_SANITIZE_STRING);
			$useremail =  filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['useremail'])))), '"'), FILTER_SANITIZE_EMAIL);
			$userpass =  filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['userpass'])))), '"'), FILTER_SANITIZE_STRING);
			//--------------------------------------------------------------------
			
			//Validate fullname
			if (strlen($fullname) < 8 || strlen($fullname) > 40){
				$err_name = "*Error in full name. Length must be up to 8 or more!";
			} else {
				$strip_space = preg_replace('/\s+/', '', $fullname);
				if (strlen($strip_space) < 8 || strlen($strip_space) > 40){
					$err_name = "*Error in full name. Length must be up to 8 or more!";
				}
			}
			//----------------------------------------
			
			// Validate username
			if (
				!preg_match('/^[a-zA-Z0-9._\-!@#$%^&*]+$/', $username) ||
				strlen($username) > 10
			) {
				$err_username = "*Username may contain only letters, numbers, special characters and must be at most 10 characters long!";
			}
			
			//Validate useremail
			if (!filter_var($useremail,FILTER_VALIDATE_EMAIL) || strlen($useremail) > 30 || $useremail == ""){
				$err_useremail = "*Error in email. Must be a valid email!";
			}
			//----------------------------------------
			
			//Validate user password
			if (strlen($userpass) < 10 || strlen($userpass) > 10 || $userpass == ""){
				$err_pass = "*Error in password. Length must be equals to 10!";
			} else {
				$strip_space = preg_replace('/\s+/', '', $userpass);
				if (strlen($strip_space) < 10 || strlen($strip_space) > 10){
					$err_pass = "*Error in password. Length must be equals to 10!";
				}
			}
			//----------------------------------------
			
			//Check if the terms and agreement checkbox has been ticked
			if (isset($_POST['agree-term']) == false){
				$success = "* Must agree with terms and services! *";
			}
			//----------------------------------------
			
			//Check if the error messages variables are empty and the agree term checkbox is ticked
			if (empty($err_name) && empty($err_username) && empty($err_useremail) && empty($err_pass) && isset($_POST['agree-term'])){
				//If the condition above is met, Write an sql query to select all the rows in the user_details table based on the users username, the users password and the users email
				$query = $conn->prepare("
					SELECT * FROM user_details
					WHERE users_username = :username OR user_password = :userpass OR user_email = :useremail;
				");
				//------------------------------------------------------------------------
				
				//Bind the placeholders above with real values
				$query->bindParam(":username", $u_name);
				$query->bindParam(":userpass", $u_pass);
				$query->bindParam(":useremail", $u_email);
				//-----------------------------------------
				
				//Create variables and pass in values that will be used to bind parameters above
				$u_name = $username;
				$u_pass = $userpass;
				$u_email = $useremail;
				//-----------------------------------------
				
				$query->execute(); //Execute the sql query above
				
				$num_rows = $query->fetchAll(); //Fetch all the rows if any, gotten from the execution of the sql query above on the server
				
				//Check if the rows fetched are greater than zero
				if (count($num_rows) > 0){
					$success = "* Account with similar details already exists! *"; //If the condition above is met, Print a message telling the user that there is an account existing with similar details
				} else {
					//A random message_id generator function to grant users unique message id's to be used in sending messages
					function message_id_generator($length){
						//A list of characters that can be used in the
						//message id.
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						//Create an empty variable which will be used to store the final generated message id to be used.
						$msg_pin_id = '';
						//Get the index of the last character in the characters variable.
						$characterListLength = mb_strlen($characters, '8bit') - 1;
						//Loop from 1 to the length variable that was specified.
						foreach(range(1, $length) as $i){
							$msg_pin_id .= $characters[random_int(0, $characterListLength)];
						}
						return $msg_pin_id; //Return the final value of the message id
					}
					//-----------------------------------------------
					
					do {
						$userm_id = message_id_generator(6);
						
						$query = $conn->prepare("
							SELECT * FROM user_details
							WHERE messageID = :userm_id;
						");
						
						//Bind the placeholders above with real values
						$query->bindParam(":userm_id", $u_msg_id);
						//-------------------------------------------
						
						//Create a variable and pass in a value that will be used to bind parameters above
						$u_msg_id = $userm_id;
						//-------------------------------------------
						
						$query->execute(); //Execute the sql query above
					
						$num_rows = $query->fetchAll(); //Fetch all the rows if any, gotten from the execution of the sql query above on the server
					} while(count($num_rows) > 0);
					
					//If the details entered by the user are found in the user details table, Then insert the user details into the database
					//through writing a prepared statement in mysql to find out if there are any username's, password's, useremails, messaged id's and reg_date on a single row in the user details table on the database
					$query = $conn->prepare("
						INSERT INTO user_details(user_fullname, user_password, user_email, users_username, messageID, reg_date) VALUES(:fullname, :userpass, :useremail, :username, :userm_id, CURRENT_TIMESTAMP);
					");
					//---------------------------------------------------------
					
					//Bind the placeholders above with real values
					$query->bindParam(":fullname", $f_xname);
					$query->bindParam(":userpass", $u_xpass);
					$query->bindParam(":useremail", $u_xemail);
					$query->bindParam(":username", $u_xname);
					$query->bindParam(":userm_id", $u_x_msg_id);
					//---------------------------------------
					
					//Create variables and pass in values that will be used to bind parameters above
					$f_xname = $fullname;
					$u_xpass = $userpass;
					$u_xemail = $useremail;
					$u_xname = $username;
					$u_x_msg_id = $userm_id;
					//-----------------------------------------------
					
					$query->execute(); //Execute the sql query above
					
					//Write a query to create a table in the database that can be used to store different pieces of data for the user
					$query = $conn->prepare("
						CREATE TABLE IF NOT EXISTS `$userm_id` (
						  id INT(11) AUTO_INCREMENT PRIMARY KEY,
						  user_msg_id VARCHAR(30),
						  message VARCHAR(255),
						  post_time TIMESTAMP,
						  post_date DATE
						);
					");
					//-------------------------------------------
					
					//Execute the sql query
					if ($query->execute()){
						$success = "* Account created successfully! *"; //Print out a success message based on a successful registration
						
						//Try to connect to the SMTP server in order to send a mail message to the user based on the users email
						try {
							// SMTP server settings
							$mail->isSMTP();
							$mail->Host = 'smtp.gmail.com';
							$mail->SMTPAuth = true;
							$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
							$mail->Port = 587;
							//----------------------------
							
							//Senders email credentials both email address and password
							$mail->Username = 'alfredmichael819@gmail.com'; //The email address to be used in sending the mail
							$mail->Password = 'diegocoasta56'; //The password for the email address
							//----------------------------------------

							//Sender and recipient settings
							$mail->setFrom('alfredmichael819@gmail.com', 'Anonymous-Chat'); //The email address to be used in sending the mail
							$mail->addAddress("$useremail", "$fullname"); //The users name and email address
							$mail->addReplyTo('alfredmichael819@gmail.com', 'Anonymous-Chat'); // to set the reply to
							//---------------------------------

							//Setting the email content
							$mail->IsHTML(true); //Setting up the mail to be of an html format
							$mail->Subject = "Welcome To Anonymous-Chat"; //The subject of the mail to be sent
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
									<meta name="description" content="This is an anonymous massage system developed in php and mysql">
									<meta name="keywords" content="chat, anonymous-chat, message, ping">
									<title>Anonymous-chat | Welcome</title>
									<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
									<link rel="stylesheet" href="css/anonymous-chat_styles.css">
									<link rel="stylesheet" href="css/font-awesome.min.css">
									<link rel="icon" href="images/anonymous-message.png">
								  </head>
								  <body style="background-color: #fff; padding-top: 60px; padding-bottom: 0px;">
									<div id="align_icon">
									  <img style="width: 34px;" src="images/anonymous-message.png">
									</div>
									<p id="tag_head">Ready to have fun?</p>
									<p style="text-align: center; padding-left: 10px; padding-right: 10px; margin-bottom: 25px;">Explore the world of anonymous-chat as you send anonymous messages to your friends and also get to recieve one as well.</p>
									<img style="width: 100%; height: 160px; object-fit: cover;" src="images/work-3.jpg">
									<div id="exp_area">
									  <a id="exp_btn" href="index.php">EXPLORE</a>
									</div>
									<div style="text-align: center; margin-top: 36px;">
									  <img style="width: 26px;" src="images/FB.png">
									  <img style="width: 26px; margin-left: 20px;" src="images/YT.png">
									  <img style="width: 26px; margin-left: 20px;" src="images/IG.png">
									</div>
									<p style="text-align: center; font-size: 12px; padding-left: 10px; padding-right: 10px;">Registered under the platform brinx_S.A. 2020 All Rights Reserved By Anonymous-chat.com</p>
									<div id="roll_plain"></div>
								  </body>
							  </html>
							';
							//-----------------------------------------------------
							
							$mail->AltBody = 'Please make use of a compatible HTML mail viewer to read the contents of this mail!'; //Alternate body to replace the main body above if a non html mail viewer is used to access the sent mail
							
							$mail->send(); //Send the prepared mail to the user
						} catch (Exception $e) {
							echo ""; //If an error occurs when trying to send the mail, Print out an empty space
						}	
					} else {
						echo "Error creating table!"; //If the sql query was not executed successfully, Print out an error message
					}
				}
			}
		} else {
			//Message variables to be used to print out errors messages
			$err_name = "*Error in full name. Length must be up to 8 or more!";
			$err_username = "*Error in username. Must contain letters, numbers and white spaces with a length up to 10!";
			$err_useremail = "*Error in email. Must be a valid email!";
			$err_pass = "*Error in password. Length must be equals to 10!";
			//----------------------------------------------------------
		}
	  }
	?>
	<!-- The div container containing the html sign up form -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
				
					<!-- The html form used for collecting user registration details -->
                    <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>" id="signup-form" class="signup-form">
                        <h2 style="margin-bottom: 16px;" class="form-title">Create your account</h2>
						<p style="text-align: center; margin-top: 4px; color: green; margin-bottom: 20px; font-size: 13px;"><?php echo $success; ?></p>
						
						<!-- Fullname field -->
                        <div class="form-group">
                            <input type="text" class="form-input" name="fullname" id="fullname" value="<?php if(isset($_POST['fullname'])) echo $_POST['fullname']; ?>" placeholder="Your Name" required/>
							<p style="color: red; font-size: 13px;"><?php echo $err_name; ?></p>
                        </div>
						<!-------------------->
						
						<!-- Username field -->
						<div class="form-group">
                            <input type="text" class="form-input" name="username" id="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="Create Username" required/>
							<p style="color: red; font-size: 13px;"><?php echo $err_username; ?></p>
                        </div>
						<!-------------------->
						
						<!-- Useremail field -->
						<div class="form-group">
                            <input type="text" class="form-input" name="useremail" id="useremail" value="<?php if(isset($_POST['useremail'])) echo $_POST['useremail']; ?>" placeholder="Your Email" required/>
							<p style="color: red; font-size: 13px;"><?php echo $err_useremail; ?></p>
                        </div>
						<!-------------------->
						
						<!-- Userpassword field -->
                        <div class="form-group">
                            <input type="password" class="form-input" name="userpass" id="userpassword" value="<?php if(isset($_POST['userpass'])) echo $_POST['userpass'] ?>" placeholder="Create Password" required/>
							<p style="color: red; font-size: 13px;"><?php echo $err_pass; ?></p>
                        </div>
						<!------------------------>
						
						<!-- User must agree to terms and services -->
                        <div class="form-group">
                            <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                            <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree to all statements in  <a href="#" class="term-service">Terms of service</a></label>
                        </div>
						<!------------------------------------------->
						
						<!-- The submit button to accept all the registration details from the user -->
                        <div class="form-group">
                            <input type="submit" style="cursor: pointer;" name="submit" id="submit" class="form-submit" value="Sign up"/>
                        </div>
						<!----------------------------------------------------------------------->
						
                    </form>
					<!-------------------------------------------------------->
					
					<!-- A link to the login page -->
                    <p class="loginhere" style="font-size: 14px;">
                        Already have an account ? <a href="login.php" class="loginhere-link">Login here</a>
                    </p>
					<!------------------------------>
					
                </div>
            </div>
        </section>
    </div>
	<!------------------------------------------------------------------>

	<!-- Javascript files to be used on the signup page -->
    <script src="vendor/jquery/jquery.min.js"></script>
	<script type="text/javascript">
	  $(document).ready(function(){
		setTimeout(function(){
			$("#preloder").fadeOut();
			$(".loader").fadeOut();
		}, 2000);
	  });
	</script>
	<!--------------------------------------------------->
</body>
</html>