<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'reason'=>''];
    require_once('../utils/dbcon.php');
    $conn = DatabaseConn::get_conn();
    if (!isset($_POST['owner_id']) || !$_POST['owner_id'] || !isset($_POST['acc_no']) || !$_POST['acc_no'] || !isset($_POST['acc_type']) || !$_POST['acc_type'] || !isset($_POST['balance']) || !$_POST['balance'] || !isset($_POST['branch_id']) || !$_POST['branch_id']) {
        $response['reason'] = "error1";
        echo json_encode($response);
        die();
    }
    $owner_id = $_POST['owner_id']; $acc_no = $_POST['acc_no']; $acc_type = $_POST['acc_type']; $balance = $_POST['balance']; $branch_id = $_POST['branch_id'];
    if ($_POST['acc_type'] === "fd") {
        if (!isset($_POST['savings_acc_no']) || !$_POST['savings_acc_no'] || !isset($_POST['duration']) || !$_POST['duration']) {
            $response['reason'] = "error2";
            echo json_encode($response);
            die();
        }
        else{
            $savings_acc_no = $_POST['savings_acc_no']; $duration = $_POST['duration'];
            $result = $conn->create_account($owner_id, $acc_no, $acc_type, $balance, $branch_id, $savings_acc_no, $duration, "");
        }
    }
    elseif ($_POST['acc_type'] === "savings") {
        if (!isset($_POST['customer_type']) || !$_POST['customer_type']) {
            $response['reason'] = "error3";
            echo json_encode($response);
            die();
        }
        else{
            $customer_type = $_POST['customer_type'];
            $result = $conn->create_account($owner_id, $acc_no, $acc_type, $balance, $branch_id, "", "", $customer_type);
        }
    }
    elseif ($_POST['acc_type'] === "checking") {
        $result = $conn->create_account($owner_id, $acc_no, $acc_type, $balance, $branch_id, "", "", "");
    }
    $response['success'] = $result;
    $response['reason'] = "error4";
    echo json_encode($response);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/createAccount.php');
