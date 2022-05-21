<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD']==='GET'){
    @include($_SERVER['DOCUMENT_ROOT'] . '/views/admin/addBranch.php');
    die();
}

function fail($reason=null)
{
    $status = ['success' => false];
    if ($reason!=null){
        $status['reason'] = $reason;
    }
    echo json_encode($status);
    die();
}

if (!(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['location']) && isset($_POST['manager']) && $_POST['id'] && $_POST['name'] && $_POST['location'] && $_POST['manager'])){
    fail();
}

$branch_id = $_POST['id'];
$branch_name = $_POST['name'];
$location = $_POST['location'];
$manager = $_POST['manager'];
$creator = $user->getUsername();

if (!preg_match('/^[0-9]{1,5}$/', $branch_id)){
    fail('Invalid branch ID');
}
if (!preg_match('/^[a-zA-Z0-9.\-\x20]{2,30}$/', $branch_name)){
    fail('Invalid branch name');
}
if (!preg_match('/^[a-zA-Z0-9.,\/\-\x20]{2,50}$/', $location)){
    fail('Invalid location');
}
if (!preg_match('/^[a-zA-Z0-9._]{5,12}$/', $manager)){
    fail('Invalid manager ID');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();
if ($dbcon->createBranch($branch_id, $branch_name, $location, $manager, $creator)){
    echo json_encode(['success' => true]);
    die();
}else{
    fail();
}