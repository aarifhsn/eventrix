<?php

ob_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Fetch blog section title and description from settings (optional)
$blog_section = null;
try {
  $stmt = $pdo->prepare("SELECT title, description FROM homepage_sections WHERE section_name = 'blog'");
  $stmt->execute();
  $blog_section = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  // Use defaults if settings don't exist
  $blog_section = [
    'title' => 'Our Blog',
    'description' => 'Read our latest news and updates'
  ];
}

// Fetch all posts (keeping your original working query)
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY date DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Optional: Add page header with database-driven content -->
<?php if ($blog_section): ?>
  <div class="container mt-4 mb-2">
    <div class="row">
      <div class="col-12 text-center">
        <h1 class="mb-3"><?php echo htmlspecialchars($blog_section['title']); ?></h1>
        <p class="text-muted"><?php echo htmlspecialchars($blog_section['description']); ?></p>
        <?php if (count($posts) > 0): ?>
          <small class="text-muted"><?php echo count($posts); ?> post<?php echo count($posts) != 1 ? 's' : ''; ?>
            available</small>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<div id="blog-section" class="pt_50 pb_50 white blog-section">
  <div class="container">
    <?php if (!empty($posts)): ?>
      <div class="row pt_40">
        <?php foreach ($posts as $post): ?>
          <div class="col-lg-4 col-sm-6 col-xs-12 mb-4">
            <div class="blog-box text-center">
              <div class="blog-post-images">
                <?php if ($post['photo']): ?>
                  <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>">
                    <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo htmlspecialchars($post['photo']); ?>"
                      alt="<?php echo htmlspecialchars($post['title']); ?>" />
                  </a>
                <?php else: ?>
                  <!-- Placeholder for posts without images -->
                  <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>">
                    <div
                      style="height: 200px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                      <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                  </a>
                <?php endif; ?>
              </div>
              <div class="blogs-post">
                <h4>
                  <a
                    href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                </h4>
                <p>
                  <?php
                  // Truncate content to prevent very long excerpts
                  $content = strip_tags($post['content']);
                  echo htmlspecialchars(strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content);
                  ?>
                </p>
                <!-- Optional: Add date -->
                <?php if (isset($post['created_at'])): ?>
                  <div class="mt-2">
                    <small class="text-muted">
                      <i class="far fa-calendar"></i>
                      <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    </small>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <!-- No posts found -->
      <div class="row pt_40">
        <div class="col-12 text-center">
          <div class="alert alert-info">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h4>No Posts Found</h4>
            <p>There are no blog posts available at the moment. Please check back later!</p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add some CSS for better styling -->
<style>
  .blog-box {
    transition: transform 0.2s ease-in-out;
    height: 100%;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
  }

  .blog-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .blog-post-images img {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }

  .blogs-post {
    padding: 20px;
  }

  .blogs-post h4 a {
    color: #333;
    text-decoration: none;
  }

  .blogs-post h4 a:hover {
    color: #28a745;
  }
</style>

<?php include(__DIR__ . '/../includes/footer.php'); ?>
<?php ob_end_flush(); ?>