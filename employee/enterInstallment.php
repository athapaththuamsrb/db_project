<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();
$type = $user->getType();


//$username = $user->getUsername();
// function fail()
// {
//   header('Location: enterInstallment.php');
//   //echo json_encode(['status' => false]);
//   die();
// }

require_once('../utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = ['success' => false, 'reason' => ''];

  if (!isset($_POST['loan_id']) || !$_POST['loan_id'] || !isset($_POST['amount']) || !$_POST['amount']) {
    $response['reason'] = "Form should be filled correctly";
    echo json_encode($response);
    die();
  }else if($_POST['amount']<0){
    $response['reason'] = "Amount cannot be negative!";
    echo json_encode($response);
    die();

  }else{
    if (!preg_match('/^([0-9]+(\.?[0-9]?[0-9]?)?)$/', $_POST['amount'])) {
      $response['reason'] = "Invalid balance amount";
      echo json_encode($response);
      die();
    }
    $result = $dbcon->enter_Installment($_POST['loan_id'], $_POST['amount'], $user->getUsername());
    $response['success'] = $result['result'];
    $response['reason'] = $result['reason'];
    echo json_encode($response);
    die();

  }


  // $ownername = $_POST['ownername'];
  // $to_acc = $_POST['to_acc'];
  // $from_acc = $_POST['from_acc'];
  // $amount = $_POST['amount'];

  // $error = false;

  // if (!$dbconn->check_username($ownername)) {
  //   $error = "Invalid User Name";
  // } else if ($dbconn->check_account($from_acc) === null) {
  //   $error = "Invalid From Account Number";
  // } else if (!$dbconn->get_account_ownership($from_acc, $ownername)) {
  //   $error = "Username and Account Number don't match";
  // } else if ($dbconn->check_account($to_acc) === null) {
  //   $error = "Invalid To Account Number";
  // } else if ($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5) {
  //   $error = "Transaction Limit Reached";
  // } else if ($dbconn->check_balance($ownername, $from_acc) < $amount) {
  //   $error = "Insufficient Balance";
  // } else {

  //   $status = $dbconn->transaction($from_acc, $to_acc, $ownername, $amount);
  //   header("Location: " . $_SERVER['PHP_SELF']);
  // }


  //unset($_POST);




}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/enterInstallment.php');
