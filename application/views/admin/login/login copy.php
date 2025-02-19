<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <title>Login In</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- STYLESHEETS -->
  <!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
  <link rel="stylesheet" type="text/css" href="<?php echo site_url("assets/" . ADMIN_DIR); ?>/css/cloud-admin.css">
  <link href="<?php echo site_url("assets/" . ADMIN_DIR); ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">


  <link rel="stylesheet" href="//fonts.googleapis.com/earlyaccess/notonastaliqurdudraft.css">

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-151551956-1');
  </script>
</head>

<body class="log in" style="position: relative;
    height: 100%;
    background-image: url(<?php echo site_url("assets/" . ADMIN_DIR . "img/background.jpeg"); ?>);
    background-size: cover;
    ">
  <!-- PAGE -->
  <section id="page">

    <section id="login_bg" <?php if ($this->input->get('register') != 1) { ?>class="visible" <?php } ?>>
      <div class="container">
        <div class="row" style="margin: 10px; margin-top: 70px;">
          <div class="col-md-7">
            <div id="logo">
              <div style=" width:100%; text-align: center; margin:0px auto; color:black; ">
                <style>
                  @media (max-width:629px) {
                    img#logo_image {
                      display: none;
                    }
                  }
                </style>
                <img id="logo_image" src="<?php echo site_url("assets/uploads/" . $system_global_settings[0]->sytem_admin_logo); ?>" alt="<?php echo $system_global_settings[0]->system_title ?>" title="<?php echo $system_global_settings[0]->system_title ?>" style="width:500px !important;" />

                <h2><?php echo $system_global_settings[0]->system_title ?></h2>
                <h2><?php echo $system_global_settings[0]->system_sub_title ?></h2>
                <address><i class="fa fa-envelope"></i> <?php echo $system_global_settings[0]->email_address ?>
                  <span style="margin-left: 10px;"></span> <i class="fa fa-phone" aria-hidden="true"></i>

                  Phone: <?php echo $system_global_settings[0]->phone_number ?>
                  <?php //echo "Mobile: ".$system_global_settings[0]->mobile_number 
                  ?>
                  <span style="margin-left: 10px;"></span> <i class="fa fa-fax" aria-hidden="true"></i>
                  Fax: <?php echo $system_global_settings[0]->fax_number ?>
                  <br />
                  <span style="margin-left: 10px;"></span> <i class="fa fa-globe" aria-hidden="true"></i>
                  website: <a style="color: blue;" target="_blank" href="<?php echo $system_global_settings[0]->web_address ?>"><?php echo $system_global_settings[0]->web_address ?></a>
                  <br />


                  <i class="fa fa-map-marker" aria-hidden="true"></i>
                  <?php echo $system_global_settings[0]->address ?>
                </address>

              </div>
              <div style="clear:both;"></div>

            </div>
          </div>
          <div class="col-md-4">
            <div class="login-box" style="background-color:#9bb3bb;  margin: 5px auto; padding-top:10px !important; padding: 55px 40px 40px;">
              <h2 class="bigintro">Sign In</h2>
              <div class="divide-20"></div>

              <?php
              if ($this->session->flashdata("msg") || $this->session->flashdata("msg_error") || $this->session->flashdata("msg_success")) {

                $type = "";
                if ($this->session->flashdata("msg_success")) {
                  $type = "success";
                  $msg = $this->session->flashdata("msg_success");
                } elseif ($this->session->flashdata("msg_error")) {
                  $type = "danger";
                  $msg = $this->session->flashdata("msg_error");
                } else {
                  $type = "info";
                  $msg = $this->session->flashdata("msg");
                }
                echo '<div style="font-family: \'Noto Nastaliq Urdu Draft\', serif; line-height: 22px;" class="alert alert-' . $type . '" role="alert"> <i class="fa fa-info-circle" aria-hidden="true"></i>  ' . $msg . '</div>';
              }
              ?>

              <?php if (validation_errors()) { ?>
                <div class="alert alert-block alert-danger fade in">
                  <?php echo validation_errors(); ?>
                </div>
              <?php } ?>

              <form role="form" method="post" action="<?php echo site_url(ADMIN_DIR . "login/validate_user"); ?>">
                <div class="form-group">
                  <label for="userName">User Name</label>
                  <i class="fa fa-user"></i>
                  <input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo set_value("userName"); ?>">
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Password</label>
                  <i class="fa fa-lock"></i>
                  <input type="password" name="user_password" class="form-control" id="user_password" value="">
                </div>
                <div class="form-group">
                  <div class="g-recaptcha" data-sitekey="6Leuqa4ZAAAAAEBURd3DWqmwV4cdzXi5zzcljMLR" style="height: 100px;">
                  </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-danger">Login</button>
                </div>
              </form>
              <!-- SOCIAL LOGIN -->
              <div class="divide-20"></div>



            </div>

          </div>
        </div>
      </div>

    </section>

    <section id="forgot_bg">




      <div class="container">
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <div class="login-box" style="background-color:#2C2C2C; opacity:.9; margin: 5px auto; padding-top:10px !important;">
              <h2 class="bigintro">Reset Password</h2>
              <div class="divide-40"></div>
              <form role="form">
                <div class="form-group">
                  <label for="exampleInputEmail1">Enter your Email address</label>
                  <i class="fa fa-envelope"></i>
                  <input type="email" class="form-control" id="exampleInputEmail1">
                </div>
                <div>
                  <button type="submit" class="btn btn-info">Send Me Reset Instructions</button>
                </div>
              </form>
              <div class="login-helpers"> <a href="<?php echo site_url("assets/" . ADMIN_DIR); ?>/#" onclick="swapScreen('login_bg');return false;">Back to Login</a> <br>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- FORGOT PASSWORD -->
  </section>
  <!--/PAGE -->
  <!-- JAVASCRIPTS -->
  <!-- Placed at the end of the document so the pages load faster -->
  <!-- JQUERY -->
  <script src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/jquery/jquery-2.0.3.min.js"></script>
  <!-- JQUERY UI-->
  <script src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
  <!-- BOOTSTRAP -->
  <script src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/bootstrap-dist/js/bootstrap.min.js"></script>

  <!-- UNIFORM -->
  <script type="text/javascript" src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/uniform/jquery.uniform.min.js"></script>
  <!-- BACKSTRETCH -->
  <script type="text/javascript" src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/backstretch/jquery.backstretch.min.js"></script>
  <!-- CUSTOM SCRIPT -->
  <script src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/script.js"></script>

</body>

</html>