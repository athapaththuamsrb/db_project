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
    if (!preg_match('/^[0-9]{1,14}$/', $_POST['loan_id'])) {
      $response['reason'] = "Invalid loan ID";
      echo json_encode($response);
      die();
    }
    $result = $dbcon->enter_Installment($_POST['loan_id'], $_POST['amount'], $user->getUsername());
    $response['success'] = $result['result'];
    $response['reason'] = $result['reason'];
    echo json_encode($response);
    die();

  }




}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/enterInstallment.php');
