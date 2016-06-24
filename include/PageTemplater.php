<?php

add_filter('template_include', 'pt_plugin_template_chooser');



function pt_plugin_template_chooser($template) {
    global $post;
    if ( $post->post_name == 'countries') {
       return rc_tc_get_template_hierarchy('countries');
    }
    if ( $post->post_type == 'country'){
        return rc_tc_get_template_hierarchy('country');
    }
    return $template;
}

function rc_tc_get_template_hierarchy($template) {

    // Get the template slug
    $template_slug = rtrim($template, '.php');
    $template = $template_slug . '.php';

    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ($theme_file = locate_template(array('views/' . $template))) {
        
        $file = $theme_file;
        
    } else {
        $file = RC_TC_BASE_DIR . '/views/' . $template;
        
    }

    return apply_filters('rc_repl_template_' . $template, $file);
}
