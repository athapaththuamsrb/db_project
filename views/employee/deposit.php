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
    <title>Withdrawal</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>

    <?php @include(__DIR__ . '/../navbar.php'); ?>

    <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
        <h1>Deposit or Withdraw money</h1>
        <br />
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7">
                <form method="post" class="form-row align-items-center">
                    <div class="row">
                        <div class="col-3 item">
                            <label for="ownername">USER NAME</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="ownername" id="ownername" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3 item">
                            <label for="from_acc">ACCOUNT</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="from_acc" id="from_acc" required>
                        </div>
                    </div>

                    <div>
                        <label for="dw">SELECT</label>
                        <label for="deposit">DEPOSIT</label>
                        <input type="radio" name="dw" id="deposit" value="deposit" required>
                        <label for="withdraw">WITHDRAW</label>
                        <input type="radio" name="dw" id="withdraw" value="withdraw">

                    </div>

                    <div class="row">
                        <div class="col-3 item">
                            <label for="amount">AMOUNT</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="number" name="amount" id="amount" min="0.00" step="0.01" required />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-4 item">
                            <button type="submit" name="confirm" id="submitBtn" class="btn btn-info" style="width: 150%">
                                Confirm
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
    include_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Deposit/Withdrawal');
    ?>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/employee/deposit.js"></script>
</body>

</html>