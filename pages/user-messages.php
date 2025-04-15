<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>
<div class="user-section pt_70 pb_70">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(__DIR__ . '/../templates/user-sidebar.php'); ?>
      </div>
      <div class="col-lg-9">
        <h4 class="message-heading">Write Message</h4>
        <form action="" method="post">
          <div class="mb-2">
            <textarea name="" class="form-control h_100" cols="30" rows="10"
              placeholder="Write your message here"></textarea>
          </div>
          <div class="mb-2 text-right">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>

        <h4 class="message-heading mt_40">All Messages</h4>
        <div class="message-item message-item-admin-border">
          <div class="message-top">
            <div class="left">
              <img src="dist/images/admin.jpg" alt="" />
            </div>
            <div class="right">
              <h4>Morshedul Arefin</h4>
              <h5>Admin</h5>
              <div class="date-time">2024-08-20 09:33:22 AM</div>
            </div>
          </div>
          <div class="message-bottom">
            <p>
              Thank you for contacting. Sure, you can take it with you
              without any problem.
            </p>
          </div>
        </div>

        <div class="message-item">
          <div class="message-top">
            <div class="left">
              <img src="dist/images/attendee.jpg" alt="" />
            </div>
            <div class="right">
              <h4>Smith Brent</h4>
              <h5>Client</h5>
              <div class="date-time">2024-08-20 08:12:43 AM</div>
            </div>
          </div>
          <div class="message-bottom">
            <p>
              I forgot to tell one thing. Can you please allow some toys for
              my son in this tour? It will be very much helpful if you
              allow.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>