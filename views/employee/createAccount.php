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
  <title>Create Account</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php @include(__DIR__ . '/../navbar.php'); ?>

  <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
    <h1>Create Account</h1>
    <br />
    <div class="row">
      <div class="col-3"></div>
      <div class="col-7">
        <form method="POST" class="form-row align-items-center">
          <div class="row">
            <div class="col-4 item">
              <label for="owner_id">Owner ID : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="owner_id" id="owner_id" required />
            </div>
          </div>

          <div class="row">
            <div class="col-4 item">
              <label for="acc_no">Account No : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="acc_no" id="acc_no" required />
            </div>
          </div>

          <div class="row">
            <div class="col-4 item">
              <label for="type">Choose account type : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <select name="type" id="type">
                <option value="savings" selected>Savings</option>
                <option value="checking">Checking</option>
                <option value="fd">Fixed Deposit</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-4 item">
              <label for="balance"> Balance : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="balance" id="balance" required />
            </div>
          </div>

          <!-- should be visible only if fd is selected for type -->
          <div id="fd_visible" style="display: none">
            <div class="row">
              <div class="col-4 item">
                <label for="duration">Duration: </label>
              </div>
              <div class="col-1 item"></div>
              <div class="col-3 item">
                <select name="duration" id="duration">
                  <?php
                  foreach($fd_arr as $key=>$value){
                  ?>
                  <option value="<?php echo htmlentities($key, ENT_QUOTES | ENT_HTML5); ?>"><?php echo htmlentities($value, ENT_HTML5); ?></option>
                  <?php
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-4 item">
                <label for="savings_acc_no">Savings Account Number: </label>
              </div>
              <div class="col-1 item"></div>
              <div class="col-3 item">
                <input type="text" name="savings_acc_no" id="savings_acc_no" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-2"></div>
            <div class="col-4 item">
              <button id="submitBtn" type="submit" class="btn btn-info" style="width: 150%">
                Create Account
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
  addModal('Create Account');
  ?>
  <script src="/scripts/common.js"></script>
  <script src="/scripts/employee/createAccount.js"></script>
</body>

</html>