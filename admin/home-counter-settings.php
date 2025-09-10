<?php

ob_start();
session_start();

// Include necessary files
include(__DIR__ . '/layouts/header.php');

// Check if admin is logged in
checkAdminAuth();

// Include necessary files
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Initialize
$success_message = '';
$error_message = '';
$edit_counter = null;

// Generate CSRF token if not exists
if (!isset($_SESSION['home_counter_csrf_token'])) {
    $_SESSION['home_counter_csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch current counter data
try {
    $stmt = $pdo->prepare("SELECT * FROM counters ORDER BY id ASC");
    $stmt->execute();
    $counters = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching counter data: " . $e->getMessage();
    $counters = [];
}

// Handle DELETE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_counter'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_counter_csrf_token']) || $_POST['home_counter_csrf_token'] !== $_SESSION['home_counter_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        $counter_id = intval($_POST['counter_id'] ?? 0);
        if ($counter_id <= 0) {
            throw new Exception("Invalid counter ID.");
        }

        $stmt = $pdo->prepare("DELETE FROM counters WHERE id = :id");
        $stmt->execute([':id' => $counter_id]);

        if ($stmt->rowCount() > 0) {
            $success_message = "Counter deleted successfully!";
        } else {
            $error_message = "Counter not found or already deleted.";
        }

        // Refresh counters
        $stmt = $pdo->prepare("SELECT * FROM counters ORDER BY id ASC");
        $stmt->execute();
        $counters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle EDIT operation (fetch data for editing)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    try {
        $counter_id = intval($_GET['edit']);
        $stmt = $pdo->prepare("SELECT * FROM counters WHERE id = :id");
        $stmt->execute([':id' => $counter_id]);
        $edit_counter = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$edit_counter) {
            $error_message = "Counter not found.";
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle ADD/UPDATE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_counters_form'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_counter_csrf_token']) || $_POST['home_counter_csrf_token'] !== $_SESSION['home_counter_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        // Check if this is an update operation
        $is_update = isset($_POST['counter_id']) && is_numeric($_POST['counter_id']);

        if ($is_update) {
            // UPDATE existing counter
            $counter_id = intval($_POST['counter_id']);
            $icon = trim($_POST['icon'] ?? '');
            $number = intval($_POST['number'] ?? 0);
            $label = trim($_POST['label'] ?? '');

            if (empty($number)) {
                throw new Exception("Counter number cannot be empty.");
            }

            $stmt = $pdo->prepare("UPDATE counters SET icon = :icon, number = :number, label = :label WHERE id = :id");
            $stmt->execute([
                ':icon' => $icon,
                ':number' => $number,
                ':label' => $label,
                ':id' => $counter_id
            ]);

            $success_message = "Counter updated successfully!";
        } else {
            // INSERT new counters
            if (!isset($_POST['counters']) || !is_array($_POST['counters'])) {
                throw new Exception("Invalid data submitted.");
            }

            $pdo->beginTransaction();

            foreach ($_POST['counters'] as $id => $counter) {
                $icon = trim($counter['icon'] ?? '');
                $number = intval($counter['number'] ?? 0);
                $label = trim($counter['label'] ?? '');

                if (empty($number)) {
                    throw new Exception("Counter number cannot be empty.");
                }

                $stmt = $pdo->prepare("INSERT INTO counters (icon, number, label) VALUES (:icon, :number, :label)");
                $stmt->execute([
                    ':icon' => $icon,
                    ':number' => $number,
                    ':label' => $label
                ]);
            }

            $pdo->commit();
            $success_message = "Counters added successfully!";
        }

        // Regenerate CSRF token
        $_SESSION['home_counter_csrf_token'] = bin2hex(random_bytes(32));

        // Refresh counters
        $stmt = $pdo->prepare("SELECT * FROM counters ORDER BY id ASC");
        $stmt->execute();
        $counters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Clear the edit mode if we were updating
        if ($is_update) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction())
            $pdo->rollBack();
        $error_message = $e->getMessage();
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Home Counter Section</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                    <?php endif; ?>

                    <!-- Display existing counters -->
                    <?php if (!empty($counters)): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Existing Counters</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Icon</th>
                                                <th>Number</th>
                                                <th>label</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($counters as $counter): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($counter['id']) ?></td>
                                                    <td><i class="<?= htmlspecialchars($counter['icon']) ?>"></i>
                                                        <?= htmlspecialchars($counter['icon']) ?></td>
                                                    <td><?= htmlspecialchars($counter['number']) ?></td>
                                                    <td><?= htmlspecialchars($counter['label']) ?></td>
                                                    <td>
                                                        <a href="?edit=<?= $counter['id'] ?>#update_counter"
                                                            class="btn btn-sm btn-warning">Edit</a>
                                                        <form method="POST" style="display:inline-block;">
                                                            <input type="hidden" name="home_counter_csrf_token"
                                                                value="<?= $_SESSION['home_counter_csrf_token'] ?>">
                                                            <input type="hidden" name="counter_id"
                                                                value="<?= $counter['id'] ?>">
                                                            <button type="submit" name="delete_counter"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this counter?')">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h4><?= $edit_counter ? 'Update Counter' : 'Add New Counters' ?></h4>
                        </div>
                        <div class="card-body">
                            <?php if ($edit_counter): ?>
                                <!-- Edit Form -->
                                <form action="" method="POST">
                                    <input type="hidden" name="home_counter_csrf_token"
                                        value="<?= $_SESSION['home_counter_csrf_token'] ?>">
                                    <input type="hidden" name="counter_id" value="<?= $edit_counter['id'] ?>">

                                    <div class="row" id="update_counter">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="icon"
                                                    value="<?= htmlspecialchars($edit_counter['icon']) ?>"
                                                    class="form-control" placeholder="Icon Class">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Number</label>
                                                <input type="number" name="number"
                                                    value="<?= htmlspecialchars($edit_counter['number']) ?>"
                                                    class="form-control" placeholder="Number" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>label</label>
                                                <input type="text" name="label"
                                                    value="<?= htmlspecialchars($edit_counter['label']) ?>"
                                                    class="form-control" placeholder="label">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="add_counters_form" class="btn btn-primary">Update
                                            Counter</button>
                                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <!-- Add Form -->
                                <form action="" method="POST">
                                    <input type="hidden" name="home_counter_csrf_token"
                                        value="<?= $_SESSION['home_counter_csrf_token'] ?>">

                                    <div id="counters-wrapper">
                                        <div class="counter-block">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" name="counters[0][icon]" placeholder="Icon Class"
                                                        class="form-control mb-2">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" name="counters[0][number]" placeholder="Number"
                                                        class="form-control mb-2" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="counters[0][label]" placeholder="label"
                                                        class="form-control mb-2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" onclick="addCounter()" class="btn btn-secondary mt-2">Add
                                        More</button>
                                    <button type="submit" name="add_counters_form" class="btn btn-primary mt-2">Save
                                        Counters</button>
                                </form>
                            <?php endif; ?>

                            <p class="mt-4 text-muted">
                                Use FontAwesome 4.7 like <code>fa fa-user</code> or follow the
                                <a target="_blank" href="https://fontawesome.com/v4/">
                                    FontAwesome/v4</a>
                                for icons.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("layouts/footer.php"); ?>
<script>
    let counterIndex = 1;

    function addCounter() {
        const wrapper = document.getElementById('counters-wrapper');
        const block = document.createElement('div');
        block.className = 'counter-block';
        block.innerHTML = `
            <div class="row">
                <div class="col-md-4"><input type="text" name="counters[${counterIndex}][icon]" placeholder="Icon Class" class="form-control mb-2"></div>
                <div class="col-md-4"><input type="number" name="counters[${counterIndex}][number]" placeholder="Number" class="form-control mb-2" required></div>
                <div class="col-md-4"><input type="text" name="counters[${counterIndex}][label]" placeholder="label" class="form-control mb-2"></div>
            </div>
        `;
        wrapper.appendChild(block);
        counterIndex++;
    }
</script>
<?php ob_end_flush(); ?>