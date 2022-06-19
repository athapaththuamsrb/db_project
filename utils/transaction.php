<?php


require_once('../utils/dbcon.php');


function manageTransaction(string $type, string $username){

    $response = ['success'=> false];
    $dbconn = DatabaseConn::get_conn();

    if($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) && $type === 'customer'){

        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
    
        $status = false;
        if($dbconn->check_account($to_acc) === null){
            $msg = "Invalid Account Number";
        }
        else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){
            $msg = "Transaction Limit Reached";
        }
    
        else if( $dbconn->check_balance($username, $from_acc) < $amount ){
            $msg = "Insufficient Balance";
        }
    
        else{
            $status = $dbconn->transaction($from_acc, $to_acc, $username, $amount);
            ($status) ? $msg= "Transaction Successful" : $msg=  "Transaction Failed";
             
        }
        $response['success'] = $status;
        $response['msg'] = $msg;

    }
    elseif($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['confirm']) && $type === 'employee' ){

        $ownername = $_POST['ownername'];
        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
    
        $status = false;
        if(!$dbconn->check_username($ownername) ){
            $msg = "Invalid User Name";
        }
        else if($dbconn->check_account($from_acc) === null){    
            $msg = "Invalid From Account Number";
        }     
        else if(!$dbconn->get_account_ownership($from_acc, $ownername)){
            $msg = "Username and Account Number don't match";
        }
        else if($dbconn->check_account($to_acc) === null){    
            $msg = "Invalid To Account Number";
        }
        else if($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5){        
            $msg = "Transaction Limit Reached";
        }
    
        else if( $dbconn->check_balance($ownername, $from_acc) < $amount ){
           $msg = "Insufficient Balance";
        }
    
        else{
            $status = $dbconn->transaction($from_acc, $to_acc, $ownername, $amount);
            ($status) ? $msg= "Transaction Successful" : $msg=  "Transaction Failed";

        }
        $response['success'] = $status;
        $response['msg'] = $msg;
        
        
        
    
       
    }

    echo json_encode($response);
    die();

}
