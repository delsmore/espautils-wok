<?php

/*
1. Create temp table
2. Insert rows from wok.csv into temp table
3. Update EdinaWOKData from temp table
4. Drop temp table
*/
$file_handle = fopen("files/wok.csv", "r");

// read the first line and ignore it
//fgets($file_handle); 

include 'conn.php';

try{
   $dbh = new PDO("sqlsrv:Server=$server;Database=EDINAImports", $username, $password);
   
    $dbh->exec("CREATE TABLE edina_temp ( id smallint IDENTITY(1,1)PRIMARY KEY , DOI VARCHAR(80), WOKID VARCHAR(80), TimesCited INT)");

//echo 'table created';

while (($line_of_data = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
	$doi = $line_of_data[0];
	if ($doi !==''){ 

     $dbh->exec("INSERT into edina_temp(DOI,WOKID,TimesCited) values('$line_of_data[0]','$line_of_data[1]','$line_of_data[2]')");

	}
   }
     $dbh->exec("UPDATE EdinaWOKData  set 
EdinaWOKData.WOKID = edina_temp.WOKID, 
EdinaWOKData.TimesCited = edina_temp.TimesCited
FROM edina_temp, EdinaWOKData 
WHERE edina_temp.DOI = EdinaWOKData.DOI");
 
 
 $dbh->exec("DROP TABLE edina_temp");


 //   echo 'Citations and WOK IDs written to Database';

$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
?>