<?php
require_once('../utils/dbcon.php');
require_once('patterns.php');

function fail(string $msg)
{
    $response = ['success' => false, 'msg' => $msg];
    echo json_encode($response);
    die();
}

function manageDeposit(string $username)
{

    $response = ['success' => false];
    $dbconn = DatabaseConn::get_conn();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount']) && isset($_POST['from_acc']) && isset($_POST['dw'])) {

        $ownername = $_POST['ownername'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
        $dw = $_POST['dw'];
        $status = false;

        //fail("fuck");

        if (!preg_match(BALANCE_PATTERN, $amount)) {
            fail("Please enter a valid amount");
        } else if (!preg_match(ACC_NO_PATTERN, $from_acc) || $dbconn->check_account($from_acc) === null) {
            fail("Invalid Account Number");
        } else if (!preg_match(USERNAME_PATTERN, $ownername) || !$dbconn->check_username($ownername)) {
            fail("Invalid User Name");
        } else if ($dbconn->check_account($from_acc) === 'fixed') {
            fail("Transactions cannot be done on fixed deposits");
        } else if ($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5) {
            fail("Transaction Limit Reached");
        } else if (!$dbconn->get_account_ownership($from_acc, $ownername)) {
            fail("Username and Account Number don't match");
        } 
        if($dw === 'withdraw'){$type = 'WTDW';}
        else{$type = 'DPST';}
        $status = $dbconn->transaction($from_acc, null, $username, $amount, $type);
        if ($status != null) $response = $status;
    }

    echo json_encode($response);
    die();
}
