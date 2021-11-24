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
      <a href="../index.html">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        //Count soldiers then multiply that number by 1
        $village = $_POST['village'];
        $query1 = "select count(*) as total from Human h where h.role='Henchman' group by h.role";

        //Get name of hero and dmg and hp        
        $hero = $_POST['hero'];
        $query2 = "select h.firstName, h.health, w.attack from Human h inner join Weapon w on h.Weapon_Name=w.Name 
        where h.firstName=";
        $query2 = $query2."'".$hero."';";

        //Display current human status
        $queryAtt = "select * from Human h";

        /* Get all the soldiers with their weapons*/
        /* Subtract the dmg from heros hp*/
        /* Choose random soldier and decrease their hp by dmg of hero*/

        /* Get all the soldiers with their weapons*/
        printf("Hero: %s\n",$hero);
        printf("Village: %s\n", $village);

        printf("QR1: %s\n", $query1);
        printf("QR2: %s\n", $query2);
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
      $totDmg = 0;
      $newHeroHp = 0;
      $dmgFromHero = 0;
        //q1
        $result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));
        print "<pre>";
        while($row = mysqli_fetch_array($result1, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          {
            printf("[%- 8s]",$element); 
            $totDmg += $element;
          }  
          print "<br>";
        }
        print "</pre>";
        mysqli_free_result($result1);
        printf("Total Dmg to Hero: %s\n", $totDmg);
        //end q1

        //q2
        $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
        print "<pre>";
        while($row = mysqli_fetch_array($result2, MYSQLI_ASSOC))
        {  
          $counter = 0;
          foreach ($row as $element)
          {
            printf("[%- 8s]",$element);
            $counter++;
            if (counter == 2)
            {
                $newHeroHp -= $element;
            }
            else if (counter == 3)
            {
                $dmgFromHero = $element;
            }
          }   
          print "<br>";
        }
        print "</pre>";
        mysqli_free_result($result2);
        printf("Total Dmg to Hero: %s\n", $newHeroHp);
        printf("Total Dmg to Hero: %s\n", $totDmg);
        //end q2

        
        //Print results
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
        //Print results end


        mysqli_close($conn);
        
        // Subtract from one soldier

        // Subtract from hero
      ?>
    </section>
  </body>
</html>	  