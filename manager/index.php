<?php
require_once('auth.php');
(new Authenticator())->checkAuth();
?>
<html>
    <body>
        Hello customer!
    </body>
</html>