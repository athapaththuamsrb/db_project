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
  <title>Manager Dashboard</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php @include(__DIR__ . '/../dashboardNavbar.php'); ?>

  <div class="container">
    <h1>Manager Dashboard</h1>
    <br />
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="addUser.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Add Employee
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="loanApprove.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Approve Loan
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row item">
      <div class="col-4"></div>
      <div class="col-4">
        <a href="report.php"><button style="height: 10vh; width: 100%; font-size: larger" type="button" class="btn btn-primary fun-item fade">
            Report
          </button></a>
      </div>
      <div class="col-4"></div>
    </div>
  </div>
  <?php @include(__DIR__ . '/../footer.php'); ?>
</body>

</html>