<?php

require_once('auth.php');
$user = (new Authenticator())->checkAuth();
$type = $user->getType();


//$username = $user->getUsername();

require_once('../utils/dbcon.php');
$dbconn = DatabaseConn::get_conn();

if($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) ){

    $ownername = $_POST['ownername'];
    $to_acc = $_POST['to_acc'];
    $from_acc = $_POST['from_acc'];
    $amount = $_POST['amount'];

    $error = false;

    if(!$dbconn->check_username($ownername) )
    {
        $error = "Invalid User Name";
    }

    else if($dbconn->check_account($from_acc) === null)
    {    
        $error = "Invalid From Account Number";
    }
    
    else if(!$dbconn->get_account_ownership($from_acc, $ownername))
    {
        $error = "Username and Account Number don't match";
    }

    else if($dbconn->check_account($to_acc) === null)
    {    
        $error = "Invalid To Account Number";
    }

    

    else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5)
    {        
        $error = "Transaction Limit Reached";
    }

    else if( $dbconn->check_balance($ownername, $from_acc) < $amount )
    {
       $error = "Insufficient Balance";
    }

    else{

        $status = $dbconn->transaction($from_acc, $to_acc, $ownername, $amount);
        header("Location: ".$_SERVER['PHP_SELF']);
        
    }

    
    //unset($_POST);
    
    

   
}

include($_SERVER['DOCUMENT_ROOT'] . '/views/employee/transactions.php');






