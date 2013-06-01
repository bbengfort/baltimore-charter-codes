<?php
ini_set("error_reporting", E_ALL);
//$laws = glob('*.xml');
//var_dump($laws);
$src = file_get_contents('01 - Charter.xml');
//var_dump($sections) ;
$xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?>
<law></law>');
//echo $src;
$articles = preg_split("/anchor id=\"Art.*\/>/", $src);
//echo $articles[2];
for($i =1; $i<count($articles) ; $i +=1){
    $structure = preg_replace("/Article (.+)<\/para>[\s\S]?<para>(.*)<\/para>/","$1 $2", $articles[$i]);
    echo "parents: ";
    var_dump($structure);
	$sections = preg_split("/&#167;/", $articles[$i]);
	
	foreach($sections as $section){
		preg_match("/^\s?\d/", $section, $array_section_number);
		$section_number = trim($array_section_number[0]);
		echo 'Section:'.$section_number;
		preg_match_all("/<para> \([a-z]\)/", $section, $prefixes);
		$subsections = preg_split("/<para> \([a-z]\)/", $section);
		var_dump($prefixes[0]);
		var_dump($subsections);
	}
}




?>