<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();
$type = $user->getType();


$username = $user->getUsername();

require_once('../utils/dbcon.php');
$dbconn = DatabaseConn::get_conn();
$accounts = $dbconn->get_accounts_list($username);


if($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) ){

    
    $to_acc = $_POST['to_acc'];
    $from_acc = $_POST['from_acc'];
    $amount = $_POST['amount'];

    $error = false;

    if($dbconn->check_account($to_acc) === null){
        
        $error = "Invalid Account Number";
    }

    else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){
        
        $error = "Transaction Limit Reached";
    }

    else if( $dbconn->check_balance($username, $from_acc) < $amount ){

       $error = "Insufficient Balance";
    }

    else{

        $status = $dbconn->transaction($from_acc, $to_acc, $username, $amount);
        header("Location: ".$_SERVER['PHP_SELF']);
        
    }

    
    //unset($_POST);
    
    

   
}

include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/transactions.php');






