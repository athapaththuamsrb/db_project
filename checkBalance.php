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
  if (strlen($owner_id) < 4 || strlen($owner_id) > 12) { // change according to relavent constraints
    echo json_encode($balance);
    die();
  }
  if (strlen($acc_no) < 4 || strlen($acc_no) > 12) { // change according to relavent constraints
    echo json_encode($balance);
    die();
  }
  require_once('utils/dbcon.php');
  $conn = DatabaseConn::get_conn();
  if ($conn) {
    $balance = $conn->check_balance($owner_id, $acc_no);
  }
  echo json_encode($balance);
  die();
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/checkBalance.php');
