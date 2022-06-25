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
  } else {
    $result = $dbcon->apply_loan($_POST['fix_acc'], $_POST['amount'], $_POST['duration'], $user->getUsername());
    $response['success'] = $result['result'];
    $response['reason'] = $result['reason'];
    echo json_encode($response);
    die();
   
  }
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/customer/applyLoan.php');
