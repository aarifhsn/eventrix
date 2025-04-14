<?php

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

?>
<div id="speakers" class="pt_70 pb_70 white team speakers-item">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="speaker-detail-img">
          <img src="images/speaker-1.jpg" />
        </div>
      </div>
      <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="speaker-detail">
          <h2>John Smith</h2>
          <h4 class="mb_20">Founder, AA Company</h4>
          <p>
            John is a renowned User Experience (UX) designer with over 15
            years of experience in the field. He holds a Master's degree in
            Human-Computer Interaction from Carnegie Mellon University.
            Throughout his career, John has worked with top-tier tech
            companies, including Google and Apple, where he led teams in
            designing user-friendly interfaces for a range of digital
            products. His expertise lies in creating seamless and engaging
            user experiences that not only meet but exceed user
            expectations.
          </p>
          <p>
            In addition to his professional accomplishments, John is a
            sought-after speaker and educator. He regularly conducts
            workshops and seminars at major design conferences worldwide,
            sharing his insights and knowledge with aspiring designers and
            industry veterans alike.
          </p>

          <h4>More Information</h4>
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <th><b>Address:</b></th>
                <td>43, Park Street, NYC, USA</td>
              </tr>
              <tr>
                <th><b>Email:</b></th>
                <td>contact@example.com</td>
              </tr>
              <tr>
                <th><b>Phone:</b></th>
                <td>123-322-1248</td>
              </tr>
              <tr>
                <th><b>Website:</b></th>
                <td>
                  <a href="https://www.example.com" target="_blank">https://www.example.com</a>
                </td>
              </tr>
              <tr>
                <th><b>Social:</b></th>
                <td>
                  <ul class="social-icon">
                    <li>
                      <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-instagram"></i></a>
                    </li>
                  </ul>
                </td>
              </tr>
            </table>
          </div>

          <h4>My Sessions</h4>
          <div class="row">
            <div class="col-md-6">
              <div class="speaker-img">
                <img src="images/day1_session1.jpg" />
              </div>
              <div class="speaker-box">
                <h3>Introduction to PHP and Laravel</h3>
                <h4>
                  <span>Tim Center, 34, Park Street, NYC, USA</span><br />
                  <span>Sep 20, 2024 (Day 1)</span><br />
                  <span>09:00 AM - 09:45 AM</span>
                </h4>
              </div>
            </div>
            <div class="col-md-6">
              <div class="speaker-img">
                <img src="images/day3_session1.jpg" />
              </div>
              <div class="speaker-box">
                <h3>User Experience (UX) Design Principles</h3>
                <h4>
                  <span>Tim Center, 34, Park Street, NYC, USA</span><br />
                  <span>Sep 22, 2024 (Day 3)</span><br />
                  <span>10:00 AM - 10:30 AM</span>
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>