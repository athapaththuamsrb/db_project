<?php


require_once('../utils/dbcon.php');


function manageTransaction(string $type, string $username){

    $response = ['success'=> false];
    $dbconn = DatabaseConn::get_conn();

    if( $_SERVER['REQUEST_METHOD'] === 'POST' && $type == "customer" ){
  
        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
    
        $status = false;

        if(!preg_match('/^[0-9]+(\.[0-9]{2})?$/', $amount)){
            $msg = "Please enter a valid amount";
        }
        else if($to_acc === $from_acc){
            $msg = "Transfer to same account";
        }
        else if( !preg_match('/^[0-9]{12}$/', $to_acc) || $dbconn->check_account($to_acc) === null ){
            $msg = "Invalid Account Number";
        }
        else if(!$dbconn->get_account_ownership($from_acc, $username)){
            $msg = "Invalid transaction";
        }
        else if($dbconn->check_account($from_acc) === 'fixed' || $dbconn->check_account($to_acc) === 'fixed'){
            $msg = "Transactions cannot be done on fixed deposits";
        }
        else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){
            $msg = "Transaction Limit Reached";
        }
        else if( ($dbconn->check_min_balance($username, $from_acc)) < $amount ){
            $msg = "Insufficient Balance";
        }
        else{
            $status = $dbconn->transaction($from_acc, $to_acc, $username, $amount);
            ($status === true) ? $msg= "Transaction Successful" : $msg=  "Transaction Failed";    
        }
        $response['success'] = $status;
        $response['msg'] = $msg;

    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST'   && $type === 'employee' ){

        $ownername = $_POST['ownername'];
        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
    
        $status = false;
        if(!preg_match('/^[0-9]+(\.[0-9]{2})?$/', $amount)){
            $msg = "Please enter a valid amount";
        }
        else if($to_acc === $from_acc){
            $msg = "Transfer to same account";
        }
        else if(!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $ownername) || !$dbconn->check_username($ownername) ){
            $msg = "Invalid User Name";
        }
        else if(!preg_match('/^[0-9]{12}$/', $from_acc) || $dbconn->check_account($from_acc) === null  ){    
            $msg = "Invalid From Account Number";
        } 
        else if(!preg_match('/^[0-9]{12}$/', $to_acc) || $dbconn->check_account($to_acc) === null ){    
            $msg = "Invalid To Account Number";
        }
        else if($dbconn->check_account($from_acc) === 'fixed' || $dbconn->check_account($to_acc) === 'fixed'){
            $msg = "Transactions cannot be done on fixed deposits";
        }  
        else if(!$dbconn->get_account_ownership($from_acc, $ownername)){
            $msg = "Username and Account Number don't match";
        }
        else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){        
            $msg = "Transaction Limit Reached";
        }    
        else if( $dbconn->check_min_balance($ownername, $from_acc) < $amount ){
           $msg = "Insufficient Balance";
        }    
        else{
            $status = $dbconn->transaction($from_acc, $to_acc, $ownername, $amount);
            ($status === true) ? $msg= "Transaction Successful" : $msg=  "Transaction Failed";

        }
        $response['success'] = $status;
        $response['msg'] = $msg;
        
       
    }

    echo json_encode($response);
    die();

}
