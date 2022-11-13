<?php
	session_start();
	$_SESSION['page'] = 'signup';
  require('data.php');
  $errors = ['','','','']; //email, pw, pwc, fill in
  $email = '';
  $pw = '';
  $aftersend = '';
  $pwc = '';
  $conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
	$can_sign;
	$name = $_SESSION['name'];

	if($name == null) {
		$can_sign = false;
	}else {
		$query = "SELECT is_admin FROM user_login WHERE u_id='".$name."'";
	  $run = mysqli_query($conn, $query);
	  $user = mysqli_fetch_all($run, MYSQLI_ASSOC);
		$is_admin = $user[0]['is_admin'];
		if($is_admin == 1) {
			$can_sign = true;
		}else {
			$can_sign = false;
		}
	}


  if(isset($_COOKIE['fresh'])) {
    $aftersend = "<script>document.getElementById('myCheck').checked=true;document.getElementById('sign').style.display = 'inline';</script>";
  }

  if(!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
  }

  if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = $_POST['email'];
    $pw = $_POST['pw'];
    $pwc = $_POST['pwc'];
    if(empty($_POST['email']) || empty($_POST['pw']) || empty($_POST['pwc'])){
      $errors[3] = "Please fill in al fields";
    }
    else {
      if($pwc != $pw) {
        $errors[2] = "Does not match";
      }else {
        $query = 'SELECT u_id,pw FROM user_login WHERE u_id="' . $_POST['email'] . '"';
        $run = mysqli_query($conn, $query);
        $result = mysqli_fetch_all($run, MYSQLI_ASSOC);
        if (implode(null,$result) == null) {
          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $sql = 'INSERT INTO user_login (u_id,pw) VALUES ("'.$_POST['email'].'", "'.$_POST['pw'].'")';

          if ($conn->query($sql) === TRUE) {
              header("Location: http://luxitlegalbill.co.za/index.php");
          } else {
              setcookie('fresh', 'set', time() + 20000);
              $errors[3] = "Error: " . $sql . "<br>" . $conn->error;
              //echo "<script>document.getElementById('mycheck').checked=true;</script>";
          }
        }else {
          $errors[0] = 'Email already in use';
        }
      }

      mysqli_free_result($run);
      mysqli_close($conn);
      //echo htmlspecialchars($_POST['division']);
    }
  }

?>
<!DOCTYPE html>
<html>

  <?php include('header.php');?>
  <br>
	<?php if($can_sign){ ?>
	  <form action="" method="POST">
	    <div class="pnl col-sm-12">
	      <div class="container">
	        <div class="input-group col-sm-10">
	          <span class="input-group-addon col-sm-5 left" id="basic-addon1">Email</span>
	          <input type="text" name="email" class="shadow form-control col-sm-5 right" placeholder="Email" aria-describedby="basic-addon1" value="<?php echo $email ?>">
	        </div>
	        <h3 class="redText"><?php echo $errors[0] ?></h3>
	        <div class="input-group col-sm-10">
	          	<span class="input-group-addon col-sm-5 left" id="basic-addon1">Password</span>
	          	<input name="pw" class="shadow form-control col-sm-5 right" placeholder="Password" type="password" value="<?php echo $pw ?>">
	        </div>
	        <h3 class="redText"><?php echo $errors[1] ?></h3>
	        <div class="input-group col-sm-10">
	          <span class="input-group-addon col-sm-5 left" id="basic-addon1">Password Confirmation</span>
	          <input name="pwc" class="shadow form-control col-sm-5 right" placeholder="Confirm Password" type="password" value="<?php echo $pwc ?>">
	        </div>
	        <h3 class="redText"><?php echo $errors[2] ?></h3>
	      </div>
	    </div>
	    <br>
	    <div class="container">

	          <h4 class="redText">
	            <input type="checkbox" id="myCheck"  onclick="myFunction()">I agree to the <label class="blue" onclick='switchPage("about.php")' >Terms and Conditions</label>.
	        </h4><br/>
	             <h3 class="left redText">This website is currently under beta testing, therefore no one can currently be held liable for any contracts as they are not yet official or enforceable, all bills generated and all accounts created will be deleted as soon as the website is ready and this message is no longer displayed.</h3>
	            <h4 class="left redText">Which states, amongst other things, that:</h4>
	          	<h4 class="redText">
	            <ul>
	              <li class="left">I will be paying R1000 per month to have access to this website.</li>
	              <li class="left">I will pay a fee of 6.5% per bill.</li>
	            </ul>
	        </h4>
	    </div>
	    <div id="sign" class="container">
	      <input name="submit" type="submit" value="Sign up" class="shadow btn btn-primary"></input>
	      <h3 class="redText"><?php echo $errors[3] ?></h3>
	    </div>
	  </form>
	  <p>Already have an account? Log in <label class="blue" onclick='switchPage("index.php")' >here</label></p>
	        <script>
	          var sub = document.getElementById("sign");
	          sub.style.display = "none";
	        function myFunction() {
	          var checkBox = document.getElementById("myCheck");
	          var sub = document.getElementById("sign");
	          if (checkBox.checked == true){
	            sub.style.display = "inline";
	  			var today = new Date();
				today.setHours(today.getHours() + 24);
	  			var mystr = "fresh=fresh; expires="+today;
	  			document.cookie = mystr;
	            var x = document.cookie;
	            console.log(mystr);
	            console.log(x);
	          } else {
	             sub.style.display = "none";
	          }
	        }
	        </script>
	<?php }
	else { ?>
		<h2 class="redText center">Only admin users are allowed to add new users.</h2>
		<h2 class="redText center">Please contact our admin to add a new company or to add a user to an existing company.</h2>
		<h2 class="redText center">Feel free to check out our <label class="blue" onclick='switchPage("about.php")' >Terms and Conditions</label> before contacting us on support@luxitlegalbill.co.za</h2>
		<h2 class="redText center">If you are an admin user, feel free to <label class="blue" onclick='switchPage("index.php")' >Login</label> instead.</h2>
	<?php } ?>
  <?php include('footer.php');?>
</html>
