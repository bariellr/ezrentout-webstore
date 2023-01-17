<?php

namespace EzRentOutWebstore;

class Settings
{
    protected $option_key = 'ezrentoutwebstore_settings';

    protected $store_url = 'https://%s.ezrentalstore.com';

    protected $options = array();

    public function __construct($options = array())
    {
        foreach ($options as $key => $value) {
            $this->add_option($key, $value['name'], $value['value'], $value['type'], $value['desc']);
        }
    }

    public function add_option($key, $name = '', $value = '', $type = 'text', $desc = '')
    {
        $this->options[$key] = [
            'name' => $name,
            'value' => $value,
            'type' => $type,
            'desc' => $desc,
        ];
    }

    public function get_options($key = '')
    {
        $options = $this->options;

        if (!get_option($this->option_key)) {
            $data = array();

            foreach ($options as $key => $value) {
                $data[$key] = $value['value'];
            }

            update_option($this->option_key, $data);
        } else {
            $saved = get_option($this->option_key);

            foreach ($options as $key => $value) {
                if ($saved[$key]) {
                    $options[$key]['value'] = $saved[$key];
                }
            }
        }

        return $options;
    }

    public function get_option($key)
    {
        return $this->options[$key];
    }

    public function get_option_key()
    {
        return $this->option_key;
    }

    public function get_subdomain()
    {
        return $this->get_options()['subdomain']['value'] ?? null;
    }

    public function get_webstore_url()
    {
        $subdomain = $this->get_subdomain();

        return $subdomain ? sprintf($this->store_url, $subdomain) : null;
    }

    public function get_secret_key()
    {
        return $this->get_options()['ezrentout_secret_key']['value'] ?? null;
    }

    public function get_mapbox_token()
    {
        return $this->get_options()['mapbox_access_token']['value'] ?? null;
    }
}
