<?php




ini_set("error_reporting", E_ALL);
//$laws = glob('*.xml');
//var_dump($laws);

function addChildren(&$parent, $index, $patterns){
	
}

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
		echo "----------------------------------------\n";
		//print_r($section);
		$law = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><law></law>');
		$structureNode = $law->addChild("structure");
		$unit = $structureNode->addChild("unit", $titleStuff[2]);
		$unit->addAttribute("label", "article");
		$unit->addAttribute("identifier", $titleStuff[1]);
		$unit->addAttribute("order_by", $i);
		$unit->addAttribute("level", '1');

			
		$children = array();

		//Patterns: Title, (a), (1), iv, 1.
		$structure = array('@^\s+([0-9]+)\.(.*)@', '@\n\s*(\([a-z]+\))\s.*@', '@\n\s*\(\d+\).*@', '@\n\s*\([ixv]+\).*@', '@\n\s*\d+\..*@');
		
		foreach($structure as $index =>$pattern){
			//echo "****** $pattern ******\n";
			$ret = preg_match_all($pattern, $section, $matches);
			//print_r($section);
			echo $index . "\n";
			echo $ret . "\n";
			print_r($matches);
			if(!isset($parent)){
				$parent = $law;
			}
			
			if($ret > 0){
				echo "$index \n";
				//pattern matches
				if($index == 0){
					$section = preg_replace($pattern, '', $section);
					$law->addChild("section_number", $matches[1][0]);
					$law->addChild("catch_line", trim($matches[2][0]));
					$law->addChild("order_by", $matches[1][0]);
					$parent = $law->addChild("text");
				}
				else{
					$siblings = preg_split($pattern, $section);
					foreach($siblings as $sIndex => $sibling){
						
						echo "SINDEX: $sIndex\n";
						if($sIndex == 0){continue;}
						// if(preg_match('@\n\s*\([ixv]+\).*@', $matches[1][$sIndex - 1])){
						// 							if(preg_match('@\n\s*\([ixv]+\).*@', $matches[1][$sIndex - 2])){
						// 								//This is a roman numeral
						// 								continue;
						// 							}
						// 						}
						
						$tmp = $parent->addChild('section', trim($sibling));
						$tmp->addAttribute('prefix', $matches[1][$sIndex - 1]);
						$section = str_replace($sibling, '', $section);
						unset($tmp);						
					}
					$section = preg_replace($pattern, '', $section);
					echo "SIBLINGS: \n";
					print_r($siblings);
				}
				//Append a section to the text node
				//then another section if there is one.
			}
			else if($ret == 0){
				if($parent->getName() == 'text'){
					if(strlen($section) > 0){
						$parent->addChild('section', $section);
					}
				}
				else{
					$parent->addChild('section', $section);
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
		
		echo "----------------------------------------\n";
		echo($law->asXML());
		echo "----------------------------------------\n";
		
		// print_r($law);
		//print_r($section);
		// print_r($children);
		// echo "----------------------------------------\n";
	}
}




?>