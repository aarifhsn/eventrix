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
              <li class="list-group-item active-item">
                <a href="user-dashboard.php">Dashboard</a>
              </li>
              <li class="list-group-item">
                <a href="user-tickets.php">My Tickets</a>
              </li>
              <li class="list-group-item">
                <a href="user-messages.php">Messages</a>
              </li>
              <li class="list-group-item">
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
        <h4 class="mb_15 fw600">User Detail:</h4>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th>Name:</th>
              <td>Mister Smith</td>
            </tr>
            <tr>
              <th>Email:</th>
              <td>smith@gmail.com</td>
            </tr>
            <tr>
              <th>Phone:</th>
              <td>237-453-2264</td>
            </tr>
            <tr>
              <th>Address:</th>
              <td>45 Sp Valley, NYC, USA</td>
            </tr>
            <tr>
              <th>State:</th>
              <td>NYC</td>
            </tr>
            <tr>
              <th>City:</th>
              <td>NYC</td>
            </tr>
            <tr>
              <th>Country:</th>
              <td>USA</td>
            </tr>
            <tr>
              <th>Zip Code:</th>
              <td>12873</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>