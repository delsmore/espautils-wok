<?php
$start = time();

$msghead = '*********************************************************************************' . PHP_EOL .
           '                          Web of Knowledge Log'  .  PHP_EOL .
		   '*********************************************************************************' . PHP_EOL ;

$my_log_file = 'files/log.txt';

$loghandle = fopen($my_log_file, 'a') or die('Cannot open file:  '.$my_log_file); //implicitly creates file
if ( 0 == filesize( $my_log_file ) ) {
fwrite($loghandle, $msghead);
}

require	'1.php';

$msg1 = date('Y-m-d H:i:s') . ' - '  . ' records updated in Metadata Table'. PHP_EOL;

fwrite($loghandle, $msg1);

require '2.php';

$msg2 = date('Y-m-d H:i:s') . ' - DOIs written to doi.txt' . PHP_EOL;

fwrite($loghandle, $msg2);

require '3.php';

$msg3 = date('Y-m-d H:i:s') . ' - ' . $reqno . ' WoK response files written' . PHP_EOL;

fwrite($loghandle, $msg3);

require '4.php';

$msg4 = date('Y-m-d H:i:s') . ' - ' . $my_csv_file . ' created containing WOK IDs and citation counts' .PHP_EOL;

fwrite($loghandle, $msg4);

require '5.php';

$msg5 = date('Y-m-d H:i:s') . ' - WOKIDs and Citation Counts written to Metadata table' .PHP_EOL .
        '-------------------------------------------------------------------------------------' . PHP_EOL;

fwrite($loghandle, $msg5);

$end = time();


 $dateDiff    =  $end - $start ;
 
 $mins = floor($dateDiff/60);
 $secs = $dateDiff - ($mins*60);
 
 $msg6 = 'Job completed in: ' . $mins . ' minutes ' . $secs . ' seconds.' .PHP_EOL .
        '-------------------------------------------------------------------------------------' . PHP_EOL;
 
 fwrite($loghandle, $msg6);
 
 echo 'Job completed in: ' . $mins . ' minutes ' . $secs . ' seconds. See <a href="files/log.txt">Log</a> for details.';

?>
