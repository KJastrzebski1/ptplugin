<?php

function parse_csv() {
    $csv = $_FILES["csv"];

//parse CSV
    $file = fopen($csv["tmp_name"], "r");
    $headers = fgetcsv($file);
    while (!feof($file)) {
        $record = array();
        $line = fgetcsv($file);
        for ($i = 0; $i < count($headers); $i++) {
            $record[$headers[$i]] = $line[$i];
        }
        $data[] = $record;
    }
    $countriesList = array();
    $providersList = array();
    $cp = array();
    
    foreach ($data as $record) {
        $i = 0;
        if (strlen($record["Code"]) <= 3) {
            $countriesList[] = $record;
            $cp[0][$i] = $record["Code Name"]; 
            $i++;
        } else {
            $providersList[] = $record;
            $cp[$cp[0][$i]][] = $record;
        }
    }
    foreach ($countriesList as $country){
        $content = "<table><thead></thead><tbody>";
        foreach($cp[$country["Code Name"]] as $provider){
            $content .= '<tr>';
            $content .= '<td>'. $provider["Code Name"]. '</td>';
            $content .= '</tr>';
        }
        $content .= "</tbody></table>";
        $args = array(
            'post_title' => $country['Code Name'],
            'post_type' => 'country',
            'post_status' => 'publish',
            'post_content' => $content,
        );
        $id = wp_insert_post($args);
    }
    foreach ($providersList as $provider) {
        $args = array(
            'post_title' => $provider['Code Name'],
            'post_type' => 'provider',
            'post_status' => 'publish'
        );
        $id = wp_insert_post($args);
        $country = findCountry($provider["Code"], $countriesList);
        if ($country) {
            wp_set_object_terms($id, $country, 'country');
        }
    }
// create posts for each provider and country nad check if it exists
// 
//wp_redirect('http://localhost/PluginFW/wp-admin/options-general.php?page=price-table-plugin');
}

function findCountry($code, $countries) {
    foreach ($countries as $country) {
        if (strpos($code, $country["Code"]) === 0) {
            return $country["Code Name"];
        }
    }
    return null;
}
