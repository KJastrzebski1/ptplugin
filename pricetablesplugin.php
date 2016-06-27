<?php
/*
  Plugin Name: PriceTablePlugin
  Plugin URI:
  Description: Plugin with prices of connection via runo.io
  Version: 0.1
  Author: Krzysztof Jastrzebski
  Author URI: http://www.polcode.net
 */

require_once 'include/PageTemplater.php';
require_once 'include/parseCSV.php';

add_action('admin_menu', 'pt_plugin_menu');
add_action('init', 'create_country_post_type');
add_action('wp_enqueue_scripts', 'pt_plugin_scripts');
add_action("admin_post_parse_csv", "parse_csv");
add_action("admin_post_nopriv_parse_csv", "parse_csv");
add_action("wp_ajax_get_countries", "get_countries_callback");
add_action("wp_ajax_nopriv_get_countries", "get_countries_callback");

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
    flush_rewrite_rules();
}

function pt_plugin_deactivation() {
    $page = get_page_by_title("Countries");
    $countries = new WP_Query(array("post_type" => "country", 'posts_per_page' => -1));
    if ($countries->have_posts()) {
        while ($countries->have_posts()) {
            $countries->the_post();
            wp_delete_post(get_the_ID(), true);
        }
        wp_reset_postdata();
    }
    delete_option('pt_table_id');
    delete_option("show_vas");
    wp_delete_post($page->ID, true);
}
function get_countries_callback(){
    $countries = new WP_Query(array("post_type" => "country", 'posts_per_page' => -1));
    $result = array();
    if ($countries->have_posts()) {
        while ($countries->have_posts()) {
            $countries->the_post();
            $title = get_the_title();
            if(strpos($title, '#038;') > -1){
                $title = str_replace('#038;', '', $title);
            }
            $result[] = $title;
        }
        wp_reset_postdata();
    }
    echo json_encode($result);
    wp_die();
}

function pt_plugin_scripts() {
    wp_enqueue_script('pt_script', plugins_url('js/main.js', __FILE__), array('jquery'));
    wp_enqueue_script('jquery-ui', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'));
    wp_enqueue_style('pt_style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('jq-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
    wp_localize_script('pt_script', 'ajax_object',
            array( 'ajax_url' => admin_url('admin-ajax.php') ) );
}

function pt_plugin_menu() {
    add_options_page('Price Table Options', 'PT Plugin', 'manage_options', 'price-table-plugin', 'pt_plugin_options');
    add_option("show_vas", 0);
}

function pt_plugin_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h3>Upload data in .csv</h3>
        <form action="<?php echo site_url(); ?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="parse_csv">
            <input type="file" name="csv" id="fileToUpload">
            <input id="sendCSV" type="submit" value="Send" name="submit">
        </form>
        <title>Show VAS</title>
    </div>
    <?php
}

function create_country_post_type() {
    $args = array(
        "public" => true,
        "show_ui" => false,
        "rewrite" => array("slug" => 'country', "rewrite" => array()
        )
    );

    register_post_type("country", $args);
}

function insert_countries_page() {
    $content = "";
    $args = array(
        'post_title' => "Countries",
        'post_content' => $content,
        'post_type' => "page",
        'post_status' => 'publish',
    );

    $id = wp_insert_post($args);
    add_option("pt_table_id", $id);
}
