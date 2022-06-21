<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD']==='GET'){
    @include($_SERVER['DOCUMENT_ROOT'] . '/views/manager/report.php');
    die();
}