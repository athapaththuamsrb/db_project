<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="/styles/all.css" />
  <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/styles/dashboard.css" />
  <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
  <title>Employee Dashboard</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php
  @include(__DIR__ . '/../dashboardNavbar.php'); ?>

  <div class="container">
    <h1>Employee Dashboard</h1>
    <br />

    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="addUser.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Add User
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="viewTransactionHistory.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            View Transaction History
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="checkBalance.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Check Balance
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="transactions.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Transaction
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="requestLoan.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Request Loans
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="enterInstallment.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Add Payments
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="createAccount.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Create Account
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="changePassword.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Change Password
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
  </div>
  <?php @include(__DIR__ . '/../footer.php'); ?>
</body>

</html>