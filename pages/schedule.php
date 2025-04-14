<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>

<div id="schedule-section" class="gray pt_50 pb_50">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 schedule-tab">
        <ul id="scheduleTab" class="nav nav-tabs justify-content-center text-center">
          <li class="nav-item">
            <a href="#" data-target="#one" data-toggle="tab" class="nav-link active">
              <p>Day 1</p>
              <span>Sep 20, 2024</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" data-target="#two" data-toggle="tab" class="nav-link">
              <p>Day 2</p>
              <span>Sep 21, 2024</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" data-target="#three" data-toggle="tab" class="nav-link">
              <p>Day 3</p>
              <span>Sep 22, 2024</span>
            </a>
          </li>
        </ul>

        <div id="scheduleTabContent" class="tab-content">
          <div id="one" class="tab-pane active show fade">
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day1_session1.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 1</h2>
                  <h3>Introduction to PHP and Laravel</h3>
                  <p>
                    Join our experts, John Smith and Pat Flynn, as they
                    guide you through the fundamentals of PHP and how it
                    integrates with Laravel to build robust web
                    applications. Perfect for beginners and those looking to
                    enhance their web development skills.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">John Smith</a>
                    <a href="speaker.php" class="badge badge-primary">Pat Flynn</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Tim Center (3rd Floor), 34, Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>09:00 AM - 09:45 AM</span>
                  </h4>
                </div>
              </div>
            </div>
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day1_session2.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 2</h2>
                  <h3>Advanced SEO Technique</h3>
                  <p>
                    Discover advanced SEO strategies with Robin Hood, a
                    seasoned SEO expert, to improve your website's
                    visibility and ranking on search engines. This session
                    is ideal for professionals looking to stay ahead in the
                    competitive digital landscape.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">Robin Hood</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Tim Center (3rd Floor), 34, Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>10:00 AM - 10:30 AM</span>
                  </h4>
                </div>
              </div>
            </div>
          </div>

          <div id="two" class="tab-pane fade">
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day2_session1.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 1</h2>
                  <h3>Introduction to Artificial Intelligence</h3>
                  <p>
                    Dive into the world of AI with Dr. Paul Smith, a leading
                    researcher in the field. This session will cover the
                    basics of artificial intelligence, its applications, and
                    the future potential of AI technologies.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">Paul Smith</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Rokman Hall (5th Floor), 76 Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>10:00 AM - 10:45 AM</span>
                  </h4>
                </div>
              </div>
            </div>
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day2_session2.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 2</h2>
                  <h3>Machine Learning for Beginners</h3>
                  <p>
                    Join Alex Johnson, a machine learning expert, as he
                    simplifies the concepts of machine learning. This
                    session is perfect for those new to the field, providing
                    an overview of algorithms, models, and real-world
                    applications.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">Alex Johnson</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Rokman Hall (5th Floor), 76 Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>11:00 AM - 11:30 AM</span>
                  </h4>
                </div>
              </div>
            </div>
          </div>

          <div id="three" class="tab-pane fade">
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day3_session1.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 1</h2>
                  <h3>User Experience (UX) Design Principles</h3>
                  <p>
                    Join Don Anderson, a seasoned UX designer, as she walks
                    you through the fundamental principles of user
                    experience design. This session will cover key concepts
                    such as user research, wireframing, and creating
                    intuitive interfaces.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">Don Anderson</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Tim Center (2nd Floor), 34, Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>10:00 AM - 10:30 AM</span>
                  </h4>
                </div>
              </div>
            </div>
            <div class="row speaker-mainbox">
              <div class="col-lg-4 col-xs-12">
                <div class="speaker-img">
                  <img src="dist/images/day3_session2.jpg" />
                </div>
              </div>
              <div class="col-lg-8 col-xs-12">
                <div class="speaker-box">
                  <h2>Session 2</h2>
                  <h3>Graphic Design Trends in 2024</h3>
                  <p>
                    Discover the latest trends in graphic design with Mark
                    Thompson, a creative director with a keen eye for
                    aesthetics. This session will explore current trends,
                    tools, and techniques that are shaping the graphic
                    design landscape in 2024.
                  </p>
                  <h3>Speakers:</h3>
                  <h4>
                    <a href="speaker.php" class="badge badge-primary">Mark Thompson</a>
                  </h4>
                  <h3>Location:</h3>
                  <h4>
                    <span>Tim Center (4th Floor), 34, Park Street, NYC,
                      USA</span>
                  </h4>
                  <h3>Time:</h3>
                  <h4>
                    <span>11:00 AM - 11:30 AM</span>
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>