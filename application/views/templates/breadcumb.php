<div class="m-subheader ">
     <div class="d-flex align-items-center">
        <?php echo (empty($inpeg) ? null : '<h3 class="m-subheader__title m-subheader__title--separator">'.$inpeg.'</h3>' ) ?>
        <div class="mr-auto">
            <?php  
                echo (!empty($ktnTipe) ? '<h3 class="m-subheader__title m-subheader__title--separator">'.$ktnTipe.'</h3>' : '');
                $text = '';
                foreach ($breadcumb as $key => $value) {
                    $text .= '<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">';
                    if($key == 'index'){
                        $text .= '<li class="m-nav__item m-nav__item--home">
                                    <a href="'.$value.'" class="m-nav__link m-nav__link--icon ajaxify">
                                        <i class="m-nav__link-icon la la-home"></i>
                                    </a>
                                </li>';
                    }else{
                        $text .= '<li class="m-nav__separator">
                                    <i class="la la-angle-right"></i>
                                </li>
                                <li class="m-nav__item">
                                    <a '.($value == NULL ? 'style="pointer-events: none;cursor: default;" href="'.$value.'"' : 'href="'.$value.'"' ).' class="m--font-bolder m-link m-link--state '.($value == NULL ? 'm-link--custom' : 'm-link--primary' ).' ajaxify">
                                        <span class="m-nav__link-text">
                                            '.$key.'
                                        </span>
                                    </a>
                                </li>';
                    }

                    $text .= '</ul>';
                }

                echo $text;
            ?>
        </div>
        <div>
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>/assets/app/js/dashboard.js" type="text/javascript"></script>