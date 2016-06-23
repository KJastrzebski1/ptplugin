<?php
/*
  Plugin Name: PriceTablePlugin
  Plugin URI: https://github.com/theantichris/wordpress-plugin-boilerplate
  Description: An object oriented boilerplate for developing a WordPress plugin.
  Version: 0.1
  Author: Krzysztof Jastrzebski
  Author URI: http://www.polcode.net
 */

require_once 'include/PageTemplater.php';
require_once 'include/parseCSV.php';

add_action('admin_menu', 'pt_plugin_menu');
add_action('init', 'country_init');
add_action('init', 'create_country_post_type');
add_action('init', 'create_provider_post_type');

add_action("admin_post_parse_csv", "parse_csv");
add_action("admin_post_nopriv_parse_csv", "parse_csv");
//add_action('init', 'pt_plugin_register_shortcodes');
register_activation_hook(__FILE__, 'pt_plugin_activation');
register_deactivation_hook(__FILE__, 'pt_plugin_deactivation');

if (!defined('RC_TC_BASE_FILE')) {
    define('RC_TC_BASE_FILE', __FILE__);
}

if (!defined('RC_TC_BASE_DIR')) {
    define('RC_TC_BASE_DIR', dirname(RC_TC_BASE_FILE));
}
if (!defined('RC_TC_PLUGIN_URL')) {
    define('RC_TC_PLUGIN_URL', plugin_dir_url(__FILE__));
}

function pt_plugin_activation() {
    insert_countries_page();
}

function pt_plugin_deactivation() {
    $page = get_page_by_title("Countries");
    $providers = new WP_Query(array("post_type" => "provider", 'posts_per_page' => -1));
    $countries = new WP_Query(array("post_type" => "country", 'posts_per_page' => -1));
    if($providers->have_posts()){
        while($providers->have_posts()){
            $providers->the_post();
            wp_delete_post(the_ID(), true);
            
        }
        wp_reset_postdata();
    }
   
    wp_delete_post($page->ID, true);
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
        <form action="<?php echo site_url();?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="parse_csv">
            <input type="file" name="csv" id="fileToUpload">
            <input id="sendCSV" type="submit" value="Send" name="submit">
        </form>
    </div>
    <?php
}

// country taxonomy
function country_init() {
    register_taxonomy(
            "countries", 'post', array(
        'label' => __('Countries'),
        'rewrite' => array('slug' => 'country')
            )
    );
}

function create_country_post_type() {
    $args = array();

    register_post_type("country", $args);
}

function create_provider_post_type() {
    $args = array();

    register_post_type("provider", $args);
}

function insert_countries_page() {
    $content = "";
    $args = array(
        'post_title' => "Countries",
        'post_content' => $content,
        'post_type' => "page",
        'post_status' => 'publish',
    );

    wp_insert_post($args);
}

/*
 * there are unnessecery
 * 
function pt_plugin_register_shortcodes() {
    add_shortcode('country-list', 'country_list_shortcode');
    add_shortcode('provider-list', 'provider_list_shortcode');
}

function country_list_shortcode() {
    $countries = get_posts(array('post_type' => 'country', 'orderby' => 'title'));
    $list = "";
    if ($countries) {
        $list = '<div class="country-list">';
        foreach ($countries as $country) {
            $list .= '<div>' . $country->title . '</div>';
        }
        $list .= '</div>';
    }

    return $list;
}

function provider_list_shortcode($atts) {
    $provider_atts = shortcode_atts(array(
        'country' => 'Country'
            ), $atts);
    $country = $provider_atts['country'];
    $providers = get_posts(array('post_type' => 'provider', 'category' => $country));
    if ($providers) {
        $list = '<div class="provider-list">';
        foreach ($providers as $provider) {
            $list .= '<div>' . $provider->title . '</div>';
        }
        $list .= '</div>';
    }

    $list = "";
    return $list;
}
*/