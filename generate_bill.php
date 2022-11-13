<?php
	session_start();
	$_SESSION['page'] = 'generate_bill';
	$noLog = '';
	$lastID;

	$conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
	//if(isset($_POST['isLogged']) {
  $sql = "SELECT * FROM bills WHERE bill_id='".$_POST['bill']."'";
  $run = mysqli_query($conn, $sql);
  $bill = mysqli_fetch_all($run, MYSQLI_ASSOC)[0];
		//print_r($bill);
		//print_r($bill[0]['division']);

	$disbursement;

	if(!isset($_POST['submitAdd']) && empty($_POST['submitAdd'])) {

	  $division = strtoupper($bill['division']);
	  $caseNr = $_POST['bill'];
	  $pORa = $bill['pa'];
	  $dORr = $bill['dr'];
	  $pORaName = $bill['plain_name'];
	  $dORrName = $bill['def_name'];
	  $compName = strtoupper($bill['comp']);
	  $actingFor = strtoupper($bill['att_for']);
	  $scale = strtoupper($bill['scale']);
		$rate = $bill['hr'];

	  $sql2 = "SELECT * FROM bill_item WHERE parent_bill='".$_POST['bill']."' ORDER BY date ASC";
	  $run2 = mysqli_query($conn, $sql2);
	  $items = mysqli_fetch_all($run2, MYSQLI_ASSOC);

		if(!isset($_SESSION['name'])) {
	      $noLog = "WARNING: You are not logged in, therefore you are only allowed trial functionality for this website and it is assumed that you are only testing. With trial functionality you are only allowed to test the website using a maximum amount of 10 bill items";
	      header("Location: https://luxitlegalbill.co.za/index.php");
		}

		setcookie('caseNr',$caseNr, time() + 20000);
		setcookie('division',$division, time() + 20000);
		setcookie('compName',$compName, time() + 20000);
		setcookie('scale',$scale, time() + 20000);
		setcookie('pORaName',$pORaName, time() + 20000);
		setcookie('dORrName',$dORrName, time() + 20000);
		setcookie('hourly',$rate, time() + 20000);
		setcookie('pORa',$pORa, time() + 20000);
		setcookie('dORr',$dORr, time() + 20000);
		setcookie('acting',$actingFor, time() + 20000);
		//echo $_COOKIE['caseNr'];
	}

	if(isset($_POST['submitAdd']) && !empty($_POST['submitAdd'])) {
			/*setcookie('caseNr',$caseNr, time() + 20000);
			setcookie('division',$division, time() + 20000);
			setcookie('compName',$compName, time() + 20000);
			setcookie('scale',$scale, time() + 20000);
			setcookie('pORaName',$pORaName, time() + 20000);
			setcookie('dORrName',$dORrName, time() + 20000);
			setcookie('hourly',$rate, time() + 20000);
			setcookie('pORa',$pORa, time() + 20000);
			setcookie('dORr',$dORr, time() + 20000);
			setcookie('acting',$actingFor, time() + 20000);*/

			//$_POST['caseNr'] = $_COOKIE['caseNr'];
			/*echo $_POST['caseNr'];

			$url = 'https://luxitlegalbill.co.za/add.php';
			$myvars = 'caseNr' . $_POST['caseNr'];

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($myvars)));

			$result = curl_exec($ch);
			$response = curl_getinfo($ch);
			curl_close($ch);*/


			//echo "<script>window.location.href='https://luxitlegalbill.co.za/add.php'</script>";
      header("Location: https://luxitlegalbill.co.za/add.php");
  }
?>
<!DOCTYPE html>
<html>

  <?php include('header.php');?>

  <script>
  	$(document).ready(function() {
    	/*$('#addIt').click(function(event) {
				event.preventDefault();
				var nr = $('#caseNr').val();
				$.post("add.php", {
					caseNr: nr
				});
			});*/
    	$('#btnCalc').click(function(event) {
          event.preventDefault();
          var last = $('#last').text();
          var arr = new Array();
          var total = 0;
					console.log("pizza");
    	  for(var i=1; i<=parseInt(last); i++) {
          	var tex='#'+i;
						var texDa = tex+'date';
						var texIn = tex+'info';
						var texAm = tex+'amount';
						var texID = tex+'id';
            var cur = parseInt($(tex).val());
            var date = $(texDa).text();
            var info = $(texIn).text();
            var amount = $(texAm).text();
            var id = $(texID).val();
            if(Number.isInteger(cur)) {
            	total += cur;
							$.post('update_bill_items.php', {
								disb: cur,
								amount: amount,
								date: date,
								info: info,
								id: id
							}, function (data) {
								console.log(data);
							});
            }
          }
          $('#totalDisb').text(total);
        });
  	});
  </script>
  <h3 class="redText"><?php echo $noLog;?></h3>
  <div class="header jumbotron head">
    <div class="container">
      <div class="row row-1">
        <a class="col-sm-12 tight"><b>IN THE HIGH COURT OF SOUTH AFRICA</b></a>
        <a id="div" class="col-sm-12 tight under"><b></p>GAUTENG DIVISION - <?php echo $division; ?></b></a>
        <a id="case"  class="col-sm-12 right"><b>CASE NR: <?php echo $caseNr; ?></b></a>
        <a class="col-sm-12 left">In the matter between</a>
        <a id="p1" class="col-sm-6 left"><b><?php echo $pORaName; ?></b></a>
        <a id="pa" class="col-sm-6 right"><b><?php echo $pORa; ?></b></a>
        <a class="col-sm-12 left">and</a>
        <a id="p2" class="col-sm-6 left"><b><?php echo $dORrName; ?></b></a>
        <a id="dr" class="col-sm-6 right"><b><?php echo $dORr; ?></b></a>
      </div>
    </div>
  </div>
  <p class="jumbotron tight memo-msg"><b>MEMORANDUM OF FEES AND DISBURSEMENTS DUE TO <?php echo $compName; ?> ATTORNEYS, INSTRUCTING ATTORNEYS FOR <?php echo $actingFor; ?>, AS ON A SCALE BETWEEN <?php echo $scale; ?> - FOR PROFESSIONAL SERVICES RENDERED</b></p>
  <div class="tabelle jumbotron input-group">
        <?php
    		$totalFees = 0;
				$totaldisb = 0;
			$count = 0;
    		foreach ($items as $key => $item) {
              $info;
              $amount;
					    if($item['disbursement'] != null && $item['disbursement'] >= 0) {
					      $disbursement = $item['disbursement'];
								$totaldisb += $disbursement;
					    }else {
					      $disbursement = '';
					    }
              if($item['info_add'] != null) {
                if(strpos($item['information'], '#') !== false) {
                  $info = str_replace("#",$item['info_add'],$item['information']);
                }else {
                  $info = $item['information'].$item['info_add'];
                }
              }else {
                $info = $item['information'];
              }
              if($item['pm'] == 'pg') {
                $amount = ((int)$item['amount']*(int)$item['per_amount']);
              }elseif($item['pm'] == 'min') {
                $amount = ((int)$item['amount']/60*(int)$rate);
              }else {
								$amount = $item['amount'];
							}
              $totalFees += $amount;
              $count += 1;
              $lastID = ( $key !== count( $items ) -1 ) ? "" : "last";
							$dte = (strval($count) . 'date');
							$inf = (strval($count) . 'info');
							$amt = (strval($count) . 'amount');
							$id = strval($count) . 'id';
      	?>
          <div class="area input-group jumbotron">
						<input id="<?php echo $id; ?>" name="caseNr" class="hide" type="text" value="<?php echo $item['item_id']; ?>"/>
            <div id="<?php echo $dte; ?>" class="form-control ctrl col-md-2 border-right"><?php echo $item['date']; ?></div>
            <div id="<?php echo $lastID; ?>" class="form-control ctrl col-md-1 border-right"><?php echo $count;?></div>
            <div id="<?php echo $inf; ?>" class="form-control left ctrl col-md-7 border-noleft"><?php echo $info; ?></div>
            <div class="col-sm-1"><input id="<?php echo $count;?>:fee" name="de" class="form-control disb" placeholder="" value="<?php echo number_format((float)$amount, 2, '.', ''); ?>"></div>
            <div class="col-sm-1"><input id="<?php echo $count;?>" name="de" class="form-control disb" placeholder="" value="<?php echo $disbursement; ?>"></div>
          </div>
        <?php }; ?>
          <div class="area2 input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-7 left border-noleft"><b>BALANCE BROUGHT FORWARD (A) & (B)</b></span>
            <span class="form-control ctrl col-sm-1 singledouble"><?php echo number_format((float)$totalFees, 2, '.', ''); ?></span>
            <span id="totalDisb" class="col-sm-1"><?php echo number_format((float)$totaldisb, 2, '.', ''); ?></span>
          </div>
			      <div class="container">
			        <input id="btnCalc" name="submit1" type="submit" class="btn btn-primary center" value="Calculate Disbursements"/>
			      </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-7 left border-noleft"></span>
            <span class="form-control ctrl col-sm-1 singledouble"></span>
            <span class="col-sm-1"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-7 left border-noleft">MINUS TAXED OFF</span>
            <span class="form-control ctrl col-sm-1 singledouble bottomdouble"></span>
            <span class="col-sm-1 bottomdouble"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">PLUS DRAWING FEE</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">PLUS DISBURSEMENTS</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">PLUS FEE ATTENDING TAXATION</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">PLUS 14% VAT ON FEES <i>before 1 April 2018</i> <b>(Subtotal on Fees R_____________)(A)</b></span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">PLUS 15% VAT ON FEES <i>after 1 April 2018</i> <b>(Subtotal on Fees R_____________)(A)</b></span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>TOTAL DUE</b></span>
            <span class="form-control ctrl col-sm-2 bottomdouble singledouble"></span>
            <span class="col-sm-2 "></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>TAXED AND ALLOWED IN THE AMOUNT OF</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span><!--<b>Case nr:</b></label><br style="clear:both;"/><label class=" center"><?php echo $caseNr; ?></label>-->
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft">R__________________________</span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left bottomdouble border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-6 left border-noleft"><b>TAXING MASTER</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
    </div>
		<!--<div class="area fullsingle fullmetal jumbotron">
			<div class="left container fullleft">
				<b><u><p>Settlement endorsement in terms of Rule 70 of the High Court Uniform Rules:</p></u></b>
				<b><p>Settled between *user name* abo __________________________________________________and</p></b>
				<b><p> __________________________________________________from __________________________________________________</p></b>
				<b><p>on this the ______ day of  _________________________ 2020.</p></b>
				<b><p>Contact info of *user at company name* - tel: *tel*</p></b>
				<b><p>Contact info of  __________________________________________________ at  __________________________________________________ -tel:</p></b>
				<b><p> _________________________</p></b>
			</div>
		</div>-->
		<br>
		<form class="" action="genPdf.php" method="post">
			<input id="case" name="case" class="hide" type="text" value="<?php echo $caseNr; ?>"/>
			<div class="container">
				<input id="genPdf" name="submit1" type="submit" class="btn btn-primary center" value="Generate PDF"/>
			</div>
		</form>
		<br>
		<form class="" action="generate_bill.php" method="POST">
			<div class="container">
				<input id="addIt" name="submitAdd" type="submit" class="btn btn-primary center" value="Add Items"/>
				<input id="caseNr" name="caseNr" class="hide" type="text" value="<?php echo $caseNr; ?>"/>
			</div>
		</form>
  <?php include('footer.php');?>
</html>
