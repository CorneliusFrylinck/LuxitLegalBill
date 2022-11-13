<?php
	ob_start();
	session_start();
	$name = $_SESSION['name'];
	$page = $_SESSION['page'];
	$cur;
	$login;
	$logout;

	if($_POST['action'] == 'log_out') {
       $_SESSION['action'] = 'log_out';
    }

	if (isset($name)) {
      $login = 'Logged in as: ' . $name . ' ';
      $logout = '<label class="blue" onclick="logout()"> Logout</label>';
    }
	
	switch ($page) {
    case 'login':
        $cur = 'Login';
        if($_SERVER['PHP_SELF'] != '/index.php') {
          header("location: http://luxitlegalbill.co.za/index.php");
        }
        break;
    case 'signup':
        $cur = 'Signup';
        if($_SERVER['PHP_SELF'] != '/signup.php') {
          header("location: http://luxitlegalbill.co.za/signup.php");
        }
        break;
    case 'Client Details':
        $cur = 'Client Details';
        if($_SERVER['PHP_SELF'] != '/client_details.php') {
          header("location: http://luxitlegalbill.co.za/client_details.php");
        }
        break;
    case 'billDet':
        $cur = 'Bill Details';
        if($_SERVER['PHP_SELF'] != '/main.php') {
          header("location: http://luxitlegalbill.co.za/main.php");
        }
        break;
    case 'bill':
        $cur = 'Bill';
        if($_SERVER['PHP_SELF'] != '/bill.php') {
          header("location: http://luxitlegalbill.co.za/bill.php");
        }
        break;
    case 'generate_bill':
        $cur = 'generate_bill';
        if($_SERVER['PHP_SELF'] != '/generate_bill.php') {
          header("location: http://luxitlegalbill.co.za/generate_bill.php");
        }
        break;
    case 'add':
        $cur = 'Add';
        if($_SERVER['PHP_SELF'] != '/add.php') {
          header("location: http://luxitlegalbill.co.za/add.php");
        }
        break;
    case 'about':
        $cur = 'About';
        if($_SERVER['PHP_SELF'] != '/about.php') {
          header("location: http://luxitlegalbill.co.za/about.php");
        }
        break;
    default:
        $cur = 'Login';
        if($_SERVER['PHP_SELF'] != '/index.php') {
          header("location: http://luxitlegalbill.co.za/index.php");
        }
    }

	if($_SESSION['action'] == 'log_out') {
      unset($_SESSION['name']);
      unset($_COOKIE['logged']);
      header("Location:index.php");
      unset($_SESSION['action']);
    }

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) 	{
      // last request was more than 30 minutes ago
      session_unset();     // unset $_SESSION variable for the run-time 
      session_destroy();   // destroy session data in storage
      unset($_COOKIE['logged']);
  	}
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  ?>
<head>
  <title>LuxItLegalBill</title>
  <meta charset="utf-8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="js/main.js"></script>
  <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
  <nav class="navbar navbar-expand-md bg-primary navbar-dark">
   <!-- Brand -->
   <img class="shadow logo" src="logo/logo.jpg"/><a class="navbar-brand col-sm-2" href="index.php">
	LuxIt Legal Bill</a>

   <!-- Toggler/collapsibe Button -->
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
     <span class="navbar-toggler-icon"></span>
   </button>

   <!-- Navbar links -->
   <div class="collapse navbar-collapse" id="collapsibleNavbar">
     <ul class="navbar-nav">
       <li class="nav-item">
         <a class="nav-link <?php if($page=='signup'){echo 'disabled';} ?>" href="signup.php">Signup</a>
       </li>
       <li class="nav-item">
         <a class="nav-link <?php if($page=='login'){echo 'disabled';} ?>" href="index.php">Login</a>
       </li>
       <li class="nav-item">
         <a class="nav-link <?php if($page=='Client Details'){echo 'disabled';} ?>" href="client_details.php">Client Details</a>
       </li>
       <li class="nav-item">
         <a class="nav-link <?php if($page=='billDet'){echo 'disabled';} ?>" href="main.php">New Bill</a>
       </li>
       <li class="nav-item">
         <a class="nav-link <?php if($page=='about'){echo 'disabled';} ?>" href="about.php">About</a>
       </li>
     </ul>
   </div>
	<a><?php echo htmlspecialchars($login); ?><?php echo $logout; ?></a>
    <script> 
      function logout() {
            $.ajax({
                 type: "POST",
                 url: 'index.php',
                 data:{action:'log_out'},
                 success:function(html) {
                   location.reload();
                 }

            });
       }
    </script>
  </nav>