<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();
$type = $user->getType();

if ($type != "customer") {
    echo json_encode(null);
    die();
}

$username = $user->getUsername();

require_once('../utils/dbcon.php');
$dbconn = DatabaseConn::get_conn();
$accounts = $dbconn->get_accounts_list($username);

/*
to account valdation
from account type
if savings trans count
check balance
*/
include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');

print_r($_SERVER['REQUEST_METHOD']);
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $error = false;

    if($dbconn->check_account($_POST["to_acc"]) === null){
        
        $error = "Invalid Account Number";
    }

    else if($dbconn->check_account($_POST["from_acc"]) === 'savings' && $dbconn->check_transaction_count($_POST['from_acc']) >= 5){
        
        $error = "Transaction Limit Reached";
    }

    else if( $dbconn->check_balance($username, $_POST['from_acc']) < $_POST['amount'] ){

        $error = "Insufficient Balance";
    }

    if($error !== false){
        print_r($error);
    }
    
}

 






