<?php
require_once('auth.php');
(new Authenticator())->checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/applyLoan.php');
  die();
}
