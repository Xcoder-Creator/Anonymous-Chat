<?php 
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
    
    header("Location: admin_login.php");
?>