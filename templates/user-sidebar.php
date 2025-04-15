<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="user-sidebar">
    <div class="card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item <?php echo ($current_page == 'user-dashboard.php') ? 'active-item' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>user-dashboard">Dashboard</a>
            </li>
            <li class="list-group-item <?php echo ($current_page == 'user-tickets.php') ? 'active-item' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>user-tickets">My Tickets</a>
            </li>
            <li class="list-group-item  <?php echo ($current_page == 'user-messages.php') ? 'active-item' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>user-messages">Messages</a>
            </li>
            <li class="list-group-item <?php echo ($current_page == 'user-profile.php') ? 'active-item' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>user-profile">Profile</a>
            </li>
            <li class="list-group-item <?php echo ($current_page == 'logout.php') ? 'active-item' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>logout">Logout</a>
            </li>
        </ul>
    </div>
</div>