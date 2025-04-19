<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Check for messages in session
initMessages();

// Check if admin is logged in
checkAdminAuth();

// Fetch all from schedules with relation to schedule_days

$stmt = $pdo->prepare("SELECT schedules.*, schedule_days.title AS day_name FROM schedules
INNER JOIN schedule_days ON schedules.schedule_day_id = schedule_days.id
ORDER BY schedules.item_order ASC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Schedules</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>schedule-add.php" class="btn btn-primary"><i class="fas fa-plus"></i>
                    Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <?php echo displaySuccess($success_message); ?>

                    <?php echo displayError($error_message); ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Title</th>
                                            <th>Schedule Day</th>
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
                                                    <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $row['photo']; ?>"
                                                        alt="" class="w_50">
                                                </td>
                                                <td>
                                                    <?php echo $row['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['day_name']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>schedule-edit.php?id=<?php echo $row['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>

                                                    <form method="POST" action="<?= ADMIN_URL ?>schedule-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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