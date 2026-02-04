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

    //Creating session variables storing values such as the admin username, password, fullname and profile image
    $admin_username = $_SESSION["admin_username"];
    $admin_password = $_SESSION["admin_password"];
    $admin_profile_img = $_SESSION["admin_profile_image"];
    $admin_fullname = $_SESSION['admin_fullname'];
    //-----------------------------------------------------------------
    
    //If user credentials are not set, Redirect the admin back to the login page
    if (!isset($admin_username) || !isset($admin_password) || !isset($admin_profile_img) || !isset($admin_fullname)){
        header("Location: admin_login.php"); //Redirect to login.php
    }
    //----------------------------------------------------

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
<head lang="en-Us">

    <!-- All meta information for the generated cookies page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http_equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Michael Alfred Afia">
    <meta name="description" content="Admin dashboard for Anonymous-chat.com">
    <meta name="keywords" content="admin, anonymous-chat, dashboard">
    <!------------------------------------------------------>

    <!-- Title of the web page -->
    <title>Anonymous-chat | Admin-dashboard</title>
    <!--------------------------->

    <!-- Css files to be used on the generated cookies page -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../admin_dashboard/css/admin_dashboard.css" rel="stylesheet">
    <link href="../css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/icofont/icofont.min.css" rel="stylesheet">
    <link href="../css/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../css/venobox/venobox.css" rel="stylesheet">
    <link href="../css/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/aos/aos.css" rel="stylesheet">
	<link href="../css/animate.css" rel="stylesheet">
	<link href="../css/materialize.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap_datatables/dataTables.bootstrap.min.css" rel="stylesheet">
    <link id="css-main" href="../css/codebase.min.css" rel="stylesheet">
    <link type="image/png" sizes="16x16" href="../images/Anonymous-chat.png" rel="icon">
    <!-------------------------------------------------------->    

</head>
<body>

    <?php 
        if (isset($_POST['logout_profile'])){
			//Write a query to delete the cookie id from the admin_cookie_table in the database
			$query_exec = $conn->prepare("
				DELETE FROM admin_cookie_table
				WHERE cookie_id = :cookie_val;
			");
            //-------------------------------------------------
            
            $query_exec->bindParam(":cookie_val", $cookie_value);

            $cookie_value = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_COOKIE["admin_id"], FILTER_SANITIZE_STRING))))))); //Store the value of the cookie in the $cookie_value variable
			
			$query_exec->execute(); //Execute the query above
            
            session_destroy();

			//Expire the cookies in the admins browser
			unset($_COOKIE['admin_id']);
			setcookie('admin_id', null, -1, '/');
            //-----------------------------------------

            header("Location: admin_login.php");
        }
    ?>

    <!-- Custom css styles for the web page -->
    <style>
        tbody tr td {
            vertical-align: middle !important;
        }
    </style>
    <!---------------------------------------->

    <!-- Page screen loader -->
	<div id="preloder">
      <div class="loader"></div>
    </div>
	<!------------------------>

    <!-- Mobile navigation toggle button -->
    <button type="button" class="mobile-nav-toggle d-xl-none">
        <i class="icofont-navigation-menu"></i>
    </button>
    <!------------------------------------->

    <!-- Side navigation panel -->
    <header id="side_navbar">
        <div class="d-flex flex-column">
            <div class="intro_txt">
                <img src="../admin_dashboard/admin_img/<?php echo $admin_profile_img; ?>" alt="" class="img-fluid rounded-circle">
                <h1 class="text-light"><a href="admin_profile.php"><?php echo $admin_fullname; ?></a></h1>
            </div>
            <nav class="navbar-menu">
                <ul>
                    <li><a id="lnk" href="admin_profile.php"><i class="si si-home" style="font-size: 20px;"></i> <span>Home</span></a></li>
                    <li><a id="lnk" href="subscribers.php"><i class="si si-note" style="font-size: 20px;"></i> <span>Subscribers</span></a></li>
                    <li><a id="lnk" href="visitors.php"><i class="si si-users" style="font-size: 20px;"></i> <span>Visitors</span></a></li>
                    <li><a id="lnk" href="mail_sent.php"><i class="si si-speech" style="font-size: 20px;"></i>Mails Sent</a></li>
                    <li><a id="lnk" href="registerd_users.php"><i class="si si-user" style="font-size: 20px;"></i> Registerd Users</a></li>
                    <li><a id="lnk" href="user_feedback.php"><i class="fa fa-comments-o" style="font-size: 20px;"></i> User Feedback</a></li>
                </ul>
            </nav>
            <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>
        </div>
    </header>
    <!--------------------------->

    <!-- Top horizontal navigation bar -->
    <div class="horizontal_nav">
        <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>">
            <span id="lg_1" style="font-weight: 700;">Log-out</span>
            <button name="logout_profile" type="submit" class="btn" id="log_out">
                <i class="fa fa-sign-out"></i>
            </button>
            <span id="lg_2" style="font-weight: 700;">Log-out</span>
        </form>
    </div>
    <!--------------------------------------->

    <!-- Main content body -->
    <main id="main-body_content">
        <section>
            <div class="container">
                <div>
                    <h2 style="font-size: 1.9rem; font-weight: 600;">
                        Total Generated Cookies:
                        <span style="color: #149ddd;">
                            <?php 
                                //Sql query to select all the records from the cookie table in the database
                                $query = $conn->prepare("
                                    SELECT * FROM cookie_table;
                                ");
                                //---------------------------------

                                $query->execute(); //Execute the query

                                $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                echo count($no_of_rows); //Get the count of all the rows in the table
                            ?>
                        </span>
                    </h2>
                    <p>The table below contains information about all the cookies generated for different users on the system service.</p>
                </div>
                <div class="table_view_error">
                    <div style="text-align: center;"><i class="fa fa-frown-o" style="font-size: 6.7em;"></i></div>
                    Sorry, But this table cannot be displayed on your mobile screen because of its small size.
                    <p><b>Solution:</b> Try accessing the website from a laptop or change the screen orientation of your device to landscape in order to view the table.</p>
                </div>
                <div class="row" id="cont_table_display">
                    <div class="col-lg-12" style="background: #fff; padding: 19px;">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th>Cookie ID</th>
                                    <th>Date Generated</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    //An sql query to select all the records in the cookie table
                                    $query = $conn->prepare("
                                        SELECT * FROM cookie_table;
                                    ");
                                    //------------------------------------------

                                    $query->execute(); //Execute the sql query

                                    $result = $query->fetchAll(); //Fetch all the data from the table

                                    //Render the data in html table form
                                    foreach ($result as $rows){
                                        $table_id = $rows[0]; //Table id
                                        $user_name = $rows[2]; //User name
                                        $cookie_id = $rows[1]; //Cookie id
                                        $date_recorded = $rows[4]; //Date cookie id was created

                                        echo "<tr><td>$table_id</td><td>$user_name</td><td>$cookie_id</td><td>$date_recorded</td><td><a href=\"delete_cookies.php?id=$table_id\" class=\"btn\" id=\"del_btn\"><span id=\"del_cont\"><i class=\"fa fa-trash-o\" id=\"del_btn_icon\"></i> Delete</span></a></td></tr>";
                                    }
                                    //------------------------------------
                                ?>
                            </tbody>
                        </table>
                        <div>
                            <a href="delete_all_cookies.php" class="btn" id="del_all_btn"><span id="del_all_btn_txt">Delete all cookies</span></a>
                        </div>         
                    </div>    
                </div>    
            </div>    
        </section>
    </main>
    <!-------------------------->

    <!-- Web page footer -->
    <footer id="page_footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Anonymous-Chat</span></strong>
            </div>
        </div>
    </footer>
    <!---------------------->

    <!-- Javascript files used for the generated cookies page -->
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/datatables/jquery.dataTables.min.js"></script>
    <script src="../admin_dashboard/js/main.js"></script>
    <script src="../admin_dashboard/js/bootstrap.min.js"></script>
    <script type="text/javascript">
	  $(document).ready(function(){
        $('#example').DataTable();
        
		setTimeout(function(){
			$("#preloder").fadeOut();
			$(".loader").fadeOut();
		}, 2000);
	  });
    </script>
    <!-------------------------------------------------------->

</body>