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
?>
<!DOCTYPE html>
<head lang="en-Us">

    <!-- All meta information for the delete all visitors page -->
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

    <!-- Css files to be used on the delete all visitors page -->
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
                <h1 class="text-light"><a href="index.html"><?php echo $admin_fullname; ?></a></h1>
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
                    <h2 style="font-size: 1.9rem; font-weight: 600;">Delete All Visitors</h2>
                    <p>You are now about to delete all visitors.<br><b>Do you still want to go ahead with this process ?</b></p>
                    <p>
                        <form method="POST" action="<?php echo htmlentities(htmlspecialchars($_SERVER['PHP_SELF'])); ?>">
                            <button type="submit" class="btn" name="del_all_vis"><I class="fa fa-check"></i> YES</button>
                        </form>
                        <a style="margin-top: 12px;" href="admin_profile.php" class="btn"><I class="bx bx-arrow-back"></i> <span style="position: relative; top: -3px;">NO</span></a>
                    </p>
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

    <?php 
        if (isset($_POST['del_all_vis'])){
            $query = $conn->prepare("
                DELETE FROM visitor_table;
            ");

            if ($query->execute()){
                $query = $conn->prepare("
                    UPDATE no_of_visitors
                    SET total_visitors = 0
                    WHERE id = 1;
                ");

                if ($query->execute()){
                    $query = $conn->prepare("
                        ALTER TABLE visitor_table AUTO_INCREMENT = 0;
                    ");

                    if ($query->execute()){
                        echo "
                            <script>
                                document.write(\"<title>Anonymous-chat | Admin-dashboard</title> <link type='image/png' sizes='16x16' href='../images/Anonymous-chat.png\' rel='icon'>\");
                                window.alert(\"All visitors deleted successfully!\");
                                window.location.href = \"admin_profile.php\";
                            </script>
                        ";
                    }
                }
            }
        }
    ?>

    <!-- Javascript files used for the delete all visitors page -->
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