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

// Fetch accommodations data
$accommodations = fetchAll($pdo, 'accommodations');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>accommodations</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>accommodation-add.php" class="btn btn-primary"><i
                        class="fas fa-plus"></i>
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
                                            <th>Address</th>
                                            <th>Website</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($accommodations as $accommodation) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $accommodation['photo']; ?>"
                                                        alt="" class="w_50">
                                                </td>
                                                <td>
                                                    <?php echo $accommodation['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $accommodation['email']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $accommodation['address']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $accommodation['website']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $accommodation['phone']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>accommodation-edit.php?id=<?php echo $accommodation['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>accommodation-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this accommodation?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($accommodation['id']) ?>">
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