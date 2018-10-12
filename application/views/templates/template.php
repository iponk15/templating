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
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $pagetitle .' - '. 'Template Metronic' ?></title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: { "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"] },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link href="<?php echo base_url()?>assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url()?>assets/demo/base/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/demo/media/img/logo/favicons.png" />
    <?php 
        // foreach ( $this->config->item('plugin') as $key => $value) {
        //     get_additional( $value, 'css' );
        // } 
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        var base_url = '<?php echo base_url() ?>';
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700","Asap+Condensed:500"]},
                active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <script src="<?php echo base_url()?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/demo/base/scripts.bundle.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/app/js/global_helper.js'); ?>" type="text/javascript"></script>
    
    <script src="<?php echo base_url()?>assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
    
    <script src="<?php echo base_url()?>assets/app/js/ajaxify.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/app/js/custom.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>assets/app/js/jquery.formatCurrency-1.4.0.min.js" type="text/javascript"></script>
    
    <?php 
        // foreach ( $this->config->item('plugin') as $key => $value) {
        //     echo get_additional( $value, 'js' );
        // } 
    ?>
    
</head>
    <body class="m-page--fluid m-page--loading-enabled m-page--loading m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        <div class="m-page-loader m-page-loader--base">
            <div class="m-blockui">
                <span>Please wait...</span>
                <span><div class="m-loader m-loader--brand"></div></span>
            </div>
        </div>
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <?php echo $_header ?>
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-page__container m-body">
                <?php echo $_sidebar; ?>
                <div class="m-grid__item m-grid__item--fluid m-wrapper body-content" id="body-content">
                    <?php echo $_breadcumb ?>
                    <div class="m-content">
                        <?php echo $_content ?>
                    </div>
                </div>
            </div>
            <?php //echo $_footer ?>
        </div>
        <div id="m_scroll_top" class="m-scroll-top">
            <i class="la la-arrow-up"></i>
        </div>
    </body>
    <script>
        $(window).on('load', function() {
            $('body').removeClass('m-page--loading');         
        });
    </script> 
</html>