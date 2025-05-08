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

$packageTitle = isset($_GET['package']) ? $_GET['package'] : '';
$packageId = isset($_GET['id']) ? $_GET['id'] : '';

if (!$packageTitle && !$packageId) {
    echo "<div class='alert alert-danger'>Invalid package selected.</div>";
    exit();
}

// Fetch package
$stmt = $pdo->prepare("SELECT id, title, price FROM packages WHERE title = ?");
$stmt->execute([$packageTitle]);
$package = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$package) {
    // Package not found
    echo "<div class='alert alert-danger'>Invalid package selected.</div>";
    exit();
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_submit_form'])) {
    try {
        $token = $_POST['token'];
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            throw new Exception('Invalid token');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';

        $user['name'] = $name;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;

        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $address, $_SESSION['user']['id']]);

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, package_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $package['id']]);

        echo "<div class='alert alert-success'>Order placed successfully.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
}

?>
<div id="price-section" class="pt_50 pb_70 gray prices">
    <div class="container">
        <div class="row">
            <form class="form" method="post" action="">

        </div>
        <div class=" row">
            <div class="col-lg-8">
                <div class="contact">
                    <h3 class="mb_15 fw600">Billing Information</h3>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_name" class="form-control" placeholder="Name *"
                                value="<?php echo $user['name'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_email" class="form-control" placeholder="Email *"
                                value="<?php echo $user['email'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_phone" class="form-control" placeholder="Phone *"
                                value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_address" class="form-control" placeholder="Address *"
                                value="<?php echo $user['address'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_country" class="form-control" placeholder="Country *"
                                value="<?php echo $user['country'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_state" class="form-control" placeholder="State *"
                                value="<?php echo $user['state'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_city" class="form-control" placeholder="City *"
                                value="<?php echo $user['city'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="billing_zip_code" class="form-control" placeholder="Zip Code *"
                                value="<?php echo $user['zip_code'] ?? ''; ?>">
                        </div>
                        <div class="form-group col-md-12">
                            <textarea rows="3" name="billing_note" class="form-control"
                                placeholder="Note (Optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <input type="hidden" name="token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <h3 class=" mb_15 fw600">Ticket Information</h3>
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
                                <input type="text" name="total_amount" class="form-control" id="totalAmount"
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
                    <button type="submit" class="btn btn-primary" name="package_submit_form">Buy Ticket</button>
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