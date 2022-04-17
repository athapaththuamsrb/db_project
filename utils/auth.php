<?php
function checkAuth()
{
    session_start();
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        $_SESSION['target'] = $_SERVER['REQUEST_URI'];
        session_write_close();
        header('Location: /login.php');
        die();
    }else{
        $_SESSION['target'] = null;
        session_write_close();
    }
}