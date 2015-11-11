<?php

/*

1. Create csv file
2. Loop through each WoK response file and append DOI, WOKID and Times Cited to new line

*/




# Enable Error Reporting and Display:
error_reporting(~0);
ini_set('display_errors', 1);


$my_csv_file = 'files/wok.csv';


if (file_exists($my_csv_file)) {
	
	rename($my_csv_file, "files/wok-" .date('Y-m-d-His'). ".csv");
}
$handle = fopen($my_csv_file, 'a') or die('Cannot open file:  '.$my_csv_file); //implicitly creates file

//echo $my_csv_file;
$directory = "files/";
if (glob($directory . "response-*.xml") != false)
{
 $filecount = count(glob($directory . "response-*.xml"));
 //echo $filecount;
}
else
{
 echo 0;
}



$c=0;
while ($c <  $filecount) {
	
	
$c++;




$string = file_get_contents('files/response-' .$c .'.xml');

$WOK = simplexml_load_string($string);

//print_r($WOK);

  
$i=0;

$line ='';
foreach($WOK->fn->map->map as $item) 
{
	
	$vals = $item->map->val->count();
	 foreach ($item->map->val as $val) {
	 if ($val->attributes()->name == 'doi') {
		
		$doi = $val;
		 } elseif  ($val->attributes()->name == 'message')
	 {
		 $doi = '';
		 }
	 	 if ($val->attributes()->name == 'ut') {
		// $doi = $item->map->val;
		$ut = $val;
		
	 }elseif  ($val->attributes()->name == 'message')
	 {
		 $ut = '';
		 }
		 
		 
	if ($val->attributes()->name == 'timesCited') {
		// $doi = $item->map->val;
		$cites = $val;
		
	 }elseif ($vals < 6)
	 {
		 $cites = '';
		 }
		 
		 

}
if ($ut !==''){
$line .=  '"' . $doi . '","' . $ut . '",' .$cites. "\n";
}
	 }
	 
//$header = 'doi,wokid,cites' . "\n";

$lines = $line;	 
//echo $lines;
 
fwrite($handle, $lines);



}
//echo $my_csv_file . ' created containing WOK IDs and citation counts<br><br>';
?>



