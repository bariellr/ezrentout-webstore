<?php
/*
Plugin Name: EzRentOut Webstore
Plugin URI: 
Description: Integrates your EzRentOut Webstore catalog and widgets to your WordPress site. Use shortcode `[ezrentoutwebstore-frontend-app]`.
Version: 0.1.0
Author: 
Author URI: 
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ezrentoutwebstore
Domain Path: /languages
*/

if (!defined('ABSPATH')) exit;

final class EzRentOutWebstore
{
    public $version = '0.1.0';

    private $container = array();

    public function __construct()
    {
        $this->define_constants();

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'init_plugin'));
    }

    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new EzRentOutWebstore();
        }

        return $instance;
    }

    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    public function define_constants()
    {
        define('EZRENTOUTWEBSTORE_VERSION', $this->version);
        define('EZRENTOUTWEBSTORE_FILE', __FILE__);
        define('EZRENTOUTWEBSTORE_PATH', dirname(EZRENTOUTWEBSTORE_FILE));
        define('EZRENTOUTWEBSTORE_INCLUDES', EZRENTOUTWEBSTORE_PATH . '/includes');
        define('EZRENTOUTWEBSTORE_URL', plugins_url('', EZRENTOUTWEBSTORE_FILE));
        define('EZRENTOUTWEBSTORE_ASSETS', EZRENTOUTWEBSTORE_URL . '/public');
    }

    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }

    public function activate()
    {
        $installed = get_option('ezrentoutwebstore_installed');

        if (!$installed) {
            update_option('ezrentoutwebstore_installed', time());
        }

        update_option('ezrentoutwebstore_version', EZRENTOUTWEBSTORE_VERSION);
    }

    public function deactivate()
    {
    }

    public function includes()
    {
        $autoload = EZRENTOUTWEBSTORE_PATH . '/vendor/autoload.php';

        if (file_exists($autoload)) {
            require_once $autoload;
        }
    }

    public function init_hooks()
    {
        add_action('init', array($this, 'init_classes'));
        add_action('init', array($this, 'localization_setup'));
    }

    public function init_classes()
    {
        $this->container['settings'] = new EzRentOutWebstore\Settings($this->define_settings());

        $this->container['assets'] = new EzRentOutWebstore\Assets();

        $this->container['api'] = new EzRentOutWebstore\Api();

        if ($this->is_request('admin')) {
            $this->container['admin'] = new EzRentOutWebstore\Admin();
        }

        if ($this->is_request('frontend')) {
            $this->container['frontend'] = new EzRentOutWebstore\Frontend();
        }
    }

    public function define_settings()
    {
        return array(
            'ezrentout_secret_key' => array(
                'name' => 'EZRentOut Secret Key',
                'value' => '',
                'type' => 'text',
                'desc' => ''
            ),
            'subdomain' => array(
                'name' => 'EZRentOut Webstore Subdomain',
                'value' => '',
                'type' => 'text',
                'desc' => ''
            ),
            'mapbox_access_token' => array(
                'name' => 'Mapbox Access Token',
                'value' => '',
                'type' => 'text',
                'desc' => ''
            ),
        );
    }

    public function localization_setup()
    {
        load_plugin_textdomain('ezrentoutwebstore', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined('DOING_AJAX');

            case 'rest':
                return defined('REST_REQUEST');

            case 'cron':
                return defined('DOING_CRON');

            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
    }
}

$ezrentoutwebstore = EzRentOutWebstore::init();
