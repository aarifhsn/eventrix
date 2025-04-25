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

// Fetch features data
$features = fetchAll($pdo, 'features');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>features</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>feature-add.php" class="btn btn-primary"><i class="fas fa-plus"></i>
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
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>Max Price</th>
                                            <th>Item Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($features as $feature) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <?php echo $feature['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $feature['price']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $feature['max_price']; ?>
                                                </td>
                                                </td>
                                                <td>
                                                    <?php echo $feature['item_order']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>feature-edit.php?id=<?php echo $feature['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>feature-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this feature?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($feature['id']) ?>">
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