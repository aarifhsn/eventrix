<?php
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

include(__DIR__ . '/../config/helpers.php');

// Fetch all features
$stmt = $pdo->prepare("SELECT id, name FROM features ORDER BY id ASC");
$stmt->execute();
$allFeatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch packages and their assigned features
$stmt = $pdo->prepare("
    SELECT 
        p.id AS package_id,
        p.title,
        p.price,
        p.max_price,
        p.item_order,
        pf.feature_id
    FROM packages p
    LEFT JOIN feature_package pf ON p.id = pf.package_id
    ORDER BY p.item_order ASC
");
$stmt->execute();
$rawResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize packages
$packages = [];

foreach ($rawResults as $row) {
  $packageId = $row['package_id'];

  if (!isset($packages[$packageId])) {
    $packages[$packageId] = [
      'title' => $row['title'],
      'price' => $row['price'],
      'features' => []
    ];
  }

  if (!empty($row['feature_id'])) {
    $packages[$packageId]['features'][] = $row['feature_id'];
  }
}
?>

<div id="price-section" class="pt_50 pb_70 gray prices">
  <div class="container">
    <div class="row pt_40">
      <?php foreach ($packages as $package): ?>
        <div class="col-md-4 col-sm-12 my-4">
          <div class="info">
            <h5 class="event-ti-style"><?php echo htmlspecialchars($package['title']); ?></h5>
            <h3 class="event-ti-style"><?php echo htmlspecialchars($package['price']); ?></h3>

            <ul>
              <?php foreach ($allFeatures as $feature): ?>
                <?php
                $isSelected = in_array($feature['id'], $package['features']);
                ?>
                <li>
                  <i class="fa <?php echo $isSelected ? 'fa-check' : 'fa-times'; ?>"></i>
                  <?php echo htmlspecialchars($feature['name']); ?>
                </li>
              <?php endforeach; ?>
            </ul>

            <div class="global_btn mt_20">
              <a class="btn_two" href="<?php echo BASE_URL; ?>buy/<?php echo $package['package_id']; ?>">Buy Ticket</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>