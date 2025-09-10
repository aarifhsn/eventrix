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
        <div class="section-header">
            <h1>Invoice</h1>
        </div>
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Invoice</h2>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Invoice To</strong><br>
                                        <?php echo $result[0]['billing_name']; ?><br>
                                        <?php echo $result[0]['billing_email']; ?><br>
                                        <?php echo $result[0]['billing_address']; ?>,<br>
                                        <?php echo $result[0]['billing_state']; ?>,
                                        <?php echo $result[0]['billing_city']; ?>,
                                        <?php echo $result[0]['billing_country']; ?>,
                                        <?php echo $result[0]['billing_zip']; ?>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right" style="text-align: right;">
                                    <address>
                                        <strong>Invoice Date</strong><br>
                                        <?php echo date('F d, Y', strtotime($result[0]['purchase_date_time'])); ?><br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <hr class="invoice-above-table">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th>SL</th>
                                        <th>Package Name</th>
                                        <th class="text-center">Per Ticket Price</th>
                                        <th class="text-center">Total Tickets</th>
                                        <th class="text-right">Total Price</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $result[0]['package_name']; ?></td>
                                        <td class="text-center">$<?php echo $result[0]['per_ticket_price']; ?></td>
                                        <td class="text-center"><?php echo $result[0]['total_tickets']; ?></td>
                                        <td class="text-right">$<?php echo $result[0]['total_price']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            $<?php echo $result[0]['total_price']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="about-print-button">
                <div class="text-md-right">
                    <a href="javascript:window.print();"
                        class="btn btn-warning btn-icon icon-left text-white print-invoice-button"><i
                            class="fas fa-print"></i> Print</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>