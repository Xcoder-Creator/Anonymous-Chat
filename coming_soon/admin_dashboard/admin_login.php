<?php 
    session_start();

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
	  header("Location: ../error_pages/website_error.php"); //Redirect to website_error.php
	}
    //------------------------------
    
    $err_msg = "";

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
    
    if (isset($_SESSION["feedback_id"])){
        unset($_SESSION["feedback_id"]);
    }

    //Checking for cookies on the admin browser
    if (isset($_COOKIE["admin_id"])){
        $exec_run = $conn->prepare("
            SELECT * FROM admin_cookie_table
            WHERE cookie_id = :cookie_v;
        ");
        
        $exec_run->bindParam(":cookie_v", $catch_cookie_val);
        $catch_cookie_val = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_COOKIE["admin_id"], FILTER_SANITIZE_STRING)))))));
        
        $exec_run->execute();
        
        $array = $exec_run->fetchAll();
        
        if (count($array) > 0 && count($array) == 1){
            $auth_admin_name = $array[0][2]; //Grabing the username found on the admin cookie table and storing it in the auth_admin_name variable
            $auth_admin_pass = $array[0][3]; //Grabing the password found on the admin cookie table and storing it in the auth_admin_pass variable
            
            $exec_run = $conn->prepare("
                SELECT * FROM admin_details
                WHERE admin_username = '$auth_admin_name' AND admin_password = '$auth_admin_pass';
            ");
            
            $exec_run->execute();
            
            $list_row = $exec_run->fetchAll();
            
            if (count($list_row) > 0 && count($list_row) == 1){
                //Creating session variables storing values such as the admins username, password, profile image and fullname
                $_SESSION['admin_username'] = $list_row[0][1];
                $_SESSION['admin_password'] = $list_row[0][2];
                $_SESSION['admin_profile_image'] = $list_row[0][3];
                $_SESSION['admin_fullname'] = $list_row[0][4];
                header("Location: admin_profile.php"); //After creating sessions, Redirect the admin to the admin profile page
                //--------------------------------------------------------------------
            }
        }     
    }
?>
<!DOCTYPE html>
<html lang="en-Us">
    <head>

        <!-- All meta information for the admin login page -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta http_equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="Michael Alfred Afia">
        <meta name="description" content="Admin dashboard for Anonymous-chat.com">
        <meta name="keywords" content="admin, anonymous-chat, dashboard">
        <!---------------------------------------------------->

        <!-- Title of the web page -->
        <title>Anonymous-chat | Admin-login</title>
        <!-------------------->

        <!-- Css files to be used on the admin login page -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../admin_dashboard/css/admin_dashboard.css" rel="stylesheet">
        <link href="../css/icofont/icofont.min.css" rel="stylesheet">
        <link href="../css/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../css/venobox/venobox.css" rel="stylesheet">
        <link href="../css/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="../css/aos/aos.css" rel="stylesheet">
        <link href="../css/animate.css" rel="stylesheet">
        <link href="../css/util.css" rel="stylesheet">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link id="css-main" href="../css/codebase.min.css" rel="stylesheet">
        <link type="image/png" sizes="16x16" href="../images/Anonymous-chat.png" rel="icon">
        <!--------------------------------------------------------------------------->

    </head>
    <body>

        <?php 
            if (isset($_POST['login_submit'])){
                $admin_username = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['admin_username'], FILTER_SANITIZE_STRING))))))); //Admin username
                $admin_password = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['admin_password'], FILTER_SANITIZE_STRING))))))); //Admin password
                
                $query = $conn->prepare("
                    SELECT * FROM admin_details 
                    WHERE admin_username = :username AND admin_password = :password;
                ");

                $query->bindParam(":username", $username);
                $query->bindParam(":password", $password);

                $username = $admin_username;
                $password = $admin_password;

                $query->execute();

                $result = $query->fetchAll();

                if (count($result) > 0 && count($result) == 1){
                    $_SESSION["admin_username"] = $result[0][1];
                    $_SESSION["admin_password"] = $result[0][2];
                    $_SESSION["admin_profile_image"] = $result[0][3];
                    $_SESSION['admin_fullname'] = $result[0][4];

                    //A random cookie id generator function to grant the admin a unique cookie id to store important credentials
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
					//in the admin_cookie_table in the database finding related cookie id's
					//and then generating a new cookie id to make sure that there is no cookie id in the table related to the one being generated.
					do {
						$test_cookie = cookie_id_generator(18);
					
						$run_query = $conn->prepare("
							SELECT * FROM admin_cookie_table
							WHERE cookie_id = '$test_cookie';
						");
					
						$run_query->execute();
					
						$rows = $run_query->fetchAll();
					} while(count($rows) > 0);	
					//-------------------------------------------------
					
					//An sql query for inserting data into the admin_cookie_table and then executing the query on the database
					$run_query = $conn->prepare("
						INSERT INTO admin_cookie_table(cookie_id, admin_username, admin_password, date_created) VALUES('$test_cookie', '$username', '$password', NOW());
					");
					
					$run_query->execute();
					//---------------------------------------
					
					//Creating a cookie by giving it a key and a value
					$cookie_name = "admin_id"; //The Key
					$cookie_value = $test_cookie; //The Value
					//Finally setting the cookie
					setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 84600 = 1 day / 30 = 30 days
					//--------------------------------------------------

                    header("Location: admin_profile.php");
                } else {
                    $err_msg = "* Authentificaton Failed! *";
                }
            }
        ?>

        <!-- Main contents of the admin login page -->
        <div class="admin_login_container">
            <div class="login-content" style="background: #f7f7f7;">
                <div class="form_container p-l-55 p-r-55 p-t-65 p-b-50">
                    <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>" class="admin_login_form">
                        <div style="text-align: center;">
                        <img style="width: 28px;" src="../Images/anonymous-chat.png" alt="Logo"></img>
                        </div>
                        <span class="admin_login_form-title p-b-10">
                            Admin-Dashboard
                        </span>
                        <p style="text-align: center; margin-bottom: 14px; color: red; font-size: 0.9em;"><?php echo $err_msg; ?></p>

                        <!-- Username input field -->
                        <div class="input_field tp_border ps_rel">
                            <input class="inp_element" type="text" name="admin_username" placeholder="Enter Username..." style="border-color: #999999; border-width: 1px; border-style: solid; margin-bottom: 0px;" required />
                            <span class="focus_inp"></span>
                            <span class="focus_inp_2"></span>
                        </div>
                        <!----------------------------->

                        <!-- Password input field -->
                        <div class="input_field tp_border ps_rel" style="margin-top: 6px;">
                            <input class="inp_element" type="password" name="admin_password" placeholder="Enter Password..." style="border-color: #999999; border-width: 1px; border-style: solid; margin-bottom: 0px;" required />
                            <span class="focus_inp"></span>
                            <span class="focus_inp_2"></span>
                        </div>
                        <!------------------------------>

                        <div class="submit-btn_area m-t-19">
                            <button type="submit" name="login_submit" class="submit_btn" style="background: #333;">
                                Authenticate
                            </button>
                        </div>

                        <!-- Return to homepage -->
                        <div class="text-center p-t-30">
                            <span>
                                Go back to
                            </span>
                            <a href="../index.php">
                                Homepage
                            </a>
                        </div>
                        <!------------------------->

                    </form>
                </div>
            </div>
        </div>
        <!------------------------------------------------->

    </body>
</html>