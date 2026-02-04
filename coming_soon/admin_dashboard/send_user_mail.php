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

    //SMTP functions to be used in sending mail in PHPMailer0
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

    if (isset($_GET['id'])){
        $_SESSION["user_mail_id"] = $_GET['id'];
    }

    if (!isset($_SESSION["user_mail_id"])){
        header("Location: admin_login.php"); //Redirect to admin_login.php
    } else {
        //Sql query to find a record in the subscription_line table that matches up with the id in the url
        $query = $conn->prepare("
            SELECT * FROM no_mails_sent
            WHERE id = :id;
        ");
        //--------------------------------------------------

        $query->bindParam(":id", $id); 

        $id = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_SESSION["user_mail_id"], FILTER_SANITIZE_STRING)))))));

        $query->execute(); //Execute the sql query

        $rows = $query->fetchAll(); //Fetch all the data from the database

        if (count($rows) == 0){
            unset($_SESSION["user_mail_id"]);
            echo "
                <script>
                  document.write(\" \");
                  window.alert(\"No such record found!\");
                  window.location.href = \"admin_profile.php\";
                </script>
            ";
        } else if (count($rows) > 0 && count($rows) == 1) {
            $id_user_name = $rows[0][1];
            $id_user_email = $rows[0][2];
        }
    }
?>
<!DOCTYPE html>
<head lang="en-Us">

    <!-- All meta information for the send user mail page -->
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

    <!-- Css files to be used on the send user mail page -->
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
    <script src="../js/jquery-3.5.1.js"></script>
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
                    <h2 style="font-size: 1.9rem; font-weight: 600;">Send Mail To A User</h2>
                    <p>Using the text area below, You can compose mails and send them to users.</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="err_txt"></div>
                        <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>">
                            <textarea name="mail_text" id="editor1" rows="10" cols="80">
                                <?php
                                   if(isset($_POST['mail_text'])){
                                      echo $_POST['mail_text'];
                                   } else {
                                      echo "Compose your mail here....";
                                   }   
                                ?>
                            </textarea>
                            <button class="btn" type="submit" name="send_mail" style="margin-top: 20px;"><i class="fa fa-mail-forward"></i> SEND</button>
                        </form>
                        <div id="image_upload_element">
                            <p style="margin-bottom: 5px;"><b>You can upload images that you wish to use in the mail that you are composing below: </b></p>
                            <form id="uploadForm" method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>">
                                <input id="img_upl_field" type="file" name="image_upload" />
                                <button class="btn" type="submit" name="img_upload" style="margin-top: 20px;"><i class="bx bx-cloud-upload"></i> <span style="position: relative; top: -3px;">UPLOAD</span></button>
                            </form>
                            <div style="margin-top: 20px;">
                                <input id="url_field" type="text" value="" placeholder="Url Empty!" />
                                <br>
                                <button class="btn" id="data_copy" data-clipboard-demo="" data-clipboard-action="copy" data-clipboard-text="" type="button" style="margin-top: 4px;"><i class="fa fa-copy"></i> COPY URL</button>
                            </div>
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

    <?php 
        if (isset($_POST['send_mail'])){
            if (isset($_POST['mail_text']) && strlen($_POST['mail_text']) >= 60){
                $mail_content = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['mail_text'], FILTER_SANITIZE_STRING)))))));
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
                    $mail->addAddress("$id_user_email", "$id_user_name"); //The users name and email address
                    $mail->addReplyTo('anonymouschat456@gmail.com', 'Anonymous-chat'); // to set the reply to
                    //---------------------------------

                    //Setting the email content
                    $mail->IsHTML(true); //Setting up the mail to be of an html format
                    $mail->Subject = "Anonymous-chat | Daily"; //The subject of the mail to be sent
                    //-------------------------------
                    
                    //Body of the html mail to be sent to the user
                    $mail->Body = "
                      <!DOCTYPE html>
                      <html>
                          <head>
                            <meta charset=\"UTF-8\">
                            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=no\">
                            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, shrink-to-fit=no\">
                            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                            <meta name=\"author\" content=\"Michael Alfred\">
                            <meta name=\"description\" content=\"Anonymous-chat is an online anonymous messaging system and its coming soon!\">
                            <meta name=\"keywords\" content=\"chat, anonymous-chat, message, ping\">
                            <title>Anonymous-chat | Welcome</title>
                            <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"http://anonymous-chat.6te.net/Images/anonymous-chat.png\">
                          </head>
                          <body style=\"background-color: #fff; padding-top: 60px; padding-bottom: 0px;\">
                            $mail_content
                            <div style=\"text-align: center; margin-top: 36px;\">
                              <img style=\"width: 26px;\" src=\"http://anonymous-chat.6te.net/Images/FB.png\">
                              <img style=\"width: 26px; margin-left: 20px;\" src=\"http://anonymous-chat.6te.net/Images/twitter.png\">
                              <img style=\"width: 26px; margin-left: 20px;\" src=\"http://anonymous-chat.6te.net/Images/instagram.png\">
                            </div>
                            <p style=\"text-align: center; font-size: 12px; padding-left: 10px; padding-right: 10px;\">Registered under the platform brinx_S.A. 2020 All Rights Reserved By Anonymous-chat.6te.net</p>
                            <div style=\"width: 100%; padding: 3px 10px; box-sizing: border-box; font-size: 14px; font-weight: 700; color: #fff; text-transform: uppercase; border: none; margin-top: 17px; border-radius: 100px; background-image: -moz-linear-gradient(to left,#74ebd5,#9face6); background-image: -ms-linear-gradient(to left,#74ebd5,#9face6); background-image: -o-linear-gradient(to left,#74ebd5,#9face6); background-image: -webkit-linear-gradient(to left,#74ebd5,#9face6); background-image: linear-gradient(to left,#74ebd5,#9face6);\"></div>
                          </body>
                      </html>
                    ";
                    //-----------------------------------------------------
                    
                    $mail->AltBody = 'Please make use of a compatible HTML mail viewer to read the contents of this mail!'; //Alternate body to replace the main body above if a non html mail viewer is used to access the sent mail
                    
                    $mail->send(); //Send the prepared mail to the user

                    echo "
                        <script>
                            $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-success alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Success! </strong>Your mail has been sent successfully.</div>');
                        </script>
                    ";
                } catch (Exception $e) {
                    echo "
                        <script>
                            $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-warning alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error! </strong>Couldn\'t send mail, Check your network.</div>');
                        </script>
                    ";
                }	
            } else {
                echo "
                    <script>
                        $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-danger alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error! </strong>Mail is to small in length to be sent.</div>');
                    </script>
                ";
            }
        }
    ?>

    <!-- Javascript files used for the send_user_mail page -->
    <script src="../js/datatables/jquery.dataTables.min.js"></script>
    <script src="../admin_dashboard/js/main.js"></script>
    <script src="../admin_dashboard/js/bootstrap.min.js"></script>
    <script src="js/ckeditor.js"></script>
    <script src="../js/clipboard.min.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'editor1' )
    </script>
    <script type="text/javascript">
	  $(document).ready(function(){
        $('#example').DataTable();
        
		setTimeout(function(){
			$("#preloder").fadeOut();
			$(".loader").fadeOut();
        }, 2000);

        var clipboardDemos = new Clipboard('[data-clipboard-demo]');

		clipboardDemos.on('success', function(e) {
			e.clearSelection();
			alert("url lnk copied!");
		});

		clipboardDemos.on('error', function(e) {
			console.error('Action:', e.action);
			console.error('Trigger:', e.trigger);
		});

        $("#uploadForm").on('submit',(function(e) {
            console.log("Working!");
            e.preventDefault();
            $.ajax({
                url: "upload_mail_img.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                    console.log(data);
                    $("#page_footer").after(data);
                },
                error: function(){
                } 	        
            });
        }));
	  });
    </script>
    <!-------------------------------------------------------->

</body>