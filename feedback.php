<?php 
  session_start(); //Starting up sessions for the web application through the feedback page
  
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
<html lang="en-Us">
<head>
    <!-- All meta information for the feedback page -->
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
    <title>Anonymous-chat | Feedback</title> 
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

    <!-- The div container containing the html feedback form -->
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
				
					<!-- The html form used for collecting user feedback information -->
                    <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>" id="signup-form" class="signup-form">
                        <h2 style="margin-bottom: 16px;" class="form-title">User Feedback</h2>
                        <p style="font-size: 13px;">Help us improve by sending us feedback in order for us to know how to serve you better.</p>
						<p id="err_text" style="text-align: center; margin-top: 4px; color: red; margin-bottom: 20px; font-size: 13px;"></p>
						
						<!-- Fullname field -->
                        <div class="form-group">
                            <input type="text" class="form-input" name="fullname" id="fullname" placeholder="Your Name" required/>
                        </div>
						<!-------------------->
						
						<!-- Email field -->
						<div class="form-group">
                            <input type="email" class="form-input" name="user_email" id="username" placeholder="Your Email" required/>
                        </div>
						<!-------------------->
						
						<!-- Feedback field -->
						<div class="form-group">
                            <textarea class="form-input" name="feedback_info" placeholder="Feedback..." style="padding: 0px; height: 120px; padding-left: 20px; padding-right: 20px; padding-top: 10px;">
                            </textarea>
                        </div>
						<!-------------------->
						
						<!-- User must agree to terms and services -->
                        <div class="form-group">
                            <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                            <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree to all statements in  <a href="#" class="term-service">Terms of service</a></label>
                        </div>
						<!------------------------------------------->
						
						<!-- The submit button to accept feedback from the user -->
                        <div class="form-group">
                            <input type="submit" style="cursor: pointer;" name="submit_feedback" id="submit" class="form-submit" value="Send Feedback"/>
                        </div>
						<!----------------------------------------------------------------------->
						
                    </form>
					<!-------------------------------------------------------->
					
					<!-- A link to the login page -->
                    <p class="loginhere" style="font-size: 14px;">
                        Login to your account ? <a href="login.php" class="loginhere-link">Login here</a>
                    </p>
					<!------------------------------>
					
                </div>
            </div>
        </section>
    </div>
	<!------------------------------------------------------------------>

    <?php 
        if (isset($_POST['submit_feedback'])){
            if (isset($_POST['agree-term'])){
                if (isset($_POST['fullname']) && isset($_POST['user_email']) && isset($_POST['feedback_info'])){
                    $name_of_user = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['fullname'], FILTER_SANITIZE_STRING)))))));
                    $email_of_user = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['user_email'], FILTER_SANITIZE_STRING)))))));
                    $user_feedback = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($_POST['feedback_info'], FILTER_SANITIZE_STRING)))))));

                    if (strlen($name_of_user) >= 6){
                        if (filter_var($email_of_user, FILTER_VALIDATE_EMAIL)){
                            if (strlen($user_feedback) >= 10){
                                $query = $conn->prepare("
                                    INSERT INTO feedback_table(Name, Email, message, date_sent) VALUES(:name, :email, :msg, NOW());
                                ");

                                $query->bindParam(":name", $name_xyz);
                                $query->bindParam(":email", $email_xyz);
                                $query->bindParam(":msg", $msg_xyz);

                                $name_xyz = $name_of_user;
                                $email_xyz = $email_of_user;
                                $msg_xyz = $user_feedback;

                                if ($query->execute()){
                                    echo "
                                        <script>
                                            window.alert(\"Your feedback has been sent. Thank you!\");
                                        </script>
                                    ";
                                }
                            } else {
                                echo "
                                    <script>
                                        document.getElementById('err_text').innerHTML = \"* Feedback length must not be less than 10! *\";
                                    </script>
                                ";
                            }
                        } else {
                            echo "
                                <script>
                                    document.getElementById('err_text').innerHTML = \"* Invalid email! *\";
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                document.getElementById('err_text').innerHTML = \"* Name must be up to 6 characters or more! *\";
                            </script>
                        ";
                    }
                } else {
                    echo "
                        <script>
                            document.getElementById('err_text').innerHTML = \"* Input fields must not be empty! *\";
                        </script>
                    ";
                }
            } else {
                echo "
                    <script>
                        document.getElementById('err_text').innerHTML = \"* Must agree with terms and services! *\";
                    </script>
                ";
            }
        }
    ?>

	<!-- Javascript files to be used on the feedback page -->
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