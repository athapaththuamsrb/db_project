<?php

function fail(){
    header('Location: applyLoan.php'); 
    //echo json_encode(['status' => false]);
    die();
}

function applyLoan(User $creator){
    
    if (!(isset($_POST['fix_acc']) && isset($_POST['amount']))) {
        fail();
    }

    require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/dbcon.php');
    $dbcon = DatabaseConn::get_conn();
    if ($dbcon->apply_loan($_POST['fix_acc'], $_POST['amount'], $_POST['duration'], $creator->getUsername())) {
        header('Location: index.php'); 
        die();
    } else {
        fail();
    }
}
