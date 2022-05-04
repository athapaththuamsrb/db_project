<?php
require_once('../utils/auth.php');

class Authenticator extends AbstractAuthenticator{
    function __construct()
    {
        parent::__construct('admin');
    }
}