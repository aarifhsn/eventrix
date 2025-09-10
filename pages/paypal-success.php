<?php
ob_start();
session_start();
include(__DIR__ . '/../config/config.php');
include(__DIR__ . '/../config/config-payment.php');
if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) {
    $transaction = $gateway->completePurchase(array(
        'payer_id' => $_GET['PayerID'],
        'transactionReference' => $_GET['paymentId'],
    ));
    $response = $transaction->send();
    if ($response->isSuccessful()) {
        $arr_body = $response->getData();
        $payment_id = $arr_body['id'];
        $payer_id = $arr_body['payer']['payer_info']['payer_id'];
        $payer_email = $arr_body['payer']['payer_info']['email'];
        $amount = $arr_body['transactions'][0]['amount']['total'];
        $currency = PAYPAL_CURRENCY;
        $payment_status = $arr_body['state'];

        $order_number = time() . rand(1000, 9999);

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
        $statement->execute([
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
            $_SESSION['billing_note'],
            'PayPal',
            $currency,
            'Completed',
            $payment_id,
            $payment_id,
            '',
            $_SESSION['per_ticket_price'],
            $_SESSION['total_tickets'],
            $_SESSION['total_price'],
            date('Y-m-d H:i:s')
        ]);

        // Success message
        $success_message = "Payment completed successfully. Your tickets have been purchased. Transaction ID: " . $payment_id . ". You will receive a confirmation email shortly.";
        $_SESSION['success_message'] = $success_message;

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

        header('location: ' . BASE_URL);
        exit;
    } else {
        echo $response->getMessage();
    }
} else {
    $error_message = "Payment is cancelled!";
    $_SESSION['error_message'] = $error_message;
    header('location: ' . BASE_URL);
    exit;
}
ob_end_flush();