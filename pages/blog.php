<?php

ob_start();
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');


// Validate the incoming ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: ' . BASE_URL . 'blog');
  exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$posts) {
  header('Location: ' . BASE_URL . 'blog');
  exit;
}


?>

<div id="blog-section" class="pt_50 pb_50 white blog-section">
  <div class="container">
    <div class="row pt_40">
      <?php foreach ($posts as $post): ?>
        <div class="col-lg-4 col-sm-6 col-xs-12">
          <div class="blog-box text-center">
            <div class="blog-post-images">
              <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>">
                <img src="<?php echo ADMIN_URL; ?>uploads/<?php echo $post['photo']; ?>" alt="image" />
              </a>
            </div>
            <div class="blogs-post">
              <h4>
                <a href="<?php echo BASE_URL; ?>post?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
              </h4>
              <p>
                <?php echo $post['content']; ?>
              </p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>


<?php include(__DIR__ . '/../includes/footer.php'); ?>
<?php ob_end_flush(); ?>