<?php
require_once('auth.php');
(new Authenticator())->checkAuth();

include($_SERVER['DOCUMENT_ROOT'] . '/views/employee/index.php');
?>