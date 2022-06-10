<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="/styles/all.css" />
  <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
  <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
  <title>Add Branch</title>
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
  </style>
</head>

<body>
  <?php @include(__DIR__ . '/../navbar.php'); ?>

  <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
    <h1>Add Branch</h1>
    <br />
    <div class="row">
      <div class="col-3"></div>
      <div class="col-7">
        <form method="post" class="form-row align-items-center">
          <div class="row">
            <div class="col-3 item">
              <label for="id">Branch ID</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="id" id="id" onkeypress="keyPressFn(event, 'name')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="name">Branch Name</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="name" id="name" onkeypress="keyPressFn(event, 'location')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="location">Location</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="location" id="location" onkeypress="keyPressFn(event, 'manager')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="manager">Manager</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="manager" id="manager" onkeypress="keyPressFn(event, '')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-2"></div>
            <div class="col-4 item">
              <button id="submitBtn" type="submit" class="btn btn-info" style="width: 150%">
                Add
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
  <script src="/scripts/common.js"></script>
  <script src="/scripts/admin/addBranch.js"></script>

</body>

</html>