<?php
// Fetch blog section content from database (optional)
$blog_section = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'blog' AND is_active = 1");
    $stmt->execute();
    $blog_section = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Use default values if database fetch fails or table doesn't exist
    $blog_section = [
        'title' => 'Latest News',
        'description' => 'All the latest news and updates about our event and conference are available here. Stay informed and don\'t miss any important information!',
        'is_active' => 1
    ];
}

// Get posts limit setting (default to 3)
$posts_limit = 3;
try {
    $stmt = $pdo->prepare("SELECT description FROM homepage_sections WHERE section_name = 'blog_settings' AND title = 'posts_limit'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $posts_limit = intval($result['description']);
    }
} catch (Exception $e) {
    // Use default
}

// Fixed query with proper parameter binding
try {
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY date DESC LIMIT ?");
    $stmt->bindParam(1, $posts_limit, PDO::PARAM_INT);
    $stmt->execute();
    $homepage_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>

<?php if ($blog_section && $blog_section['is_active']): ?>
    <div id="blog-section" class="pt_70 pb_70 white blog-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-1 col-lg-2"></div>
                <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
                    <h2 class="title-1 mb_15">
                        <span class="color_green"><?php echo htmlspecialchars($blog_section['title']); ?></span>
                    </h2>
                    <p class="heading-space">
                        <?php echo nl2br(htmlspecialchars($blog_section['description'])); ?>
                    </p>
                </div>
                <div class="col-sm-1 col-lg-2"></div>
            </div>

            <?php if (!empty($homepage_posts)): ?>
                <div class="row pt_40">
                    <?php foreach ($homepage_posts as $post): ?>
                        <div class="col-lg-4 col-sm-6 col-xs-12">
                            <div class="blog-box text-center">
                                <div class="blog-post-images">
                                    <?php if (!empty($post['photo'])): ?>
                                        <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>">
                                            <img src="<?php echo ADMIN_URL; ?>/uploads/<?php echo htmlspecialchars($post['photo']); ?>"
                                                alt="<?php echo htmlspecialchars($post['title']); ?>">
                                        </a>
                                    <?php else: ?>
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
                                        <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h4>
                                    <p>
                                        <?php
                                        $content = strip_tags($post['content']);
                                        echo htmlspecialchars(strlen($content) > 120 ? substr($content, 0, 120) . '...' : $content);
                                        ?>
                                    </p>
                                    <?php if (isset($post['date'])): ?>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fa fa-calendar"></i>
                                                <?php echo date('F j, Y', strtotime($post['date'])); ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- View All Posts Button (show if there are more posts than displayed) -->
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM posts");
                    $stmt->execute();
                    $total_posts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                } catch (Exception $e) {
                    $total_posts = count($homepage_posts);
                }
                ?>

                <?php if ($total_posts > $posts_limit): ?>
                    <div class="row pt_30">
                        <div class="col-12 text-center">
                            <a href="<?php echo BASE_URL; ?>blog" class="btn btn-primary">
                                <i class="fas fa-newspaper"></i> View All Posts (<?php echo $total_posts; ?>)
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="row pt_40">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <h4>Coming Soon</h4>
                            <p>We're working on exciting blog content. Stay tuned for updates!</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>