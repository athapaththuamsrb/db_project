<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $balance = null;
  if (!isset($_POST['owner_id']) || !$_POST['owner_id']) {
    echo json_encode($balance);
    die();
  }
  if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
    echo json_encode($balance);
    die();
  }
  $owner_id = $_POST['owner_id'];
  $acc_no = $_POST['acc_no'];

  require_once('../utils/checkBalanceHelper.php');
  getBalance($owner_id, $acc_no);
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/checkBalance.php');
