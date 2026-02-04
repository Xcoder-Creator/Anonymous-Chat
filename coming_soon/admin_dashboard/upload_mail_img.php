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
    if (!isset($_SESSION["admin_username"]) || !isset($_SESSION["admin_password"]) || !isset($_SESSION["admin_profile_image"]) || !isset($_SESSION["admin_fullname"])){
        header("Location: admin_login.php"); //Redirect to login.php
    } else {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            error_reporting(0);

            if(is_array($_FILES)) {
                $errors;
                $file_name = $_FILES['image_upload']['name'];
                $file_size = $_FILES['image_upload']['size'];
                $file_tmp = $_FILES['image_upload']['tmp_name'];
                $file_type = $_FILES['image_upload']['type'];
                $file_ext = strtolower(end(explode('.',$_FILES['image_upload']['name'])));
                
                $extensions= array("jpg","png", "jpeg");
                
                if(in_array($file_ext,$extensions)=== false){
                    $errors = "
                        <script>
                            $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-danger alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error! </strong>This is not an image, Please choose a JPEG or PNG file.</div>');
                            $('html, body').animate({
                                scrollTop: 0
                            }, 678);
                            $('#url_field').val('');
                        </script>
                    ";
                }
                
                if($file_size > 2097152){
                    $errors = "
                        <script>
                            $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-danger alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error! </strong>Image size must not be greater than 2 MB.</div>');
                            $('html, body').animate({
                                scrollTop: 0
                            }, 678);
                            $('#url_field').val('');
                        </script>
                    ";
                }
                
                if(empty($errors)==true){
                    if (file_exists("images/" . $file_name)){
                        echo "
                            <script>
                                $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-danger alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Error! </strong>Image already exists.</div>');
                                $('html, body').animate({
                                    scrollTop: 0
                                }, 678);
                                $('#url_field').val('');
                            </script>
                        ";
                    } else {
                        move_uploaded_file($file_tmp, "mail_img/".$file_name);
                        echo "
                            <script>
                                $('#data_copy').attr('data-clipboard-text', 'http://localhost/Coming%20soon%20Anonymous-Chat/admin_dashboard/mail_img/$file_name');
                                $('#url_field').val('http://localhost/Coming%20soon%20Anonymous-Chat/admin_dashboard/mail_img/$file_name');
                                $('html, body').animate({
                                    scrollTop: 0
                                }, 678);
                            </script>
                        ";
                        echo "
                            <script>
                                $('#err_txt').html('<div id=\"pop_up_alert\" class=\"alert alert-success alert-dismissible\"><a href=\"javascript:void(0)\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><strong>Success! </strong>Image Upload Success.</div>');
                                $('html, body').animate({
                                    scrollTop: 0
                                }, 678);
                            </script>
                        ";
                    }
                } else{
                    print_r($errors);
                }
            } else {
                echo "Not Allowed!";
            }    
        } else {
            echo "<h1>Not Working!</h1>";
        }
    }
    //----------------------------------------------------
?>