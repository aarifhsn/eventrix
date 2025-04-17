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
                'icon' => 'fa fa-user',
                'number' => '20',
                'label' => 'Speakers'
            ],
            [
                'icon' => 'fa fa-retweet',
                'number' => '15',
                'label' => 'Sponsors'
            ],
        ];
    }
} catch (Exception $e) {
    // Handle database errors
    $error_message = $e->getMessage();
    // Provide fallback data in case of database error
    $counters = [
        [
            'icon' => 'fa fa-user',
            'number' => '20',
            'label' => 'Speakers'
        ]
    ];
}
?>

<div id="counter-section" class="pt_70 pb_70"
    style="background-image: url(<?php echo BASE_URL; ?>/dist/images/counter-bg.jpg);">
    <div class="container">
        <div class="row number-counters label-center text-center">
            <?php foreach ($counters as $counter): ?>
                <?php
                $totalCounters = count($counters);

                $col = match ($totalCounters) {
                    1 => 'col-12 col-sm-12 col-md-12 col-lg-12',
                    2 => 'col-6 col-sm-6 col-md-6 col-lg-6',
                    3 => 'col-12 col-sm-6 col-md-4 col-lg-4',
                    4 => 'col-12 col-sm-6 col-md-3 col-lg-3',
                    5 => 'col-6 col-sm-4 col-md-3 col-lg-2',
                    default => 'col-6 col-sm-4 col-md-3 col-lg-2', // fallback for 6+
                };
                ?>
                <div class="<?php echo $col; ?>">
                    <div class="counters-item">
                        <?php $value = !empty($counter['number']) ? htmlspecialchars($counter['number']) : '0'; ?>
                        <i
                            class="<?php echo !empty($counter['icon']) ? htmlspecialchars($counter['icon']) : 'fas fa-star'; ?>"></i>
                        <strong data-to="<?php echo $value; ?>"><?php echo $value; ?></strong>
                        <p><?php echo !empty($counter['label']) ? htmlspecialchars($counter['label']) : ''; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>