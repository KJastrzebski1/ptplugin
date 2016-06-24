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
    if ($data) {
        $countries = new WP_Query(array("post_type" => "country", 'posts_per_page' => -1));
        if ($countries->have_posts()) {
            while ($countries->have_posts()) {
                $countries->the_post();
                wp_delete_post(get_the_ID(), true);
            }
            wp_reset_postdata();
        }
    }
    $countriesList = array();
    $providersList = array();
    $countriesToProviders = array();

    foreach ($data as $record) {
        $i = 0;
        if(!$record["Code Name"]){
            continue;
        }
        $country = findCountry($record, $countriesList);
        if ($country) {
            $providersList[] = $record;
            $countriesToProviders[$country][] = $record;
        } else {
            if(strlen($record["Code"]) >= 5){
                continue;
            }
            $countriesList[] = $record;
            $countriesToProviders[$record["Code Name"]][] = $record;
            $countriesToProviders[0][$i] = $record["Code Name"];
            $i++;
        }
    }
    foreach ($countriesList as $country) {
        $content = "<table><thead><tr><th>Code</th><th>Name</th><th>Rate</th></tr></thead><tbody>";
        $rates = array();
        $codes = array();
        foreach ($countriesToProviders[$country["Code Name"]] as $provider) {
            $rates[] = $provider["Rate"];
            $codes[] = $provider["Code"];
        }
        array_multisort($rates, SORT_ASC, $countriesToProviders[$country["Code Name"]]);
        $country["Rate"] = $countriesToProviders[$country["Code Name"]][0]["Rate"];
        foreach ($countriesToProviders[$country["Code Name"]] as $provider) {

            $content .= '<tr>';
            $content .= '<td>' . $provider["Code"] . '</td>';
            $content .= '<td>' . $provider["Code Name"] . '</td>';
            $content .= '<td>' . $provider["Rate"] . '</td>';
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
        add_post_meta($id, 'rate', $country["Rate"]);
        
        add_post_meta($id, 'code', min($codes));
    }
// create posts for each provider and country nad check if it exists
// 
wp_redirect(site_url().'/wp-admin/options-general.php?page=price-table-plugin');
}

function findCountry($record, $countries) {
    foreach ($countries as $country) {
        if ((strpos($record["Code"], $country["Code"]) === 0) || (strpos($record["Code Name"], $country["Code Name"]) === 0)) {
            return $country["Code Name"];
        }
    }
    return null;
}
