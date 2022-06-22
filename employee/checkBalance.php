<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = ['success'=>false];
  if (!isset($_POST['owner_id']) || !$_POST['owner_id']) {
    echo json_encode($response);
    die();
  }
  if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
    echo json_encode($response);
    die();
  }
  $owner_id = $_POST['owner_id'];
  $acc_no = $_POST['acc_no'];

  require_once('../utils/checkBalanceHelper.php');
  getBalance($owner_id, $acc_no);
  die();
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/checkBalance.php');
