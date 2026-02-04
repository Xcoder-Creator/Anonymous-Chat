<?php 
    session_start(); //Starting up sessions for the web application through the zQaxqq23_12qa page
	
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
  
    //Check if the user id and the message is set
	if (isset($_POST['id']) && isset($_POST['msg'])){
		if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://localhost/www.anonymous-chat.com/message.php?msg_id=' . $_POST['id'] && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if (strlen($_POST['msg']) >= 200 || ctype_space($_POST['msg']) || strlen($_POST['msg']) < 10){
					echo "
					  <script>
						try {
						  document.getElementById('msg_send_err').innerHTML = '* Message length must be up to 10 and not more than 199! *';
						} catch (err){
							window.location.href = '../login.php';
						}  
					  </script>
					";
				} else {
					$user_id_table = $_POST['id']; //Users id
					
					//Sql query used to insert the sent messages into the users message id table
					$send_msg = $conn->prepare("
					  INSERT INTO `$user_id_table`(user_msg_id, message, post_time, post_date) VALUES(:user_id, :user_msg, CURRENT_TIMESTAMP, NOW());
					");
					//----------------------------------------------------

					$send_msg->bindParam(":user_id", $user_id); //Bind the :user_id placeholder with the $user_id variable
					$send_msg->bindParam(":user_msg", $user_msg); //Bind the :user_msg placeholder with the $user_msg variable
				  
					$user_id = htmlspecialchars(stripslashes(trim(stripcslashes($_POST['id'])))); //Contains the users message id
					$user_msg = htmlspecialchars(stripslashes(trim(stripcslashes($_POST['msg'])))); //Contains the sanitized message
				  
					//Execute the sql query above
					if ($send_msg->execute()){
					  $_SESSION['msg_send_token'] = "aq3exq";
					  
					  //If condition above is met, redirect the user to the msg_success page
					  echo "
						<script>
						  window.location.href = 'success_messages/msg_success.php';
						</script>
					  ";
					  //----------------------------------------------		  
					} else {
					  //If there was an error in running the sql query, redirect the user to the login page
					  echo "
						<script>
						  window.location.href = '../login.php';
						</script>
					  ";
					  //-------------------------------------------------
					}
				}
		} else {
			echo "
			  <script>
				document.write('Not allowed!');
			  </script>
			";  
		}
	} else {
		echo "
		  <script>
		    window.location.href = '../login.php';
		  </script>
		";
	}	
?>