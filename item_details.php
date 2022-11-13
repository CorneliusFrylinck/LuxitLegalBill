<?php
  	$conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
	$query = 'SELECT * FROM item_info';
    $run = mysqli_query($conn, $query);
    $results = mysqli_fetch_all($run, MYSQLI_ASSOC);

	if(isset($_POST['selectedVal'])) {
      $val = explode(':', $_POST['selectedVal']);
      $chosen;
      
      foreach ($results as $result) {
        if($val[0] == $result['item_id']) {
          $chosen = $result; 
          break;
        }
      }
    }
?>
<?php echo json_encode($chosen); ?>