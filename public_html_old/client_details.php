<?php
	session_start();
	$_SESSION['page'] = 'Client Details';
	require('data.php');
	$selBill;
	$name = $_SESSION['name'];
  	$conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
	
	$query = "SELECT is_admin FROM user_login WHERE u_id='".$name."'";
    $run = mysqli_query($conn, $query);
    $user = mysqli_fetch_all($run, MYSQLI_ASSOC);
	$bills;
      if (!file_exists('uploads/'+$_POST['caseNr'])) {
          mkdir('uploads/'+$_POST['caseNr'], 0777, true);
      }
	
	
    $sql = "SELECT * FROM bill_item WHERE parent_bill='".$name."' ORDER BY date ASC";
    $run3 = mysqli_query($conn, $sql);
    $items = mysqli_fetch_all($run3, MYSQLI_ASSOC);

	$is_admin = $user[0]['is_admin'];
	
	if($is_admin==1) {
      $query1 = "SELECT * FROM bills ORDER BY date";
      $run2 = mysqli_query($conn, $query1);
      $bills = mysqli_fetch_all($run2, MYSQLI_ASSOC);
      $count = 1;
    }else {
      $query1 = "SELECT * FROM bills WHERE user='".$name."' ORDER BY date";
      $run2 = mysqli_query($conn, $query1);
      $bills = mysqli_fetch_all($run2, MYSQLI_ASSOC);
    }
	
	if(!isset($_SESSION['name'])) {
       $noLog = "WARNING: You are not logged in, therefore you are only allowed trial functionality for this website and it is assumed that you are only testing. With trial functionality you are only allowed to test the website using a maximum amount of 10 bill items";
      header("Location: https://luxitlegalbill.co.za/index.php");
	}
?>
<!DOCTYPE html>
<html>
  <?php include('header.php');?>
  <br> <br>
  <script>
  	$(document).ready(function() {
    	$('#btnShow').css("display", "none");
    	$('#selBill').change(function() {
            $('#btnShow').css("display", "inline");
        });
    	/*$('#btnShow').click(function(event) {
          var bill = $('#selBill').val();
          console.log(bill);
           var redirect = 'https://luxitlegalbill.co.za/generate_bill.php?bill='+bill;
			$.redirectPost(redirect, {bill: bill});
        });*/
  	});
  </script>
  <form method="post" action="generate_bill.php">
    <div class="form-group col-sm-12">
      <label for="sel1">
        List of Bills by Case Number:
      </label>
      <span class="caret"></span>
      <select name="bill" class="form-control" id="selBill">
          <option value="placeholder">--Please Select an Option--</option>
          <?php foreach($bills as $bill): ?>
              <option value="<?php echo $bill['bill_id']; ?>">
                  <?php echo $bill['bill_id']; ?>
              </option>
          <?php endforeach; ?>
      </select>
    </div>
    <input id="btnShow" name="submit" type="submit" class="btn btn-primary center" value="Show Bill Details"/>
  </form>
  <?php include('footer.php');?>
  
</html>
