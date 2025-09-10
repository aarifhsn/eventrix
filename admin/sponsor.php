<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Check for messages in session
initMessages();

// Fetch sponsors data
$stmt = $pdo->prepare("SELECT 
    s.*,
    sc.title AS category_title
    FROM sponsors s
    INNER JOIN sponsor_categories sc ON s.sponsor_category_id = sc.id
    ORDER BY s.id ASC");
$stmt->execute();
$sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>sponsors</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/sponsor-add.php" class="btn btn-primary"><i class="fas fa-plus"></i>
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
                                <table class="table table-bordered dataTable no-footer" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Title</th>
                                            <th>Sponsor Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($sponsors as $sponsor) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $sponsor['logo']; ?>"
                                                        alt="" class="w_50">
                                                </td>
                                                <td>
                                                    <?php echo $sponsor['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $sponsor['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $sponsor['category_title']; ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>/sponsor-edit.php?id=<?php echo $sponsor['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>sponsor-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this sponsor?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($sponsor['id']) ?>">
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