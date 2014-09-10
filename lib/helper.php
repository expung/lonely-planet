<?php

/**
 * Developed by Ioannis Kouris, September 2014, for Lonely Planet code test
 * email: kouris.ioannis at gmail dot com
 */

/*
 * Get the input string and replace any underscore with space
 * If the heading level is provided, returns the input string
 * decorated with the heading level and with the first letter capital
 */
function normalizeTitle($input, $headingLevel = null){
    $output = null;

    if(is_string($input)){
        if(!is_null($headingLevel) && is_integer($headingLevel) && ($headingLevel>0 && $headingLevel<=6)){
            $output .= '<h'.$headingLevel.'>'.trim(ucwords(str_replace('_', ' ', $input))).'</h'.$headingLevel.'>';
        }
        else{
            $output .= trim(ucwords(str_replace('_', ' ', $input)));
        }
    }

    return $output;
}

/*
 * Get the input string and replace any underscore with space
 * Create an object with the title and the link to the page
 */
function addToSecondaryNavigation($input){

    $navigationList = new stdClass();
    $changeArray = array('_', ' ');

    if(is_string($input) && ($input !== '')){
        $navigationList->title = normalizeTitle($input, 3);
        $navigationList->href = str_replace($changeArray, '', lcfirst($input));
    }
    else{
        throw new Exception('Not a valid input');
    }

    return $navigationList;
}

/*
 * Gets the node attribute of the object
 */
function getNode($input, $item){

    if(!( $input instanceof SimpleXMLElement)){
        throw new Exception('No valid xml.');
    }

    if(!is_integer($item)){
        throw new Exception('No valid node selected.');
    }

    if(property_exists($input, 'node')){
        try{
            $result = $input->node[$item];
        }
        catch(Exception $e){
            throw new Exception('The node does not exist', 0, $e);
        }
    }
    else{
        throw new Exception('The node does not exist');
    }

    if(is_null($result)){
        throw new Exception('The node key does not exist');
    }

    return $result;
}

/*
 * Gets the root of the navigation tree
 */
function getNavigationRoot($input, $rootNodeName){

    if(!( $input instanceof SimpleXMLElement)){
        throw new Exception('No valid xml.');
    }

    if(!is_string($rootNodeName) || empty($rootNodeName)){
        throw new Exception('No valid root node name.');
    }

    if(!isset($input->$rootNodeName)){
        throw new Exception('Cannot find the provided root node.');
    }

    return array("taxonomy_name" => (string) $input->$rootNodeName->taxonomy_name,
                 "parent_id" => "root" );
}

/*
 * Gets each leaf of the navigation tree
 */
function getLeaf($nodeData, $parentNode, $level ){

    if(!( $nodeData instanceof SimpleXMLElement)){
        throw new Exception('No valid xml.');
    }

    if(!is_integer(intval($parentNode))){
        throw new Exception('Invalid parent node.');
    }

    if(!is_integer($level) || $level < 0){
        throw new Exception('Invalid input level.');
    }

    if(!isset($nodeData[0]) || !isset($nodeData[0]->node_name) ){
        throw new Exception('Cannot find the node.');
    }

    if(!isset($nodeData[0]->attributes()->atlas_node_id) ||
       !isset($nodeData[0]->attributes()->ethyl_content_object_id) ||
       !isset($nodeData[0]->attributes()->geo_id) ){
        throw new Exception('Cannot find node attributes.');
    }

    $navigationTree = array( "node_name" => (string) $nodeData[0]->node_name,
        "atlas_node_id" => (string) $nodeData[0]->attributes()->atlas_node_id,
        "ethyl_content_object_id" => (string) $nodeData[0]->attributes()->ethyl_content_object_id,
        "geo_id" => (string) $nodeData[0]->attributes()->geo_id,
        "parent_id" => (string) $parentNode,
        "node_level" => $level);
    return $navigationTree;
}

/*
 *Copy the CSS file to the output folder
 */
function copyStyleSheetToTargetDirectory($cssFile, $folderName)
{

    try {
        copy($cssFile, $folderName . '/' . $cssFile);
    } catch (Exception $exception) {
        die("Cannot copy CSS file. " . $exception);
    }
}

?>