<?php
include(__DIR__ . '/includes/header.php');

?>

<?php include(__DIR__ . '/templates/home-banner.php'); ?>


<?php include(__DIR__ . '/templates/home-about.php'); ?>


<?php include(__DIR__ . '/templates/home-speakers.php'); ?>


<?php include(__DIR__ . '/templates/home-counter.php'); ?>



<div id="price-section" class="pt_70 pb_70 gray prices">
  <div class="container">

    <div class="row">
      <div class="col-sm-1 col-lg-2"></div>
      <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
        <h2 class="title-1 mb_10"><span class="color_green">Pricing</span></h2>
        <p class="heading-space">
          You will find below the different pricing options for our event. Choose the one that suits you best and
          register now! You will have access to all sessions, unlimited coffee and food, and the opportunity to meet
          with your favorite speakers.
        </p>
      </div>
      <div class="col-sm-1 col-lg-2"></div>
    </div>


    <div class="row pt_40">

      <div class="col-md-4 col-sm-12">
        <div class="info">
          <h5 class="event-ti-style">Standard</h5>
          <h3 class="event-ti-style">$49</h3>
          <ul>
            <li><i class="fa fa-check"></i> Access to all sessions</li>
            <li><i class="fa fa-check"></i> Unlimited Drinkgs & Coffee</li>
            <li><i class="fa fa-times"></i> Lunch Facility</li>
            <li><i class="fa fa-times"></i> Meet with Speakers</li>
          </ul>
          <div class="global_btn mt_20">
            <a class="btn_two" href="buy.html">Buy Ticket</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-12">
        <div class="info">
          <h5 class="event-ti-style">Business</h5>
          <h3 class="event-ti-style">$99</h3>
          <ul>
            <li><i class="fa fa-check"></i> Access to all sessions</li>
            <li><i class="fa fa-check"></i> Unlimited Drinkgs & Coffee</li>
            <li><i class="fa fa-check"></i> Lunch Facility</li>
            <li><i class="fa fa-times"></i> Meet with Speakers</li>
          </ul>
          <div class="global_btn mt_20">
            <a class="btn_two" href="buy.html">Buy Ticket</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-12">
        <div class="info">
          <h5 class="event-ti-style">Premium</h5>
          <h3 class="event-ti-style">$139</h3>
          <ul>
            <li><i class="fa fa-check"></i> Access to all sessions</li>
            <li><i class="fa fa-check"></i> Unlimited Drinkgs & Coffee</li>
            <li><i class="fa fa-check"></i> Lunch Facility</li>
            <li><i class="fa fa-check"></i> Meet with Speakers</li>
          </ul>
          <div class="global_btn mt_20">
            <a class="btn_two" href="buy.html">Buy Ticket</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="blog-section" class="pt_70 pb_70 white blog-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-1 col-lg-2"></div>
      <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
        <h2 class="title-1 mb_15">
          <span class="color_green">Latest News</span>
        </h2>
        <p class="heading-space">
          All the latest news and updates about our event and conference are available here. Stay informed and don't
          miss any important information!
        </p>
      </div>
      <div class="col-sm-1 col-lg-2"></div>
    </div>
    <div class="row pt_40">
      <div class="col-lg-4 col-sm-6 col-xs-12">
        <div class="blog-box text-center">
          <div class="blog-post-<?php echo BASE_URL; ?>/dist/images">
            <a href="post.html">
              <img src="<?php echo BASE_URL; ?>/dist/images/post-1.jpg" alt="image">
            </a>
          </div>
          <div class="blogs-post">
            <h4><a href="post.html">Essential Tips for a Successful Virtual Conference</a></h4>
            <p>
              Organizing a virtual conference can be challenging. Focus on engaging content, interactive sessions, &
              reliable technology to ensure a successful event.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6 col-xs-12">
        <div class="blog-box text-center">
          <div class="blog-post-<?php echo BASE_URL; ?>/dist/images">
            <a href="post.html"><img src="<?php echo BASE_URL; ?>/dist/images/post-2.jpg" alt="image"></a>
          </div>
          <div class="blogs-post">
            <h4><a href="post.html">Maximizing Your Networking Opportunities at Events</a></h4>
            <p>
              Networking at events requires strategic planning. Attend relevant sessions, participate in discussions,
              and utilize apps to connect with professionals.
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6 col-xs-12">
        <div class="blog-box text-center">
          <div class="blog-post-<?php echo BASE_URL; ?>/dist/images">
            <a href="post.html"><img src="<?php echo BASE_URL; ?>/dist/images/post-3.jpg" alt="image"></a>
          </div>
          <div class="blogs-post">
            <h4><a href="post.html">How to Choose the Perfect Venue for Your Conference</a></h4>
            <p>
              Selecting the ideal venue involves considering location, capacity, and amenities. Ensure it aligns with
              your goals, and fits within your budget.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include(__DIR__ . '/templates/home-sponsor.php'); ?>

<?php include(__DIR__ . '/includes/footer.php'); ?>