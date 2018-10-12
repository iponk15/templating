<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
        <ul class="m-menu__nav ">
            <?php 
                $menu       = menu();
                $url        = $this->uri->segment(1) == '' ? 'landing' : $this->uri->segment(1);
                
                sidebar_menu( $menu, $url );
             ?>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>