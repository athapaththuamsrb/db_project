<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success'=>false];
    if (!isset($_POST['owner_id']) || !$_POST['owner_id']) {
        $response['reason'] = "Insufficient data";
        echo json_encode($response);
        die();
    }
    else {
        $owner_id = $_POST['owner_id'];
    }
    if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
        $response['reason'] = "Insufficient data";
        echo json_encode($response);
        die();
    }
    else {
        $acc_no = $_POST['acc_no'];
    }

    if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $owner_id)) {
        $response['reason'] = "Invalid username";
        echo json_encode($response);
        die();
    }
    if (!preg_match('/^[0-9]{12}$/', $acc_no)) { 
        $response['reason'] = "Invalid account number";
        echo json_encode($response);
        die();
    }

    if (!isset($_POST['start_date']) || !$_POST['start_date']) {
        $start_date = null;
    }
    else {
        $start_date = new DateTime($_POST['start_date']);
    }
    if (!isset($_POST['end_date']) || !$_POST['end_date']) {
        $end_date = null;
    }
    else {
        $end_date =new DateTime($_POST['end_date']);
    }
    require_once('../utils/dbcon.php');
    $conn = DatabaseConn::get_conn();
    if ($conn) {
        $data = $conn->view_transaction_history($owner_id, $acc_no, $start_date, $end_date);
    }
    $response['success'] = true;
    $response['data'] = $data;
    echo json_encode($response);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/viewTransactionHistory.php');