<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();

/*
$type = $user->getType();
if ($type != "customer") {
    echo json_encode(null);
    die();
}
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $balance = null;
    if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
      echo json_encode($balance);
      die();
    }
    $owner_id = $user->getUsername();
    $acc_no = $_POST['acc_no'];
    require_once('../utils/checkBalanceHelper.php');
    getBalance($owner_id, $acc_no);
  }

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/customer/checkBalance.php');
