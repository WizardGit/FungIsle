<!--
Author: Kaiser
Original Author: Chris
Date last edited: 11/15/2021
-->

<?php
include('connectionData.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');
?>

<html lang="en">
<head>
  <title>Another Simple PHP-MySQL Program</title>
  <link rel="stylesheet" href="displayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
  <section>
  <a href="index.html">Back to Home Page</a>
    
<?php  
$query = $_POST['query'];
?>

<p> <h2>The query:</h2> <p>
<?php
print $query;
?>

<p> <h2>Result of query:</h2> <p>
<p>
<?php
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$queryr = "SELECT `COLUMN_NAME` 
FROM `INFORMATION_SCHEMA`.`COLUMNS` 
WHERE `TABLE_SCHEMA`='practice' 
    AND `TABLE_NAME`='dep_policy';    ";
$newr = mysqli_query($conn, $queryr)

while($row = mysqli_fetch_array($newr, MYSQLI_ASSOC))
{
  
  $arr[] = $row;
  print_r($arr);
  foreach ($row as $element)
  {
    print $element." ";    
    
  }
  
  //print($columnArr);
  print "<br>";
}




//$hel =  mysql_field_name($result, 0);
//print ($hel);
print "<pre>";
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
  
  $arr[] = $row;
  print_r($arr);
  foreach ($row as $element)
  {
    print $element." ";    
    
  }
  
  //print($columnArr);
  print "<br>";
}
//$columnArr = array_column($result, 'COLUMN_NAME');
//print_r($columnArr);
print "</pre>";
mysqli_free_result($result);
mysqli_close($conn);
?>
</p>
</section>
</body>
</html>	  