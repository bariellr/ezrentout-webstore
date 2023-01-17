<?php

namespace EzRentOutWebstore;

class Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu()
    {
        global $submenu;

        $capability = 'manage_options';
        $slug = 'ezrentoutwebstore-admin-app';

        $hook = add_menu_page(
            __('EzRentOut Webstore', 'ezrentoutwebstore'),
            __('EzRentOut Webstore', 'ezrentoutwebstore'),
            $capability,
            $slug,
            [$this, 'plugin_page'],
            'dashicons-cart'
        );

        if (current_user_can($capability)) {
            $submenu[$slug][] = array(
                __('Settings', 'ezrentoutwebstore'),
                $capability,
                'admin.php?page=' . $slug . '#/'
            );
        }

        add_action('load-' . $hook, [$this, 'init_hooks']);
    }

    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('ezrentoutwebstore-admin');
        wp_enqueue_script('ezrentoutwebstore-admin');

        $object = array(
            'page_title' => 'EzRentOut Webstore Settings',
            'options' => \EzRentOutWebstore::init()->settings->get_options(),
            'endpoints' => array(
                'settings' => get_rest_url(null, 'ezrentoutwebstore-api/v1') . '/settings',
            ),
        );

        wp_localize_script('ezrentoutwebstore-admin', 'ezrentoutwebstore_object', $object);
    }

    public function plugin_page()
    {
        echo '<div class="wrap"><div id="ezrentoutwebstore-admin-app"></div></div>';
    }
}
