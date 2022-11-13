<?php
  $conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
  $query = 'SELECT * FROM item_info';
  $run = mysqli_query($conn, $query);
  $results = mysqli_fetch_all($run, MYSQLI_ASSOC);
  $received;
  $item;
  $ready = true;
  $chosen;
  //$bill;
  $irritasie;

	if(isset($_SESSION['name'])) {
      $received['loggedAs'] = $_SESSION['name'];
    }

	if(isset($_POST['count']) && !empty($_POST['count'])) {
      $received['count'] = $_POST['count']+1;
    }else {
      $received['count'] = 1;
    }

	if(isset($_POST['date']) && !empty($_POST['date'])) {
      $received['date'] = $_POST['date'];
    }else {
      $errors['errdate'] = "Please select a date first";
      $ready = false;
    }
	if(isset($_POST['sel'])) {
      $received['sel'] = explode(':', $_POST['sel']);
    }
    foreach ($results as $result) {
      if($received['sel'][0] == $result['item_id']) {
        $chosen = $result;
        break;
      }
    }
	$received['type'] = $chosen['amount'];
	if(isset($_POST['type']) && (!empty($_POST['type']) || $chosen['desc_add'] == 0)) {
      $received['type'] = $_POST['type'];
    }else {
      $errors['errtype'] = "Please enter information";
      $ready = false;
    }
	if(isset($_POST['pm']) && (!empty($_POST['pm']) || $chosen['pm'] == null || $chosen['pm'] == 'null')) {
      $received['pm'] = $_POST['pm'];
    }else {
      $errors['errpm'] = "Please enter amount";
      $ready = false;
    }

	if($ready){
      $item['count'] = $received['count'];
      $item['date'] = $received['date'];
      if($chosen['desc_add'] > 0) {
      	$item['info_add'] = $received['type'];
      	$item['type'] = $chosen['desc_add'];
      }
      if($chosen['pm'] != null && $chosen['pm'] != 'null'){
      	$item['pm'] = $chosen['pm'];
      }
      $item['info'] = $received['sel'][1];
      if ($received['pm'] != null && $received['pm'] != 'null'){
        if($chosen['amount'] <= 0 || $chosen['amount'] == null) {
          $item['amount'] = $received['pm'];
        }else {
          $item['amount'] = $chosen['amount'];
        }
      }else {
        $item['amount'] = $chosen['amount'];
      }
      if($chosen['per_amount'] != null && $chosen['per_amount'] != 'null') {
        $item['per_amount'] = $chosen['per_amount'];
      }
    }

	if(empty($errors)) {

      if(isset($_COOKIE['name'])|| !empty($_COOKIE['name'])) {
        $sql = "SELECT * FROM bills WHERE bill_ID='".$_COOKIE['caseNr']."'";
        $run2 = mysqli_query($conn, $sql);
        $bill = mysqli_fetch_all($run2, MYSQLI_ASSOC);
        $irritasie['bill1'] = $bill;
        if(empty($bill) || !isset($bill)) {
          $sql1 = "INSERT INTO bills(bill_id, pa, dr, plain_name, def_name, division,att_for, scale, user, comp, hr) VALUES ('";
          $sql1 .= $_COOKIE['caseNr'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['pORa'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['dORr'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['pORaName'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['dORrName'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['division'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['acting'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['scale'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['name'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['comp'];
          $sql1 .= "','";
          $sql1 .= $_COOKIE['hourly'];
          $sql1 .= "')";
            if(!$conn->query($sql1) === TRUE){
              print_r("Error: " . $sql1 . "<br>" . $conn->error);
              //$irritasie['err'] = "Error: " . $sql1 . "<br>" . $conn->error;
            }
        }
      }
      if(!isset($item['amount']) || empty($item['amount'])) {
        $item['amount'] = 0;
      }
      if($item['amount'] == 0) {
        $item['amount'] = $chosen['per_amount'];
      }
      if(!isset($item['per_amount']) || empty($item['per_amount'])) {
        $item['per_amount'] = 0;
      }
      //$addDate = $item['date']->format('Y-m-d');
      $addDate = date("Y-m-d", strtotime($item['date']));
        $sql2 = "INSERT INTO bill_item (parent_bill, date, information, info_add, pm, amount, per_amount) VALUES ('";
        $sql2 .= $_COOKIE['caseNr'];
        $sql2 .= "','";
        $sql2 .= $addDate;
        $sql2 .= "', '";
        $sql2 .= $item['info'];
        $sql2 .= "', '";
        $sql2 .= $received['type'];
        $sql2 .= "', '";
        $sql2 .= $item['pm'];
        $sql2 .= "', ";
        $sql2 .= $item['amount'];
        $sql2 .= ",";
        $sql2 .= $item['per_amount'];
        $sql2 .= ")";
      if(!$conn->query($sql2) === TRUE){
        print_r("Error: " . $sql2 . "<br>" . $conn->error);
      }
  		echo json_encode($item, JSON_PRETTY_PRINT);
    }else {
      	echo json_encode($errors, JSON_PRETTY_PRINT);
    }
?>
