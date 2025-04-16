<?php
include(__DIR__ . '/includes/header.php');

?>

<?php include(__DIR__ . '/templates/home-banner.php'); ?>


<?php include(__DIR__ . '/templates/home-about.php'); ?>



<div id="speakers" class="pt_70 pb_70 gray team">
  <div class="container">
    <div class="row">
      <div class="col-sm-1 col-lg-2"></div>
      <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
        <h2 class="title-1 mb_10">
          <span class="color_green">Speakers</span>
        </h2>
        <p class="heading-space">
          You will find below the list of our valuable speakers. They are all experts in their field and will share
          their knowledge with you.
        </p>
      </div>
      <div class="col-sm-1 col-lg-2"></div>
    </div>
    <div class="row pt_40">
      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="team-img mb_20">
          <a href="speaker.html"><img src="<?php echo BASE_URL; ?>/dist/images/speaker-1.jpg"></a>
        </div>
        <div class="team-info text-center">
          <h6><a href="speaker.html">Danny Allen</a></h6>
          <p>Founder, AA Company</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="team-img mb_20">
          <a href="speaker.html"><img src="<?php echo BASE_URL; ?>/dist/images/speaker-2.jpg"></a>
        </div>
        <div class="team-info text-center">
          <h6><a href="speaker.html">John Sword</a></h6>
          <p>Founder, BB Company</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="team-img mb_20">
          <a href="speaker.html"><img src="<?php echo BASE_URL; ?>/dist/images/speaker-3.jpg"></a>
        </div>
        <div class="team-info text-center">
          <h6><a href="speaker.html">Steven Gragg</a></h6>
          <p>Founder, CC Company</p>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="team-img mb_20">
          <a href="speaker.html"><img src="<?php echo BASE_URL; ?>/dist/images/speaker-4.jpg"></a>
        </div>
        <div class="team-info text-center">
          <h6><a href="speaker.html">Jordan Parker</a></h6>
          <p>Founder, DD Company</p>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="counter-section" class="pt_70 pb_70"
  style="background-image: url(<?php echo BASE_URL; ?>/dist/images/counter-bg.jpg);">
  <div class="container">
    <div class="row number-counters text-center">
      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="counters-item">
          <i class="fa fa-calendar"></i>
          <strong data-to="3">0</strong>
          <p>Days Event</p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="counters-item">
          <i class="fa fa-user"></i>
          <strong data-to="8">0</strong>
          <p>Speakers</p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="counters-item">
          <i class="fa fa-users"></i>
          <strong data-to="60">0</strong>
          <p>Members Registered</p>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 col-xs-12">
        <div class="counters-item">
          <i class="fa fa-th-list"></i>
          <strong data-to="12">0</strong>
          <p>Sponsors</p>
        </div>
      </div>
    </div>
  </div>
</div>



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


<div id="sponsor-section" class="pt_70 pb_70 gray">
  <div class="container">
    <div class="row">
      <div class="col-sm-1 col-lg-2"></div>
      <div class="col-xs-12 col-sm-10 col-lg-8 text-center">
        <h2 class="title-1 mb_15">
          <span class="color_green">Our Sponsers</span>
        </h2>
        <p class="heading-space">
          If you want to become a sponsor, please contact us. We offer different sponsorship packages that will help
          you promote your brand and reach a wider audience.
        </p>
      </div>
      <div class="col-sm-1 col-lg-2"></div>
    </div>
    <div class="row pt_40">
      <div class="col-md-12">
        <div class="owl-carousel">
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-1.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-2.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-3.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-4.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-5.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-6.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-7.png" class="img-responsive" alt="sponsor logo">
          </div>
          <div class="sponsors-logo">
            <img src="<?php echo BASE_URL; ?>/dist/images/partner-8.png" class="img-responsive" alt="sponsor logo">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/includes/footer.php'); ?>