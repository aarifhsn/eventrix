<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

checkAdminAuth();

// Fetch all schedule days
$result = fetchAll($pdo, 'schedule_days');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Schedule Days</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>schedule-day-add.php" class="btn btn-primary"><i
                        class="fas fa-plus"></i> Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Day</th>
                                            <th>Date</th>
                                            <th>Order</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($result as $row) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php echo $row['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('F j, Y', strtotime($row['date'])); ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['order_number']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>schedule-day-edit.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <a href="<?php echo ADMIN_URL; ?>schedule-day-delete.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onClick="return confirm('Are you sure?');"><i
                                                            class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>