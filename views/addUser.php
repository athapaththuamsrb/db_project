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
  <title>Add User</title>
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
</head>

<body>
  <?php @include('navbar.php'); ?>

  <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
    <h1>Add user</h1>
    <br />
    <div class="row">
      <div class="col-3"></div>
      <div class="col-7">
        <form method="post" class="form-row align-items-center">
          <div class="row">
            <div class="col-3 item">
              <label for="type"> Type</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <?php
              if (isset($types) && is_array($types) && sizeof($types) > 0) {
                if (sizeof($types) > 1) {
              ?>
                  <select name="type" id="type" style="width: 130%" lass="custom-select mr-sm-2" required>
                    <option selected>Choose...</option>
                    <?php
                    foreach ($types as $type => $name) {
                    ?>
                      <option value="<?php echo $type; ?>"><?php echo $name; ?></option>
                    <?php
                    }
                  } else if (sizeof($types) === 1) {
                    ?>
                    <select name="type" id="type" style="width: 130%" lass="custom-select mr-sm-2" required disabled>
                      <?php
                      foreach ($types as $type => $name) {
                      ?>
                        <option value="<?php echo $type; ?>" selected><?php echo $name; ?></option>
                      <?php
                      }
                      ?>
                    <?php
                  }
                    ?>
                    </select>
                  <?php
                }
                  ?>
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="username"> Username</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="text" name="username" id="username" onkeypress="keyPressFn(event, 'password')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="password">Password</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="password" name="password" id="password" onkeypress="keyPressFn(event, 'cnfpassword')" required />
            </div>
          </div>
          <div class="row">
            <div class="col-3 item">
              <label for="password">Conform password</label>
            </div>
            <div class="col-1 item"></div>
            <div class="col-3 item">
              <input type="password" id="cnfpassword" onkeypress="keyPressFn(event, '')" required />
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

  <?php @include('footer.php'); ?>
  <script src="/scripts/common.js"></script>
  <script src="/scripts/addUser.js"></script>

</body>

</html>