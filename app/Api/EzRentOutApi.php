<?php

namespace EzRentOutWebstore\Api;

use GuzzleHttp\Client;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class EzRentOutApi extends WP_REST_Controller
{
    protected $namespace = 'ezrentoutwebstore-api/v1';

    protected $ezrentout_url = 'https://%s.ezrentout.com';

    protected $base_url;

    protected $client;

    public function __construct()
    {
        $this->base_url = sprintf(
            $this->ezrentout_url,
            \EzRentOutWebstore::init()->settings->get_subdomain()
        );

        $this->client = new Client(array(
            'headers' => array(
                'token' => \EzRentOutWebstore::init()->settings->get_secret_key(),
            )
        ));
    }

    public function register_routes()
    {
        // ezrentoutwebstore-api/v1/locations
        register_rest_route(
            $this->namespace,
            'locations',
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_locations'),
            )
        );

        // ezrentoutwebstore-api/v1/locations/:id/assets
        register_rest_route(
            $this->namespace,
            'locations/(?P<id>[\d]+)/assets',
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_location_assets'),
            )
        );
    }

    public function get_locations(WP_REST_Request $wp_request)
    {
        try {
            $request = $this->client->request(
                'GET',
                $this->base_url . '/locations/get_line_item_locations.api',
                array(
                    'query' => $wp_request->get_query_params()
                )
            );

            return new WP_REST_Response(json_decode($request->getBody()), 200);
        } catch (\Exception $e) {
            return new WP_Error($e->getCode(), $e->getMessage());
        }
    }

    public function get_location_assets(WP_REST_Request $wp_request)
    {
        try {
            $query = array_merge($wp_request->get_query_params(), array(
                'status' => 'location',
                'filter_param_val' => intval($wp_request->get_param('id'))
            ));

            $request = $this->client->request(
                'GET',
                $this->base_url . '/assets/filter.api',
                array(
                    'query' => $query
                )
            );

            return new WP_REST_Response(json_decode($request->getBody()), 200);
        } catch (\Exception $e) {
            return new WP_Error($e->getCode(), $e->getMessage());
        }
    }
}
