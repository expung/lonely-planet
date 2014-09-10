<?php

/**
 * Developed by Ioannis Kouris, September 2014, for Lonely Planet code test
 * email: kouris.ioannis at gmail dot com
 */

require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once('lib/helper.php');

class TestBatch extends UnitTestCase {

    function testNormalizeTitleUnderscore() {
        $input = "abc_abc";
        $result = normalizeTitle($input);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleUnderscore2() {
        $input = "_abc_abc_";
        $result = normalizeTitle($input);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSentenceSpace() {
        $input = "abc Abc";
        $result = normalizeTitle($input);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSentenceSpaceCapital() {
        $input = "abc Abc";
        $result = normalizeTitle($input);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleLowerCase() {
        $input = "abc abc";
        $result = normalizeTitle($input);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSingleWord() {
        $input = "abcabc";
        $result = normalizeTitle($input);
        $expected = "Abcabc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSingleWordCapital() {
        $input = "Abcabc";
        $result = normalizeTitle($input);
        $expected = "Abcabc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleNull() {
        $input = null;
        $result = normalizeTitle($input);
        $expected = null;
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleEmpty() {
        $input = '';
        $result = normalizeTitle($input);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSpace() {
        $input = ' ';
        $result = normalizeTitle($input);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSpaceDouble() {
        $input = '';
        $result = normalizeTitle($input);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleMultipleUnderscore() {
        $input = '__';
        $result = normalizeTitle($input);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleSingleUnderscore() {
        $input = '_';
        $result = normalizeTitle($input);
        $expected = '';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleAllCapital() {
        $input = 'AAAAAAA';
        $result = normalizeTitle($input);
        $expected = 'AAAAAAA';
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleNumber() {
        $input = "1Abcabc";
        $result = normalizeTitle($input);
        $expected = "1Abcabc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleNumber2() {
        $input = "1abcabc";
        $result = normalizeTitle($input);
        $expected = "1abcabc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleNumber3() {
        $input = "abcabc 1abc";
        $result = normalizeTitle($input);
        $expected = "Abcabc 1abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleNumberSymbol() {
        $input = "-abcabc 1abc";
        $result = normalizeTitle($input);
        $expected = "-abcabc 1abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleUnderscoreHeading0() {
        $input = "abc_abc";
        $heading = 0;
        $result = normalizeTitle($input, $heading);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleUnderscoreHeading1() {
        $input = "abc_abc";
        $heading = 1;
        $result = normalizeTitle($input, $heading);
        $expected = "<h1>Abc Abc</h1>";
        $this->assertEqual($result, $expected);
    }

    function testNormalizeTitleUnderscoreHeading7() {
        $input = "abc_abc";
        $heading = 7;
        $result = normalizeTitle($input, $heading);
        $expected = "Abc Abc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigation(){
        $input = "abc_abc";
        $result = addToSecondaryNavigation($input);
        $expected = new stdClass();
        $expected->title = "<h3>Abc Abc</h3>";
        $expected->href = "abcabc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigationSpace(){
        $input = "abc abc";
        $result = addToSecondaryNavigation($input);
        $expected = new stdClass();
        $expected->title = "<h3>Abc Abc</h3>";
        $expected->href = "abcabc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigationSpace2(){
        $input = "abc   abc";
        $result = addToSecondaryNavigation($input);
        $expected = new stdClass();
        $expected->title = "<h3>Abc   Abc</h3>";
        $expected->href = "abcabc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigationSpaceUnderscore(){
        $input = "_abc abc";
        $result = addToSecondaryNavigation($input);
        $expected = new stdClass();
        $expected->title = "<h3>Abc Abc</h3>";
        $expected->href = "abcabc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigationSpaceUnderscore2(){
        $input = " abc__abc _";
        $result = addToSecondaryNavigation($input);
        $expected = new stdClass();
        $expected->title = "<h3>Abc  Abc</h3>";
        $expected->href = "abcabc";
        $this->assertEqual($result, $expected);
    }

    function testAddToSecondaryNavigationNull(){
        $input = null;
        $this->expectException();
        addToSecondaryNavigation($input);
    }

    function testAddToSecondaryNavigationEmpty(){
        $input = '';
        $this->expectException();
        addToSecondaryNavigation($input);
    }

    function testGetNodeEmpty(){
        $input = new stdClass();
        $this->expectException();
        getNode($input, 0);
    }

    function testGetNodeEmptyString(){
        $input = '';
        $this->expectException();
        getNode($input, 0);
    }

    function testGetNodeSpace(){
        $input = ' ';
        $this->expectException();
        getNode($input, 0);
    }

    function testGetNode(){
        $input = new stdClass();
        $input->name = "abc";
        $this->expectException();
        getNode($input, 0);
    }

    function testGetNodeInvalidInput(){
        $input = new stdClass();
        $input->node = "abc";
        $this->expectException();
        getNode($input, 0);
    }

    function testGetNodeInvalidLevel(){
        $input = new stdClass();
        $input->node = "abc";
        $this->expectException();
        getNode($input, '-1');
    }

    function testGetNodeLevel(){

        $inputXml = <<< XML
    <node atlas_node_id = "355629" ethyl_content_object_id="3263" geo_id = "355629">
        <node_name>Sudan</node_name>
        <node atlas_node_id = "355630" ethyl_content_object_id="" geo_id = "355630">
            <node_name>Eastern Sudan</node_name>
        </node>
        <node atlas_node_id = "355632" ethyl_content_object_id="" geo_id = "355632">
            <node_name>Khartoum</node_name>
        </node>
    </node>
XML;

        $input = simplexml_load_string($inputXml);
        $result = getNode($input, 0);
        $this->assertTrue($result);
    }

    function testGetNodeLevel1(){

        $inputXml = <<< XML
    <node atlas_node_id = "355629" ethyl_content_object_id="3263" geo_id = "355629">
        <node_name>Sudan</node_name>
        <node atlas_node_id = "355630" ethyl_content_object_id="" geo_id = "355630">
            <node_name>Eastern Sudan</node_name>
        </node>
        <node atlas_node_id = "355632" ethyl_content_object_id="" geo_id = "355632">
            <node_name>Khartoum</node_name>
        </node>
    </node>
XML;

        $input = simplexml_load_string($inputXml);
        $result = getNode($input, 1);
        $this->assertTrue($result);
    }

    function testGetNodeLevelInvalid(){

        $inputXml = <<< XML
    <node atlas_node_id = "355629" ethyl_content_object_id="3263" geo_id = "355629">
        <node_name>Sudan</node_name>
        <node atlas_node_id = "355630" ethyl_content_object_id="" geo_id = "355630">
            <node_name>Eastern Sudan</node_name>
        </node>
        <node atlas_node_id = "355632" ethyl_content_object_id="" geo_id = "355632">
            <node_name>Khartoum</node_name>
        </node>
    </node>
XML;

        $input = simplexml_load_string($inputXml);
        $this->expectException();
        getNode($input, 3);
    }

    function testGetNavigationRootEmpty(){
        $inputXml = <<< XML
<taxonomies>
    <taxonomy>
        <taxonomy_name>World</taxonomy_name>
            <node atlas_node_id = "355064" ethyl_content_object_id="82534" geo_id = "355064">
                <node_name>Africa</node_name>
            </node>
    </taxonomy>
</taxonomies>
XML;

        $this->expectException();
        getNavigationRoot($inputXml, '');
    }

    function testGetNavigationRootWrongInput(){
        $inputXml = <<< XML
<taxonomies>
    <taxonomy>
        <taxonomy_name>World</taxonomy_name>
            <node atlas_node_id = "355064" ethyl_content_object_id="82534" geo_id = "355064">
                <node_name>Africa</node_name>
            </node>
    </taxonomy>
</taxonomies>
XML;

        $this->expectException();
        getNavigationRoot($inputXml, 4);
    }

    function testGetNavigationRootWrongXML(){
        $inputXml = <<< XML
<taxonomies>
    <game>
        <taxonomy_name>World</taxonomy_name>
            <node atlas_node_id = "355064" ethyl_content_object_id="82534" geo_id = "355064">
                <node_name>Africa</node_name>
            </node>
    </game>
</taxonomies>
XML;

        $this->expectException();
        getNavigationRoot($inputXml, "taxonomy");
    }

    function testGetNavigationRootValid(){
        $inputXml = <<< XML
<taxonomies>
    <taxonomy>
        <taxonomy_name>World</taxonomy_name>
            <node atlas_node_id = "355064" ethyl_content_object_id="82534" geo_id = "355064">
                <node_name>Africa</node_name>
            </node>
    </taxonomy>
</taxonomies>
XML;
        $input = simplexml_load_string($inputXml);
        $result = getNavigationRoot($input, "taxonomy");
        $expected = array("taxonomy_name" => "World", "parent_id" => "root");
        $this->assertEqual($result, $expected);
    }

    function testGetLeafValid(){
        $inputXml = <<< XML
<node atlas_node_id="355064" ethyl_content_object_id="82534" geo_id="355064">
	<node_name>Africa</node_name>
	<node atlas_node_id="355611" ethyl_content_object_id="3210" geo_id="355611">
		<node_name>South Africa</node_name>
	</node>
	<node atlas_node_id="355629" ethyl_content_object_id="3263" geo_id="355629">
		<node_name>Sudan</node_name>
	</node>
	<node atlas_node_id="355633" ethyl_content_object_id="3272" geo_id="355633">
		<node_name>Swaziland</node_name>
	</node>
</node>
XML;
        $input = simplexml_load_string($inputXml);
        $result = getLeaf($input, "123", 0);
        $expected = array(  "node_name" => "Africa",
                            "atlas_node_id" => "355064",
                            "ethyl_content_object_id" => "82534",
                            "geo_id" => "355064",
                            "parent_id" => "123",
                            "node_level" => 0);
        $this->assertEqual($result, $expected);
    }

    function testGetLeafInvalidLevel(){
        $inputXml = <<< XML
<node atlas_node_id="355064" ethyl_content_object_id="82534" geo_id="355064">
	<node_name>Africa</node_name>
	<node atlas_node_id="355611" ethyl_content_object_id="3210" geo_id="355611">
		<node_name>South Africa</node_name>
	</node>
	<node atlas_node_id="355629" ethyl_content_object_id="3263" geo_id="355629">
		<node_name>Sudan</node_name>
	</node>
	<node atlas_node_id="355633" ethyl_content_object_id="3272" geo_id="355633">
		<node_name>Swaziland</node_name>
	</node>
</node>
XML;
        $input = simplexml_load_string($inputXml);
        $this->expectException();
        getLeaf($input, "123", "a");
    }

    function testGetLeafMissingAttributes(){
        $inputXml = <<< XML
<node atlas_node_id="355064" >
	<node_name>Africa</node_name>
	<node atlas_node_id="355611" ethyl_content_object_id="3210" geo_id="355611">
		<node_name>South Africa</node_name>
	</node>
	<node atlas_node_id="355629" ethyl_content_object_id="3263" geo_id="355629">
		<node_name>Sudan</node_name>
	</node>
	<node atlas_node_id="355633" ethyl_content_object_id="3272" geo_id="355633">
		<node_name>Swaziland</node_name>
	</node>
</node>
XML;
        $input = simplexml_load_string($inputXml);
        $this->expectException();
        getLeaf($input, "123", 0);
    }

    function testGetLeafInvalidNode(){
        $inputXml = <<< XML
<node atlas_node_id="355064" ethyl_content_object_id="82534" geo_id="355064">
	<node atlas_node_id="355611" ethyl_content_object_id="3210" geo_id="355611">
		<node_name>South Africa</node_name>
	</node>
	<node atlas_node_id="355629" ethyl_content_object_id="3263" geo_id="355629">
		<node_name>Sudan</node_name>
	</node>
	<node atlas_node_id="355633" ethyl_content_object_id="3272" geo_id="355633">
		<node_name>Swaziland</node_name>
	</node>
</node>
XML;
        $input = simplexml_load_string($inputXml);
        $this->expectException();
        getLeaf($input, "123", 0);
    }
}


?>