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
              <li class="list-group-item active-item">
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
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th>SL</th>
              <th>Invoice No</th>
              <th>Booking Date</th>
              <th>Number of Tickets</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
            <tr>
              <td>1</td>
              <td>INV-123456</td>
              <td>10-07-2024</td>
              <td>2</td>
              <td>
                <span class="badge badge-success">Active</span>
              </td>
              <td>
                <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_1"><i
                    class="fa fa-eye"></i></a>
                <a href="user-invoice.php" class="btn btn-success btn-sm pl_10 pr_10"><i class="fa fa-info"></i></a>
              </td>
              <div class="modal fade" id="modal_1" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title fw600">Detail</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="row mb_15">
                        <div class="col-md-4">Invoice No:</div>
                        <div class="col-md-8">INV-12345</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Booking Date:</div>
                        <div class="col-md-8">Jul 12, 2024</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Ticket Price:</div>
                        <div class="col-md-8">$40</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Number of Tickets:</div>
                        <div class="col-md-8">2</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Total Price:</div>
                        <div class="col-md-8">$80</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Payment Method:</div>
                        <div class="col-md-8">PayPal</div>
                      </div>
                      <div class="divider-1"></div>
                      <div class="row mb_15">
                        <div class="col-md-4">Payment Status:</div>
                        <div class="col-md-8">Completed</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </tr>
            <tr>
              <td>2</td>
              <td>INV-123457</td>
              <td>10-07-2024</td>
              <td>1</td>
              <td>
                <span class="badge badge-danger">Pending</span>
              </td>
              <td>
                <a href="" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal_1"><i
                    class="fa fa-eye"></i></a>
                <a href="user-invoice.php" class="btn btn-success btn-sm pl_10 pr_10"><i class="fa fa-info"></i></a>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>