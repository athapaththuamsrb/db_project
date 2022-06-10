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
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php
  @include(__DIR__ . '/../dashboardNavbar.php'); ?>

  <div class="container">
    <h1>Customer Dashboard</h1>
    <br />
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="applyLoan.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Apply Loan
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
        <a href="viewTransactionHistory.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            view Transaction History
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
  </div>
  <?php @include(__DIR__ . '/../footer.php'); ?>
</body>

</html>