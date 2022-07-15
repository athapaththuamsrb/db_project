<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/patterns.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    @include($_SERVER['DOCUMENT_ROOT'] . '/views/changePasswd.php');
    die();
}

function fail($reason = null)
{
    $status = ['success' => false];
    if ($reason != null) {
        $status['reason'] = $reason;
    }
    echo json_encode($status);
    die();
}

if (!(isset($_POST['curpass']) && isset($_POST['newpass']) && $_POST['curpass'] && $_POST['newpass'])) {
    fail();
}

$curpass = $_POST['curpass'];
$newpass = $_POST['newpass'];
$username = $user->getUsername();

if (!preg_match(PASSWORD_PATTERN, $curpass)) {
    fail('Invalid password');
}
if (!preg_match(PASSWORD_PATTERN, $newpass)) {
    fail('Invalid password');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();
if ($dbcon && $dbcon->changePasswd($username, $curpass, $newpass)) {
    echo json_encode(['success' => true]);
    die();
} else {
    fail();
}
