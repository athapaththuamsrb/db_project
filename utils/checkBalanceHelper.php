<?php

function getBalance($owner_id, $acc_no) {
    $balance = null;
    if (strlen($owner_id) < 3 || strlen($owner_id) > 12) { // change according to relavent constraints
        echo json_encode($balance);
        die();
      }
      if (strlen($acc_no) < 3 || strlen($acc_no) > 12) { // change according to relavent constraints
        echo json_encode($balance);
        die();
      }
      require_once('../utils/dbcon.php');
      $conn = DatabaseConn::get_conn();
      if ($conn) {
        $balance = $conn->check_balance($owner_id, $acc_no);
      }
      echo json_encode($balance);
      die();
}