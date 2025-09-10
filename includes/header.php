<?php
declare(strict_types=1);
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/../config/config.php');
require(__DIR__ . '/../vendor/autoload.php');

// include messages
include(__DIR__ . '/../pages/messages.php');

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="dist/images/favicon.png">

    <title>Eventrix - Event & Conference Management</title>

    <link href="<?php echo BASE_URL; ?>dist/css/animate.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/magnific-popup.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/owl.carousel.min.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/global.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/style.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/responsive.css" type="text/css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>dist/css/spacing.css" type="text/css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,500,700,900" rel="stylesheet">

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

    <?php include(__DIR__ . '/../templates/navbar.php'); ?>

    <?php echo displayMessages(); ?>
    <?php echo getMessageScript(); ?>