<?php ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Include helpers functions
include(__DIR__ . '/../config/helpers.php');

// Initialize
initMessages();

// fetch data
$faqData = fetchById($pdo, 'faqs', $_REQUEST['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['faq_update_form'])) {
    try {
        if (empty($_POST['title'])) {
            throw new Exception("Title cannot be empty");
        }

        $statement = $pdo->prepare(
            "UPDATE faqs SET title = ?, details = ? WHERE id = ?"
        );

        $statement->execute([
            $_POST['title'],
            $_POST['details'],
            $_REQUEST['id']
        ]);

        $success_message = "FAQ updated successfully";
        $_SESSION['success_message'] = $success_message;
        header("location: " . ADMIN_URL . "faq.php");
        exit;

    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "faq-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit FAQ</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>faq.php" class="btn btn-primary"><i class="fas fa-eye"></i>
                    View
                    All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <?php echo displaySuccess($success_message); ?>
                            <?php echo displayError($error_message); ?>

                            <form action="" method="post">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="<?php echo $faqData['title']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Details</label>
                                            <textarea name="details" class="form-control h_200" rows="10"
                                                cols="30"><?php echo $faqData['details']; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="faq_update_form">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>