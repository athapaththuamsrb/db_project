<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();
$type = $user->getType();


//$username = $user->getUsername();

require_once('../utils/dbcon.php');
$dbconn = DatabaseConn::get_conn();



include($_SERVER['DOCUMENT_ROOT'] . '/views/employee/transactions.php');






