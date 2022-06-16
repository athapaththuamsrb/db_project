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
  <title>Add Branch</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
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
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/views/modal.php');
  addModal('Place Appointment');
  ?>
  <script src="/scripts/common.js"></script>
  <script src="/scripts/admin/addBranch.js"></script>

</body>

</html>