<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_start();
    $_SESSION['logged_in'] = false;
    $_SESSION['target'] = null;
    $_SESSION['user'] = null;
    session_write_close();
    header('Location: /login.php');
    die();
}

require_once('utils/User.php');

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['user']) && ($_SESSION['user'] instanceof User)) {
    session_write_close();
    $type = $_SESSION['user']->getType();
    header("Location: /$type/index.php");
    die();
}
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('utils/dbcon.php');
    if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] && $_POST['password']) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbcon = DatabaseConn::get_conn();
        $user = null;
        if ($dbcon != null) {
            $user = $dbcon->auth($username, $password);
        }
        if ($user != null) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;
            $type = $user->getType();
            $target = "/$type/index.php";
            if (isset($_SESSION['target']) && $_SESSION['target'] != null && preg_match("/^\\/$type/", $_SESSION['target'])) {
                $target = $_SESSION['target'];
            }
            $_SESSION['target'] = null;
            session_write_close();
            header('Location: ' . $target);
            die();
        }
    }
    header('Location: /login.php');
    die();
} else {
    include($_SERVER['DOCUMENT_ROOT'] . '/views/login.php');
}
?>