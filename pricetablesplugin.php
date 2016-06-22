<?php
/*
  Plugin Name: PriceTablePlugin
  Plugin URI: https://github.com/theantichris/wordpress-plugin-boilerplate
  Description: An object oriented boilerplate for developing a WordPress plugin.
  Version: 0.1
  Author: Krzysztof Jastrzebski
  Author URI: http://www.polcode.net
 */

add_action('admin_menu', 'pt_plugin_menu');
add_action( 'init', 'country_init' );
add_action('init', 'create_country_post_type');
add_action('init', 'create_provider_post_type');

register_activation_hook( __FILE__, 'pt_plugin_activation' );
register_deactivation_hook( __FILE__, 'pt_plugin_deactivation' );

function pt_plugin_activation(){
    insert_countries_page();
}

function pt_plugin_deactivation(){
    $page = get_page_by_title("Countries");
    wp_delete_post($page->ID);
}

function pt_plugin_menu() {
    add_options_page('Price Table Options', 'PT Plugin', 'manage_options', 'price-table-plugin', 'pt_plugin_options');
}

function pt_plugin_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h3>Upload data in .csv</h3>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input id="sendCSV" type="submit" value="Sent" name="submit">
    </div>
    <?php
}

// country taxonomy
function country_init() {
    register_taxonomy(
            "countries", 
            'post', 
            array(
                'label' => __('Countries'),
                'rewrite' => array('slug' => 'country')
            )
    );
}

function create_country_post_type(){
    $args = array();
    
    register_post_type("country", $args);
}

function create_provider_post_type(){
    $args = array();
    
    register_post_type("provider", $args);
}

function insert_countries_page(){
    $content = "";
    $args = array(
        'post_title' => "Countries",
        'post_content' => $content,
        'post_type' => "page",
        'post_status'   => 'publish',
    );
    
    wp_insert_post($args);
}
