<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="/styles/all.css" />
  <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
  <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
  <title>View Transaction History</title>
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

    .box {
      margin-top: 2%;
      margin-bottom: 2%;
    }

    .item {
      padding: 2%;
      margin: 1%;
    }

    .btn {
      border: #21081a solid 2px;
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

    select {
      width: 135%;
    }
  </style>
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
            <div class="col-4 item">
              <label for="owner_id">Owner ID : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="owner_id" id="owner_id" required />
            </div>
          </div>

        <label for="type">Choose an account type:</label>
            <select name="type" id="type">
                <option value="savings">Savings</option>
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

          <div class="row">
            <div class="col-4 item">
              <label for="branch_id">Branch ID : </label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="branch_id" id="branch_id" required />
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
                <input type="text" name="duration" id="duration" required />
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

          <!-- should be visible only if savings is selected for type -->
          <div id="saving_visible">
            <div class="row">
              <div class="col-4 item">
                <label for="savings_acc_no">Customer Type: </label>
              </div>
              <div class="col-1 item"></div>
              <div class="col-3 item">
                <input type="text" name="customer_type" id="customer_type" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-2"></div>
            <div class="col-4 item">
              <button type="submit" class="btn btn-info" style="width: 150%">
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
</body>
<script type="text/javascript">
  function getType() {
    const typeValue = document.getElementById("type").value;
    if (typeValue === "savings") {
      document.getElementById("saving_visible").style.display = "block";
      document.getElementById("fd_visible").style.display = "none";
    } else if (typeValue === "fd") {
      document.getElementById("saving_visible").style.display = "none";
      document.getElementById("fd_visible").style.display = "block";
    } else {
      document.getElementById("saving_visible").style.display = "none";
      document.getElementById("fd_visible").style.display = "none";
    }
  }
</script>

</html>