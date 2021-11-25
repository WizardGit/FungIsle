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

        getNumHenchmenIn($conn, $village);
        reduceHeroHealth($conn, $hero, 10);
        reduceHenchmanHealth($conn, $village, 50);
        reduceBossHealth($conn, 50);

        //Print results
        $resultAtt = mysqli_query($conn,$queryAtt) or die(mysqli_error($conn));
        print "<pre>";
        while($row = mysqli_fetch_array($resultAtt, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
          printf("[%- 17s]",$element);   
          print "<br>";
        }
        print "</pre>";
        mysqli_free_result($result); 
        mysqli_close($conn);        
        
        function getNumHenchmenIn($conn, $village)
        {     
            $query = "select count(*) as total  from Human h  inner join Village v on h.Village_ID = v.VillageID 
            where h.role='Henchman' and v.name=";
            $query = $query."'".$village."';";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
            foreach ($row as $element)
                $totDmg = $element;  
            mysqli_free_result($result);
            printf("getNumHenchmenIn: %s <br>", $totDmg);

        }       
        
        function reduceHeroHealth($conn, $hero, $dmg)
        {
            // Get the old health
            $query = "select h.health from Human h where h.firstName=";
            $query = $query."'".$hero."';"; 
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);              
            foreach ($row as $element)
                $newHp =  $element - $dmg;       
            mysqli_free_result($result);
            printf("Hero's new HP should be: %s <br>", $newHp);  

            // Set the new health
            $query = "update Human h set h.health=";
            $query = $query."'".$newHp."'where h.firstName=";
            $query = $query."'".$hero."';";  
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
            mysqli_free_result($result);
        }

        function reduceHenchmanHealth($conn, $village, $dmg)
        {
            $query = "select h.health, h.SaladSN from Human h where h.role='Henchman' and h.health > 0 limit 1;";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
            $counter = 0;
            foreach ($row as $element)
            {
              if ($counter == 0)
                $totDmg = $element; 
              else if ($counter == 1)
                $SSN = $element;
              $counter++;
            }    
            mysqli_free_result($result);
            printf("First Henchman Health: %s <br>", $totDmg); 
            $newHp = $totDmg - $dmg;
            
            // Set the new health
            $query = "update Human h set h.health=";
            $query = $query."'".$newHp."'where h.SaladSN=";
            $query = $query."'".$SSN."';";  
            printf($query);     
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            mysqli_free_result($result);
            
        }

        function reduceBossHealth($conn, $dmg)
        {
           // Get the old health
           $query = "select h.health from Human h where h.firstName='SaladoreTheTyrant'";
           $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
           $row = mysqli_fetch_array($result, MYSQLI_ASSOC);              
           foreach ($row as $element)
               $newHp =  $element - $dmg;       
           mysqli_free_result($result);
           printf("Boss' new HP should be: %s <br>", $newHp);  

           // Set the new health
           $query = "update Human h set h.health=";
           $query = $query."'".$newHp."'where h.firstName='SaladoreTheTyrant'";
           $result = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
           mysqli_free_result($result);
        }

        function checkVillageStatus($conn, $village)
        {
            // Return true if $village status is freed, false if not
        }

      ?>
    </section>
  </body>
</html>	  