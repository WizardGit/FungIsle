<!--
Author: Kaiser
Original Author: Chris
Date last edited: 11/15/2021
-->

<?php
include('../Connections/connectionData.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');
?>

<html lang="en">
<head>
  <title>Reset Database</title>
  <link rel="stylesheet" href="displayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
    <section>
      <a href="index.html">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        $query = "UPDATE Human h SET h.health=99 WHERE SaladSN > 4;";
        $queryAtt = "select * from Human h";
        print $query;
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $resultAtt = mysqli_query($conn,$queryAtt) or die(mysqli_error($conn));

        print "<pre>";

        while($row = mysqli_fetch_array($resultAtt, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          printf("[%- 8s]",$element);   
          print "<br>";
        }

        print "</pre>";
        mysqli_free_result($result);
        mysqli_close($conn);
      ?>
    </section>
  </body>
</html>	  