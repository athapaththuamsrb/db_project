<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/addUser.php');
addUser($user, ['customer']);
