<?php
require_once('../utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();
$result = $dbcon->getPendingApprovalLoans();
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
            <div id="table">
                <table class="d-flex align-items-center justify-content-center">
                    <tr>
                        <th>loan ID</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                        <th>Customer</th>
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
                            <td><?php print_r($result[$x][0]); ?></td>
                            <td><?php print_r($result[$x][1]); ?></td>
                            <td><?php print_r($result[$x][2]); ?></td>
                            <td><?php print_r($result[$x][3]); ?></td>
                            <td><?php print_r($result[$x][4]); ?></td>
                            <td><?php print_r($result[$x][5]); ?></td>
                            <td><button class="button" value=<?php print_r($result[$x][0]); ?>>Approve</button></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </section>
        <?php @include(__DIR__ . '/../footer.php'); ?>
        <?php
        include_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
        addModal('Loan Approve');
        ?>

        <script src="/scripts/common.js"></script>
        <script src="/scripts/manager/loanApprove.js"></script>
</body>

</html>