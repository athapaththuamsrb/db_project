<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="/styles/all.css" />
  <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
  <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  <style>
    h1 {
      text-align: center;
    }

    a {
      margin-right: 2%;
    }

    span {
      margin-left: 2%;
    }

    nav {
      border-bottom: Maroon solid 2px;
    }

    .navbar {
      position: relative;
      animation-name: nav-header;
      animation-duration: 1s;
    }

    @keyframes nav-header {
      0% {
        top: -40px;
      }

      100% {
        left: 0px;
        top: 0px;
      }
    }

    .fun-item:hover {
      -ms-transform: scale(1.05);
      /* IE 9 */
      -webkit-transform: scale(1.05);
      /* Safari 3-8 */
      transform: scale(1.05);
      background-color: #525252;
    }

    .fade {
      animation: fadeInAnimation ease 3s;
      animation-iteration-count: 1;
      animation-fill-mode: forwards;
    }

    @keyframes fadeInAnimation {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .item {
      padding-bottom: 2%;
    }

    .btn {
      border: #21081a solid 2px;
    }
  </style>
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