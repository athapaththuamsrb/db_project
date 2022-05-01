<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/auth.php');
checkAuth();

include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/index.php');
