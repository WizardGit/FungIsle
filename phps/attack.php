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
  <title>Perform Attack</title>
  <link rel="stylesheet" href="../StylesdisplayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
    <section>
      <a href="index.html">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        $hero = $_POST['hero'];
        $village = $_POST['village'];
        $query = $_POST['query'];
        //Count soldiers then multiply that number by 1
        $query1 = "select count(*) as total from Human h where h.role='Henchman' group by h.role";
        //Get name of hero and dmg and hp
        $query2 = "select h.firstName, h.health, w.attack from Human h inner join Weapon w on h.Weapon_Name=w.Name 
        where h.firstName=";
        $query2 = $query2."'".$hero."';";

        $queryAtt = "select * from Human h";


        /* Get all the soldiers with their weapons*/
        /* Subtract the dmg from heros hp*/
        /* Choose random soldier and decrease their hp by dmg of hero*/

        /* Get all the soldiers with their weapons*/
        printf("Hero: %s\n",$hero);
        printf("Village: %s\n", $village);
        printf("Query: %s\n", $query);

        printf("QR1: %s\n", $query1);
        printf("QR2: %s\n", $query2);
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
        $result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));
        $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
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