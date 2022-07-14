<?php
require_once('../utils/dbcon.php');
require_once('patterns.php');

function fail(string $msg)
{
    $response = ['success' => false, 'msg' => $msg];
    echo json_encode($response);
    die();
}
function manageTransaction(string $type, string $username)
{

    $response = ['success' => false];
    $dbconn = DatabaseConn::get_conn();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount']) && isset($_POST['from_acc']) && isset($_POST['to_acc'])) {

        $to_acc = $_POST['to_acc'];
        $from_acc = $_POST['from_acc'];
        $amount = $_POST['amount'];
        $status = false;
        if (!preg_match(BALANCE_PATTERN, $amount)) {
            fail("Please enter a valid amount");
        } else if ($to_acc === $from_acc) {
            fail("Transfer to same account");
        } else if (!preg_match(ACC_NO_PATTERN, $to_acc) || $dbconn->check_account($to_acc) === null) {
            fail("Invalid Account Number");
        } else if (!preg_match(ACC_NO_PATTERN, $from_acc) || $dbconn->check_account($from_acc) === null) {
            fail("Invalid From Account Number");
        } else if ($dbconn->check_account($from_acc) === 'fixed' || $dbconn->check_account($to_acc) === 'fixed') {
            fail("Transactions cannot be done on fixed deposits");
        } else if ($dbconn->check_account($from_acc) === 'savings' && $dbconn->check_transaction_count($from_acc) >= 5) {
            fail("Transaction Limit Reached");
        }
        if ($type == "customer") {
            if (!$dbconn->get_account_ownership($from_acc, $username)) {
                fail("Invalid transaction");
            }
        } elseif ($type === 'employee' && isset($_POST['ownername'])) {
            $ownername = $_POST['ownername'];

            if (!preg_match(USERNAME_PATTERN, $ownername) || !$dbconn->check_username($ownername)) {
                fail("Invalid User Name");
            } else if (!$dbconn->get_account_ownership($from_acc, $ownername)) {
                fail("Username and Account Number don't match");
            }
        }
        $status = $dbconn->transaction($from_acc, $to_acc, $username, $amount);
        if ($status != null) $response = $status;
    }

    echo json_encode($response);
    die();
}
