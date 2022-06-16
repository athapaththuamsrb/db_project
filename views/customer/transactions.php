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
    <title>Transactions</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>

    <?php @include(__DIR__ . '/../navbar.php'); ?>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ($error !== false) {
            print_r($error);
        }
    }
    ?>
    <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
        <h1>Tranfer Money</h1>
        <br />
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7">
                <form method="post" class="form-row align-items-center">
                    <div class="row">
                        <div class="col-3 item">
                            <label for="to_acc">TO ACCOUNT</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="to_acc" id="to_acc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 item">
                            <label for="from acc">FROM ACCOUNT</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <select name="from_acc" id="from_acc" required>
                                <option value="" disabled selected></option>
                                <?php foreach ($accounts as $x) { ?>
                                    <option value="<?php echo $x ?>"><?php echo $x ?></option>
                                <?php } ?>

                            </select>
                        </div>
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
                            <button type="submit" name="confirm" class="btn btn-info" style="width: 150%">
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
</body>

</html>