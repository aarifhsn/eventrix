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
                  <input class="form-control" name="email" placeholder="Email Address" type="text" />
                </div>
                <div class="form-group">
                  <button type="submit">SUBMIT</button>
                </div>
                <div class="form-group bottom">
                  <a href="<?php echo BASE_URL; ?>login">Back to login page</a>
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