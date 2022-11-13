<?php
  $conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
  $query = 'SELECT * FROM bill_item';
  $run = mysqli_query($conn, $query);
  $results = mysqli_fetch_all($run, MYSQLI_ASSOC);
  $tempFull;

  //echo json_encode($_POST['info'], JSON_PRETTY_PRINT);

  foreach ($results as $result) {

    //if(($tempFull == $_POST['info']) && ($result['date']==$_POST['date'])) {
    if($result['item_id'] == $_POST['id']) {
      $tempDesc = $result['information'];
      $tempAddInfo = $result['info_add'];
      if($tempAddInfo != null) {
        if(strpos($tempDesc, '#') !== false) {
          $tempFull = str_replace("#", $tempAddInfo, $tempDesc);
        }else {
          $tempFull = $tempDesc . $tempAddInfo;
        }
      }else {
        $tempFull = $tempDesc;
      }
      $sql = "UPDATE `bill_item` SET `disbursement`=".$_POST['disb']." WHERE `item_id`=" . $result['item_id'];
      $temp;
      if ($conn->query($sql) === TRUE) {
          $temp = "Record updated successfully";
      } else {
          $temp = "Error updating record: " . $conn->error;
      }

      $conn->close();

      echo json_encode(($temp), JSON_PRETTY_PRINT);
    }
  }
  /*$ret = substr_replace($ret ,"",-1);
  $ret = substr_replace($ret ,"",-1);
  $ret .= ']';
  echo json_encode($ret, JSON_PRETTY_PRINT);*/
?>
