<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>

<div class="user-section pt_70 pb_70">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <div class="user-sidebar">
          <div class="card">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <a href="user-dashboard.php">Dashboard</a>
              </li>
              <li class="list-group-item">
                <a href="user-tickets.php">My Tickets</a>
              </li>
              <li class="list-group-item">
                <a href="user-messages.php">Messages</a>
              </li>
              <li class="list-group-item active-item">
                <a href="user-profile.php">Profile</a>
              </li>
              <li class="list-group-item">
                <a href="login.php">Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-9">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="">Existing Photo:</label>
            <div>
              <img src="dist/images/attendee.jpg" alt="" class="w_150" />
            </div>
          </div>
          <div class="form-group">
            <label for="">Change Photo:</label>
            <div>
              <input type="file" name="photo" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Name *</label>
                <input type="text" class="form-control" name="" value="Mister Smith" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Email *</label>
                <input type="text" class="form-control" name="" value="smith@gmail.com" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Phone *</label>
                <input type="text" class="form-control" name="" value="123-333-2222" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Address *</label>
                <input type="text" class="form-control" name="" value="45, Street Road" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Country *</label>
                <input type="text" class="form-control" name="" value="USA" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">State *</label>
                <input type="text" class="form-control" name="" value="NYC" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">City *</label>
                <input type="text" class="form-control" name="" value="NYC" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Zip Code *</label>
                <input type="text" class="form-control" name="" value="23455" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Confirm Password</label>
                <input type="password" class="form-control" name="" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>