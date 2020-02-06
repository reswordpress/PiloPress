<?php

if ( !class_exists( 'PIP_Admin' ) ) {
    class PIP_Admin {
        public function __construct() {
            // WP hooks
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
            add_filter( 'parent_file', array( $this, 'menu_parent_file' ) );
            add_filter( 'submenu_file', array( $this, 'menu_submenu_file' ) );
            add_action( 'pre_get_posts', array( $this, 'admin_pre_get_posts' ) );
            add_filter( 'posts_where', array( $this, 'query_pip_post_content' ), 10, 2 );
            add_action( 'adminmenu', array( $this, 'admin_menu_parent' ) );
        }

        /**
         * Enqueue admin style
         */
        public function enqueue_scripts() {
            wp_enqueue_style( 'admin-style', _PIP_URL . 'assets/pilopress-admin.css', array(), null );
        }

        /**
         * Filter ACF archive page in admin
         *
         * @param WP_Query $query
         */
        public function admin_pre_get_posts( $query ) {
            // In admin, on ACF field groups archive
            if ( !is_admin() || !acf_is_screen( 'edit-acf-field-group' ) ) {
                return;
            }

            if ( acf_maybe_get_GET( 'layouts' ) == 1 ) {
                // Layouts view
                $query->set( 'pip_post_content', array(
                    'compare' => 'LIKE',
                    'value'   => 's:14:"_pip_is_layout";i:1',
                ) );
            } elseif ( acf_maybe_get_GET( 'layouts' ) === null ) {
                // Classic view

                // Remove layouts
                $query->set( 'pip_post_content', array(
                    'compare' => 'NOT LIKE',
                    'value'   => 's:14:"_pip_is_layout";i:1',
                ) );

                // Remove flexible
                $flexible_mirror = PIP_Field_Groups_Flexible_Mirror::get_flexible_mirror_group();
                $query->set( 'post__not_in', array( $flexible_mirror['ID'] ) );
            }
        }

        /**
         * Add custom param for WP_Query
         *
         * @param string $where
         * @param WP_Query $wp_query
         *
         * @return mixed
         */
        public function query_pip_post_content( $where, $wp_query ) {
            global $wpdb;
            if ( !$pip_post_content = $wp_query->get( 'pip_post_content' ) ) {
                return $where;
            }

            if ( is_array( $pip_post_content ) ) {
                $where .= ' AND ' . $wpdb->posts . '.post_content ' . $pip_post_content['compare'] . ' \'%' . esc_sql( $wpdb->esc_like( $pip_post_content['value'] ) ) . '%\'';
            }

            return $where;
        }

        /**
         * Add Pilo'Press menu pages
         */
        public function add_admin_menu() {
            // Pilot'in logo
            $pip_logo_base64_svg = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iI2EwYTVhYSI+PHBhdGggZD0iTTEwIC4yQzQuNi4yLjMgNC42LjMgMTBzNC40IDkuOCA5LjcgOS44YzIuNiAwIDUuMS0xIDYuOS0yLjggMS44LTEuOCAyLjgtNC4zIDIuOC02LjkgMC01LjUtNC4zLTkuOS05LjctOS45em02LjQgMTYuM2MtMS43IDEuNy00IDIuNi02LjQgMi42LTUgMC05LTQuMS05LTkuMVM1IC45IDEwIC45IDE5IDUgMTkgMTBjMCAyLjUtLjkgNC43LTIuNiA2LjV6Ii8+PHBhdGggZD0iTTEwIDUuM2MtMi41IDAtNC42IDIuMS00LjYgNC43di41Yy4yIDEuOCAxLjQgMy4zIDMgMy45LjUuMiAxIC4zIDEuNS4zLjQgMCAuOS0uMSAxLjMtLjIuMSAwIC4xIDAgLjItLjEuMy0uMS41LS4yLjgtLjMgMCAwIC4xIDAgLjEtLjEgMCAwIC4xIDAgLjEtLjFoLjFzLjEgMCAuMS0uMWMwIDAgLjEgMCAuMS0uMS4yLS4yLjUtLjQuNy0uNmwuMy0uM2MuNi0uOCAxLTEuOSAxLTIuOSAwLTIuNS0yLjEtNC42LTQuNy00LjZ6bTMuMSA3LjNjMC0uMSAwLS4xIDAgMC0uNi0uNC0uNy0uOS0uNy0xLjR2LS40LS4xLS4zYzAtLjctLjItMS41LTEuNS0xLjYtLjUgMC0xLjMuMS0yLjMuNC0uMi0uMS0uNCAwLS42LjEtLjYuMi0xLjIuNC0yIC43IDAtMi4yIDEuOC00IDMuOS00IDEuNSAwIDIuOC44IDMuNSAyLjEuNC42LjYgMS4yLjYgMS45IDAgLjktLjMgMS44LS45IDIuNnoiLz48L3N2Zz4=';

            // Get flexible mirror
            $flexible_mirror = PIP_Field_Groups_Flexible_Mirror::get_flexible_mirror_group();

            // Main menu page
            add_menu_page(
                __( "Pilo'Press", 'pilopress' ),
                __( "Pilo'Press", 'pilopress' ),
                'manage_options',
                'pilopress.php',
                false,
                'data:image/svg+xml;base64,' . $pip_logo_base64_svg,
                61 // After 'Appearance' menu
            );

            // Flexible sub menu
            add_submenu_page(
                'pilopress.php',
                __( 'Flexible', 'pilopress' ),
                __( 'Flexible', 'pilopress' ),
                'manage_options',
                'post.php?post=' . $flexible_mirror['ID'] . '&action=edit'
            );

            // Layouts sub menu
            add_submenu_page(
                'pilopress.php',
                __( 'Layouts', 'pilopress' ),
                __( 'Layouts', 'pilopress' ),
                'manage_options',
                'edit.php?layouts=1&post_type=acf-field-group'
            );

            global $menu, $submenu;

            // Change menu_slug for main menu page to have the same fo first child (Flexible menu)
            foreach ( $menu as $key => $item ) {
                if ( $item[2] === 'pilopress.php' ) {
                    $menu[ $key ][2] = 'post.php?post=' . $flexible_mirror['ID'] . '&action=edit';
                }
            }

            // Remove first item (main menu page)
            unset( $submenu['pilopress.php'][0] );

            // Re-assign sub-items
            $submenu[ 'post.php?post=' . $flexible_mirror['ID'] . '&action=edit' ] = $submenu['pilopress.php'];

            // Remove useless menu
            unset( $submenu['pilopress.php'] );
        }

        /**
         * Change highlighted parent menu
         *
         * @param $parent_file
         *
         * @return string
         */
        public function menu_parent_file( $parent_file ) {
            // Get flexible mirror
            $flexible_mirror = PIP_Field_Groups_Flexible_Mirror::get_flexible_mirror_group();

            // Define parent menu for Flexible menu
            if ( acf_maybe_get_GET( 'post' ) == $flexible_mirror['ID'] ) {
                $parent_file = 'post.php?post=' . $flexible_mirror['ID'] . '&action=edit';
            }

            return $parent_file;
        }

        /**
         * Change highlighted subpage menu
         *
         * @param $submenu_file
         *
         * @return string
         */
        public function menu_submenu_file( $submenu_file ) {
            // Get flexible mirror
            $flexible_mirror = PIP_Field_Groups_Flexible_Mirror::get_flexible_mirror_group();

            // Define submenu for Flexible menu
            if ( acf_maybe_get_GET( 'post' ) == $flexible_mirror['ID'] ) {
                $submenu_file = 'post.php?post=' . $flexible_mirror['ID'] . '&action=edit';
            }

            // Define submenu for Layouts menu
            $is_layout = PIP_Field_Groups_Layouts::is_layout( acf_maybe_get_GET( 'post' ) );
            if ( acf_maybe_get_GET( 'layouts' ) == 1 || $is_layout ) {
                $submenu_file = 'edit.php?layouts=1&post_type=acf-field-group';
            }

            return $submenu_file;
        }

        /**
         * Define parent menu for Layout menu
         */
        public function admin_menu_parent() {
            global $current_screen;

            // Define parent menu for Layouts menu
            $is_layout = PIP_Field_Groups_Layouts::is_layout( acf_maybe_get_GET( 'post' ) );
            if ( ( $current_screen->id === 'edit-acf-field-group' && acf_maybe_get_GET( 'layouts' ) == 1 ) || $is_layout ) :
                ?>
                <script type="application/javascript">
                  (function ($) {
                    $('#toplevel_page_edit-post_type-acf-field-group').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu')
                    $('#toplevel_page_edit-post_type-acf-field-group > .wp-has-current-submenu').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu')

                    $('#toplevel_page_pilopress').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu')
                    $('#toplevel_page_pilopress > .wp-not-current-submenu').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu')
                  })(jQuery)
                </script>
            <?php

            endif;
        }

    }

    // Instantiate class
    new PIP_Admin();
}
