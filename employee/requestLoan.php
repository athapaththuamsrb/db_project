<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/patterns.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'reason' => ''];
    require_once('../utils/dbcon.php');
    $dbcon = DatabaseConn::get_conn();
    if (!isset($_POST['sav_acc']) || !$_POST['sav_acc'] || !isset($_POST['amount']) || !$_POST['amount'] || !isset($_POST['duration']) || !$_POST['duration']) {
        $response['reason'] = "Form should be filled correctly";
        echo json_encode($response);
        die();
    } else {
        if (!preg_match(ACC_NO_PATTERN, $_POST['sav_acc'])) {
            $response['reason'] = "Invalid account number";
            echo json_encode($response);
            die();
        }
        if (!preg_match(BALANCE_PATTERN, $_POST['amount'])) {
            $response['reason'] = "Invalid amount";
            echo json_encode($response);
            die();
        }
        if (!preg_match(DURATION_PATTERN, $_POST['duration'])) {
            $response['reason'] = "Invalid duration";
            echo json_encode($response);
            die();
        }
        if ($_POST['duration'] > 120) {
            $response['reason'] = "You cannot apply loan for more than 10 years";
            echo json_encode($response);
            die();
        }
        $result = $dbcon->requestLoan($_POST['sav_acc'], $_POST['amount'], $_POST['duration'], $user->getUsername());
        $response['success'] = $result['result'];
        $response['reason'] = $result['reason'];
        echo json_encode($response);
        die();
    }
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/employee/requestLoan.php');
