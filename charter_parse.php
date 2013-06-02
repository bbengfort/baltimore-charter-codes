<?php




ini_set("error_reporting", E_ALL);
//$laws = glob('*.xml');
//var_dump($laws);
$src = file_get_contents('01 - Charter.xml');

$articles = preg_split("/anchor id=\"Art.*\/>/", $src);
//echo $articles[2];
for($i =1; $i<count($articles) ; $i +=1){
	$article = strip_tags($articles[$i]);
    $isMatch = preg_match('/^Article ([IXV]{0,10})\s*(\S.*)/m', $article, $titleStuff);

    if($isMatch){
    	$art_num = $titleStuff[1];
    $art_name = $titleStuff[2];
    }
    else{
    	echo $article;
    }

	$sections = preg_split("/&#167;/", $article);
	foreach($sections as $section){
		$law = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><law></law>');
		$structureNode = $law->addChild("structure");
		$unit = $structureNode->addChild("unit", $titleStuff[2]);
		$unit->addAttribute("label", "article");
		$unit->addAttribute("identifier", $titleStuff[1]);
		$unit->addAttribute("order_by", $i);
		$unit->addAttribute("level", '1');

			
		$children = array();

		//Patterns: Title, (a), (1), iv, 1
		$structure = array('@^\s+([0-9]+)\.(.*)@', '@\n\s*\([a-z]+\).*@', '@\n\s*\(\d+\).*@', '@\n\s*\([ixv]+\).*@', '@\n\s*\d+\..*@');
		
		foreach($structure as $index =>$pattern){
			//echo "****** $pattern ******\n";
			$ret = preg_match_all($pattern, $section, $matches);
			if(!isset($parent)){
				$parent = $law;
			}
			
			if($ret == 1){
				//pattern matches
				if($index == 0){
					$section = preg_replace($pattern, '', $section);
					$law->addChild("section_number", $matches[1][0]);
					$law->addChild("catch_line", trim($matches[2][0]));
					$law->addChild("order_by", $matches[1][0]);
					$parent = $law->addChild("text");
				}
				//Append a section to the text node
				//then another section if there is one.
			}
			else if($ret == 0){
				if($parent->getName() == 'text'){
					$parent[0] = trim($section);
				}
				break;
			}else{
				//There was an error
			}
			
			
			// echo "*** $ret ***\n";
			if(!empty($matches)){
				array_push($children, $matches);
			}
			
		}
		unset($parent);
		echo($law->asXML());
		// print_r($law);
		//print_r($section);
		// print_r($children);
		// echo "----------------------------------------\n";
	}
}




?>