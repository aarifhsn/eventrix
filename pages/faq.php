<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');


$stmt = $pdo->prepare("SELECT * FROM faqs");
$stmt->execute();
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="faq-section" class="pt_50 pb_50 gray">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div id="accordion" class="faq">
          <?php foreach ($faqs as $index => $faq):
            $headingId = "heading" . $faq['id'];
            $collapseId = "collapse" . $faq['id'];
            $isFirst = $index === 0;
            ?>
            <div class="card">
              <div class="card-header" id="<?php echo $headingId; ?>">
                <h5 class="mb-0">
                  <button class="btn btn-link <?php echo !$isFirst ? 'collapsed' : ''; ?>" data-toggle="collapse"
                    data-target="#<?php echo $collapseId; ?>" aria-expanded="true"
                    aria-controls="<?php echo $collapseId; ?>">
                    <?php echo $faq['title']; ?>
                  </button>
                </h5>
              </div>

              <div id="<?php echo $collapseId; ?>" class="collapse <?php echo $isFirst ? 'show' : ''; ?>"
                aria-labelledby="<?php echo $headingId; ?>" data-parent="#accordion">
                <div class="card-body">
                  <?php echo $faq['details']; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>