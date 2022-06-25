<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = ['success'=>false];
  if (!isset($_POST['owner_id']) || !$_POST['owner_id']) {
    $response['reason'] = "Insufficient data";
    echo json_encode($response);
    die();
  }
  if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
    $response['reason'] = "Insufficient data";
    echo json_encode($response);
    die();
  }
  $owner_id = $_POST['owner_id'];
  $acc_no = $_POST['acc_no'];

  if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $owner_id)) {
    $response['reason'] = "Invalid username";
    echo json_encode($response);
    die();
  }
  if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $acc_no)) { /* change pattern */
      $response['reason'] = "Invalid account number";
      echo json_encode($response);
      die();
  }

  require_once('../utils/checkBalanceHelper.php');
  getBalance($owner_id, $acc_no);
  die();
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/checkBalance.php');
