<?php

/**
 * Developed by Ioannis Kouris, September 2014, for Lonely Planet code test
 * email: kouris.ioannis at gmail dot com
 */

// Load the files
function loadFilesAndCheckDestinationFolder($options){

    $destinationFile = $options['d'];
    $taxonomiesFile = $options['t'];
    $targetFolder = $options['f'];
    $cssFile = "static/all.css";

    if(file_exists($destinationFile))
    {
        $destinations = simplexml_load_file($destinationFile);
    }
    else
    {
        die("\nCannot open destinations file.");
    }

    if(file_exists($taxonomiesFile))
    {
        $taxonomies = simplexml_load_file($taxonomiesFile);
    }
    else
    {
        die("\nCannot open taxonomy file.");
    }

    if (!file_exists($targetFolder)) {
        die("\nCannot find target folder.");
    }

    if (!file_exists($cssFile)) {
        die("\nCannot find CSS file.");
    }

    if (!file_exists($targetFolder.'/static')) {
        mkdir($targetFolder.'/static', 0777, true);
    }

    return array($destinations, $taxonomies, $targetFolder, $cssFile);
}

?>
