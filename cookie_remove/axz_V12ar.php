<?php
  session_start(); //Starting up sessions for the web application through the axz_V12ar.php page
  
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
	
	//Redirect to website_error.php
	echo "
	  <script>
	    window.location.href = 'website_error.php';
	  </script>
	";
	//-----------------------------
  }
  //--------------------------------
  
  //Check if the cookies are set
  if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://localhost/www.anonymous-chat.com/profile_page.php' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){ 
	if (isset($_POST['ajx_65']) && $_POST['ajx_65'] == 'qwe45y'){
		session_destroy(); //Destroy the entire session
		
		//If the cookie is set, then check if the name of the cookie is 'id_unique'
		if (isset($_COOKIE["id_unique"])){
			$cookie_value = $_COOKIE["id_unique"]; //Store the value of the cookie in the $cookie_value variable
			
			//Write a query to delete the cookie id from the cookie_table in the database
			$query_exec = $conn->prepare("
				DELETE FROM cookie_table
				WHERE cookieID = '$cookie_value';
			");
			//-------------------------------------------------
			
			$query_exec->execute(); //Execute the query above
			
			//Expire the cookies in the users browser
			unset($_COOKIE['id_unique']);
			setcookie('id_unique', null, -1, '/');
			//-----------------------------------------
		}
		//---------------------------------------------------------------
		
		//Take the user to the login page
		echo "
		  <script>
			window.location.href = 'login.php';
		  </script>
		";
		//---------------------------------
	 } else {
	   //If the cookie is not set, take the user to the login page
	   echo "
		  <script>
			window.location.href = 'login.php';
		  </script>
	   ";
	   //------------------------------------------------
	 }
	 //-----------------------------------------------
  } else { 
	echo "<title>Error Page</title>";
	echo '<link rel="icon" type="image/png" sizes="16x16" href="../images/anonymous-message.png">'; 
	echo "
	  <script>
	    document.write('Not allowed!');
	  </script>
	";  
  }
?>