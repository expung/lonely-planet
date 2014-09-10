<?php

/**
 * Developed by Ioannis Kouris, September 2014, for Lonely Planet code test
 * email: kouris.ioannis at gmail dot com
 */

include 'Twig/Autoloader.php';
include 'lib/loadFiles.php';
include 'lib/helper.php';
Twig_Autoloader::register();

// Variable initialisation
$contentArray = array();
$rootNodeName = "taxonomy";
$level=0;
$root = true;
$nodeCount = 0;
$parentNodeArray = array();
$parentNode = "root";
$currentLevel = 0;
$repeats = 0;
$numberOfNavigationNodes = 0;

$usage = <<< USAGE
Usage:
    php batch.php -d [destination.xml] -t [taxonomy.xml] -f [folder to save location]

    example: php batch.php -d destinations.xml -t taxonomy.xml -f Guide

USAGE;

//Get command line arguments
$shortopts = "d:";
$shortopts .= "t:";
$shortopts .= "f:";
$options = getopt($shortopts);

if((count($options) == 0)){
    print_r("No parameters provided, running tests.");
}
elseif(!(count($options) == 3) || !array_key_exists( 'd', $options) || !array_key_exists( 't', $options)
    || !array_key_exists( 'f', $options)){
    die("Please provide all the parameters.\n\n".$usage);
}
else{
    print_r("Checking input files... ");
    $checkedFiles = loadFilesAndCheckDestinationFolder($options);
    $destinations = $checkedFiles[0];
    $taxonomies = $checkedFiles[1];
    $targetFolder = $checkedFiles[2];
    $cssFile = $checkedFiles[3];
    $folderName = $options['f'];

    //Count the continents
    $continents = count($taxonomies->$rootNodeName->children()) - 1;
    //Add the root navigation node, aka World
    $navigationTree = array();
    $navigationTree[] = getNavigationRoot($taxonomies, $rootNodeName);
    print_r("done\n");

    //Scan the taxonomies and create navigation tree
    print_r("Creating navigation tree... ");
    while($repeats < ($continents*2) ){

        $taxonomiesXML = getNode($taxonomies->$rootNodeName, $level );

        if(strcmp($parentNode, "root") == 0){
            $navigationTree[] = getLeaf($taxonomiesXML, 0, $currentLevel);
            $root = false;
            $parentNodeArray[] = $parentNode = (string) $taxonomiesXML->attributes()->atlas_node_id;
            $currentLevel++;
        }
        else{
            $nodeCount = count($taxonomiesXML->node);
            for($i=0;$i<$nodeCount;$i++){
                $node = getNode($taxonomiesXML, $i);
                $navigationTree[] = getLeaf($node, $parentNode, $currentLevel);
                scanNodes($node, $currentLevel);
            }
            $parentNode = "root";
            $level++;
            $currentLevel=0;
        }
        $repeats++;
    }
    print_r("done\n");

    $numberOfNavigationNodes = count($navigationTree);

    //Create a list of the AtlasIDs to be processed
    $arrayAtlasID = array();
    for($k=1; $k < $numberOfNavigationNodes; $k++){
        $arrayAtlasID[] = $navigationTree[$k]['atlas_node_id'];
    }

    copyStyleSheetToTargetDirectory($cssFile, $folderName);

    //Get the destinations file to something more manageable 
    $file = new DOMDocument();
    $file->load($options['d']);
    $xpath = new DOMXPath( $file );

    print_r("Generating files... ");
    // Save the HTMLs
    saveHtml($arrayAtlasID, $xpath, $folderName);
    print_r("done\n");
}

/*
 * Scans each xml node and extracts the navigation tree relations
 */
function scanNodes($node, $currentLevel = 0){
    global $parentNodeArray;
    global $navigationTree;
    $nodeCount = count($node->node);
    $parentNode = (string) $node->attributes()->atlas_node_id;
    $parentNodeArray[] = $parentNode;
    $currentLevel++;
    if($nodeCount > 0){
        for($j=0;$j<$nodeCount;$j++){
            $node_L2 = getNode($node, $j);
            $navigationTree[] = getLeaf($node_L2, $parentNode, $currentLevel);
            scanNodes($node_L2, $currentLevel);
        }
    }
    $currentLevel--;
    array_pop($parentNodeArray);
}

/*
 * Generates the navigation menu
 */
function generateMenu($currentAtlasID){
    global $navigationTree;
    $parentOfCurrentNode = null;
    $currentNodeName = '';

    $navigationList = new stdClass();
    $childNodeArray = array();

    if (is_array($navigationTree)) {
        // Get current node name
        foreach($navigationTree as $navigationNode){

            if(array_key_exists('atlas_node_id', $navigationNode)){

                if($navigationNode['atlas_node_id'] == $currentAtlasID){

                    $parentOfCurrentNode = $navigationNode['parent_id'];
                    $currentNodeName = $navigationNode['node_name'];

                    $navigationList->parentOfCurrentNodeID = $navigationNode['parent_id'];
                    $navigationList->currentNodeName = $navigationNode['node_name'];
                    $navigationList->currentNodeID = $navigationNode['atlas_node_id'];
                }
            }
        }

        foreach($navigationTree as $navigationNode){
            // Get parent node name
            if(array_key_exists('atlas_node_id', $navigationNode)){

                if($navigationNode['atlas_node_id'] == $parentOfCurrentNode){
                    $navigationList->parentOfCurrentNodeName = $navigationNode['node_name'];

                }
            }
            // Get children node names
            if(array_key_exists('parent_id', $navigationNode)){

                if ($navigationNode['parent_id'] == $currentAtlasID) {
                    $childNode = new stdClass();
                    $childNode->name = $navigationNode['node_name'];
                    $childNode->id = $navigationNode['atlas_node_id'];

                    $childNodeArray[] = $childNode;
                }
            }
        }
        $navigationList->childNodes = $childNodeArray;
    }

    //Cleanup navigation
    if(!property_exists($navigationList, 'parentOfCurrentNodeName') ){
        $navigationList->parentOfCurrentNodeName = '';
    }

    return $navigationList;
}

/**
 * Save the HTML files
 */
function saveHtml($arrayAtlasID, $xpath, $folderName)
{
    foreach ($arrayAtlasID as $atlasID) {
        $nodelist = $xpath->query("//*[@atlas_id='{$atlasID}']");
        $navigationItem = generateMenu($atlasID);
        $contentArray = generateDestinations($nodelist);

        try {
            //Load the template
            $loader = new Twig_Loader_Filesystem('templates');
            //Initialize Twig environment
            $twig = new Twig_Environment($loader);
            //Load template
            $template = $twig->loadTemplate('lonely-planet.twig');
            //Render template for each ID
            $htmlOutput = $template->render(array(
                'Content' => $contentArray->content,
                'Secondary' => $contentArray->content,
                'NavigationParentName' => $navigationItem->parentOfCurrentNodeName,
                'NavigationParentID' => $navigationItem->parentOfCurrentNodeID,
                'NavigationCurrentName' => $navigationItem->currentNodeName,
                'NavigationCurrentID' => $navigationItem->currentNodeID,
                'NavigationChildren' => $navigationItem->childNodes,
            ));

            $fileOutput = $folderName . '\\' . $navigationItem->currentNodeID . '.html';
            file_put_contents($fileOutput, $htmlOutput, LOCK_EX);

        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }

    }
}

/*
 * Generates the destinations content
 */
function generateDestinations($nodelist)
{
    global $numberOfNavigationNodes;
    $result = new stdClass();
    $secondaryNavigation = '';
    $currentParagraphTitle = '';
    $outputObject = array();

    if($nodelist->length > 0)
    {
        foreach ($nodelist as $node)
        {
            if ( $node->nodeType == XML_ELEMENT_NODE )
            {
                $attributes = $node->attributes; // get all the attributes(eg: id, class)
                foreach($attributes as $attribute)
                {
                    if($attribute->name == 'title')
                    {
                        $result->destinationName = $attribute->value;
                    }
                }

                $childNodes = $node->childNodes;
                foreach($childNodes as $childNode)
                {
                    $currentParagraph = $childNode->localName;

                    if(count($currentParagraph) > 0)
                    {
                        $currentParagraphTitle = normalizeTitle($currentParagraph, 3);
                        $secondaryNavigation = addToSecondaryNavigation($currentParagraph);

                        $destinationContentObj = new stdClass();    //TODO: replace the old one
                        $destinationContentObj->title = normalizeTitle($currentParagraph);
                        $destinationContentObj->title = normalizeTitle($currentParagraph);
                        $destinationContentObj->href = $secondaryNavigation->href;
                        $destinationContentObj->content = nl2br(htmlentities($childNode->textContent));
                        $outputObject[] = $destinationContentObj;
                    }
                }
            }
        }
    }

    $result->content = $outputObject;
    return $result;
}

?>