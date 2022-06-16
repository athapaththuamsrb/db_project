<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/styles/form.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <title>View Transaction History</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
    <?php @include(__DIR__ . '/../navbar.php'); ?>

    <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
        <h1>Check Your Account Balance</h1>
        <br />
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7">
                <form method="POST" class="form-row align-items-center">
                    <div class="row">
                        <div class="col-3 item">
                            <label for="owner_id"> Owner ID</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="owner_id" id="owner_id" onkeypress="keyPressFn(event, 'acc_no')" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 item">
                            <label for="acc_no">Account No</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="acc_no" id="acc_no" onkeypress="keyPressFn(event, 'start_date')" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 item">
                            <label for="start_date">Start Date</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="date" name="start_date" id="start_date" onkeypress="keyPressFn(event, 'end_date')" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 item">
                            <label for="end_date">End Date</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="date" name="end_date" id="end_date" onkeypress="keyPressFn(event, '')" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-4 item">
                            <button id="submitBtn" type="submit" class="btn btn-info" style="width: 150%">
                                View History
                            </button>
                        </div>
                        <div class="col-1"></div>
                    </div>
                </form>
            </div>
            <div class="col-2"></div>
        </div>
    </div>

    <?php @include(__DIR__ . '/../footer.php'); ?>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Place Appointment');
    ?>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/employee/viewTransactionHistory.js"></script>

</body>

</html>