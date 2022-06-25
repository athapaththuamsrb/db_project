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
  <title>Apply Loan</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php
  @include(__DIR__ . '/../navbar.php'); ?>

  <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
    <h1>Apply Loan</h1>
    <br />
    <div class="row">
      <div class="col-3"></div>
      <div class="col-7">
        <form method="post" class="form-row align-items-center">
          <div class="row">
            <div class="col-3 item">
              <label for="fix_acc">Fixed Deposit Account Number : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="fix_acc" id="fix_acc" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="fix_acc">Duration (Months) : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="duration" id="duration" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="amount">AMOUNT :</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="number" name="amount" id="amount" min="0.00" step="0.01" required />
            </div>
          </div>
          <div class="row">
            <div class="col-2"></div>
            <div class="col-4 item">
              <button id="submitBtn" type="submit" class="btn btn-info" style="width: 150%">
                apply Loan
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
  addModal('Apply Loan');
  ?>
  <script src="/scripts/common.js"></script>
  <script src="/scripts/customer/applyLoan.js"></script>
</body>

</html>