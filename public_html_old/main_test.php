<?php
	ob_start();
	session_start();
	$_SESSION['page'] = 'billDet';
	$error = '';
	$noLog = '';
  	require('data.php');
	$parts;
	$thetext = '';
	$thetext1 = '';

	$addAtt;
	if(isset($_SESSION['name'])) {
      $addAtt = '<button id="addAtt" onclick="addFile(event)" class="shadow btn"><i class="fa fa-plus"></i>  Add Attachment</button> <a id="max"></a>';
    }else {
      $noLog = "WARNING: You are not logged in, therefore you are only allowed trial functionality for this website and it is assumed that you are only testing. With trial functionality you are only allowed to test the website using a maximum amount of 10 bill items";
      header("Location: https://luxitlegalbill.co.za/index.php");
      $addAtt = ' ';
    }
  $conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');

  if(!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
  }

  if(isset($_POST['submit']) && !empty($_POST['submit'])) {
    if(empty($_POST['division']) || empty($_POST['caseNr']) || empty($_POST['compName']) || empty($_POST['scale']) || empty($_POST['pORaName']) || empty($_POST['dORrName'])){
      $error = '<h3 class="redText">Please fill in all fields!</h3>';
    }
    else {
      	setcookie('division',$_POST['division'], time() + 20000);	// set cookies to expire now + wait_time (in seconds)
		setcookie('caseNr',$_POST['caseNr'], time() + 20000);
		setcookie('compName',$_POST['compName'], time() + 20000);
		setcookie('scale',$_POST['scale'], time() + 20000);
		setcookie('pORaName',$_POST['pORaName'], time() + 20000);
		setcookie('dORrName',$_POST['dORrName'], time() + 20000);
		setcookie('hourly',$_POST['hourlyrate'], time() + 20000);
        $parts = $_POST['pORa']; 
        $thetext =  explode(':', $parts)[1];
        $parts1 = $_POST['dORr']; 
        $thetext1 =  explode(':', $parts1)[1];
        $parts2 = $_POST['acting']; 
        $thetext2 =  explode(':', $parts)[1];
		setcookie('pORa',$thetext, time() + 20000);
		setcookie('dORr',$thetext1, time() + 20000);
		setcookie('acting',$thetext2, time() + 20000);
      
      /*$to = 'cfrylinck1997@gmail.com';
      $subject = 'New Bill';
      $message = 'message details'; 
      $from = 'support@luxitlegalbill.co.za';*/
		
      if (!file_exists('uploads/'+$_POST['caseNr'])) {
          mkdir('uploads/'+$_POST['caseNr'], 0777, true);
      }
      
      // Sending email
      /*if(mail($to, $subject, $message)){
          echo 'Your mail has been sent successfully.';
        setcookie('mailerr', 'Sent successfully from' + $_SESSION['name'], 600);
      } else{
          echo 'Unable to send email. Please try again.';
        setcookie('mailerr', 'unable to send email from ' + $_SESSION['name'], 600);
      }*/
      
      header("Location:add.php");
    }
  }
	

?>
<!DOCTYPE html>
<html>

  <?php include('header.php');?>
  <br>
    <h3 class="redText"><?php echo $noLog ?></h3>
    <h3 class="redText"><?php echo $error ?></h3>
  <form action="" method="POST">
    <div class="container">
      <div class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Division Name</span>
        <input type="text" name="division" class="shadow form-control col-sm-5 right" placeholder="Division Name" aria-describedby="basic-addon1">
      </div>
      <div class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Case Number</span>
        <input type="text" name="caseNr" class="shadow form-control col-sm-5 right" placeholder="Case Number" aria-describedby="basic-addon1">
      </div>
      <div class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Company Name</span>
        <input type="text" name="compName" class="shadow form-control col-sm-5 right" placeholder="Company Name" aria-describedby="basic-addon1">
      </div>
      <div class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Scale</span>
        <input type="text" name="scale" class="shadow form-control col-sm-5 right" placeholder="Scale" aria-describedby="basic-addon1">
      </div>
      <div class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Hourly Rate</span>
        <input type="text" name="hourlyrate" class="shadow form-control col-sm-5 right" placeholder="Hourly Rate" aria-describedby="basic-addon1">
      </div>
      <div class="shadow pnl">
        <div class="input-group col-sm-12">
          <span class="input-group-addon col-sm-2 left" name="basic-addon1">Name</span>
          <input type="text" name="pORaName" class="shadow form-control col-sm-4" placeholder="Plaintiff/Applicant Name" aria-describedby="basic-addon1">
          <div class="form-group col-sm-4">
            <label for="sel1">
              Select from list:
              </label>
              <span class="caret"></span>
            <select onchange="change1()" name="pORa" class="shadow form-control" id="sel1">
              <option value="1:Plaintiff">Plaintiff</option>
              <option value="2:Applicant">Applicant</option>
            </select>
          </div>
        </div>
      </div>
      <div class="shadow pnl">
        <div class="input-group col-sm-12">
          <span class="input-group-addon col-sm-2 left" id="basic-addon1">Name</span>
          <input type="text" name="dORrName" class="shadow form-control col-sm-4" placeholder="Defendant/Respondant Name" aria-describedby="basic-addon1">
          <div class="form-group col-sm-4">
            <label for="sel1">
              Select from list:
              </label>
              <span class="caret"></span>
            <select onchange="change2()" name="dORr" class="shadow form-control" id="sel2">
              <option value="1:Defendant">Defendant</option>
              <option value="2:Respondant">Respondant</option>
            </select>
          </div>
        </div>
      </div>
      <div class="shadow pnl">
        <div class="input-group col-sm-12">
          <div class="form-group col-sm-12">
            <label for="sel3">
              Acting for:
              </label>
              <span class="caret"></span>
            <select name="acting" class="shadow form-control" id="sel3">
              <option value="1:Plaintiff">Plaintiff</option>
              <option value="2:Defendant">Defendant</option>
            </select>
          </div>
        </div>
      </div>
      <?php echo $addAtt ?>
      <div id="files" class="blck container">
      </div>
      <br>
      <input name="submit" value="Submit Bill Details" type="submit" class="shadow btn btn-primary"></input>
  </form>
  <script type="text/javascript">
    function change1(e) {
      var e1 = document.getElementById("sel1");
      var strUser1 = e1.options[e1.selectedIndex].text;

      var x = document.getElementById("sel3");
      x.removeChild( x.options[0] );
      var option1 = document.createElement("option");
      option1.text = strUser1;
      option1.value = "1:"+strUser1.toString();
      x.add(option1, x[0]);
    }
    function change2(e) {
      var e2 = document.getElementById("sel2");
      var strUser2 = e2.options[e2.selectedIndex].text;

      var x = document.getElementById("sel3");
      x.removeChild( x.options[1] );
      var option2 = document.createElement("option");
      option2.text = strUser2;
      option2.value = "2:"+strUser2.toString();
      x.add(option2, x[1]);
    }
	function addFile(event) {
      event.preventDefault();
      var count = document.getElementById("files").children.length;
      if(count<19) {
        var where = document.getElementById("files");
        var what = document.createElement("input");
        what.type = "file";
        what.id="file" + count;
        what.name = what.id;
        what.class = "blck";
        where.appendChild(what);
      }else {
        var where = document.getElementById("files");
        var what = document.createElement("input");
        what.type = "file";
        what.id="file" + count;
        what.name = what.id;
        what.class = "blck";
        where.appendChild(what);
        document.getElementById("addAtt").disabled = true;
        document.getElementById("max").innerHTML = "Maximum attachments reached";
      }
    }
  </script>

  <?php include('footer.php');?>

  </div>
</html>
