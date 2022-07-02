<?php
require_once('../utils/dbcon.php');
$dbcon = DatabaseConn::get_conn();
$result = $dbcon->getPendingApprovalLoans();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Approve Loans</title>
    <style>
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }

        h1 {
            text-align: center;
            color: #006600;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT',
                ' Calibri', 'Trebuchet MS', 'sans-serif';
        }

        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }

        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        td {
            font-weight: lighter;
        }
    </style>

</head>

<body>
    <section>
        <h1>Approve Loans</h1>
                <table>
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
    </section>
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Loan Approve');
    ?>

    <script src="/scripts/common.js"></script>
    <script src="/scripts/manager/loanApprove.js"></script>
</body>

</html>