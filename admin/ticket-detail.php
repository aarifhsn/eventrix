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
                            WHERE t1.id=?");
$statement->execute([$_REQUEST['id']]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$total = $statement->rowCount();
if (!$total) {
    header('location: ' . ADMIN_URL . 'ticket.php');
    exit;
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Order Detail</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/ticket.php" class="btn btn-primary"><i class="fas fa-plus"></i> Back
                    to
                    Previous</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">

                                    <tr>
                                        <th>User Name</th>
                                        <td>
                                            <?php echo $result[0]['user_name']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>User Email</th>
                                        <td>
                                            <?php echo $result[0]['user_email']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Package Name</th>
                                        <td>
                                            <?php echo $result[0]['package_name']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Name</th>
                                        <td>
                                            <?php echo $result[0]['billing_name']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Email</th>
                                        <td>
                                            <?php echo $result[0]['billing_email']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Phone</th>
                                        <td>
                                            <?php echo $result[0]['billing_phone']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Address</th>
                                        <td>
                                            <?php echo $result[0]['billing_address']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Country</th>
                                        <td>
                                            <?php echo $result[0]['billing_country']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing State</th>
                                        <td>
                                            <?php echo $result[0]['billing_state']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing City</th>
                                        <td>
                                            <?php echo $result[0]['billing_city']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Billing Zip</th>
                                        <td>
                                            <?php echo $result[0]['billing_zip']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method</th>
                                        <td>
                                            <?php echo $result[0]['payment_method']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Currency</th>
                                        <td>
                                            <?php echo $result[0]['payment_currency']; ?>
                                        </td>
                                    </tr>

                                    <?php if ($result[0]['payment_method'] != 'Bank'): ?>
                                        <tr>
                                            <th>Transaction Id</th>
                                            <td>
                                                <?php echo $result[0]['transaction_id']; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($result[0]['payment_method'] == 'Bank'): ?>
                                        <tr>
                                            <th>Bank Transaction Info</th>
                                            <td>
                                                <?php echo nl2br($result[0]['bank_transaction_info']); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <tr>
                                        <th>Payment Status</th>
                                        <td>
                                            <?php echo $result[0]['payment_status']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Per Ticket Price</th>
                                        <td>
                                            $<?php echo $result[0]['per_ticket_price']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Tickets</th>
                                        <td>
                                            <?php echo $result[0]['total_tickets']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Price</th>
                                        <td>
                                            $<?php echo $result[0]['total_price']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Purchase Date & Time</th>
                                        <td>
                                            <?php echo $result[0]['purchase_date_time']; ?>
                                        </td>
                                    </tr>
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