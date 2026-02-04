<?php 
  session_start(); //Starting up sessions for the web application through the website error page
  
  //Check if the session is set
  if (isset($_SESSION['load_err'])){
	unset($_SESSION['load_err']); //Destroy the session, if the session is set  
  } else {
	header("Location: index.php"); //Redirect the user to index.php  
  }
  //------------------------
?>
<p style="font-size: 21px;"><b>This website might be down or experiencing technical difficulties. Please try again later!</b></p>