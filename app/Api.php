<?php

namespace EzRentOutWebstore;

use EzRentOutWebstore\Api\EzRentOutApi;
use EzRentOutWebstore\Api\SettingsApi;

class Api
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        (new SettingsApi())->register_routes();

        (new EzRentOutApi())->register_routes();
    }
}
