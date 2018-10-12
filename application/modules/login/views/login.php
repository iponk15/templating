<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        
        <title>Login - Template Metronic</title>
        <meta name="description" content="Latest updates and statistic charts"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
          	WebFont.load({
				google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
				active: function() {
					sessionStorage.fonts = true;
				}
          	});
        </script>
		<link href="<?php echo base_url() ?>/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url() ?>assets/demo/base/style.bundle.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo base_url() ?>/assets/demo/media/img/logo/favicon.ico" /> 
    </head>
    <!-- end::Head -->
    <!-- begin::Body -->
    <body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >       
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">		
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--signin" id="m_login">
				<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
					<div class="m-stack m-stack--hor m-stack--desktop">
						<div class="m-stack__item m-stack__item--fluid">
							<div class="m-login__wrapper">
								<div class="m-login__logo">
									<a href="#">
									<img src="<?php echo base_url() ?>/assets/app/media/img/logos/logo-1.png">  	
									</a>
								</div>

								<div class="m-login__signin">
									<div class="m-login__head">
										<h3 class="m-login__title">Sign In To Admin</h3>
									</div>
									<form method="POST" class="m-login__form m-form" action="<?php echo base_url('login/cek_login'); ?>">
										<div class="form-group m-form__group">
											<input tabindex="1" class="form-control m-input" type="email" placeholder="Email" name="user_email" autocomplete="off">
										</div>
										<div class="form-group m-form__group">
											<input tabindex="2" class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="user_password">
										</div>
										<div class="form-group m-form__group">
											<val id="image_captcha"><?php echo $captcha['capImage']; ?></val> &nbsp; 
											<button type="button" id="syncap" class="btn btn-outline-success m-btn m-btn--icon m-btn--icon-only m-btn--outline-2x captcha-refresh">
												<i class="fa fa-sync-alt"></i>
											</button>
										</div>
										<div class="form-group m-form__group">
											<input tabindex="3" class="form-control m-input m-login__form-input--last" type="text" placeholder="Captcha" name="catpcha">
										</div>
										<div class="row m-login__form-sub">
											<div class="col m--align-left">
												<label class="m-checkbox m-checkbox--focus">
												<input type="checkbox" name="remember"> Remember me
												<span></span>
												</label>
											</div>
											<div class="col m--align-right">
												<a href="javascript:;" id="m_login_forget_password" class="m-link">Forget Password ?</a>
											</div>
										</div>
										<div class="m-login__form-action">
											<button tabindex="4" type="submit" id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Sign In</button>
										</div>
									</form>
								</div>
								<div class="m-login__signup">
									<div class="m-login__head">
										<h3 class="m-login__title">Sign Up</h3>
										<div class="m-login__desc">Enter your details to create your account:</div>
									</div>
									<form method="POST" class="m-login__form m-form" action="<?php echo base_url('login/register'); ?>">
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="text" placeholder="Nama" name="nama">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="text" placeholder="Email" name="email" autocomplete="off">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input pSwd" type="password" placeholder="Password" name="password">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="rpassword">
										</div>
										<div class="row form-group m-form__group m-login__form-sub">
											<div class="col m--align-left">
												<label class="m-checkbox m-checkbox--focus">
												<input type="checkbox" name="agree"> I Agree the <a href="#" class="m-link m-link--focus">terms and conditions</a>.
												<span></span>
												</label>
												<span class="m-form__help"></span>
											</div>
										</div>
										<div class="m-login__form-action">
											<button id="m_login_signup_cancel" class="btn btn-outline-focus  m-btn m-btn--pill m-btn--custom">Batal</button>
											<button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Submit</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="m-stack__item m-stack__item--center">  
							<div class="m-login__account">
								<span class="m-login__account-msg">
								Don't have an account yet ?
								</span>&nbsp;&nbsp;
								<a href="javascript:;" id="m_login_signup" class="m-link m-link--focus m-login__account-link">Sign Up</a>
							</div>
						</div>
					</div>
				</div>
				<div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content m-grid-item--center" style="background-image: url(<?php echo base_url() ?>/assets/app/media/img/bg/bg-1.jpg)">
					<div class="m-grid__item">
						<h3 class="m-login__welcome">Template Metronic</h3>
						<p class="m-login__msg">
							<!-- Lorem ipsum dolor sit amet, coectetuer adipiscing<br>elit sed diam nonummy et nibh euismod -->
						</p>
					</div>
				</div>
			</div>				
		</div>
		<!-- end:: Page -->
		<!--begin::Base Scripts -->        
		<script src="<?php echo base_url() ?>/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="<?php echo base_url() ?>/assets/demo/base/scripts.bundle.js" type="text/javascript"></script>
		<!--end::Base Scripts -->   
		<!--begin::Page Snippets --> 
		<script src="<?php echo base_url() ?>/assets/app/js/login.js" type="text/javascript"></script>
		<!--end::Page Snippets -->   	     
		<script type="text/javascript">
			var base_url = '<?php echo base_url() ?>';

			$( function(){

				$('.captcha-refresh').on('click', function(){

					$.get('<?php echo base_url().'login/refresh/'; ?>', function(data){

						$('#image_captcha').html(data);
					});

				});

			});
		</script>           
	</body>
		<!-- end::Body -->
</html>