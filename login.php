<?php include("layouts/header.php"); ?>

<div class="common-banner" style="background-image:url(images/banner.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="item">
                    <h2>Login</h2>
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                            <li class="breadcrumb-item active">Login</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="Loginsection" class="pt_50 pb_50 gray Loginsection">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="login-register-bg">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form action="user-dashboard.html" class="registerd" method="post">
                                <div class="form-group">
                                    <input class="form-control" name="email" placeholder="Email Address" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" placeholder="Password" type="password">
                                </div>
                                <div class="form-group">
                                    <button type="submit">
                                        LOGIN
                                    </button>
                                </div>
                                <div class="form-group bottom">
                                    <a href="forget-password.html">Forgot Password?</a>
                                    <br>
                                    <a href="registration.html">Create New account</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("layouts/footer.php"); ?>