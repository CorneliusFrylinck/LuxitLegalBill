<?php
	session_start();
	$_SESSION['page'] = 'login';
	require('data.php');
  $errors = ['','','']; //email, pw, fill in
  $email = '';
  $pw = '';
	$recBrow;
  $conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');



  $browser = get_browser(null, true);

  if(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') == false) {
    $recBrow = "<a href='https://www.mozilla.org/en-US/firefox/download/thanks/'>You are not using the recommended browser, please download Firefox</a>";
  }

  if(!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
  }

  if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = $_POST['email'];
    $pw = $_POST['pw'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     	 $errors[0] = 'Must be a valid email';
    }
    elseif(empty($_POST['email']) || empty($_POST['pw'])){
      $errors[2] = "Please fill in al fields";
    }
    else {
      $query = 'SELECT u_id,pw FROM user_login WHERE u_id="'. $email .'"';
      $run = mysqli_query($conn, $query);
      $result = mysqli_fetch_all($run, MYSQLI_ASSOC);
      if (implode(null,$result) == null) {
        $errors[0] = 'Email is incorrect or account does not exist';
      }elseif ($result[0]['u_id'] != $email) {
      	$errors[0] = 'Email is incorrect or account does not exist';
      }else {
      	if ($result[0]['pw'] == $pw) {
          session_start();
          $_SESSION['name'] = $email;
            setcookie('name',$email, time() + 20000);
            setcookie('logged',"yes", time() + 20000);
        	header("Location: http://luxitlegalbill.co.za/main.php");
        }else {
          $errors[1] = 'Incorrect password';
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
  <br> <br>
    <?php echo $recBrow; ?>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="col-sm-12">
      <div class="container">
        <div class="input-group col-sm-10">
          <span class="input-group-addon col-sm-5 left" id="basic-addon1">Email</span>
          <input name="email" class="shadow form-control col-sm-5 right" placeholder="Email" aria-describedby="basic-addon1" value="<?php echo $email ?>">
        </div>
        <h3 class="redText"><?php echo $errors[0] ?></h3>
        <div class="input-group col-sm-10">
          	<span class="input-group-addon col-sm-5 left" id="basic-addon1">Password</span>
          	<input name="pw" class="shadow form-control col-sm-5 right" placeholder="Password" type="password" value="<?php echo $pw ?>">
        </div>
        <h3 class="redText"><?php echo $errors[1] ?></h3>
      </div>
    </div>
    <br>
    <div class="container">
      <input name="submit" type="submit" value="Log in" class="shadow btn btn-primary"></input>
    </div>
    <h3 class="redText"><?php echo $errors[2] ?></h3>
  </form>
  <p>Create a new account <label class="blue" onclick='switchPage("signup.php")' >here</label></p>
  <?php include('footer.php');?>

</html>
