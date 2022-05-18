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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $error = null;

    if($dbconn->check_account($_POST["to_acc"]) === null){
        
        $error = "Invalid Account Number";
    }
    if($dbconn->check_account($_POST["from_acc"]) === 'savings'){
        
        $dbconn->check_transaction_count($_POST['from_acc']);
    }
    $balance = $dbconn->check_balance($username, $_POST['from_acc']);
    if($balance < $_POST['amount']){

        $error = "Insufficient Balance";
    }

}  
    


include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');

