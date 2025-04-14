<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>
<div id="contacts" class="pt_70 pb_50 white">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-sm-12">
        <div class="contact">
          <form class="form" method="post" action="">
            <div class="row">
              <div class="form-group col-md-6">
                <input name="name" class="form-control" placeholder="Name" type="text" />
              </div>
              <div class="form-group col-md-6">
                <input name="email" class="form-control" placeholder="Email" type="email" />
              </div>
              <div class="form-group col-md-12">
                <input name="subject" class="form-control" placeholder="Subject" type="text" />
              </div>
              <div class="form-group col-md-12">
                <textarea rows="3" name="message" class="form-control" placeholder="Message"></textarea>
              </div>
              <div class="col-md-12">
                <div class="actions">
                  <input value="Send Message" name="submit" class="btn btn-lg btn-con-bg" type="submit" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-4 col-sm-12">
        <div class="contact-info">
          <div class="contact-inner-box">
            <div class="icon">
              <div class="contact-inner-icon">
                <i class="fa fa-map-marker"></i>
              </div>
            </div>
            <div class="text">
              <div class="contact-inner-text">
                Address: <br /><span>43, Park Street, NYC, USA</span>
              </div>
            </div>
          </div>
          <div class="contact-inner-box">
            <div class="icon">
              <div class="contact-inner-icon">
                <i class="fa fa-envelope-o"></i>
              </div>
            </div>
            <div class="text">
              <div class="contact-inner-text">
                Email: <br /><span>contact@example.com</span>
              </div>
            </div>
          </div>
          <div class="contact-inner-box">
            <div class="icon">
              <div class="contact-inner-icon">
                <i class="fa fa-phone"></i>
              </div>
            </div>
            <div class="text">
              <div class="contact-inner-text">
                Phone: <br /><span>234-423-1266</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>