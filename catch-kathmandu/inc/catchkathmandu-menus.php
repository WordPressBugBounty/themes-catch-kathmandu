<?php
if ( ! function_exists( 'catchkathmandu_primary_menu' ) ) :
    /**
     * Shows the Primary Menu
     *
     * default load in sidebar-header-right.php
     */
    function catchkathmandu_primary_menu() { ?>
        <div id="primary-menu-wrapper" class="menu-wrapper">
            <div class="menu-toggle-wrapper">
                <button id="menu-toggle" class="menu-toggle" aria-controls="main-menu" aria-expanded="false"><span class="menu-label"><?php esc_html_e( 'Menu', 'catch-kathmandu' ); ?></span></button>
            </div><!-- .menu-toggle-wrapper -->

            <div class="menu-inside-wrapper">
                <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'catch-kathmandu' ); ?>">
                <?php if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu(
                        array(
                            'container'      => '',
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'menu nav-menu',
                        )
                    );
                } else {
                    wp_page_menu(
                        array(
                            'menu_class' => 'primary-menu-container',
                            'before'     => '<ul id="menu-primary-items" class="menu nav-menu">',
                            'after'      => '</ul>',
                        )
                    );
                }
                ?>
                </nav><!-- .main-navigation -->
        	</div>
        </div>
    <?php
    }
endif; // catchkathmandu_primary_menu


if ( ! function_exists( 'catchkathmandu_secondary_menu' ) ) :
    /**
     * Shows the Secondary Menu
     *
     * Hooked to catchkathmandu_after_hgroup_wrap
     */
    function catchkathmandu_secondary_menu() {
    	if ( has_nav_menu( 'secondary' ) ) {
            $options  = catchkathmandu_get_options();
            ?>
            <div id="secondary-menu-wrapper" class="menu-wrapper<?php echo empty ( $options['enable_menus'] ) ? ' disabled-in-mobile' : ''; ?>">
                <div class="menu-toggle-wrapper">
                    <button id="secondary-menu-toggle" class="menu-toggle" aria-controls="main-menu" aria-expanded="false"><span class="menu-label"><?php esc_html_e( 'Menu', 'catch-kathmandu' ); ?></span></button>
                </div><!-- .menu-toggle-wrapper -->

                <div class="menu-inside-wrapper">
                    <nav id="site-navigation" class="secondary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'catch-kathmandu' ); ?>">
                        <?php
                        wp_nav_menu(
                            array(
                                'container'      => '',
                                'theme_location' => 'secondary',
                                'menu_id'        => 'secondary-menu',
                                'menu_class'     => 'menu nav-menu',
                            )
                        );
                        ?>
                    </nav><!-- .econdary-navigation -->
                </div>
            </div>
    	<?php
    	}
    }
endif; // catchkathmandu_secondary_menu
add_action( 'catchkathmandu_after_hgroup_wrap', 'catchkathmandu_secondary_menu', 20 );
