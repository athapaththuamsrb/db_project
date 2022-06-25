<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'reason'=>''];

    require_once('../utils/dbcon.php');
    $conn = DatabaseConn::get_conn();
    if (!isset($_POST['owner_id']) || !$_POST['owner_id'] || !isset($_POST['acc_no']) || !$_POST['acc_no'] || !isset($_POST['acc_type']) || !$_POST['acc_type'] || !isset($_POST['balance']) || !$_POST['balance'] || !isset($_POST['branch_id']) || !$_POST['branch_id']) {
        $response['reason'] = "Insufficient data";
        echo json_encode($response);
        die();
    }
    $owner_id = $_POST['owner_id']; $acc_no = $_POST['acc_no']; $acc_type = $_POST['acc_type']; $balance = $_POST['balance']; $branch_id = $_POST['branch_id'];

    if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $owner_id)) {
        $response['reason'] = "Invalid username";
        echo json_encode($response);
        die();
    }
    if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $acc_no)) { /* change pattern */
        $response['reason'] = "Invalid account number";
        echo json_encode($response);
        die();
    }
    if (!preg_match('/^([0-9]+(\.?[0-9]?[0-9]?)?)$/', $balance)) {
        $response['reason'] = "Invalid balance amount";
        echo json_encode($response);
        die();
    }
    if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $branch_id)) { /* change pattern */
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
            if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $savings_acc_no)) { /* change pattern */
                $response['reason'] = "Invalid savings account number";
                echo json_encode($response);
                die();
            }
            if ($duration != "6" && $duration != "12" && $duration != "18"){
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
    $response['reason'] = "";
    $response['created_acc'] = $result['created_acc'];
    echo json_encode($response);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/createAccount.php');
