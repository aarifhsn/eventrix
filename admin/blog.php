<?php

session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Check for messages in session
initMessages();

// Fetch posts data
$posts = fetchAll($pdo, 'posts');

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>posts</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/blog-add.php" class="btn btn-primary"><i class="fas fa-plus"></i>
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
                                            <th>Title</th>
                                            <th>Slug</th>
                                            <th>Content</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($posts as $post) {
                                            $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td>
                                                    <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo $post['photo']; ?>"
                                                        alt="" class="w_50">
                                                </td>
                                                <td>
                                                    <?php echo $post['title']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $post['slug']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $post['content']; ?>
                                                </td>
                                                <td>
                                                    <?php echo date('j/n/Y', strtotime($post['date'])); ?>
                                                </td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?php echo ADMIN_URL; ?>/blog-edit.php?id=<?php echo $post['id']; ?>"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                    <form method="POST" action="<?= ADMIN_URL ?>/post-delete.php"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                        <input type="hidden" name="id"
                                                            value="<?= htmlspecialchars($post['id']) ?>">
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