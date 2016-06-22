<?php

$csv = $_POST["csv"];

//parse CSV
$data = str_getcsv($csv);
// create list of countries
$countriesList = array();
// create category for each country, check if it exists
foreach ($countriesList as $country){
    wp_create_category($country);
}
// create posts for each provider and country nad check if it exists

$providersList = array();
// 
