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

    <!-- All meta information for the admin profile page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http_equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Michael Alfred Afia">
    <meta name="description" content="Admin dashboard for Anonymous-chat.com">
    <meta name="keywords" content="admin, anonymous-chat, dashboard">
    <!------------------------------------------------------>

    <!-- Title of the web page -->
    <title>Anonymous-chat | Admin-dashboard(<?php echo $admin_fullname; ?>)</title>
    <!--------------------------->

    <!-- Css files to be used on the admin profile page -->
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
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" id="css-main" href="../css/codebase.min.css">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/Anonymous-chat.png">
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
                    <li class="active"><a id="lnk" href="index.php"><i class="si si-home" style="font-size: 20px;"></i> <span>Home</span></a></li>
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
            <button type="submit" name="logout_profile" class="btn" id="log_out">
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
                    <h2 style="font-size: 1.9rem; font-weight: 600;">Welcome <?php echo $admin_fullname; ?></h2>
                    <p>This is your personal admin dashboard panel for anonymous-chat. Look below to find out the activites that can be performed.</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                            <a class="block text-center" href="subscribers.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to select all the records from the subscription_lin table in the database
                                            $query = $conn->prepare("
                                                SELECT * FROM subscription_line;
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                            echo count($no_of_rows); //Get the count of all the rows in the table
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="si si-note fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total Subscribers</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="visitors.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to get the count of all visitors in the database
                                            $query = $conn->prepare("
                                                SELECT COUNT(*) FROM visitor_table
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $total_visitors = $query->fetchColumn(); //Total visitors
                                            
                                            if ($total_visitors > 0){
                                                echo $total_visitors;
                                            } else {
                                                echo 0;
                                            }
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="si si-users fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total Visitors</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="mail_sent.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to select all the records from the subscription_lin table in the database
                                            $query = $conn->prepare("
                                                SELECT No_of_mail_sent FROM no_mails_sent;
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                            if (count($no_of_rows) == 0){
                                                echo "0";
                                            } else {
                                                $query = $conn->prepare("
                                                    SELECT SUM(No_of_mail_sent) FROM no_mails_sent;
                                                ");
                                                //---------------------------------

                                                $query->execute(); //Execute the query

                                                $rows = $query->fetchAll();

                                                echo $rows[0][0];
                                            }
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="si si-speech fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total Mails Sent</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="registerd_users.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to select all the records from the user details table in the database
                                            $query = $conn->prepare("
                                                SELECT * FROM user_details;
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                            echo count($no_of_rows); //Get the count of all the rows in the table
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="si si-user fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total Registerd Users</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="generated_cookies.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to select all the records from the user details table in the database
                                            $query = $conn->prepare("
                                                SELECT * FROM cookie_table;
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                            echo count($no_of_rows); //Get the count of all the rows in the table
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="bx bx-cookie fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total Generated Cookies</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="change_username_and_password.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">+</div>
                                    <p class="mt-5">
                                        <i class="bx bx-lock fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Change Username/Password</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" id="modal_open" style="cursor: pointer;" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">+</div>
                                    <p class="mt-5">
                                        <i class="bx bx-cloud-upload fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Upload Profile Image</p>
                                </div>
                            </a>
                    </div>
                    <div class="col-lg-4">
                            <a class="block text-center" href="user_feedback.php" style="text-decoration: none;">
                                <div class="block-content ribbon ribbon-bookmark ribbon-crystal ribbon-left bg-gd-dusk">
                                    <div class="ribbon-box">
                                        <?php 
                                            //Sql query to select all the records from the user details table in the database
                                            $query = $conn->prepare("
                                                SELECT * FROM feedback_table;
                                            ");
                                            //---------------------------------

                                            $query->execute(); //Execute the query

                                            $no_of_rows = $query->fetchAll(); //Fetch all the data from the table

                                            echo count($no_of_rows); //Get the count of all the rows in the table
                                        ?>
                                    </div>
                                    <p class="mt-5">
                                        <i class="fa fa-comments-o fa-3x text-white-op"></i>
                                    </p>
                                    <p class="font-w600 text-white">Total User Feedbacks</p>
                                </div>
                            </a>
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

    <!-- Back to top button -->
    <a href="javascript:void(0)" class="back-to-top" style="display: inline;">
        <i class="icofont-simple-up"></i>
    </a>
    <!------------------------->

    <!-- Modal dropdown system -->
    <div class="modal_container">
        <div class="dropdown_modal">
            <div class="modal_head">UPLOAD PROFILE IMAGE &nbsp;<i style="position: relative; top: 3px; font-size: 1.3em;" class="bx bx-image"></i></div>
            <div class="modal_body">
                <p style="border-bottom-width: 1px; border-bottom-color: #e9ecef; border-bottom-style: solid; padding-bottom: 10px; text-align: left; margin-bottom: 18px;">Select the image of your choice to upload below:</p>
                <p id="txt_img_upload"></p>
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    <input id="img_upl_field" name="profile_img" type="file" />
                    <p style="border-top-width: 1px; border-top-color: #e9ecef; border-top-style: solid; padding-top: 16px; margin-top: 18px;">
                      <button type="submit" name="profile_img_upload" class="btn" id="img_upl_btn"><i class="bx bx-cloud-upload"></i>&nbsp;<span style="position: relative; top: -3px; font-size: 0.9em;">Upload</span></button>
                      &nbsp; <button type="button" class="btn" id="modal_close"><i class="bx bx-window-close"></i>&nbsp;<span style="position: relative; top: -3px; font-size: 0.9em;">Close</span></button>
                    </p>
                </form>
            </div>
            <div class="modal_foot"></div>
        </div>
    </div>
    <!------------------------------>

    <!-- Javascript files used for the admin profile page -->
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../admin_dashboard/js/main.js"></script>
    <script src="../admin_dashboard/js/bootstrap.min.js"></script>
    <script type="text/javascript">
	  $(document).ready(function(){
		setTimeout(function(){
			$("#preloder").fadeOut();
			$(".loader").fadeOut();
		}, 2000);

        $("#uploadForm").on('submit',(function(e) {
            e.preventDefault();
            $.ajax({
                url: "upload_profile_img.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    $("#txt_img_upload").html(data);
                },
                error: function(){
                } 	        
            });
        }));
	  });
    </script>
    <!-------------------------------------------------------->

</body>