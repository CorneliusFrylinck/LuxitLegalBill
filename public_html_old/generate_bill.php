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

        $division = strtoupper($bill['division']);
        $caseNr = $_POST['bill'];
        $pORa = $bill['pa'];
        $dORr = $bill['dr'];
        $pORaName = $bill['plain_name'];
        $dORrName = $bill['def_name'];
        $compName = strtoupper($bill['comp']);
        $actingFor = strtoupper($bill['att_for']);
        $scale = strtoupper($bill['scale']);

        $sql2 = "SELECT * FROM bill_item WHERE parent_bill='".$_POST['bill']."' ORDER BY date ASC";
        $run2 = mysqli_query($conn, $sql2);
        $items = mysqli_fetch_all($run2, MYSQLI_ASSOC);
		//print_r($items);

        if(empty($bill) || !isset($bill)) {
          
          //$sql1 = "INSERT INTO bills(bill_id,pa, dr, plain_name, def_name, division,att_for, scale, user) VALUES ('".$_COOKIE['caseNr']."','".$_COOKIE['pORa']."','".$_COOKIE['dORr']."','".$_COOKIE['pORaName']."','".$_COOKIE['dORrName']."','".$_COOKIE['division']."','".$_COOKIE['acting']."','".$_COOKIE['scale']."','".$_COOKIE['name']."')";
          //if(!$conn->query($sql1) === TRUE){
            //print_r("Error: " . $sql1 . "<br>" . $conn->error);
            //$irritasie['err'] = "Error: " . $sql1 . "<br>" . $conn->error;
          //}
        }
	//}
	
	if(!isset($_SESSION['name'])) {
       $noLog = "WARNING: You are not logged in, therefore you are only allowed trial functionality for this website and it is assumed that you are only testing. With trial functionality you are only allowed to test the website using a maximum amount of 10 bill items";
      header("Location: https://luxitlegalbill.co.za/index.php");
	}
?>
<!DOCTYPE html>
<html>

  <?php include('header.php');?>
    
  <script>
  	$(document).ready(function() {
    	$('#btnCalc').click(function(event) {
          event.preventDefault();
          var last = $('#last').text();
          var arr = new Array();
          var total = 0;
    	  for(var i=1; i<=parseInt(last); i++) {
          	var tex='#'+i;
            var cur = parseInt($(tex).val());
            if(Number.isInteger(cur)) {
            	total += cur;
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
        <h2 class="col-sm-12">IN THE HIGH COURT OF SOUTH AFRICA</h2>
        <h2 id="div" class="col-sm-12 under">GAUTENG DIVISION - <?php echo $division; ?></h2>
        <h2 id="case"  class="col-sm-12 right">CASE NR: <?php echo $caseNr; ?></h2>
        <p class="col-sm-12 left">In the matter between</p>
        <p id="p1" class="col-sm-6 left"><?php echo $pORaName; ?></p>
        <p id="pa" class="col-sm-6 right"><?php echo $pORa; ?></p>
        <p class="col-sm-12 left">and</p>
        <p id="p2" class="col-sm-6 left"><?php echo $dORrName; ?></p>
        <p id="dr" class="col-sm-6 right"><?php echo $dORr; ?></p>
      </div>
    </div>
  </div>
  <h2 class="jumbotron memo-msg">MEMORANDUM OF FEES AND DISBURSEMENTS DUE TO <?php echo $compName; ?> ATTORNEYS, INSTRUCTING ATTORNEYS FOR <?php echo $actingFor; ?>, AS ON A SCALE BETWEEN <?php echo $scale; ?> - FOR PROFESSIONAL SERVICES RENDERED</h2>
  <div class="tabelle jumbotron input-group">
        <?php 
    		$totalFees = 0;
			$count = 0;
    		foreach ($items as $key => $item) { 
              $info;
              $amount;
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
                $amount = ((int)$item['amount']/60*(int)$_COOKIE['hourly']);
              }
              $totalFees += $amount;
              $count += 1;
              $lastID = ( $key !== count( $items ) -1 ) ? "" : "last";
      	?>
          <div class="area input-group jumbotron">
            <div class="form-control ctrl col-md-2 border-right"><?php echo $item['date']; ?></div>                                         
            <div id="<?php echo $lastID; ?>" class="form-control ctrl col-sm-1 border-right"><?php echo $count;?></div>
            <div class="form-control left ctrl col-md-5 border-noleft"><?php echo $info; ?></div>
            <div class="form-control ctrl col-md-2 singledouble"><?php echo $amount; ?></div>
            <div class="col-sm-2"><input id="<?php echo $count;?>" name="de" class="form-control disb" placeholder=""></div>
          </div>
        <?php }; ?>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area2 input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>BALANCE BROUGHT FORWARD (A) & (B)</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"><?php echo $totalFees; ?></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">MINUS TAXED OFF</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 bottomdouble"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">PLUS DRAWING FEE</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">PLUS DISBURSEMENTS</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">PLUS FEE ATTENDING TAXATION</span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>SUB TOTAL</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">PLUS 14% VAT ON FEES <i>before 1 April 2018</i> <b>(Subtotal on Fees R_____________)(A)</b></span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">PLUS 15% VAT ON FEES <i>after 1 April 2018</i> <b>(Subtotal on Fees R_____________)(A)</b></span>
            <span class="form-control ctrl col-sm-2 singledouble bottomdouble"></span>
            <span class="col-sm-2 border-no"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>TOTAL DUE</b></span>
            <span class="form-control ctrl col-sm-2 bottomdouble singledouble"></span>
            <span class="col-sm-2 "></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>TAXED AND ALLOWED IN THE AMOUNT OF</b></span>
            <span class="form-control ctrl col-sm-4 fullsingle"><label class=" center"><b>Case nr:</b></label><br style="clear:both;"/><label class=" center"><?php echo $caseNr; ?></label></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft">R__________________________</span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left bottomdouble border-noleft"></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area input-group jumbotron">
            <span class="form-control ctrl col-sm-2 border-right"></span>
            <span class="form-control ctrl col-sm-1 border-right"></span>
            <span class="form-control ctrl col-sm-5 left border-noleft"><b>TAXING MASTER</b></span>
            <span class="form-control ctrl col-sm-2 singledouble"></span>
            <span class="col-sm-2"></span>
          </div>
          <div class="area fullsingle fullmetal jumbotron">
            <div class="left container fullleft">
              <b><u><p>Settlement endorsement in terms of Rule 70 of the High Court Uniform Rules:</p></u></b>
              <b><p>Settled between Pauline Pretorius abo __________________________________________________and</p></b>
              <b><p> __________________________________________________from __________________________________________________</p></b>
              <b><p>on this the ______ day of  _________________________ 2020.</p></b>
              <b><p>Contact info of P Pretorius at PP Legal Cost Consultants CC - tel: 082 361 4086</p></b>
              <b><p>Contact info of  __________________________________________________ at  __________________________________________________ -tel:</p></b>
              <b><p> _________________________</p></b>
            </div>
          </div>
    </div>
        <div class="container">
        <input id="btnCalc" name="submit1" type="submit" class="btn btn-primary center" value="Calculate Disbursements"/>
      </div>

  <?php include('footer.php');?>
</html>
