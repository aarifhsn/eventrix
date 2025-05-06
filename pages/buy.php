<?php

ob_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');

// check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'login');
    exit();
}

$packageId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$packageId) {
    // redirect or show error
    header('Location: ' . BASE_URL . 'pricing');
    exit();
}

// Fetch package
$stmt = $pdo->prepare("SELECT id, title, price FROM packages WHERE id = ?");
$stmt->execute([$packageId]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    // Package not found
    echo "<div class='alert alert-danger'>Invalid package selected.</div>";
    exit();
}


?>
<div id="price-section" class="pt_50 pb_70 gray prices">
    <div class="container">
        <div class="row">
            <form class="form" method="post" action="">
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="contact">
                    <h3 class="mb_15 fw600">Billing Information</h3>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Name *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Email *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Phone *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Address *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Country *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="State *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="City *">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" placeholder="Zip Code *">
                        </div>
                        <div class="form-group col-md-12">
                            <textarea rows="3" name="message" class="form-control"
                                placeholder="Note (Optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h3 class="mb_15 fw600">Ticket Information</h3>
                <div class="table-responsive">
                    <table class="table table-bordered cart">
                        <tr>
                            <td class="w_150">Ticket Price</td>
                            <td>$<?php echo htmlspecialchars($package['price']); ?></td>
                        </tr>
                        <tr>
                            <td>Total Tickets</td>
                            <td>
                                <input type="hidden" name="ticket_price" id="ticketPrice"
                                    value="<?php echo $package['price']; ?>">
                                <input type="number" min="1" max="100" name="total_person" class="form-control"
                                    value="1" id="numPersons" oninput="calculateTotal()">
                            </td>
                        </tr>
                        <tr>
                            <td>Total Price</td>
                            <td>
                                <input type="text" name="" class="form-control" id="totalAmount"
                                    value="$<?php echo $package['price']; ?>" disabled>
                            </td>
                        </tr>
                    </table>
                </div>

                <script>
                    function calculateTotal() {
                        const ticketPrice = document.getElementById('ticketPrice').value;
                        const numPersons = document.getElementById('numPersons').value;
                        const totalAmount = ticketPrice * numPersons;
                        document.getElementById('totalAmount').value = `$${totalAmount}`;
                    }
                </script>

                <h3 class="mt_25 mb_15 fw600">Payment</h3>
                <select name="" class="form-control">
                    <option value="PayPal">PayPal</option>
                    <option value="Stripe">Stripe</option>
                    <option value="Cash">Cash</option>
                </select>
                <div class="">
                    <button type="submit" class="btn btn-primary">Buy Ticket</button>
                </div>
            </div>
        </div>
        <div class="row">
            </form>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>

<?php ob_end_flush(); ?>