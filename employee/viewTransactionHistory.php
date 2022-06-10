<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array();
    if (!isset($_POST['owner_id']) || !$_POST['owner_id']) {
        echo json_encode($data);
        die();
    }
    else {
        $owner_id = $_POST['owner_id'];
    }
    if (!isset($_POST['acc_no']) || !$_POST['acc_no']) {
        echo json_encode($data);
        die();
    }
    else {
        $acc_no = $_POST['acc_no'];
    }
    if (strlen($acc_no) < 4 || strlen($acc_no) > 12) { // change according to relavent constraints
        echo json_encode($data);
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
    echo json_encode($data);
    die();
}
@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/viewTransactionHistory.php');