<?php

require_once('auth.php');
require_once('../utils/transaction.php');

$user = (new Authenticator())->checkAuth();

$username = $user->getUsername();
$type = $user->getType();

$dbconn = DatabaseConn::get_conn();
$accounts = $dbconn->get_accounts_list($username);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    manageTransaction($type, $username);
    die();
    
  }


include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');






