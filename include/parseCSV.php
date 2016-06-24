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
    $countriesToProviders = array();

    foreach ($data as $record) {
        $i = 0;
        $country = findCountry($record, $countriesList);
        if ($country) {
            $providersList[] = $record;
            $countriesToProviders[$country][] = $record;
        } else {
            $countriesList[] = $record;
            $countriesToProviders[0][$i] = $record["Code Name"];
            $i++;
        }
    }
    foreach ($countriesList as $country) {
        $content = "<table><thead><tr><th>Code</th><th>Name</th><th>Rate</th></tr></thead><tbody>";
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
        wp_insert_post($args);
    }
// create posts for each provider and country nad check if it exists
// 
//wp_redirect('http://localhost/PluginFW/wp-admin/options-general.php?page=price-table-plugin');
}

function findCountry($record, $countries) {
    foreach ($countries as $country) {
        if ((strpos($record["Code"], $country["Code"]) === 0) || (strpos($record["Code Name"], $country["Code Name"]) === 0)) {
            return $country["Code Name"];
        }
    }
    return null;
}
