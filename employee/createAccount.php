<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = false;
    require_once('../utils/dbcon.php');
    $conn = DatabaseConn::get_conn();
    if (!isset($_POST['owner_id']) || !$_POST['owner_id'] || !isset($_POST['acc_no']) || !$_POST['acc_no'] || !isset($_POST['type']) || !$_POST['type'] || !isset($_POST['balance']) || !$_POST['balance'] || !isset($_POST['branch_id']) || !$_POST['branch_id']) {
        echo json_encode($result);
        die();
    }
    $owner_id = $_POST['owner_id']; $acc_no = $_POST['acc_no']; $type = $_POST['type']; $balance = $_POST['balance']; $branch_id = $_POST['branch_id'];
    if ($_POST['type'] === "fd") {
        if (!isset($_POST['savings_acc_no']) || !$_POST['savings_acc_no'] || !isset($_POST['duration']) || !$_POST['duration']) {
            echo json_encode($result);
            die();
        }
        else{
            $savings_acc_no = $_POST['savings_acc_no']; $duration = $_POST['duration'];
            $result = $conn->create_account($owner_id, $acc_no, $type, $balance, $branch_id, $savings_acc_no, $duration, "");
        }
    }
    elseif ($_POST['type'] === "savings") {
        if (!isset($_POST['customer_type']) || !$_POST['customer_type']) {
            echo json_encode($result);
            die();
        }
        else{
            $customer_type = $_POST['customer_type'];
            $result = $conn->create_account($owner_id, $acc_no, $type, $balance, $branch_id, "", "", $customer_type);
        }
    }
    elseif ($_POST['type'] === "checking") {
        $result = $conn->create_account($owner_id, $acc_no, $type, $balance, $branch_id, "", "", "");
    }
    echo json_encode($result);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/addAccount.php');
