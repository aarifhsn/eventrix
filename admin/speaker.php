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

// Fetch speakers data
$speakers = fetchAll($pdo, 'speakers');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Speakers</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>speaker-add.php" class="btn btn-primary"><i class="fas fa-plus"></i>
                    Add New</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php echo displaySuccess($success_message); ?>

                            <?php echo displayError($error_message); ?>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Designation</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($speakers as $speaker) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $speaker['photo']; ?>"
                                                        alt="" class="w_50">
                                                </td>
                                                <td>
                                                    <?php echo $speaker['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $speaker['email']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $speaker['designation']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>speaker-edit.php?id=<?php echo $speaker['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>speaker-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this speaker?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($speaker['id']) ?>">
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