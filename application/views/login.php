<!DOCTYPE html>
<html lang="en" >
   <head>
      <meta charset="utf-8" />
      <title><?php echo $this->config_model->getConfig('Software') ?></title>
      <meta name="description" content="Latest updates and statistic charts">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link href="<?php echo base_url('assets/css/style.min.css')?>" rel="stylesheet" type="text/css" />
      <link href="<?php echo base_url('assets/css/main.min.css')?>" rel="stylesheet" type="text/css" />
      <link rel="shortcut icon" href="<?php echo base_url('assets/img/main/favicon.png')?>" />
      <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
   </head>
   <body id="page" class="page outer-page">
      <div class="page-body">
         <div class="content-wrapper">
            <div class="login-page">
               <div class="login-wrapper">
                  <div class="col-12 logo mt-auto">
                     <img src="<?php echo base_url('assets/img/main/logo-shadow.png')?>">
                     <span class="text-white d-block mt-20">Login to Your Account</span>
                  </div>
                  <div class="login-form">
                     <form method="post" action="<?php echo site_url('login/authenticate') ?>">
                        <!-- <div class="alert-text text-danger">Failed to login</div> -->
                        <div class="alert alert-danger fade <?php if ($error) echo "show" ?>" role="alert">
                           <div class="alert-text"><?php echo $error ?></div>
                        </div>
                        <div class="form-row">
                           <div class="col-12">
                              <div class="form-group">
                                 <label class="mb-10">Username</label>
                                 <input type="text" name="username" class="form-control" required>
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="form-group">
                                 <label class="mb-10">Password</label>
                                 <input type="password" name="password" class="form-control" required>
                              </div>
                           </div>
                           <div class="col-12">
                              <button type="submit" class="btn btn-primary btn-raised pill btn-block">Login</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <footer class="page-footer">
         <div class="container">
            <div class="d-flex">
               <div class="footer-copyright">
                  <p class="mb-0"><?php echo date('Y') ?> &copy; <a href="http://www.lhprime.com" class="link" target="_new">LH Prime Medical Clinic</a>.</p>
               </div>
            </div>
         </div>
      </footer>
      <!-- footer -->
      <script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js')?>" type="text/javascript"></script>
      <script src="<?php echo base_url('assets/js/popper.js')?>" type="text/javascript"></script>
      <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>" type="text/javascript"></script>
      <script src="<?php echo base_url('assets/js/plugins.min.js')?>" type="text/javascript"></script>
      <script src="<?php echo base_url('assets/js/modernizr.min.js')?>" type="text/javascript"></script>
      <script src="<?php echo base_url('assets/js/custom.js')?>" type="text/javascript"></script>
      <script>
         $(document).ready(function(){
           $("#login-tab").click(function(){
             $(".signin-text").show();
             $(".signup-text").hide();
           });
           $("#signup-tab").click(function(){
             $(".signup-text").show();
             $(".signin-text").hide();
           });
         });
      </script>
   </body>
</html>
