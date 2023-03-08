<?php 
session_start();
require '../config.php';
$url = 'http://localhost/Everest UI-Pack/public/admin/';
if (isset($_SESSION['admin_id'])) {
	header('location: index.php');
}


if (isset($_POST['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$sql = "SELECT * FROM `admin` WHERE email = '$email' ";
	$result = $connect->query($sql);
	
	if (mysqli_num_rows($result) > 0 ) {
		$row = mysqli_fetch_assoc($result);
		$password_hash = $row['password'];
		if (password_verify($password, $password_hash)) {
			$_SESSION['login'] = $email;
			$_SESSION['admin_id'] = $row['id'];
			$_SESSION['admin_name'] = $row['admin_name'];
			header('location: index.php');
		} else {
			
			$_SESSION['password_not_match'] = "Password Not Match";
			header('location: login.php');
			
		}

	}else{
		$_SESSION['email_not_found'] = "Email & User Name Not Found";
		header('location: login.php');
	}
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Everest - Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?php echo $url;?>assets/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo $url;?>assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo $url;?>assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo $url;?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo $url;?>assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                  <!--Varification Messages-->
                  <?php if (isset($_SESSION['email_not_found'])) {
                      ?>
                      <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['email_not_found']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <?php
                    } ?>
                    <?php if (isset($_SESSION['password_not_match'])) {
                      ?>
                      <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['password_not_match']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <?php
                    } 
                    ?>
						    <!--Varification Messages End-->

                    <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="../" class="">
                              <img src="<?php echo $url;?>assets/img/logo.png" alt="" width="250px">
                            </a>
                            <h3>Sign In</h3>
                        </div>
                        <form action="login.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php if (isset($email)){echo $email;}?>">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="login">Sign In</button>
                        </form>
                        <p class="text-center mb-0">Go Back to <a href="../">Home Page</a></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/chart/chart.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/easing/easing.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/waypoints/waypoints.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="<?php echo $url;?>assets/js/main.js"></script>
</body>

</html>