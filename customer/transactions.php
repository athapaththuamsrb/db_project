<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();

$username = $user->getUsername();
$type = $user->getType();

require_once('../utils/transactions.php');

$dbconn = DatabaseConn::get_conn();
$accounts = $dbconn->get_accounts_list($username);



include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');






