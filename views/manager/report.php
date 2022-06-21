<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body>
    <h1>Generate Report</h1>
    <form method="post">
        <button id="submitBtn">Generate Report</button>
    </form>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Create Account');
    ?>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/manager/report.js"></script>
</body>

</html>