<!--
Author: Kaiser
Date last edited: 11/24/2021
Purpose: Reset the FungIsle DB
-->

<?php
include('../Connections/connectionDataStrong.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
?>

<html lang="en">
<head>
  <title>Reset Database</title>
  <link rel="stylesheet" href="../Styles/displayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
    <section>
      <a href="../index.html">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        $query = "UPDATE Human h SET h.health=1000 WHERE h.role='Hero' or h.role='Boss';";
        $query2 = "UPDATE Human h SET h.health=100 WHERE h.role='Henchman';";
        $query3 = "UPDATE Village v SET v.status='suppressed' WHERE v.VillageID > 0;";
        $query4 = "UPDATE Animal a SET a.health=100 WHERE a.HumanOwnerSSN > 0;";
        $queryAtt = "select * from Human h";
        $queryAtt2 = "select * from Village v";
        $queryAtt3 = "select * from Animal a";
        print $query;
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
        $result3 = mysqli_query($conn, $query3) or die(mysqli_error($conn));
        $result4 = mysqli_query($conn, $query4) or die(mysqli_error($conn));
        $resultAtt = mysqli_query($conn,$queryAtt) or die(mysqli_error($conn));
        $resultAtt2 = mysqli_query($conn,$queryAtt2) or die(mysqli_error($conn));
        $resultAtt3 = mysqli_query($conn,$queryAtt3) or die(mysqli_error($conn));

        print "<pre>";

        while($row = mysqli_fetch_array($resultAtt, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          printf("[%- 17s]",$element);   
          print "<br>";
        }
        while($row = mysqli_fetch_array($resultAtt2, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          printf("[%- 10s]",$element);   
          print "<br>";
        }
        while($row = mysqli_fetch_array($resultAtt3, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          printf("[%- 10s]",$element);   
          print "<br>";
        }

        print "</pre>";
        mysqli_free_result($result);
        mysqli_close($conn);
      ?>
    </section>
  </body>
</html>	  