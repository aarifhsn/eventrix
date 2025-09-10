<?php ob_start();
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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faq_form'])) {
    try {

        if (empty($_POST['title'])) {
            throw new Exception("Title is required");
        }

        $statement = $pdo->prepare("INSERT INTO faqs (title, details) VALUES (?,?)");
        $statement->execute([
            $_POST['title'],
            $_POST['details'],
        ]);

        unset($_SESSION['title']);
        unset($_SESSION['details']);


        $_SESSION['success_message'] = "Data insert is successful";
        header("location: " . ADMIN_URL . "/faq.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['name'] = $_POST['title'];
        $_SESSION['email'] = $_POST['details'];

        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: " . ADMIN_URL . "/faq-add.php");
        exit;
    }
}

// Fetch all schedule days
$faqs = fetchAll($pdo, 'faqs', 'id ASC');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add FAQ</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>/faq.php" class="btn btn-primary"><i class="fas fa-eye"></i>
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
                                                value="<?php old('title'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Details</label>
                                            <textarea name="details" class="form-control h_200" rows="4"
                                                cols="50"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="add_faq_form">Submit</button>
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