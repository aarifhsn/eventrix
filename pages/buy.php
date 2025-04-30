<?php
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');

// Fetch all features
$stmt = $pdo->prepare("SELECT id, name FROM features ORDER BY id ASC");
$stmt->execute();
$allFeatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tickets data
$ticketsData = $pdo->prepare("SELECT * FROM tickets");
$ticketsData->execute();
$tickets = $ticketsData->fetchAll(PDO::FETCH_ASSOC);

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
                            <td>$40</td>
                        </tr>
                        <tr>
                            <td>Total Tickets</td>
                            <td>
                                <input type="hidden" name="ticket_price" id="ticketPrice" value="40">
                                <input type="number" min="1" max="100" name="total_person" class="form-control"
                                    value="1" id="numPersons" oninput="calculateTotal()">
                            </td>
                        </tr>
                        <tr>
                            <td>Total Price</td>
                            <td>
                                <input type="text" name="" class="form-control" id="totalAmount" value="$40" disabled>
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