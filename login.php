<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    session_start();
    $_SESSION['logged_in'] = false;
    $_SESSION['target'] = null;
    $_SESSION['type'] = null;
    session_write_close();
    header('Location: /login.php');
    die();
}

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    session_write_close();
    header('Location: /index.php');
    die();
}
session_write_close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('utils/dbcon.php');
    if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] && $_POST['password']) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbcon = DatabaseConn::get_conn();
        $type = null;
        if ($dbcon != null) {
            $type = $dbcon->auth($username, $password);
        }
        if ($type != null) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['type'] = $type;
            $target = "/$type/index.php";
            if (isset($_SESSION['target']) && $_SESSION['target'] != null) {
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