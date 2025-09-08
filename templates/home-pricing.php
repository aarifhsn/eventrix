<?php
// Fetch pricing section content from database
$pricing_section = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM homepage_sections WHERE section_name = 'pricing' AND is_active = 1");
    $stmt->execute();
    $pricing_section = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Use default values if database fetch fails
    $pricing_section = [
        'title' => 'Pricing',
        'description' => 'You will find below the different pricing options for our event. Choose the one that suits you best and register now! You will have access to all sessions, unlimited coffee and food, and the opportunity to meet with your favorite speakers.'
    ];
}

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

<?php if ($pricing_section): ?>
    <div id="price-section" class="pt_70 pb_70 gray prices">
        <div class="container">
            <div class="row">
                <div class="col-sm-1 col-lg-2"></div>
                <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
                    <h2 class="title-1 mb_10">
                        <span class="color_green"><?php echo htmlspecialchars($pricing_section['title']); ?></span>
                    </h2>
                    <p class="heading-space">
                        <?php echo nl2br(htmlspecialchars($pricing_section['description'])); ?>
                    </p>
                </div>
                <div class="col-sm-1 col-lg-2"></div>
            </div>

            <?php if (!empty($packages)): ?>
                <div class="row pt_40">
                    <?php foreach ($packages as $packageId => $package): ?>
                        <div class="col-md-4 col-sm-12 my-4">
                            <div class="info">
                                <h5 class="event-ti-style"><?php echo htmlspecialchars($package['title']); ?></h5>
                                <h3 class="event-ti-style"><?php echo htmlspecialchars($package['price']); ?></h3>

                                <?php if (!empty($allFeatures)): ?>
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
                                <?php endif; ?>

                                <div class="global_btn mt_20">
                                    <a class="btn_two"
                                        href="<?php echo BASE_URL; ?>buy?package=<?php echo strtolower($package['title']); ?>">
                                        Buy Ticket
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="row pt_40">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <h4>No Packages Available</h4>
                            <p>We're currently setting up our pricing packages. Please check back soon!</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>