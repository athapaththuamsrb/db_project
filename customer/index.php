<?php
require_once('auth.php');
(new Authenticator())->checkAuth();

@include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/index.php');
