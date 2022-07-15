<?php
require_once('auth.php');
require_once('../utils/dbcon.php');
$user = (new Authenticator())->checkAuth();
$dbcon = DatabaseConn::get_conn();
$result = $dbcon->getPendingApprovalLoans($user->getUsername());
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/styles/table.css" />
    <link rel="stylesheet" href="/styles/form.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <title>Approve Loans</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />

</head>

<body>
    <?php @include(__DIR__ . '/../navbar.php'); ?>
    <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
        <section>
            <h1>Approve Loans</h1>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div id="table">
                        <table style="margin-bottom: 5%; margin-top: 1%; color:black">
                            <tr>
                                <th>loan ID</th>
                                <th>Total Amount</th>
                                <th>Date</th>

                                <th>Savings Account</th>
                                <th>Duration</th>
                                <th>Approve</th>
                            </tr>
                            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
                            <?php
                            // LOOP TILL END OF DATA
                            for ($x = 0; $x < sizeof($result); $x++) {
                            ?>
                                <tr>
                                    <!-- FETCHING DATA FROM EACH
					ROW OF EVERY COLUMN -->
                                    <td><?php print_r(htmlentities($result[$x][0], ENT_HTML5)); ?></td>
                                    <td><?php print_r(htmlentities($result[$x][1], ENT_HTML5)); ?></td>
                                    <td><?php print_r(htmlentities($result[$x][2], ENT_HTML5)); ?></td>

                                    <td><?php print_r(htmlentities($result[$x][3], ENT_HTML5)); ?></td>
                                    <td><?php print_r(htmlentities($result[$x][4], ENT_HTML5)); ?></td>
                                    <td><button class="button" value=<?php print_r(htmlentities($result[$x][0], ENT_QUOTES | ENT_HTML5)); ?>>Approve</button></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
        </section>
    </div>
    <?php @include(__DIR__ . '/../footer.php'); ?>
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Loan Approve');
    ?>

    <script src="/scripts/common.js"></script>
    <script src="/scripts/manager/loanApprove.js"></script>
</body>

</html>