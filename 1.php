<?php
// Create EdinaMetaData table, copy ROSMetaData data into it and add new columns
// Add DOIs to metadata
include 'conn.php';

try{
    $dbh = new PDO("sqlsrv:Server=$server;Database=EdinaImports", $username, $password);

   $dbh->exec("DROP TABLE EdinaWOKData");

  $dbh->exec("SELECT * into EdinaWOKData from Results.dbo.view_WOKSource");

  $dbh->exec("ALTER TABLE EdinaWOKData ADD  [TimesCited] [int] NULL, WOKAcknowledgement VARCHAR(MAX), WOKAbstract VARCHAR(MAX), WOKFunders VARCHAR(MAX), WOKKeywords VARCHAR(MAX)");

// $dbh->exec("CREATE TABLE edina_temp3 ( id smallint IDENTITY(1,1)PRIMARY KEY , DOI VARCHAR(80), WOKID VARCHAR(80), TimesCited INT)");

//close the connection 
$dbh = null;
}catch(PDOException $e){
   echo 'Failed to connect to database: ' . $e->getMessage() . "\n";
   exit;
}
print 'done';
?>

