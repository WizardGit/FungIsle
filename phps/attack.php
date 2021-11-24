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
  <link rel="stylesheet" href="../Styles/displayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
    <section>
      <a href="../index.html">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        //Count soldiers then multiply that number by 1
        $village = $_POST['village'];        

        //Get name of hero and dmg and hp        
        $hero = $_POST['hero'];        

        //Display current human status
        $queryAtt = "select * from Human h";

        /* Get all the soldiers with their weapons*/
        /* Subtract the dmg from heros hp*/
        /* Choose random soldier and decrease their hp by dmg of hero*/

        /* Get all the soldiers with their weapons*/
        printf("Hero: %s\n\n",$hero);
        printf("Village: %s\n\n", $village);
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php



        

        
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

        //reduceHeroHealth(10);
        mysqli_close($conn);
        
        // Subtract from one soldier

        // Subtract from hero


        function getNumHenchmenIn($village)
        {     
            $query = "select count(*) as total from Human h where h.role='Henchman' group by h.role";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
            $totDmg = $row[0];  
            mysqli_free_result($result);
            printf("getNumHenchmenIn: %s\n", $totDmg);
        }
        
        function reduceHeroHealth($dmg)
        {
            $query = "select h.health from Human h where h.firstName=";
            $query = $query."'".$hero."';";        
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result2, MYSQLI_ASSOC));  
            $newHp =  $row[0] - $dmg;       
            mysqli_free_result($result);
            printf("Hero's new HP should be: %s\n", $newHp);    
        }
      ?>
    </section>
  </body>
</html>	  