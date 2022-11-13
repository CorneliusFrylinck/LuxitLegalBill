<?php
	session_start();
	$_SESSION['page'] = 'add';
	unset($_SESSION['selected']);
	unset($_COOKIE['item']);
	require('data.php');
	$noLog = '';
	$errors;
	$i;
	$count = 0;
	$conn = mysqli_connect('localhost', 'obqnffzy_admin', 'PollY@820829', 'obqnffzy_luxit');
	$query = 'SELECT * FROM item_info ORDER BY description ASC';
  $run = mysqli_query($conn, $query);
  $result = mysqli_fetch_all($run, MYSQLI_ASSOC);

	if(isset($_COOKIE['caseNr']) || !empty($_COOKIE['caseNr'])) {
		$query2 = "SELECT * FROM bill_item WHERE parent_bill='";
		$query2 .= $_COOKIE['caseNr'];
		$query2 .= "'";
    $run2 = mysqli_query($conn, $query2);
    $result2 = mysqli_fetch_all($run2, MYSQLI_ASSOC);
  	$count = count($result2);
  }

	//echo htmlspecialchars($_COOKIE['caseNr']);
	//$selected = $_COOKIE['selected'];

	//function newVal($sel) {
      //$str = '<script> console.log("' . $_COOKIE['selected'] . '")</script>';
      //echo $str;
      //$selected = $sel;
      //$str = '<script> console.log("' . $sel . '")</script>';
      //echo $str;
      //if (!isset($_COOKIE['selected'])) {
        	//$selected = $result[0]['description'];
      //}else {
          // [$_COOKIE['
          //for($i=0;$i<count($result);$i++) {
            //$a = $result[$i]['description'];
            //$b = $_COOKIE['selected'];
            //$str = '<script> console.log("' . $i . ': ' . $a . ' - ' . $b . '")</script>';
            //echo $str;
            //if($result[$i]['description'] == $_COOKIE['selected']) {
              //$selected = $result[$i];
            //}
          //}
        //selected']]; // $_COOKIE['selected'];
      //}
    //}
	//$thetext = $_COOKIE['currentItem'];
	//$parts = $_POST['add'];
    //$thetext =  explode(':', $parts)[1];
	if(!isset($_SESSION['name'])) {
       $noLog = "WARNING: You are not logged in, therefore you are only allowed trial functionality for this website and it is assumed that you are only testing. With trial functionality you are only allowed to test the website using a maximum amount of 10 bill items";
      header("Location: https://luxitlegalbill.co.za/index.php");
	}
?>

<!DOCTYPE html>
<html>

  <?php include('header.php');?>
  <?php $_COOKIE['caseNr']; ?>
  <?php $_POST['case']; ?>
  <script>
  	$(document).ready(function() {

        var count = $('#count').text();
    	if(parseInt(count) > 0){
          $('#btnGen').prop('disabled', false);
          $('#btnGen').css("display", "inline");
        }else {
          $('#btnGen').prop('disabled', true);
          $('#btnGen').css("display", "none");
        }
        $('#inpt').css("display", "none");
        $('#date').css("display", "none");
        $('#btnAdd').css("display", "none");
  		$('#selItem').change(function() {
          $('#errdate').text("");
          $('#errtype').text("");
          $('#errpm').text("");
          $('#errexceed').text("");
          try {
            $('#inpt').css("display", "block");
            $('#date').css("display", "flex");
            $('#btnAdd').css("display", "inline");
            $('#btnGen').css("display", "inline");
            var sel = $('#selItem').val();
            $.post("item_details.php", {
              selectedVal: sel
            }, function(data) {
              var arr = JSON.parse(data);
              $('#pm').css("display", "block");
              $('#pm2').css("display", "block");
              //console.log(arr['amount']);
              //console.log(arr['per_amount']);
              if(arr['desc_add'] == 1) {
                $('#type').css("display", "block");
                $('#type2').css("display", "block");
              }else {
                $('#type').css("display", "none");
                $('#type2').css("display", "none");
                $('#tyoe').attr('value', '');
              }
              if (arr['pm'] == null || arr['pm'] == "null") {
                if(arr['desc_add'] == 0) {
                  $('#inpt').css("display", "none");
                }else {
                  $('#inpt').css("display", "block");
                  $('#pm').css("display", "none");
                  $('#pm2').css("display", "none");
                  $('#pm').attr('value', '');
                }
              }else {
            	$('#inpt').css("display", "block");

                $('#pm').css("display", "flex");
              	$('#pm2').css("display", "flex");

                if(arr['pm'] == 'pg') {
                  $('#pm2').text("Pages");
                  $('#pm').attr("placeholder", "Pages");
                  //$('#pm').attr("value", 1);
                }else if(arr['pm'] == 'min') {
                  $('#pm2').text("Minutes");
                  $('#pm').attr("placeholder", "Minutes");
                }
                if(arr['amount'] != null && arr['amount'] != "null") {
                  $('#amount').attr('value', arr['amount']);
                }else {
                  $('#amount').attr('value', '');
                }
                if(arr['pm'] == 'null' || arr['pm'] == null) {
                  console.log(arr['pm']);
                  $('#pm').css("display", "none");
                  $('#pm2').css("display", "none");
                  $('#pm').attr('value', '');
                }
              }
            });
          } catch (e) {
           	console.log(e);
          }
        });
    	$('#btnAdd').click(function(event) {
          event.preventDefault();
          var count = $('#count').text();
          var date = $('#datepicker').val();
          var sel = $('#selItem').val();
          var type = $('#type').val();
          var pm = $('#pm').val();
          $('#errdate').text("");
          $('#errtype').text("");
          $('#errpm').text("");
          $('#errexceed').text("");
          $.post("help_add.php", {
            count: count,
            date: date,
            sel: sel,
            type: type,
            pm: pm
          }, function(data) {
            console.log(data);
            var arr = JSON.parse(data);
            var haserr = false;
            console.log(arr);

            if(arr["errdate"] != undefined) {
              $('#errdate').text(arr["errdate"]);
              haserr = true;
            }
            if(arr["errtype"] != undefined) {
              $('#errtype').text(arr["errtype"]);
              haserr = true;
            }
            if(arr["errpm"] != undefined) {
              $('#errpm').text(arr["errpm"]);
              haserr = true;
            }

            if(!haserr) {
              $('#btnGen').prop('disabled', false);
              $('#count').text(arr['count']);
              $('#type').val('');
              $('#pm').val('');
              $(datepicker).val('');
              if(arr['loggedAs']==undefined && arr['count']>9) {
                $('#errexceed').text("You cannot exceed 10 items without signing in");
                $('#btnAdd').prop('disabled', true);
              }
            }
          });
        });
    	$('#btnGen').click(function(event) {
          event.preventDefault();
          //$(location).attr('href', 'https://luxitlegalbill.co.za/bill.php');
          window.location.replace("bill.php");
        });
	});
  </script>

  <h3 class="redText"><?php echo $noLog;?></h3>
  <h3 id="sel"><?php print_r($selected);?></h3>
  <br>
  <?php echo $thetext; ?>
  <form action="add.php" method="POST">
    <div class="container">
      <div class="input-group col-sm-12">
        <div class="form-group col-sm-12">
          <label for="sel1">
            Choose item type:
          </label>
          <span class="caret"></span>
          <select name="add" class="form-control" id="selItem">
            <option value="0:placeholder">--Please Select an Option--</option>
          	<?php foreach($result as $item): ?>
                <option value="<?php echo $item['item_id'].':'.$item['description']; ?>">
                    <?php echo $item['description']; ?>
                </option>
             <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div id="date" class="input-group col-sm-12">
        <span class="input-group-addon col-sm-5 left" id="basic-addon1">Date:</span>
      <input id="datepicker" width="276" />
      <script>
          $('#datepicker').datepicker({
              uiLibrary: 'bootstrap4'
          });
      </script>
      </div>
        <h3 id="errdate" class="redText"></h3>
      <div id="inpt" class="pnl">
        <div class="input-group col-sm-12">
          <span id="type2" class="input-group-addon col-sm-5 left" id="basic-addon1">Type</span>
          <input type="text" id="type" class="form-control col-sm-5 right" placeholder="Type" aria-describedby="basic-addon1">
        </div>

        <h3 id="errtype" class="redText"></h3>
        <div class="input-group col-sm-12">
          <span id='pm2' class="input-group-addon col-sm-5 left" id="basic-addon1">Pages/Min</span>
          <input type="text" id='pm' class="form-control col-sm-5 right" placeholder="Pages/Min" aria-describedby="basic-addon1">
        </div>

        <h3 id="errpm" class="redText"></h3>
      </div>
      <div class="container">
        <a class="left">Item Count: <a id='count'><?php echo $count; ?></a></a>
        <h3 id="errexceed" class="redText"></h3>
        <input id="btnAdd" name="submit" type="submit" class="btn btn-primary center" value="Add This Item"/><br><br>
        <input id="btnGen" name="submit1" type="submit" class="btn btn-primary center" value="Generate Bill"/>
      </div>
    </div>
  </form>
  <br>

  <?php include('footer.php');?>
</html>
