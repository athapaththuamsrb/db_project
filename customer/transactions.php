<?php

require_once('auth.php');
require_once('../utils/transaction.php');

$user = (new Authenticator())->checkAuth();

$username = $user->getUsername();
$type = $user->getType();

$dbconn = DatabaseConn::get_conn();
$accounts = $dbconn->get_accounts_list($username);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success'=>false];
    if (!isset($_POST['to_acc']) || !$_POST['to_acc']) {
      echo json_encode($response);
      die();
    }
    if (!isset($_POST['from_acc']) || !$_POST['from_acc']) {
      echo json_encode($response);
      die();
    }
    if (!isset($_POST['amount']) || !$_POST['amount']) {
      echo json_encode($response);
      die();
    }
    
  
    manageTransaction($type, $username);
    die();
    
  }


include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');






