<?php

function getBalance($owner_id, $acc_no) {
    $response = ['success'=>false];
    if (strlen($owner_id) < 3 || strlen($owner_id) > 12) { // change according to relavent constraints
        echo json_encode($response);
        die();
      }
      if (strlen($acc_no) < 3 || strlen($acc_no) > 12) { // change according to relavent constraints
        echo json_encode($response);
        die();
      }
      require_once('../utils/dbcon.php');
      $conn = DatabaseConn::get_conn();
      if ($conn) {
        $balance = $conn->check_balance($owner_id, $acc_no);
        if ($balance >= 0){
          $response['success'] = true;
          $response['balance'] = $balance;
        }
      }
      echo json_encode($response);
      die();
}