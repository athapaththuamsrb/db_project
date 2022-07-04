<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = ['success' => false, 'reason' => ''];
  require_once('../utils/dbcon.php');
  $dbcon = DatabaseConn::get_conn();
  if (!isset($_POST['fix_acc']) || !$_POST['fix_acc'] || !isset($_POST['amount']) || !$_POST['amount'] || !isset($_POST['duration']) || !$_POST['duration']) {
    $response['reason'] = "Form should be filled correctly";
    echo json_encode($response);
    die();
  } else if ($_POST['amount'] < 0) {
    $response['reason'] = "Amount cannot be negative!";
    echo json_encode($response);
    die();
  } else {
    if (!preg_match('/^[0-9]{12}$/', $_POST['fix_acc'])) {
      $response['reason'] = "Invalid account number";
      echo json_encode($response);
      die();
    }
    if (!preg_match('/^([0-9]+(\.?[0-9]?[0-9]?)?)$/', $_POST['amount'])) {
      $response['reason'] = "Invalid balance amount";
      echo json_encode($response);
      die();
    }
    if (!preg_match('/^[0-9]{1,3}$/', $_POST['duration'])) {
      $response['reason'] = "Invalid duration";
      echo json_encode($response);
      die();
    }
    if ($_POST['duration']>120) {
      $response['reason'] = "You cannot apply loan for more than 10 years";
      echo json_encode($response);
      die();
    }
    $result = $dbcon->apply_loan($_POST['fix_acc'], $_POST['amount'], $_POST['duration'], $user->getUsername());
    $response['success'] = $result['result'];
    $response['reason'] = $result['reason'];
    echo json_encode($response);
    die();
   
  }
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/customer/applyLoan.php');
