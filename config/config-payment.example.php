<?php
// config-payment.sample.php
// Copy this file to config-payment.php and update with your real credentials.

require __DIR__ . '/../vendor/autoload.php';
use Omnipay\Omnipay;

// PayPal Configuration
define('CLIENT_ID', 'your_paypal_client_id');
define('CLIENT_SECRET', 'your_paypal_client_secret');

define('PAYPAL_RETURN_URL', BASE_URL . 'paypal-success');
define('PAYPAL_CANCEL_URL', BASE_URL . 'paypal-cancel');
define('PAYPAL_CURRENCY', 'USD');

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); // Change to false when live

// Stripe Configuration
define('STRIPE_TEST_PK', 'your_stripe_publishable_key');
define('STRIPE_TEST_SK', 'your_stripe_secret_key');

define('STRIPE_SUCCESS_URL', BASE_URL . 'stripe-success');
define('STRIPE_CANCEL_URL', BASE_URL . 'stripe-cancel');
