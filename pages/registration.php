<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>

<div id="Loginsection" class="pt_50 pb_50 gray Loginsection">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5">
        <div class="login-register-bg">
          <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
              <form action="" class="registerd" method="post">
                <div class="form-group">
                  <input class="form-control" name="name" placeholder="Name" type="text" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="email" placeholder="Email Address" type="text" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="password" placeholder="Password" type="password" />
                </div>
                <div class="form-group">
                  <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password" />
                </div>
                <div class="form-group">
                  <button type="submit">REGISTER NOW</button>
                </div>
                <div class="form-group bottom">
                  <a href="login.php">Are you a already member? Login now!</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>