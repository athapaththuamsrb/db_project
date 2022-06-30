<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link
      rel="stylesheet"
      href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="/styles/form.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <title>Reports</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
  </head>

  <body>
    <?php @include(__DIR__ . '/../navbar.php'); ?>
    <div
      class="container box fade"
      style="background-color: #880808; color: white; border: #21081a solid 2px"
    >
      <h1>Generate Report</h1>
      <form method="post">
        <div class="row">
          <div class="col-3"></div>
          <div class="col-4 item">
            <button id="lateLoanBtn" class="btn btn-info" style="width: 150%">
              Late Loan Report
            </button>
          </div>
          <div class="col-1"></div>
        </div>
        <div class="row">
          <div class="col-3"></div>
          <div class="col-4 item">
            <button id="transactionBtn" class="btn btn-info" style="width: 150%">
              Transaction Report
            </button>
          </div>
          <div class="col-1"></div>
        </div>
      </form>
    </div>
    <?php @include(__DIR__ . '/../footer.php'); ?>
    <?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
    addModal('Create Account');
    ?>
    <script src="/scripts/common.js"></script>
    <script src="/scripts/manager/report.js"></script>
  </body>
</html>
