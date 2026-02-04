<!DOCTYPE html>
<head lang="en-Us">

    <!-- All meta information for the respond to all user feedback page -->
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

    <!-- Css files to be used on the respond to all user feedback page -->
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
                <img src="../admin_dashboard/admin_img/profile-img.jpg" alt="" class="img-fluid rounded-circle">
                <h1 class="text-light"><a href="index.html">Alex Smith</a></h1>
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
            <button type="submit" class="btn" id="log_out">
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
                    <h2 style="font-size: 1.9rem; font-weight: 600;">Respond To All User Feedbacks</h2>
                    <p>Using the text area below, You can compose responses and send them to all users.</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>">
                            <textarea name="response_text" id="editor1" rows="10" cols="80">
                                Compose your response here...
                            </textarea>
                            <button class="btn" type="submit" name="send_response" style="margin-top: 20px;"><i class="fa fa-mail-forward"></i> SEND</button>
                        </form>
                        <div id="image_upload_element">
                            <p style="margin-bottom: 5px;"><b>You can upload images that you wish to use in the response that you are composing below: </b></p>
                            <form method="POST" action="<?php echo htmlspecialchars(htmlentities($_SERVER['PHP_SELF'])); ?>">
                                <input id="img_upl_field" type="file" name="image_upload" />
                                <button class="btn" type="submit" name="send_image" style="margin-top: 20px;"><i class="bx bx-cloud-upload"></i> <span style="position: relative; top: -3px;">UPLOAD</span></button>
                            </form>
                            <div style="margin-top: 20px;">
                                <input id="url_field" type="text" value="<?php echo "http://example.com"; ?>" placeholder="Url Empty!" />
                                <br>
                                <button class="btn" data-clipboard-demo="" data-clipboard-action="copy" data-clipboard-text="<?php echo "http://example.com"; ?>" type="button" style="margin-top: 4px;"><i class="fa fa-copy"></i> COPY URL</button>
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

    <!-- Javascript files used for the respond to all user feedback page -->
    <script src="../js/jquery-3.5.1.js"></script>
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
	  });
    </script>
    <!-------------------------------------------------------->

</body>