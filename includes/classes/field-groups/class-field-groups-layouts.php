<?php

if ( !class_exists( 'PIP_Field_Groups_Layouts' ) ) {
    class PIP_Field_Groups_Layouts {
        public function __construct() {
            // WP hooks
            add_action( 'current_screen', array( $this, 'current_screen' ) );
            add_action( 'wp_insert_post', array( $this, 'save_field_group' ), 10, 3 );
        }

        /**
         * Fire actions on acf field group page
         */
        public function current_screen() {
            // If not ACF field group single, return
            if ( !acf_is_screen( 'acf-field-group' ) ) {
                return;
            }

            add_action( 'acf/field_group/admin_head', array( $this, 'layout_meta_boxes' ) );
        }

        /**
         * Pilo'Press meta boxes
         */
        public function layout_meta_boxes() {
            // Get current field group
            global $field_group;

            // If mirror flexible page, don't register meta boxes
            if ( $field_group['key'] === PIP_Field_Groups_Flexible_Mirror::get_flexible_mirror_group_key() ) {
                return;
            }

            // Is current field group a layout ?
            $is_layout = self::is_layout( $field_group );

            // Meta box: Layout settings
            if ( $is_layout ) {
                add_meta_box( 'pip_layout_settings', __( "Pilo'Press: Flexible Layout settings", 'pilopress' ), array(
                    $this,
                    'render_meta_box_main',
                ), 'acf-field-group', 'normal', 'high', array( 'field_group' => $field_group ) );
            }
        }

        /**
         * Manage layout directory and files on save
         *
         * @param int $post_id
         * @param WP_Post $post
         * @param bool $update
         */
        public function save_field_group( $post_id, $post, $update ) {
            // If is a revision, not a field group or not a layout, return
            if ( wp_is_post_revision( $post_id )
                 || $post->post_status == 'draft'
                 || $post->post_status == 'auto-draft'
                 || $post->post_type !== 'acf-field-group'
                 || !PIP_Field_Groups_Layouts::is_layout( $post_id ) ) {
                return;
            }

            // Get old and new title
            $field_group = acf_get_field_group( $post_id );
            $old_title   = sanitize_title( $field_group['title'] );
            $new_title   = sanitize_title( $post->post_title );

            // Do layout directory already exists ?
            $directory_exists = file_exists( _PIP_THEME_LAYOUTS_PATH . $old_title );

            if ( $old_title === $new_title && !$directory_exists ) {
                // If old and new title are the same, create new layout directory
                $this->create_layout_dir( $old_title, $field_group );
            } elseif ( $old_title !== $new_title && $directory_exists ) {
                // If old and new title aren't the same, change layout directory name
                $this->modify_layout_dir( $old_title, $new_title );
            }
        }

        /**
         *  Meta box: Main
         *
         * @param $post
         * @param $meta_box
         */
        public function render_meta_box_main( $post, $meta_box ) {
            $field_group = $meta_box['args']['field_group'];

            // Layout settings
            acf_render_field_wrap( array(
                'label'        => '',
                'name'         => '_pip_is_layout',
                'prefix'       => 'acf_field_group',
                'type'         => 'acfe_hidden',
                'instructions' => '',
                'value'        => 1,
                'required'     => false,
            ) );

            // Layout
            $layout_name        = sanitize_title( $field_group['title'] );
            $layout_path_prefix = str_replace( home_url() . '/wp-content/themes/', '', _PIP_THEME_LAYOUTS_URL ) . $layout_name . '/';

            // Category
            acf_render_field_wrap( array(
                'label'         => __( 'Catégorie', 'pilopress' ),
                'instructions'  => __( 'Nom de catégorie du layout', 'pilopress' ),
                'type'          => 'text',
                'name'          => '_pip_category',
                'prefix'        => 'acf_field_group',
                'default_value' => 'classic',
                'value'         => isset( $field_group['_pip_category'] ) ? $field_group['_pip_category'] : 'Classic',
            ) );

            // Layout
            acf_render_field_wrap( array(
                'label'         => __( 'Layout', 'pilopress' ),
                'instructions'  => __( 'Nom du fichier de layout', 'pilopress' ),
                'type'          => 'text',
                'name'          => '_pip_render_layout',
                'prefix'        => 'acf_field_group',
                'placeholder'   => $layout_name . '.php',
                'default_value' => $layout_name . '.php',
                'prepend'       => $layout_path_prefix,
                'value'         => isset( $field_group['_pip_render_layout'] ) ? $field_group['_pip_render_layout'] : '',
            ) );

            // Style - CSS
            acf_render_field_wrap( array(
                'label'         => __( 'Style', 'pilopress' ),
                'instructions'  => __( 'Nom du fichier de style CSS', 'pilopress' ),
                'type'          => 'text',
                'name'          => '_pip_render_style',
                'prefix'        => 'acf_field_group',
                'placeholder'   => $layout_name . '.css',
                'default_value' => $layout_name . '.css',
                'prepend'       => $layout_path_prefix,
                'value'         => isset( $field_group['_pip_render_style'] ) ? $field_group['_pip_render_style'] : '',
            ) );

            // Style - SCSS
            acf_render_field_wrap( array(
                'label'         => __( 'Style', 'pilopress' ),
                'instructions'  => __( 'Nom du fichier de style SCSS', 'pilopress' ),
                'type'          => 'text',
                'name'          => '_pip_render_style_scss',
                'prefix'        => 'acf_field_group',
                'placeholder'   => $layout_name . '.scss',
                'default_value' => $layout_name . '.scss',
                'prepend'       => $layout_path_prefix,
                'value'         => isset( $field_group['_pip_render_style_scss'] ) ? $field_group['_pip_render_style_scss'] : '',
            ) );

            // Script
            acf_render_field_wrap( array(
                'label'         => __( 'Script', 'pilopress' ),
                'instructions'  => __( 'Nom du fichier de script', 'pilopress' ),
                'type'          => 'text',
                'name'          => '_pip_render_script',
                'prefix'        => 'acf_field_group',
                'placeholder'   => $layout_name . '.js',
                'default_value' => $layout_name . '.js',
                'prepend'       => $layout_path_prefix,
                'value'         => isset( $field_group['_pip_render_script'] ) ? $field_group['_pip_render_script'] : '',
            ) );

            // Get layouts for configuration field
            $choices = array();
            foreach ( PIP_Field_Groups_Flexible_Mirror::get_layout_group_keys() as $layout_group_key ) {
                // Get current field group
                $group = acf_get_field_group( $layout_group_key );

                // Save title
                $choices[ $group['key'] ] = $group['title'];
            }

            // Configuration
            acf_render_field_wrap( array(
                'label'         => __( 'Configuration', 'pilopress' ),
                'instructions'  => __( 'Clones de configuration', 'pilopress' ),
                'type'          => 'select',
                'name'          => '_pip_configuration',
                'prefix'        => 'acf_field_group',
                'value'         => ( isset( $field_group['_pip_configuration'] ) ? $field_group['_pip_configuration'] : '' ),
                'choices'       => $choices,
                'allow_null'    => 1,
                'multiple'      => 1,
                'ui'            => 1,
                'ajax'          => 0,
                'return_format' => 0,
            ) );

            // Miniature
            acf_render_field_wrap( array(
                'label'         => __( 'Thumbnail', 'pilopress' ),
                'instructions'  => __( 'Aperçu du layout', 'pilopress' ),
                'name'          => '_pip_thumbnail',
                'type'          => 'image',
                'class'         => '',
                'prefix'        => 'acf_field_group',
                'value'         => ( isset( $field_group['_pip_thumbnail'] ) ? $field_group['_pip_thumbnail'] : '' ),
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'library'       => 'all',
            ) );

            // Script for admin style
            ?>
            <script type="text/javascript">
              if (typeof acf !== 'undefined') {
                acf.postbox.render({
                  'id': 'pip_layout_settings',
                  'label': 'left'
                })
              }
            </script>
            <?php
        }

        /**
         * Check if post/field group is a layout
         *
         * @param array|int $post
         *
         * @return bool|mixed|null
         */
        public static function is_layout( $post ) {
            $is_layout   = false;
            $field_group = null;

            // If no post_id, return false
            if ( !$post ) {
                return $is_layout;
            }

            if ( is_array( $post ) ) {
                // If is array, it's a field group
                $field_group = $post;
            } else {
                // If is ID, get field group
                $field_group = acf_get_field_group( $post );
            }

            // If no field group, return false
            if ( !$field_group ) {
                return $is_layout;
            }

            $is_layout = acf_maybe_get( $field_group, '_pip_is_layout' );
            if ( acf_maybe_get_GET( 'layout' ) ) {
                $is_layout = true;
            }

            return $is_layout;
        }

        /**
         * Create layout directory with corresponding files
         *
         * @param $layout_title
         * @param $field_group
         */
        private function create_layout_dir( $layout_title, $field_group ) {
            // Create directory
            wp_mkdir_p( _PIP_THEME_LAYOUTS_PATH . $layout_title );

            // Options to check/modify
            $render = array(
                array(
                    'render'    => '_pip_render_layout',
                    'extension' => '.php',
                    'default'   => $layout_title,
                ),
                array(
                    'render'    => '_pip_render_style',
                    'extension' => '.scss',
                    'default'   => $layout_title,
                ),
                array(
                    'render'    => '_pip_render_script',
                    'extension' => '.js',
                    'default'   => $layout_title,
                ),
            );

            // Create files
            foreach ( $render as $item ) {
                if ( !acf_maybe_get( $field_group, $item['render'] ) ) {
                    // Get default file name
                    $field_group[ $item['render'] ] = $item['default'] . $item['extension'];
                }
                touch( _PIP_THEME_LAYOUTS_PATH . $layout_title . '/' . $field_group[ $item['render'] ] );
            }

            // Update field group
            acf_update_field_group( $field_group );
        }

        /**
         * Modify layout directory title
         *
         * @param $old_title
         * @param $new_title
         */
        private function modify_layout_dir( $old_title, $new_title ) {
            rename( _PIP_THEME_LAYOUTS_PATH . $old_title, _PIP_THEME_LAYOUTS_PATH . $new_title );
        }
    }

    // Instantiate class
    new PIP_Field_Groups_Layouts();
}