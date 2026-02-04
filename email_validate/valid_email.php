<?php 
  session_start(); //Starting up sessions for the web application through the valid_email page
  
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
	header("Location: ../website_error.php"); //Redirect to website_error.php
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
  
  //Check if the ajax request for the valid_email page is coming from the login page and is a valid xhr request
  if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://localhost/www.anonymous-chat.com/login.php' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		if (isset($_POST['zxv9_email'])){
			$inp_email = $_POST['zxv9_email'];
			
			if (ctype_space($inp_email)){
				echo "
				  <script>
					document.getElementById('err_snd_email').innerHTML = '* Email cannot be empty!';
				  </script>
				";
			} else {
				if (!filter_var($inp_email,FILTER_VALIDATE_EMAIL)){
					echo "
					  <script>
					    document.getElementById('err_snd_email').innerHTML = '* Email must be valid!';
					  </script>
					";
				} else if ($inp_email == "youremail@gmail.com"){
					echo "
					  <script>
					    document.getElementById('err_snd_email').innerHTML = '* This email cannot be used!';
					  </script>
					";
				} else {
					//An Sql query to select rows from the user_details table that has the same exact email with the one stated in the $inp_email variable
					$query = $conn->prepare("
					  SELECT * FROM user_details
					  WHERE user_email = :email;
					");
					//----------------------------------------------------------------
					
					$query->bindParam(":email", $email_send); //Bind the :email placeholder with the $email_send variable
					
					$email_send = filter_var($inp_email, FILTER_SANITIZE_EMAIL); //Contains the sanitized email
					
					$query->execute(); //Execute the sql query above
					
					$fetch_data = $query->fetchAll(); //Fetch all the rows gotten from the query
					
					//Check if the rows gotten are equals to one or greater than one
					if (count($fetch_data) >= 1){
						$user_data_name = $fetch_data[0][1]; //Get the fullname of the user
						$user_data_password = $fetch_data[0][2]; //Get the password of the user
						$user_data_email = $fetch_data[0][3]; //Get the email of the user
						$user_data_username = $fetch_data[0][4]; //Get the username of the user

						//Try to connect to the SMTP server in order to send a mail message to the user based on the users email
						try {
							//SMTP Server settings
							$mail->isSMTP();
							$mail->Host = 'smtp.gmail.com';
							$mail->SMTPAuth = true;
							$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
							$mail->Port = 587;
							//----------------------------
							
							//Senders email credentials both email address and password
							$mail->Username = 'youremail@gmail.com'; //The email address to be used in sending the mail
							$mail->Password = 'youremailpassword'; //The password for the email address
							//----------------------------------------

							//Sender and recipient settings
							$mail->setFrom('youremail@gmail.com', 'Anonymous-Chat'); //The email address to be used in sending the mail
							$mail->addAddress("$user_data_email", "$user_data_name"); //The users name and email address
							$mail->addReplyTo('youremail@gmail.com', 'Anonymous-Chat'); // to set the reply to
							//---------------------------------

							//Setting the email content
							$mail->IsHTML(true);
							$mail->Subject = "User Credentials on Anonymous-Chat";
							$mail->Body = "Your username is $user_data_username and your password is $user_data_password. Thank You! 😎😝😊😁";
							$mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';
							//-------------------------------
							
							$_SESSION['success_mail_token'] = 'zxlkOm23tr9a'; //Session variable to be used in the mail_msg_success page 
							
							$mail->send(); //Send the prepared mail to the user

							$query = $conn->prepare("
								SELECT * FROM no_mails_sent
								WHERE Name = '$user_data_name' AND Email = '$user_data_email';
							");

							$query->execute();

							$rows = $query->fetchAll();

							if (count($rows) == 0){
								$query = $conn->prepare("
									INSERT INTO no_mails_sent(Name, Email, No_of_mail_sent) VALUES('$user_data_name', '$user_data_email', 1);
								");

								$query->execute();
							} else if (count($rows) > 0 && count($rows) == 1){
								$query = $conn->prepare("
									UPDATE no_mails_sent
									SET No_of_mail_sent = No_of_mail_sent + 1
									WHERE Email = '$user_data_email';
								");

								$query->execute();
							}
							
							//Redirect the user to the mail_msg_success page
							echo "
								<script>
								  window.location.href = 'http://localhost/www.anonymous-chat.com/success_messages/mail_msg_success.php';
								</script>
							";
							//--------------------------------------------------
						} catch (Exception $e) {
							$_SESSION['err_net_token'] = 'we324vcG';
							
							//If an error occurs when trying to send the mail, Take the user to the netw_err page
							echo "
							  <script>
								window.location.href = 'http://localhost/www.anonymous-chat.com/web_app_error/netw_err.php';
							  </script>
							";
							//------------------------------------------------------
						}
					} else {
						$_SESSION['mail_error_key'] = '23erYbm';
						
						//Redirect the user to the err_email page
						echo "
						  <script>
							window.location.href = 'http://localhost/www.anonymous-chat.com/web_app_error/err_email.php';
						  </script>
						";
						//-----------------------------------------
					}
					//------------------------------------------------------------
				}
			}
	    } else {
		  //If the email address is not set, Redirect the user to the login page
	 	  echo "
		    <script>
			  window.location.href = 'http://localhost/www.anonymous-chat.com/login.php';
		    </script>
		  ";  
		//-------------------------------------------------
	    }
  } else {
	//If the condition above was not met, display the message below
	echo "
	  <script>
	    document.write('Not allowed!');
	  </script>
	";
	//----------------------------------------------------------------
  }
?>