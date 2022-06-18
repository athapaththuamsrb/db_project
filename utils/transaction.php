<?php

function fail()
{
    echo json_encode(['success' => false]);
    die();
}
require_once('../utils/dbcon.php');


function manageTransaction(string $type, string $username){

    $dbconn = DatabaseConn::get_conn();
    if($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) && $type === 'customer'){

    
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
       
    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) && $type === 'employee' ){

        $ownername = $_POST['ownername'];
        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
    
        $error = false;
    
        if(!$dbconn->check_username($ownername) ){
            $error = "Invalid User Name";
        }
        else if($dbconn->check_account($from_acc) === null){    
            $error = "Invalid From Account Number";
        }     
        else if(!$dbconn->get_account_ownership($from_acc, $ownername)){
            $error = "Username and Account Number don't match";
        }
        else if($dbconn->check_account($to_acc) === null){    
            $error = "Invalid To Account Number";
        }
        else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){        
            $error = "Transaction Limit Reached";
        }
    
        else if( $dbconn->check_balance($ownername, $from_acc) < $amount ){
           $error = "Insufficient Balance";
        }
    
        else{
            $status = $dbconn->transaction($from_acc, $to_acc, $ownername, $amount);
            header("Location: ".$_SERVER['PHP_SELF']);           
        }
    
        
        //unset($_POST);
        
        
    
       
    }

}

?>