<?php
require_once('auth.php');
(new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD']==='GET'){
    include($_SERVER['DOCUMENT_ROOT'] . '/views/admin/addUser.php');
    die();
}

if (!(isset($_POST['type']) && isset($_POST['username']) && isset($_POST['password']) && $_POST['type'] && $_POST['username'] && $_POST['password'])){
    header('Location: addUser.php');
    echo json_encode(['status' => false]);
    die();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();
if ($dbcon->createAccount($_POST['username'], $_POST['password'], $_POST['type'])){
    header('Location: index.php');
    echo json_encode(['status' => true]);
}else{
    header('Location: addUser.php');
    echo json_encode(['status' => false]);
}

?>