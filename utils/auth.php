<?php
require_once('User.php');

abstract class AbstractAuthenticator
{
    /** @var \string */
    private $type;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

    private function redirect()
    {
        $_SESSION['target'] = $_SERVER['REQUEST_URI'];
        session_write_close();
        header('Location: /login.php');
        die();
    }

    public final function checkAuth(): User
    {
        session_start();
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || !isset($_SESSION['user']) || !$_SESSION['user']) {
            $this->redirect();
        }
        $user = $_SESSION['user'];
        if (!($user instanceof User) || $user->getType()!==$this->type){
            $this->redirect();
        } else {
            $_SESSION['target'] = null;
            session_write_close();
        }
        return $user;
    }
}