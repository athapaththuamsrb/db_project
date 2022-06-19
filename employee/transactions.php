<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();

$username = $user->getUsername();
$type = $user->getType();


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
     if (!isset($_POST['ownername']) || !$_POST['ownername']) {
        echo json_encode($response);
        die();
    }
    
    require_once('../utils/transaction.php');
    manageTransaction($type, $username);
    die();
  }


include($_SERVER['DOCUMENT_ROOT'] . '/views/employee/transactions.php');



