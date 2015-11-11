<?php

/* 

1. Read in list of DOIs 
2. Calculate number of requests to make
3. Create XML file for each request and use cURL to request from WoK
4. Write each response to file

*/

$handle = @fopen('files/doi.txt', "r") or die('Cannot open file'); 
if ($handle) { 
   while (!feof($handle)) { 
       $lines[] = fgets($handle, 4096); 
   } 
   fclose($handle); 
} ;
$pubs = $lines;

$total = count($pubs);
//how many records in a request
$limit = 50;

//how many requests to make
$req = ceil($total/$limit);

//initialize variables
$o= 0;
$reqno = 0;
$out = '';



// create the necessary no of requests
while ($reqno< $req) {
	
//create variable for each request (reference by $$out)
$out= 'out' . $reqno;

	
$select =(array_slice($pubs, $o, $limit));
//create request header
$header = '<?xml version="1.0" encoding="UTF-8" ?>
<request xmlns="http://www.isinet.com/xrpc42" src="app.id=API Test">
<fn name="LinksAMR.retrieve">
<list>
<map>
</map>
<map>
<list name="WOS">
<val>timesCited</val>
<val>ut</val>
<val>doi</val>
<val>pmid</val>
<val>sourceURL</val>
<val>citingArticlesURL</val>
<val>relatedRecordsURL</val>
</list>
</map>
<map>';

//loop through rows
foreach ($select as $key => $value) {
$data =  '<map name="cite_'.$key.'">
<val name="doi">'.trim($value).'</val>
</map>';

//append row to previous row

$$out .=$data;
}

//create request footer
$footer = '</map>
</list>
</fn>
</request>';

$reqno++;
$o = $o + $limit ;

//assemble request
 $soap_request = $header .$$out .$footer;

//echo $soap_request;


       $header = array(
	"Accept-Encoding: gzip,deflate",
    "Content-Type: application/xml",
     "Content-length: ".strlen($soap_request),
     "Host: ws.isiknowledge.com",
	"Connection: Keep-Alive",
   
  );

  $soap_do = curl_init();
  curl_setopt($soap_do, CURLOPT_URL, "https://ws.isiknowledge.com/cps/xrpc " );
  curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 100);
  curl_setopt($soap_do, CURLOPT_TIMEOUT,        100);
  curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($soap_do, CURLOPT_POST,           false );
  curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $soap_request);
  curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);

  if(curl_exec($soap_do) === false) {
    $err = 'Curl error: ' . curl_error($soap_do);
    curl_close($soap_do);
    print $err;
	
  } else {
	 $result = curl_exec($soap_do);
    curl_close($soap_do);
  }
$my_file = 'files/response-'.$reqno.'.xml';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

fwrite($handle, $result);
//echo 'response-' . $reqno . '.txt written<br>';
}

//echo $reqno . ' WoK response files written<br><br>';

?>

