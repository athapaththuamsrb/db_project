<?php

require_once('auth.php');
require_once('../utils/transaction.php');

$user = (new Authenticator())->checkAuth();

$username = $user->getUsername();
$type = $user->getType();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  manageTransaction($type, $username);
  die();
  
}

include($_SERVER['DOCUMENT_ROOT'] . '/views/employee/transactions.php');



