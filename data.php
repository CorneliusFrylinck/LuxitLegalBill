<?php
  if (isset($_GET['action'])) {
    setcookie('selected', $_GET['action']);
  }
?>
