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
	$article = strip_tags($articles[$i]);
    $isMatch = preg_match('/^Article ([IXV]{0,10})\s*(\S.*)/m', $article, $titleStuff);
    echo "parents: ";
    if($isMatch){
    	$art_num = $titleStuff[1];
    $art_name = $titleStuff[2];
    }
    else{
    	echo $article;
    }
    
    var_dump($titleStuff);
	$sections = preg_split("/&#167;/", $articles[$i]);
	foreach($sections as $section){
		$law = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><law></law>');
		$structureNode = $law->addChild("structure");
		$unit = $structureNode->addChild("unit", $titleStuff[2]);
		$unit->addAttribute("label", "article");
		$unit->addAttribute("identifier", $titleStuff[1]);
		$unit->addAttribute("order_by", $i);
		$unit->addAttribute("level", '1');

			
		$children = array();
		//preg_match("/^\s?\d/", $section, $array_section_number);
		//$section_number = trim($array_section_number[0]);
		//echo 'Section:'.$section_number;
		//preg_match_all("/<para> \([a-z]\)/", $section, $prefixes);
		//$subsections = preg_split("/<para> \([a-z]\)/", $section);
		//var_dump($prefixes[0]);
		//var_dump($subsections);
		// $section = strip_tags($section);
		// echo "----------------------------------------\n";

		//Patterns: Title, (a), (1), iv, 1
		$structure = array('@^\s([0-9]+)\..*@', '@\n\s\([a-z]+\).*@', '@\n\s*\(\d+\).*@', '@\n\s*\([ixv]+\).*@', '@\n\s*\d+\..*@');
		$level = 0;
		foreach($structure as $index =>$pattern){
			//echo "****** $pattern ******\n";
			$ret = preg_match_all($pattern, $section, $matches);
			if($ret == 1){
				//pattern matches
				if($level == 0){
					$law->addChild("section_number", $matches[1]);

				}
				//append a text node to law.
				//then a section to that
				//then another section if there is one.
			$level ++;
			}
			// echo "*** $ret ***\n";
			array_push($children, $matches);
		}
		echo($law->asXML());
		// print_r($law);
		// print_r($section);
		// print_r($children);
		// echo "----------------------------------------\n";
	}
}




?>