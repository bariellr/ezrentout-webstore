<?php

namespace EzRentOutWebstore;

class Frontend
{
    public function __construct()
    {
        add_shortcode('ezrentoutwebstore-frontend-app', [$this, 'render_frontend']);
    }

    public function render_frontend($atts, $content = '')
    {
        wp_enqueue_style('ezrentoutwebstore-frontend');
        wp_enqueue_script('ezrentoutwebstore-frontend');

        $object = array(
            // 'options' => \EzRentOutWebstore::init()->settings->get_options(),
            'mapbox_token' => \EzRentOutWebstore::init()->settings->get_mapbox_token(),
            'webstore_url' => \EzRentOutWebstore::init()->settings->get_webstore_url(),
            'endpoints' => array(
                'locations' => get_rest_url(null, 'ezrentoutwebstore-api/v1') . '/locations',
                'location' => get_rest_url(null, 'ezrentoutwebstore-api/v1') . '/locations/%s',
                'assets' => get_rest_url(null, 'ezrentoutwebstore-api/v1') . '/locations/%s/assets',
            ),
        );

        wp_localize_script('ezrentoutwebstore-frontend', 'ezrentoutwebstore_object', $object);

        $output = '<div id="ezr-store-url" style="display:none;" data-store-url="' . \EzRentOutWebstore::init()->settings->get_webstore_url() . '"></div>';
        $output .= '<div id="ezrentoutwebstore-frontend-app"></div>';

        return $output;
    }
}
