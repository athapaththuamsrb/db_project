<?php
define('USERNAME_PATTERN', '/^[a-zA-Z0-9._]{5,12}$/');
define('PASSWORD_PATTERN', '/^[\x21-\x7E]{8,15}$/');
define('ACC_NO_PATTERN', '/^[0-9]{12}$/');
define('BALANCE_PATTERN', '/^([0-9]+(\.?[0-9]?[0-9]?)?)$/');
define('BRANCH_ID_PATTERN', '/^[0-9]{1,5}$/');
define('LOAN_ID_PATTERN', '/^[0-9]{1,14}$/');
define('DURATION_PATTERN', '/^[0-9]{1,3}$/');
define('DATE_PATTERN', '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/');
