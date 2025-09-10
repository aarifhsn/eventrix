<?php
ob_start();
session_start();
include(__DIR__ . '/../config/config.php');
include(__DIR__ . '/../config/config-payment.php');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (isset($_GET['session_id'])) {
        \Stripe\Stripe::setApiKey(STRIPE_TEST_SK);
        $response = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
        $paymentIntent = $response->payment_intent; // Transaction Id

        // Check if required session variables exist
        $requiredSessionVars = [
            'user',
            'package_id',
            'package_name',
            'billing_name',
            'billing_email',
            'billing_phone',
            'billing_address',
            'billing_country',
            'billing_state',
            'billing_city',
            'billing_zip',
            'per_ticket_price',
            'total_tickets',
            'total_price'
        ];

        foreach ($requiredSessionVars as $var) {
            if (!isset($_SESSION[$var])) {
                throw new Exception("Missing session variable: $var");
            }
        }

        $order_number = time() . rand(1000, 9999);
        $currency = 'USD';

        // Insert into database
        $statement = $pdo->prepare("INSERT INTO tickets (
                                user_id,
                                package_id,
                                package_name,
                                billing_name,
                                billing_email,
                                billing_phone,
                                billing_address,
                                billing_country,
                                billing_state,
                                billing_city,
                                billing_zip,
                                billing_note,
                                payment_method,
                                payment_currency,
                                payment_status,
                                payment_id,
                                transaction_id,
                                bank_transaction_info,
                                per_ticket_price,
                                total_tickets,
                                total_price,
                                purchase_date_time
                            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $result = $statement->execute([
            $_SESSION['user']['id'],
            $_SESSION['package_id'],
            $_SESSION['package_name'],
            $_SESSION['billing_name'],
            $_SESSION['billing_email'],
            $_SESSION['billing_phone'],
            $_SESSION['billing_address'],
            $_SESSION['billing_country'],
            $_SESSION['billing_state'],
            $_SESSION['billing_city'],
            $_SESSION['billing_zip'],
            $_SESSION['billing_note'] ?? '',
            'Stripe',
            $currency,
            'Completed',
            $paymentIntent,
            $paymentIntent,
            '',
            $_SESSION['per_ticket_price'],
            $_SESSION['total_tickets'],
            $_SESSION['total_price'],
            date('Y-m-d H:i:s')
        ]);

        if (!$result) {
            throw new Exception("Database insert failed");
        }

        $success_message = "Payment is successful.";
        $_SESSION['success_message'] = $success_message;

        // Clear session variables
        unset($_SESSION['package_id']);
        unset($_SESSION['package_name']);
        unset($_SESSION['billing_name']);
        unset($_SESSION['billing_email']);
        unset($_SESSION['billing_phone']);
        unset($_SESSION['billing_address']);
        unset($_SESSION['billing_country']);
        unset($_SESSION['billing_state']);
        unset($_SESSION['billing_city']);
        unset($_SESSION['billing_zip']);
        unset($_SESSION['billing_note']);
        unset($_SESSION['per_ticket_price']);
        unset($_SESSION['total_tickets']);
        unset($_SESSION['total_price']);

        header('Location: ' . BASE_URL);
        exit();

    } else {
        throw new Exception("No session ID provided");
    }

} catch (Exception $e) {
    // Log the error
    error_log("Stripe Success Error: " . $e->getMessage());

    $error_message = "Payment processing failed: " . $e->getMessage();
    $_SESSION['error_message'] = $error_message;
    header('Location: ' . BASE_URL);
    exit();
}

ob_end_flush();
?>