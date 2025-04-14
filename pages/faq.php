<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>

<div id="faq-section" class="pt_50 pb_50 gray">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div id="accordion" class="faq">
          <div class="card">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                  aria-controls="collapseOne">
                  How do I register for an event or conference on your
                  website?
                </button>
              </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                To register for an event or conference, simply navigate to
                the event's page and click the "Register" button. Fill out
                the required information and complete the payment process,
                if applicable. You will receive a confirmation email with
                your registration details.
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                  aria-expanded="false" aria-controls="collapseTwo">
                  Can I get a refund if I am unable to attend an event?
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                Refund policies vary depending on the event. Please refer to
                the specific event page for details on refunds and
                cancellations. If you have any questions or need assistance,
                you can contact our support team through the "Contact Us"
                page.
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header" id="headingThree">
              <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                  aria-expanded="false" aria-controls="collapseThree">
                  How can I become a sponsor for an event?
                </button>
              </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                To become a sponsor, visit our "Sponsorship Opportunities"
                page where you will find detailed information on sponsorship
                packages and benefits. Fill out the sponsorship application
                form, and our team will get in touch with you to discuss
                further steps and how we can collaborate.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>