<?php




ini_set("error_reporting", E_ALL);
//$laws = glob('*.xml');
//var_dump($laws);
$src = file_get_contents('01 - Charter.xml');
//var_dump($sections) ;

//echo $src;
$articles = preg_split("/anchor id=\"Art.*\/>/", $src);
//echo $articles[2];
for($i =1; $i<count($articles) ; $i +=1){
    $structure = preg_replace("/Article (.+)<\/para>[\s\S]?<para>(.*)<\/para>/","$1 $2", $articles[$i]);
    echo "parents: ";
    var_dump($structure);
	$sections = preg_split("/&#167;/", $articles[$i]);
	foreach($sections as $section){
		$law = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><law></law>');
		$children = array();
		//preg_match("/^\s?\d/", $section, $array_section_number);
		//$section_number = trim($array_section_number[0]);
		//echo 'Section:'.$section_number;
		//preg_match_all("/<para> \([a-z]\)/", $section, $prefixes);
		//$subsections = preg_split("/<para> \([a-z]\)/", $section);
		//var_dump($prefixes[0]);
		//var_dump($subsections);
		$section = strip_tags($section);
		echo "----------------------------------------\n";

		//Patterns: Title, (a), (1), iv, 1
		$structure = array('@^\s[0-9]+\..*@', '@\n\s\([a-z]+\).*@', '@\n\s*\(\d+\).*@', '@\n\s*\([ixv]+\).*@', '@\n\s*\d+\..*@');
		foreach($structure as $pattern){
			echo "****** $pattern ******\n";
			$ret = preg_match_all($pattern, $section, $matches);
			if($ret == 1){
				//pattern matches
				//append a text node to law.
				//then a section to that
				//then another section if there is one.

			}
			echo "*** $ret ***\n";
			array_push($children, $matches);
		}
		print_r($section);
		print_r($children);
		echo "----------------------------------------\n";
	}
}




?>