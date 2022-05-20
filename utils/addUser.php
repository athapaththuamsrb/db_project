<?php
function fail()
{
    //header('Location: addUser.php');
    echo json_encode(['success' => false]);
    die();
}

function addUser(User $creator, array $allowed)
{
    if ($_SERVER['REQUEST_METHOD']==='GET'){
        $TYPES = ['admin' => 'Administrator', 'manager' => 'Manager', 'employee' => 'Employee', 'customer' => 'Customer'];
        $types = [];
        foreach ($allowed as $type) {
            $types[$type] = $TYPES[$type];
        }
        include($_SERVER['DOCUMENT_ROOT'] . '/views/addUser.php');
        die();
    }

    if (!(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] && $_POST['password'])){
        fail();
    }
    
    if (isset($_POST['type']) && $_POST['type']){
        $type = $_POST['type'];
    }else if (sizeof($allowed)===1){
        $type = $allowed[0];
    }else{
        fail();
    }
    
    if (!in_array($type, $allowed, true)){
        fail();
    }
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/utils/dbcon.php');
    $dbcon = DatabaseConn::get_conn();
    if ($dbcon->createAccount($_POST['username'], $_POST['password'], $type, $creator->getUsername())){
        //header('Location: index.php');
        echo json_encode(['success' => true]);
        die();
    }else{
        fail();
    }
}

?>