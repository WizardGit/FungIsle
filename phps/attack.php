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
        //Get village to attack
        $village = $_POST['village']; 
        printf("Village: %s <br>", $village);       

        //Get hero to attack        
        $hero = $_POST['hero'];  
        printf("Hero: %s <br>",$hero);        
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
        //getNumHenchmenIn($conn, $village);
        //reduceHeroHealth($conn, $hero, 10);
        //reduceHenchmanHealth($conn, $village, 50);
        //reduceBossHealth($conn, 50);
        //checkVillageStatus($conn, $village);
        //getDamageTotal($conn, $hero);
        //printAllHumans($conn);
        mysqli_close($conn);     
        

        if (checkVillageStatus($conn, $village) == false)
        {
          if($village == "HellCave")
          {
            //get hero dmg
            $heroDMG = getDamageTotal($conn, $hero);
            //put hero dmg on boss
            reduceBossHealth($conn, $heroDMG);
            //get boss dmg
            $bossDMG = getDamageTotal($conn, "SaladoreTheTyrant");
            //put boss dmg on hero
            reduceHeroHealth($conn, $hero, $bossDMG);
          }
          else
          {
            //get hero dmg
            $heroDMG = getDamageTotal($conn, $hero);
            //put hero dmg on henchman
            reduceHenchmanHealth($conn, $village, $heroDMG);
            //get henchman dmg
            $bossDMG = getNumHenchmenIn($conn, $village);
            //put henchman dmg on hero
            reduceHeroHealth($conn, $hero, $bossDMG);
          }
        }
        else if ($village == "HellCave")
        {
          printf("You have won!!! <br>");
        }
        else
        {
          printf("You have defeated all of the lands - try Hell's Cave for the final boss fight! <br>");
        }
        
        function printAllHumans($conn)
        {
          $queryAtt = "select * from Human h";
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
        }
        
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
            $newHp = $totDmg - $dmg;
            printf("First Henchman Health should be: %s <br>", $newHp); 
            
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

        function getDamageTotal($conn, $human)
        {
          // Get the old health
          $query = "select w.attack, h.attackMultiplier from Human h inner join Weapon w on h.Weapon_Name=w.Name where h.firstName=";
          $query = $query."'".$human."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);              
          $counter = 0;
          foreach ($row as $element)
          {
            if ($counter == 0)
              $dmg = $element; 
            else if ($counter == 1)
              $dmgMult = $element;
            $counter++;
          }      
          $total = $dmg * $dmgMult;  
          mysqli_free_result($result);
          printf("Human's total Damage is: %s <br>", $total);  

          return $total;          
        }

        function checkVillageStatus($conn, $village)
        {
          // Return true if $village status is freed, false if not
          $query = "select count(*) as total from Human h inner join Village v on h.Village_ID = v.VillageID 
          where v.name=";
          $query = $query."'".$village."' and h.role='Henchman' and h.health>0";
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);              
          foreach ($row as $element)
            $total =  $element;      
          mysqli_free_result($result);
          printf("Total: %s <br>", $total); 
          if ($total == 0)
          {
            //Get Village and set to freed
            //$query = "update Village v set v.status='freed'";
            //$result = mysqli_query($conn, $query) or die(mysqli_error($conn));                 
            //mysqli_free_result($result);
            return true;
          }
          else
            return false;
        }

      ?>
    </section>
  </body>
</html>	  