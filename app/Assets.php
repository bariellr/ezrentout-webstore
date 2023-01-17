<?php

namespace EzRentOutWebstore;

class Assets
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    private function register_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
            $version = isset($script['version']) ? $script['version'] : EZRENTOUTWEBSTORE_VERSION;

            wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
        }
    }

    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;

            wp_register_style($handle, $style['src'], $deps, EZRENTOUTWEBSTORE_VERSION);
        }
    }

    public function get_scripts()
    {
        $scripts = array(
            // cart
            'ezrentoutwebstore-webstore_cart_widget' => array(
                'src' => \EzRentOutWebstore::init()->settings->get_webstore_url() . '/javascripts/webstore_cart_widget.js',
                'deps' => array('jquery'),
                'in_footer' => true
            ),

            // add to cart
            'ezrentoutwebstore-webstore_add_to_cart_button' => array(
                'src' => \EzRentOutWebstore::init()->settings->get_webstore_url() . '/javascripts/webstore_add_to_cart_button.js',
                'deps' => array('jquery'),
                'in_footer' => true
            ),

            // availability calendar
            'ezrentoutwebstore-webstore_calendar_widget' => array(
                'src' => \EzRentOutWebstore::init()->settings->get_webstore_url() . '/javascripts/webstore_calendar_widget.js',
                'deps' => array('jquery'),
                'in_footer' => true
            ),

            // item detail
            'ezrentoutwebstore-webstore_show_details_widget' => array(
                'src' => \EzRentOutWebstore::init()->settings->get_webstore_url() . '/javascripts/webstore_show_details_widget.js',
                'deps' => array('jquery'),
                'in_footer' => true
            ),

            'ezrentoutwebstore-frontend' => array(
                'src' => EZRENTOUTWEBSTORE_ASSETS . '/frontend/main.js',
                'deps' => array('jquery', 'ezrentoutwebstore-webstore_cart_widget', 'ezrentoutwebstore-webstore_add_to_cart_button'),
                'version' => filemtime(EZRENTOUTWEBSTORE_PATH . '/public/frontend/main.js'),
                'in_footer' => true
            ),
            'ezrentoutwebstore-admin' => array(
                'src' => EZRENTOUTWEBSTORE_ASSETS . '/admin/main.js',
                'deps' => array('jquery', 'ezrentoutwebstore-webstore_cart_widget', 'ezrentoutwebstore-webstore_add_to_cart_button'),
                'version' => filemtime(EZRENTOUTWEBSTORE_PATH . '/public/admin/main.js'),
                'in_footer' => true
            ),
        );

        return $scripts;
    }

    public function get_styles()
    {
        $styles = array(
            'ezrentoutwebstore-frontend' => array(
                'src' => EZRENTOUTWEBSTORE_ASSETS . '/frontend/main.css'
            ),
            'ezrentoutwebstore-admin' => array(
                'src' => EZRENTOUTWEBSTORE_ASSETS . '/admin/main.css'
            ),
        );

        return $styles;
    }
}
