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

// Fetch sponsor_categorys data
$sponsor_categories = fetchAll($pdo, 'sponsor_categories');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Sponsor Category</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>sponsor-category-add.php" class="btn btn-primary"><i
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
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($sponsor_categories as $sponsor_category) {
                                            ?>
                                            <tr>
                                                <td><?php echo $sponsor_category['id']; ?></td>

                                                <td>
                                                    <?php echo $sponsor_category['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $sponsor_category['description']; ?>
                                                </td>

                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>sponsor-category-edit.php?id=<?php echo $sponsor_category['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>sponsor-category-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this sponsor_category?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($sponsor_category['id']) ?>">
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