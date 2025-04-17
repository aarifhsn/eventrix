<?php
// get from counters table
try {
    $stmt = $pdo->prepare("SELECT * FROM counters");
    $stmt->execute();
    $counters = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Use fetchAll() instead of fetch()

    // If no counters found, create default values
    if (empty($counters)) {
        $counters = [
            [
                'icon' => 'fas fa-users',
                'number' => '20',
                'label' => 'Speakers'  // Make sure this matches your DB field name
            ]
        ];
    }
} catch (Exception $e) {
    // Handle database errors
    $error_message = $e->getMessage();
    // Provide fallback data in case of database error
    $counters = [
        [
            'icon' => 'fas fa-users',
            'number' => '20',
            'label' => 'Speakers'
        ]
    ];
}
?>

<div id="counter-section" class="pt_70 pb_70"
    style="background-image: url(<?php echo BASE_URL; ?>/dist/images/counter-bg.jpg);">
    <div class="container">
        <div class="row number-counters label-center">
            <?php foreach ($counters as $counter): ?>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="counters-item">
                        <i
                            class="<?php echo !empty($counter['icon']) ? htmlspecialchars($counter['icon']) : 'fas fa-star'; ?>"></i>
                        <strong
                            data-to="3"><?php echo !empty($counter['number']) ? htmlspecialchars($counter['number']) : '0'; ?></strong>
                        <p><?php echo !empty($counter['label']) ? htmlspecialchars($counter['label']) : ''; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>