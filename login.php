<?php 
  session_start(); //Starting up sessions for the web application through the login page

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
  
  $_SESSION["key_load"] = 0;
  
  //Checking for cookies on the users browser
  if (isset($_COOKIE["id_unique"])){
	$exec_run = $conn->prepare("
	  SELECT * FROM cookie_table
	  WHERE cookieID = :cookie_v;
	");
	
	$exec_run->bindParam(":cookie_v", $catch_cookie_val);
	$catch_cookie_val = filter_var($_COOKIE["id_unique"], FILTER_SANITIZE_STRING);
	
	$exec_run->execute();
	
	$array = $exec_run->fetchAll();
	
	if (count($array) > 0 && count($array) == 1){
		$auth_name = $array[0][2]; //Grabing the username found on the cookie table and storing it in the auth_name variable
		$auth_pass = $array[0][3]; //Grabing the password found on the cookie table and storing it in the auth_pass variable
		
		$exec_run = $conn->prepare("
		  SELECT * FROM user_details
		  WHERE users_username = '$auth_name' AND user_password = '$auth_pass';
		");
		
		$exec_run->execute();
		
		$list_row = $exec_run->fetchAll();
		
		if (count($list_row) > 0 && count($list_row) == 1){
			//Creating session variables storing values such as the users username, password, fullname and message id
			$_SESSION['xyz_username'] = $list_row[0][4];
			$_SESSION['xyz_userpass'] = $list_row[0][2];
			$_SESSION['xyz_fullname'] = $list_row[0][1];
			$_SESSION['xyz_message_id'] = $list_row[0][5];
			header("Location: profile_page.php"); //After creating sessions, Redirect the user to the users profile page
			//--------------------------------------------------------------------
		}
	}
  }
  //------------------------------------------------
  
  $success = $err_name = $err_pass = ""; //Message variables to be used to print out errors and success messages
?>
<!DOCTYPE html>
<html lang="en-Us">
<head>
	<!-- All meta information for the login page -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Michael Alfred">
	<meta name="description" content="Welcome to Anonymous-chat where anonymous messaging is fun and entertaining">
	<meta name="keywords" content="chat, anonymous-chat, message, messaging, anonymous, chatting">
	<!-- Meta details for whatsapp, facebook, instagram and twitter link sharing through og -->
	<meta property="og:title" content="Anonymous-chat | Login">
	<meta property="og:url" content="http://localhost/www.anonymous-chat.com/login.php">
	<meta property="og:description" content="Login to your account on anonymous-chat">
	<meta property="og:image" content="http://localhost/www.anonymous-chat.com/images/anonymous-message.png">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="en_US">
	<meta property="og:locale:alternate" content="fr_FR">
	<meta property="og:locale:alternate" content="es_ES">
	<!-- Webpage title -->
    <title>Anonymous-chat | Login</title>
	<!-- Css files to be used for the general styling of the login page -->
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/anonymous-chat_styles.css">
	<!-- The image icon to be used on the browser tab for the login page -->
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
		//Check if the username and password is set
		if (isset($_POST['name']) && isset($_POST['password'])){
			//Prepared statements with mysql query to find if there are any username's and password's on a single row in the user details table on the database
			$query = $conn->prepare("
				SELECT * FROM user_details
				WHERE users_username = :username AND user_password = :userpass;
			");
			//-----------------------------------------------
			
			//Bind parameters used to bind the eg: ':username' and ':userpass' placeholders above with actual values
			$query->bindParam(":username", $user_name);
			$query->bindParam(":userpass", $user_pass);
			//----------------------------------------------
			
			//Sanitizing the external data gotten from the html form and placing them into variables that will be used to bind the parameters with the mysql query above
			$user_name = filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['name']))))), FILTER_SANITIZE_STRING);
			$user_pass = filter_var(trim(str_replace('"', '', stripcslashes(stripslashes(htmlspecialchars($_POST['password']))))), FILTER_SANITIZE_STRING);
			//-----------------------------------------------
			
			//Execute the query and fetch all the data that should be fetched from the database on the server
			$query->execute();
			$fetch_rows = $query->fetchAll();
			//------------------------------------------------
			
			//Check the number of rows from the result gotten in the fetch_rows variable
			if (count($fetch_rows) > 0 && count($fetch_rows) == 1){
				//Check if the user checked the remember me checkbox
				if (isset($_POST['set_cookie'])){
					//A random cookie id generator function to grant users unique cookie id's to store important credentials
					function cookie_id_generator($length){
						//A list of characters that can be used in our
						//cookie id.
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						//Create an empty variable which will be used to store the final generated cookie id to be used.
						$cookie_pin_id = '';
						//Get the index of the last character in our characters variable.
						$characterListLength = mb_strlen($characters, '8bit') - 1;
						//Loop from 1 to the length variable that was specified.
						foreach(range(1, $length) as $i){
							$cookie_pin_id .= $characters[random_int(0, $characterListLength)];
						}
						return $cookie_pin_id; //Return the final value of the cookie id
					}
					//-----------------------------------------------
					
					//A do while loop structure which is used to loop through all the rows 
					//in the cookie_table in our database finding related cookie id's
					//and then generating a new cookie id to make sure that there is no cookie id in the table related to the one being generated.
					do {
						$test_cookie = cookie_id_generator(18);
					
						$run_query = $conn->prepare("
							SELECT * FROM cookie_table
							WHERE cookieID = '$test_cookie';
						");
					
						$run_query->execute();
					
						$rows = $run_query->fetchAll();
					} while(count($rows) > 0);	
					//-------------------------------------------------
					
					//An sql query for inserting data into the cookie_table and then executing the query on the database
					$run_query = $conn->prepare("
						INSERT INTO cookie_table(cookieID, user_name, user_pass, date_rec) VALUES('$test_cookie', '$user_name', '$user_pass', NOW());
					");
					
					$run_query->execute();
					//---------------------------------------
					
					//Creating a cookie by giving it a key and a value
					$cookie_name = "id_unique"; //The Key
					$cookie_value = $test_cookie; //The Value
					//Finally setting the cookie
					setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 84600 = 1 day / 30 = 30 days
					//--------------------------------------------------
				}
				
				//Setting up sessions to store username, user password, user fullname and user message id
				$_SESSION['xyz_username'] = $user_name;
				$_SESSION['xyz_userpass'] = $user_pass;
				$_SESSION['xyz_fullname'] = $fetch_rows[0][1];
				$_SESSION['xyz_message_id'] = $fetch_rows[0][5];
				//---------------------------------------------------
				
				header("Location: profile_page.php"); //Directing the user after a successful login to the profile page
			} else {
				$success = "Login Unsuccessfull!"; //If no user was found in the database, Then print out an error message to the user
			}
		} else {
			$success = "Login Unsuccessfull!"; //If username and password is not set, Print out an error message to the user
		}
	  }
	?>
	<!-- The div container containing the html login form -->
    <div class="main">
        <section class="login">
            <div class="container">
                <div class="login-content">
				    <!-- The html form used for collecting login credentials -->
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="login-form" class="login-form">
                        <h2 style="margin-bottom: 16px;" class="form-title">Login to your account</h2>
						<p style="text-align: center; margin-top: 4px; color: red; margin-bottom: 12px;"><?php echo $success; ?></p>
						
						<!-- Username field -->
                        <div class="form-group">
                            <input type="text" class="form-input" name="name" id="name" placeholder="Username" required/>
							<p id="err_name"><?php echo $err_name; ?></p>
                        </div>
						<!-------------------->
						
						<!-- Password field -->
                        <div class="form-group">
                            <input type="password" class="form-input" name="password" id="password" placeholder="Password" required/>
                            <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
							<p id="err_pass"><?php echo $err_pass; ?></p>
                        </div>
						<!-------------------->
						
						<!-- Checkbox to create a cookie for the user -->
                        <div class="form-group">
                            <input type="checkbox" name="set_cookie" id="agree-term" class="agree-term" />
                            <label for="agree-term" class="label-agree-term"><span><span></span></span>Remember Me</label>
                        </div>
						<!-------------------->
						
						<!-- The submit button to accept the username and password -->
                        <div class="form-group">
                            <input type="submit" style="cursor: pointer;" name="submit" class="form-submit" value="Login"/>
                        </div>
						<!-------------------->
						
                    </form>
					<!------------------------------------------------------------->
					
					<!-- A link to the sign up page -->
                    <p class="loginhere" style="font-size: 14px;">
                        Don't have an account ? <a href="register.php" class="loginhere-link">Click here</a>
                    </p>
					<!-------------------------------->
					
					<!-- A link to retrieve username and password if forgotten -->
					<p class="loginhere" style="margin-top: 16px; font-size: 14px;">
                        Forgot username/password ? <a href="javascript:void(0)" id="myBtn" class="loginhere-link">Click here</a>
                    </p>
					<!-------------------------------------------------------->
					
                </div>
            </div>
        </section>
    </div>
	<!----------------------------------------------->
	
	<!-- Modal dropdown panel -->
	<div id="myModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
	  
	    <!-- Head of the modal dropdown panel -->
		<div class="modal-header">
		  <span class="close">&times;</span>
		  <h2 style="margin-bottom: 0px; font-size: 14px; font-weight: 233; text-align: left;">Forgot Username/Password <i class="fa fa-lock"></i></h2>
		</div>
		<!---------------------------------------->
		
		<!-- Body of the modal dropdown panel -->
		<div class="modal-body">
		  <div id="imp_msg">Enter your <b>email</b> below so that we can send you your password and username.</div>
		  <form method="POST" action="<?php echo htmlentities(htmlspecialchars($_SERVER['PHP_SELF'])); ?>">
		    <p id="err_snd_email" style="margin-top: 0; font-size: 13px; color: red;"></p>
		    <div>
			  <input type="email" id="email_input" name="user_email" placeholder="Your Email..." required/>
			</div>
			<input type="button" style="margin-bottom: 6px;" id="ripple" name="email_subt" value="GO" />
			<p id="tak_msg" style="font-size: 13px; margin-top: -6px; padding: 10px;"><b>Note:</b> Please be patient for the confirmation message after clicking the go button!</p>
		  </form>
		</div>
		<!---------------------------------------->
		
		<!-- Footer of the modal dropdown panel -->
		<div class="modal-footer">
		</div>
		<!---------------------------------------->
		
	  </div>
	</div>
	<!--------------------------->
	
	<!-- Javascript files to be used on the login page -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/js/login_page.js"></script>
	<!----------------------------------------------->
</body>
</html>