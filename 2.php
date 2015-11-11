<?php

include 'conn.php';

try{
	
 $dbh = new PDO("sqlsrv:Server=$server;Database=EdinaImports", $username, $password);

 $sql = "SELECT dbo.EdinaWOKData.DOI as doi from dbo.EdinaWOKData WHERE (dbo.EdinaWOKData.DOI LIKE N'10.%')";
 
 
 $i=1;
 $dois = '';
 
    foreach ($dbh->query($sql) as $row)
        {
  	$dois .= $row['doi'] . PHP_EOL;
	 //	echo $i . ' - ' . $row['doi'] . "<br>";
	 $i++;
        }
$my_file = 'files/doi.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

fwrite($handle, $dois);

//echo 'DOIs written to ' . $my_file . '<br><br>';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'done2';
?>
