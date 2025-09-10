<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Check for messages in session
initMessages();
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Tickets</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>User</th>
                                            <th>Package</th>
                                            <th>Transaction ID</th>
                                            <th>Bank Transaction Info</th>
                                            <th>Payment Method</th>
                                            <th>Per Ticket Price</th>
                                            <th>Total Tickets</th>
                                            <th>Total Price</th>
                                            <th>Payment Status</th>
                                            <th>Date & Time</th>
                                            <th class="w_200">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $statement = $pdo->prepare("SELECT
                                                    t1.*,
                                                    t2.name as user_name,
                                                    t2.email as user_email,
                                                    t3.title as package_name 
                                                    FROM tickets t1
                                                    JOIN users t2
                                                    ON t1.user_id = t2.id
                                                    JOIN packages t3
                                                    ON t1.package_id = t3.id
                                                    ORDER BY t1.id DESC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php
                                                    echo $row['user_name'] . '<br>';
                                                    echo $row['user_email'];
                                                    ?>
                                                    <a href="<?php echo ADMIN_URL; ?>/attendee.php">See Detail</a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo ADMIN_URL; ?>/package.php">
                                                        <?php
                                                        echo $row['package_name'];
                                                        ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo $row['transaction_id']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['bank_transaction_info']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['payment_method']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['per_ticket_price']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['total_tickets']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['total_price']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($row['payment_status'] == 'Completed'): ?>
                                                        <span
                                                            class="badge badge-success"><?php echo $row['payment_status']; ?></span>
                                                    <?php else: ?>
                                                        <span
                                                            class="badge badge-danger"><?php echo $row['payment_status']; ?></span>
                                                    <?php endif; ?>
                                                    <br><a
                                                        href="<?php echo ADMIN_URL; ?>/ticket-change-status.php?id=<?php echo $row['id']; ?>">Change
                                                        Status</a>
                                                </td>
                                                <td>
                                                    <?php echo $row['purchase_date_time']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>/ticket-detail.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-warning btn-sm" style="margin-bottom:5px;"><i
                                                            class="fas fa-eye"></i></a><br>
                                                    <a href="<?php echo ADMIN_URL; ?>/ticket-invoice.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-success btn-sm" style="width:26px;"><i
                                                            class="fas fa-info"></i></a>
                                                    <a href="<?php echo ADMIN_URL; ?>/ticket-delete.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onClick="return confirm('Are you sure?');"><i
                                                            class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>