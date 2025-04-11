<?php

include("layouts/header.php");

unset($_SESSION['admin']);

header('location: ' . ADMIN_URL . '/login.php');

exit;