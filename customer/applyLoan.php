<?php
require_once('auth.php');
$user = (new Authenticator())->checkAuth();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  include($_SERVER['DOCUMENT_ROOT'] . '/views/customer/applyLoan.php');
  die();
}else{
  require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/applyLoan.php');
  applyLoan($user);

}


