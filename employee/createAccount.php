<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();
if (! $user instanceof Employee){
    die();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/patterns.php');
require_once('../utils/dbcon.php');
$conn = DatabaseConn::get_conn();
$fd_arr = $conn ? $conn->fd_account_types() : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'reason'=>''];

    $branch_id = $user->getBranch();

    if (!isset($_POST['owner_id']) || !$_POST['owner_id'] || !isset($_POST['acc_no']) || !$_POST['acc_no'] || !isset($_POST['acc_type']) || !$_POST['acc_type'] || !isset($_POST['balance']) || !$_POST['balance'] || is_null($branch_id)) {
        $response['reason'] = "Insufficient data";
        echo json_encode($response);
        die();
    }
    $owner_id = $_POST['owner_id']; $acc_no = $_POST['acc_no']; $acc_type = $_POST['acc_type']; $balance = $_POST['balance'];

    if (!preg_match(USERNAME_PATTERN, $owner_id)) {
        $response['reason'] = "Invalid username";
        echo json_encode($response);
        die();
    }
    if (!preg_match(ACC_NO_PATTERN, $acc_no)) { 
        $response['reason'] = "Invalid account number";
        echo json_encode($response);
        die();
    }
    if (!preg_match(BALANCE_PATTERN, $balance)) {
        $response['reason'] = "Invalid balance amount";
        echo json_encode($response);
        die();
    }
    if (!preg_match(BRANCH_ID_PATTERN, $branch_id)) {
        $response['reason'] = "Invalid branch ID";
        echo json_encode($response);
        die();
    }
    if ($_POST['acc_type'] === "fd") {
        if (!isset($_POST['savings_acc_no']) || !$_POST['savings_acc_no'] || !isset($_POST['duration']) || !$_POST['duration']) {
            $response['reason'] = "Insufficient data";
            echo json_encode($response);
            die();
        }
        else{
            $savings_acc_no = $_POST['savings_acc_no']; $duration = $_POST['duration'];
            if (!preg_match(ACC_NO_PATTERN, $savings_acc_no)) { 
                $response['reason'] = "Invalid savings account number";
                echo json_encode($response);
                die();
            }
            if (!array_key_exists($duration,$fd_arr)) {
                $response['reason'] = "Invalid duration";
                echo json_encode($response);
                die();
            }
            $result = $conn->create_account($owner_id, $acc_no, $acc_type, $balance, $branch_id, $savings_acc_no, $duration);
        }
    }
    elseif ($_POST['acc_type'] === "savings" || $_POST['acc_type'] === "checking") {
        $result = $conn->create_account($owner_id, $acc_no, $acc_type, $balance, $branch_id, "", "");
    }
    $response['success'] = $result['result'];
    if (isset($result['reason'])){
        $response['reason'] = $result['reason'];
    }
    $response['created_acc'] = $result['created_acc'];
    echo json_encode($response);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/createAccount.php');
