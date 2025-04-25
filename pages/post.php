<?php

ob_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Validate the incoming ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: ' . BASE_URL . 'blog');
  exit;
}

$id = (int) $_GET['id']; // type cast to ensure safety

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  header('Location: ' . BASE_URL . 'blog');
  exit;
}

?>

<div id="blog-section" class="pt_50 pb_50 white blog-section blogSectionInn">
  <div class="container">
    <div class="row">
      <div class="page-contents col-lg-12 col-sm-12 col-xs-12">
        <div class="blogs-featured">
          <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $post['photo']; ?>" alt="" />
        </div>
        <div class="blog-post-meta">
          <ul class="post-meta">
            <li class="post-date"><span>Posted On:</span> <?php echo date('F j, Y', strtotime($post['date'])); ?>
            </li>
          </ul>
        </div>
        <div class="post-details awt-track">
          <div class="blogs-post">
            <h4 class="font-weight-bold mb-4"><?php echo $post['title']; ?></h4>
          </div>
          <p>
            <?php echo $post['content']; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>