<?php
namespace DTCMedia\wp_menu_customizer;

require_once(__DIR__ . '../../../../vendor/autoload.php');
/*
Plugin Name: WP Menu item customizer
Version: 1.0.0
Description: Plugin voor het verbergen en sorteren van menu items
Author: Peter Otten
Author URI: https://github.com/rexxnar/
*/

define('WPMENUCUSTOMIZER_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

/**
 * Class WPMenuCustomizer
 * @package DTCMedia\wp_menu_customizer
 */
class WPMenuCustomizer
{
    /**
     * WPMenuCustomizer constructor.
     */
    public function __construct()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');

        $this->includeCustomAssets();
        $this->registerPluginSettings();

        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('admin_menu', [$this, 'optionsPage']);

        add_filter('custom_menu_order', create_function('', 'return true;'));
        add_filter('menu_order', [$this, 'customMenuOrder']);
    }

    public function registerPluginSettings()
    {
        foreach ($this->getOptionNames() as $optionName) {
            register_setting(
                'WPMenuCustomizer-menu-order-settings-group',
                $optionName
            );
        }
    }

    /**
     *
     */
    public function deactivate()
    {
        $optionName = $this->getOptionNames();
        foreach ($optionName as $value) {
            delete_option($value);
        }
    }

    /**
     * @return array
     */
    private function getOptionNames()
    {
        return [
            'WPMenuCustomizer_menu_order',
            'WPMenuCustomizer_hidden_items',
        ];
    }

    /**
     * Adds the menu page
     */
    public function optionsPage()
    {
        // Create new top-level menu
        add_menu_page(
            'WP Menu customizer', //page title
            'WP Menu customizer', //menu title
            'manage_options', //capability
            plugin_dir_path(__FILE__) . 'templates/wp_menu_customizer_admin.php',
            null,
            'dashicons-editor-ul',
            100
        );
    }

    /**
     * @return array|mixed
     */
    public function getMenuItems()
    {
        global $menu;

        $savedMenuItems = get_option('WPMenuCustomizer_menu_order');
        if (!$savedMenuItems) {
            $menuItems = $menu;
        } else {
            $newMenuItems = [];

            foreach ($savedMenuItems as $savedMenuItem) {
                foreach ($menu as $currentMenuItem) {
                    if ($currentMenuItem[2] == $savedMenuItem) {
                        array_push($newMenuItems, $currentMenuItem);
                    }
                }
            }
            $menuItems = $newMenuItems;
        }

        return $menuItems;
    }

    /**
     * Embeds custom assets for the plugin
     */
    private function includeCustomAssets()
    {
        $scripts = [
            'wp_menu_customizer.js'
        ];
        foreach ($scripts as $script) {
            if (wp_script_is($script, 'enqueued')) {
                return;
            } else {
                wp_enqueue_script($script, plugin_dir_url(__FILE__) . 'assets/js/' . $script, ['jquery'], null, 'all');
            }
        }

        $stylesArray = [
            'wp_menu_customizer.css'
        ];

        foreach ($stylesArray as $styles) {
            if (wp_style_is($styles, 'enqueued')) {
                return;
            } else {
                wp_enqueue_style($styles, plugin_dir_url(__FILE__) . 'assets/css/' . $styles, false, null, 'all');
            }
        }
    }

    /**
     * @return array|bool|mixed
     */
    public function customMenuOrder()
    {
        $savedMenuItems = get_option('WPMenuCustomizer_menu_order');
        if ($savedMenuItems) {
            return $savedMenuItems;
        }
        return [];
    }


}

$WPMenuCustomizer = new WPMenuCustomizer();