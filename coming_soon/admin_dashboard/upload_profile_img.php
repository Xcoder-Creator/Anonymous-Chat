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
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == 'http://localhost/Coming%20soon%20Anonymous-Chat/admin_dashboard/admin_profile.php' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            error_reporting(0);

            if(is_array($_FILES)) {
                $errors;
                $file_name = $_FILES['profile_img']['name'];
                $file_size =$_FILES['profile_img']['size'];
                $file_tmp =$_FILES['profile_img']['tmp_name'];
                $file_type=$_FILES['profile_img']['type'];
                $file_ext=strtolower(end(explode('.',$_FILES['profile_img']['name'])));
                
                $extensions= array("jpg","png", "jpeg");
                
                if(in_array($file_ext,$extensions)=== false){
                    $errors = "<span style=\"color: red;\">* Error Occured! *</span>";
                }
                
                if($file_size > 2097152){
                    $errors = "<span style=\"color: red;\">* Error Occured! *</span>";
                }

                if(strlen($file_name) >= 15){
                    $errors = "<span style=\"color: red;\">* Error Occured! *</span>";
                }
                
                if(empty($errors)==true){
                    if (file_exists("images/" . $file_name)){
                        echo "<span style=\"color: red;\">* File already exists! *</span>";
                    } else {
                        move_uploaded_file($file_tmp, "admin_img/".$file_name);

                        $query = $conn->prepare("
                            SELECT * FROM admin_details
                            WHERE admin_username = :username AND admin_password = :password;
                        ");

                        $query->bindParam(":username", $xyz_username);
                        $query->bindParam(":password", $xyz_password);

                        $xyz_username = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($admin_username, FILTER_SANITIZE_STRING)))))));
                        $xyz_password = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($admin_password, FILTER_SANITIZE_STRING)))))));

                        $query->execute();

                        $fetch_data = $query->fetchAll();

                        if (count($fetch_data) > 0 && count($fetch_data) == 1){
                            $query = $conn->prepare("
                                UPDATE admin_details
                                SET admin_profile_img = :file_name
                                WHERE admin_username = '$xyz_username' AND admin_password = '$xyz_password';
                            ");

                            $query->bindParam(":file_name", $name_of_image);

                            $name_of_image = str_replace('"', "", trim(stripcslashes(stripslashes(htmlspecialchars(htmlentities(filter_var($file_name, FILTER_SANITIZE_STRING)))))));

                            if ($query->execute()){
                                $_SESSION["admin_profile_image"] = $name_of_image;
                                echo "<span style=\"color: green;\">* Upload Successfull! *</span>";
                            }
                        }
                    }
                } else{
                    print_r($errors);
                }
            } else {
                echo "Not Allowed!";
            }    
        }
    }
    //----------------------------------------------------
?>