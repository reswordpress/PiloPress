<?php

if ( !class_exists( 'PIP_Shortcodes' ) ) {
    class PIP_Shortcodes {
        public function __construct() {
            add_action( 'init', array( $this, 'register_shortcodes' ) );
        }

        /**
         * Register shortcodes
         */
        public function register_shortcodes() {
            add_shortcode( 'pip_button', array( $this, 'pip_button' ) );
            add_shortcode( 'pip_breadcrumb', array( $this, 'pip_breadcrumb' ) );
            add_shortcode( 'pip_title', array( $this, 'pip_title' ) );
            add_shortcode( 'pip_thumbnail', array( $this, 'pip_thumbnail' ) );
        }

        /**
         * Button shortcode
         *
         * @param $attrs
         *
         * @return string
         */
        public function pip_button( $attrs ) {
            $attrs = shortcode_atts( array(
                'text'     => false,
                'type'     => false,
                'size'     => false,
                'target'   => false,
                'block'    => false,
                'active'   => false,
                'disabled' => false,
                'xclass'   => false,
                'link'     => '',
            ), $attrs, 'pip_button' );

            $class = 'btn';
            $class .= ( $attrs['type'] ) ? ' btn-' . $attrs['type'] : ' btn-default';
            $class .= ( $attrs['size'] ) ? ' btn-' . $attrs['size'] : '';
            $class .= ( $attrs['block'] ) ? ' btn-block' : '';
            $class .= ( $attrs['disabled'] ) ? ' disabled' : '';
            $class .= ( $attrs['active'] ) ? ' active' : '';
            $class .= ( $attrs['xclass'] ) ? ' ' . $attrs['xclass'] : '';

            return do_shortcode( sprintf(
                '<a href="%s" class="%s"%s>%s</a>',
                esc_url( $attrs['link'] ),
                esc_attr( trim( $class ) ),
                ( $attrs['target'] ) ? sprintf( ' target="%s"', esc_attr( $attrs['target'] ) ) : '',
                ( $attrs['text'] ) ? esc_attr( $attrs['text'] ) : ''
            ) );
        }

        /**
         * Breadcrumb shortcode
         * @return string|null
         */
        public function pip_breadcrumb() {
            if ( !function_exists( 'yoast_breadcrumb' ) ) {
                return null;
            }

            if ( wp_doing_ajax() ) {
                return 'You > Are > Here';
            }

            return yoast_breadcrumb( '<p class="pip_breadcrumb">', '</p>', false );
        }

        /**
         * Title shortcode
         * @return string
         */
        public function pip_title() {
            if ( wp_doing_ajax() ) {
                return 'Title here';
            }

            return get_the_title();
        }

        /**
         * Thumbnail shortcode
         *
         * @param $attrs
         *
         * @return string|null
         */
        public function pip_thumbnail( $attrs ) {
            $attrs = shortcode_atts( array(
                'size' => 'full',
            ), $attrs, 'pip_thumbnail' );

            $image_size         = $attrs['size'];
            $post_thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), $image_size );

            if ( !$post_thumbnail_url ) {
                return 'Post thumbnail here';
            }

            return '<img class="post-thumbnail" src="' . $post_thumbnail_url . '"/>';
        }
    }

    new PIP_Shortcodes();
}