<?php

ob_start();
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin']) || !isset($_SESSION['admin']['id'])) {
    header('Location: login.php');
    exit;
}

// Include necessary files
include(__DIR__ . '/layouts/header.php');
include(__DIR__ . '/layouts/navbar.php');
include(__DIR__ . '/layouts/sidebar.php');

// Initialize
$success_message = '';
$error_message = '';
$blog_section = null;

// Generate CSRF token if not exists
if (!isset($_SESSION['home_blog_csrf_token'])) {
    $_SESSION['home_blog_csrf_token'] = bin2hex(random_bytes(32));
}

// Insert default blog section if not exists
try {
    $stmt = $pdo->prepare("INSERT IGNORE INTO homepage_sections (section_name, title, description) VALUES (?, ?, ?)");
    $stmt->execute([
        'blog', 
        'Latest News', 
        'All the latest news and updates about our event and conference are available here. Stay informed and don\'t miss any important information!'
    ]);
} catch (Exception $e) {
    $error_message = "Database setup error: " . $e->getMessage();
}

// Fetch current blog section data
try {
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'blog'");
    $stmt->execute();
    $blog_section = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching blog section data: " . $e->getMessage();
}

// Handle UPDATE operation for section content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_blog_section'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_blog_csrf_token']) || $_POST['home_blog_csrf_token'] !== $_SESSION['home_blog_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $posts_limit = intval($_POST['posts_limit'] ?? 3);

        if (empty($title)) {
            throw new Exception("Title cannot be empty.");
        }

        if (empty($description)) {
            throw new Exception("Description cannot be empty.");
        }

        if ($posts_limit < 1 || $posts_limit > 12) {
            throw new Exception("Posts limit must be between 1 and 12.");
        }

        // Update or insert blog section settings
        $stmt = $pdo->prepare("UPDATE homepage_sections SET title = :title, description = :description, is_active = :is_active WHERE section_name = 'blog'");
        $result = $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':is_active' => $is_active
        ]);

        // Store posts limit in a separate setting or use JSON in description field
        // For simplicity, we'll store it as a separate setting
        $stmt = $pdo->prepare("INSERT INTO homepage_sections (section_name, title, description) VALUES ('blog_settings', 'posts_limit', ?) ON DUPLICATE KEY UPDATE description = VALUES(description)");
        $stmt->execute([$posts_limit]);

        $success_message = "Blog section updated successfully!";

        // Regenerate CSRF token
        $_SESSION['home_blog_csrf_token'] = bin2hex(random_bytes(32));

        // Refresh blog section data
        $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'blog'");
        $stmt->execute();
        $blog_section = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle individual post homepage settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post_homepage'])) {
    try {
        // CSRF validation
        if (!isset($_POST['home_blog_csrf_token']) || $_POST['home_blog_csrf_token'] !== $_SESSION['home_blog_csrf_token']) {
            throw new Exception("Invalid CSRF token.");
        }

        $post_id = intval($_POST['post_id'] ?? 0);
        $show_on_homepage = isset($_POST['show_on_homepage']) ? 1 : 0;

        if ($post_id <= 0) {
            throw new Exception("Invalid post ID.");
        }

        // Add show_on_homepage column if it doesn't exist
        try {
            $pdo->exec("ALTER TABLE posts ADD COLUMN show_on_homepage TINYINT(1) DEFAULT 1");
        } catch (Exception $e) {
            // Column might already exist, ignore error
        }

        $stmt = $pdo->prepare("UPDATE posts SET show_on_homepage = :show_on_homepage WHERE id = :id");
        $stmt->execute([
            ':show_on_homepage' => $show_on_homepage,
            ':id' => $post_id
        ]);

        $success_message = "Post homepage setting updated successfully!";

        // Regenerate CSRF token
        $_SESSION['home_blog_csrf_token'] = bin2hex(random_bytes(32));

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Fetch posts count and homepage posts count
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts");
    $stmt->execute();
    $posts_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Add show_on_homepage column if it doesn't exist
    try {
        $pdo->exec("ALTER TABLE posts ADD COLUMN show_on_homepage TINYINT(1) DEFAULT 1");
    } catch (Exception $e) {
        // Column might already exist, ignore error
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM posts WHERE show_on_homepage = 1");
    $stmt->execute();
    $homepage_posts_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (Exception $e) {
    $posts_count = 0;
    $homepage_posts_count = 0;
}

// Fetch current posts limit setting
try {
    $stmt = $pdo->prepare("SELECT description FROM homepage_sections WHERE section_name = 'blog_settings' AND title = 'posts_limit'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_posts_limit = $result ? intval($result['description']) : 3;
} catch (Exception $e) {
    $current_posts_limit = 3;
}

// Fetch all posts for management
try {
    $stmt = $pdo->prepare("SELECT id, title, photo, show_on_homepage, created_at FROM posts ORDER BY created_at DESC");
    $stmt->execute();
    $all_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $all_posts = [];
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Home Blog Section</h1>
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

                    <!-- Info Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="far fa-newspaper"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Total Posts</h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $posts_count ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="far fa-eye"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Homepage Posts</h4>
                                    </div>
                                    <div class="card-body">
                                        <?= $homepage_posts_count ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <a href="posts.php" class="btn btn-primary btn-sm mr-2">
                                        <i class="fas fa-plus"></i> Add New Post
                                    </a>
                                    <a href="posts.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-newspaper"></i> Manage All Posts
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Content Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Blog Section Content</h4>
                            <div class="card-header-action">
                                <?php if ($blog_section && $blog_section['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($blog_section): ?>
                                <form action="" method="POST">
                                    <input type="hidden" name="home_blog_csrf_token"
                                        value="<?= $_SESSION['home_blog_csrf_token'] ?>">

                                    <div class="form-group">
                                        <label for="title">Section Title <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="title" 
                                               id="title"
                                               value="<?= htmlspecialchars($blog_section['title']) ?>"
                                               class="form-control" 
                                               placeholder="Enter section title"
                                               maxlength="255"
                                               required>
                                        <small class="form-text text-muted">This will be displayed as the main heading of the blog section.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Section Description <span class="text-danger">*</span></label>
                                        <textarea name="description" 
                                                  id="description"
                                                  class="form-control" 
                                                  rows="3"
                                                  placeholder="Enter section description"
                                                  required><?= htmlspecialchars($blog_section['description']) ?></textarea>
                                        <small class="form-text text-muted">This description will appear below the title.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="posts_limit">Posts to Display on Homepage</label>
                                        <select name="posts_limit" id="posts_limit" class="form-control">
                                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                                <option value="<?= $i ?>" <?= $current_posts_limit == $i ? 'selected' : '' ?>>
                                                    <?= $i ?> Post<?= $i > 1 ? 's' : '' ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                        <small class="form-text text-muted">Maximum number of posts to show on homepage.</small>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   id="is_active" 
                                                   class="custom-control-input"
                                                   <?= $blog_section['is_active'] ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="is_active">Show blog section on homepage</label>
                                        </div>
                                        <small class="form-text text-muted">Uncheck to hide the entire blog section from homepage.</small>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="update_blog_section" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Blog Section
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Posts Management -->
                    <?php if (!empty($all_posts)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Manage Posts for Homepage</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Show on Homepage</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_posts as $post): ?>
                                        <tr>
                                            <td>
                                                <?php if ($post['photo']): ?>
                                                    <img src="<?= ADMIN_URL ?>uploads/<?= htmlspecialchars($post['photo']) ?>" 
                                                         alt="Post Image" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($post['title']) ?></strong>
                                            </td>
                                            <td>
                                                <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                            </td>
                                            <td>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="home_blog_csrf_token" value="<?= $_SESSION['home_blog_csrf_token'] ?>">
                                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                                    <?php if ($post['show_on_homepage']): ?>
                                                        <button type="submit" name="update_post_homepage" class="btn btn-sm btn-success">
                                                            <i class="fas fa-eye"></i> Visible
                                                        </button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="show_on_homepage" value="1">
                                                        <button type="submit" name="update_post_homepage" class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-eye-slash"></i> Hidden
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="edit-post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-3x mb-3"></i>
                                <h5>No Posts Found</h5>
                                <p>You haven't created any blog posts yet. <a href="add-post.php">Create your first post</a> to get started!</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Preview -->
                    <?php if ($blog_section && $homepage_posts_count > 0): ?>
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-eye"></i> Homepage Preview</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h2 class="mb-3">
                                    <span style="color: #28a745;"><?= htmlspecialchars($blog_section['title']) ?></span>
                                </h2>
                                <p class="text-muted mb-4">
                                    <?= nl2br(htmlspecialchars($blog_section['description'])) ?>
                                </p>
                                <small class="text-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Showing <?= min($current_posts_limit, $homepage_posts_count) ?> of <?= $homepage_posts_count ?> posts on homepage
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("layouts/footer.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const descriptionTextarea = document.getElementById('description');
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Character count for title
    const titleInput = document.getElementById('title');
    if (titleInput) {
        titleInput.addEventListener('input', function() {
            const remaining = 255 - this.value.length;
            let counter = document.getElementById('title-counter');
            if (!counter) {
                counter = document.createElement('small');
                counter.id = 'title-counter';
                counter.className = 'form-text text-muted';
                titleInput.parentNode.appendChild(counter);
            }
            counter.textContent = `${remaining} characters remaining`;
            counter.className = remaining < 20 ? 'form-text text-warning' : 'form-text text-muted';
        });
    }
});
</script>

<?php ob_end_flush(); ?>