<?php
echo "yo";
//$laws = glob('*.xml');
//var_dump($laws);
$src = file_get_contents('01 - Charter.xml');

//echo $src;
$articles = preg_split("/anchor id=\"Art.*\/>/", $src);
//echo $articles[2];

$sections = preg_split("/&#167;/", $articles[2]);
var_dump($sections) ;


?>