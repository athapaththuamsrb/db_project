<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/patterns.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'reason' => ''];
    require_once('../utils/dbcon.php');
    $dbcon = DatabaseConn::get_conn();
    if (!isset($_POST['loanID']) || !isset($_POST['loanID'])) {
        $response['reason'] = "Something Went Wrong";
        echo json_encode($response);
        die();
    } else {
        if (!preg_match(LOAN_ID_PATTERN, $_POST['loanID'])) {
            $response['reason'] = "Invalid loan ID";
            echo json_encode($response);
            die();
        }
        $result = $dbcon->loanApprove($_POST['loanID'], $user->getUsername());
        $response['success'] = $result['result'];
        $response['reason'] = $result['reason'];
        echo json_encode($response);
        die();
    }
}

@include_once($_SERVER['DOCUMENT_ROOT'] . '/views/manager/loanApprove.php');
?>
