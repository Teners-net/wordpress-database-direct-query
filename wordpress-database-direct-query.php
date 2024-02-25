<?php

/**
 * @package wordpress-database-direct-query
 */
/*
Plugin Name: Wordpress Database Direct Query
Plugin URI: https://teners.com/
Description: The Wordpress Database Direct Query plugin empowers you to execute custom SQL queries directly on your WordPress database.
Version: 0.0.0
Author: Teners - WordPress Team
Author URI: https://teners.net/
License: GPLv2 or later
Text Domain: wordpress-database-direct-query
Requires at least: 5.6
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2023 Automattic, Inc.
*/

defined('ABSPATH') or die('Support for WordPress only');

class WordpressDatabaseDirectQuery
{
    public $pluginName;
    
    private $wpdb;

    public function __construct()
    {
        global $wpdb;

        $this->pluginName = plugin_basename(__FILE__);
        $this->wpdb = $wpdb;
    }

    public function activate()
    {
        // Activation
    }

    public function deactivate()
    {
        // Deactivation
    }

    public function register()
    {
        add_action('admin_menu', array($this, 'admin_menu'));

        add_filter("plugin_action_links_$this->pluginName", array($this, 'settings_link'));

        add_action('rest_api_init', array($this, 'register_api_endpoint'));
    }

    public function settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=wp_db_direct_query">Settings</a>';
        array_push($links, $settings_link);

        return $links;
    }

    public function admin_menu()
    {
        add_menu_page(
            "DB Direct Query",
            "DB Direct Query",
            "manage_options",
            "wp_db_direct_query",
            fn () => require_once plugin_dir_path(__FILE__) . 'views/wp_db_direct_query.php',
            "dashicons-database",
        );
    }

    function register_api_endpoint()
    {
        register_rest_route('wp-db-direct-query/v1', '/get-tables', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_tables_in_db'),
            'permission_callback' => array($this, 'check_permission'),
        ));

        register_rest_route('wp-db-direct-query/v1', '/get-table-columns/(?P<table_name>[\w-]+)', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_columns_in_table'),
            'permission_callback' => array($this, 'check_permission'),
        ));

        register_rest_route('wp-db-direct-query/v1', '/run', array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => array($this, 'execute_query'),
            'permission_callback' => array($this, 'check_permission'),
        ));
    }

    function get_tables_in_db()
    {
        $tables = $this->wpdb->get_results("SHOW TABLES", ARRAY_N);

        $table_names = array();
        foreach ($tables as $table) {
            $table_names[] = $table[0];
        }

        $response = array(
            'success' => empty($table_names) ? false : true,
            'message' => empty($table_names) ? 'No tables found in the database.' : 'Table names retrieved successfully.',
            'data'  => $table_names,
        );

        return rest_ensure_response($response);
    }

    function get_columns_in_table(WP_REST_Request $request)
    {
        $table_name = $request['table_name'];

        if ($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $response = array(
                'success' => false,
                'message' => 'Specified table does not exist.',
            );
        } else {
            $columns = $this->wpdb->get_col("DESCRIBE $table_name");

            $response = array(
                'success' => empty($columns) ? false : true,
                'message' => empty($columns) ? 'No columns found in the table.' : 'Columns retrieved successfully',
                'data'  => $columns,
            );
        }

        return rest_ensure_response($response);
    }

    function execute_query($data)
    {
        if (empty($data['query'])) {
            $response = array(
                'success' => false,
                'message' => 'Query parameter is missing.',
            );
        } else {
            $query = $data['query'];
            $result = $this->wpdb->get_results($query, ARRAY_A);

            $response = array(
                'success' => $result ? false : true,
                'message' => $result ? 'Query executed successfully.' : 'Error executing the query.',
                'data'    => $result,
            );
        }

        return rest_ensure_response($response);
    }

    function check_permission()
    {
        return current_user_can('manage_options');
    }
}

if (class_exists('WordpressDatabaseDirectQuery')) {
    $wordpressDBSync = new WordpressDatabaseDirectQuery();
    $wordpressDBSync->register();

    register_activation_hook(__FILE__, array($wordpressDBSync, 'activate'));
    register_deactivation_hook(__FILE__, array($wordpressDBSync, 'deactivate'));
}
