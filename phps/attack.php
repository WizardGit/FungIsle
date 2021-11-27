<!--
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Perform the attack that user requests
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
        //Get village
        $village = $_POST['village']; 
        printf("Village: %s <br>", $village);       

        //Get hero      
        $hero = $_POST['hero'];  
        printf("Hero: %s <br>",$hero); 

        //Get animal       
        $animal = $_POST['animal'];  
        printf("Animal: %s <br>",$animal); 
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php

        //getDamageTotal($conn, $hero);
        //reduceHeroHealth($conn, $hero, 10);
        //reduceBossHealth($conn, 50);

        //getHenchmenDamage($conn, $village);
        //reduceHenchmanHealth($conn, $village, 50);

        //getAnimalDamage($conn, $animal, $hero);
        //reduceAnimalHealth($conn, $animal, $hero, 50);
        
        //checkVillageStatus($conn, $village);        
        //printAllHumans($conn);                    
        
        if (checkVillageStatus($conn, $village) == false)
        {
          $heroPos = getHeroPosition($conn, $hero);
          printf("hero: %s", $heroPos);
          if($village == "HellCave")
          {            
            if(allVillagesFreed($conn) == false)
            {
              printf("You have not freed all the main villages yet! <br>");
            }
            else
            {
              updateHeroPosition($conn, $hero, $village);
              //Get Hero and Animal Damage
              $totalDMG = getDamageTotal($conn, $hero);
              $totalDMG += getAnimalDamage($conn, $animal, $hero);
              //put hero dmg on boss
              reduceBossHealth($conn, $totalDMG);
              //get boss dmg
              $bossDMG = getDamageTotal($conn, "SaladoreTheTyrant");
              //put boss dmg on hero
              reduceHeroHealth($conn, $hero, $bossDMG);
            }
          } 
          else if (($heroPos != $village) && (checkVillageStatus($conn, $heroPos) == false))
          {
            printf("%s cannot move to %s because they are in %s which has not yet been freed! <br>", $hero, $village, $heroPos);
          }
          else
          {
            updateHeroPosition($conn, $hero, $village);
            //Get Hero and Animal Damage
            $totalDMG = getDamageTotal($conn, $hero);
            $totalDMG += getAnimalDamage($conn, $animal, $hero);
            //put hero dmg on henchman
            reduceHenchmanHealth($conn, $village, $totalDMG);
            //get henchman dmg
            $henchDMG = getHenchmenDamage($conn, $village);
            //put henchman dmg on hero
            reduceHeroHealth($conn, $hero, $henchDMG);
          }
          //updated village status
          checkVillageStatus($conn, $village);
        }        
        else if ($village == "HellCave")
        {
          printf("You have already won!!! <br>");
        }
        else if (allVillagesFreed($conn) == false)
        {
          printf("You have already freed this land! Try another. <br>");
        }
        else if (allVillagesFreed($conn) == true)
        {
          printf("You have freed all the villages! You must confront SaladoreTheTyrant in Hell's Cave! <br>");
        }
        else
        {
          printf("ERROR");
        }
        printAllHumans($conn);
        mysqli_close($conn); 
        
        function printAllHumans($conn)
        {
          $query = "SHOW COLUMNS FROM Human";
          $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
          print "<pre>";
          while($row = mysqli_fetch_array($result))
          {
            printf("[%- 8s]",$row['Field']);
          }
          print "<br>";
          mysqli_free_result($result);

          $queryAtt = "select * from Human h";
          $queryAtt2 = "select * from Village v";
          $queryAtt3 = "select * from Animal a";
          $resultAtt = mysqli_query($conn,$queryAtt) or die(mysqli_error($conn));
          $resultAtt2 = mysqli_query($conn,$queryAtt2) or die(mysqli_error($conn));
          $resultAtt3 = mysqli_query($conn,$queryAtt3) or die(mysqli_error($conn));        

          while($row = mysqli_fetch_array($resultAtt, MYSQLI_ASSOC))
          {            
            foreach ($row as $element)
              printf("[%- 17s]",$element); 
            printf("<br>");  
          }
          while($row = mysqli_fetch_array($resultAtt2, MYSQLI_ASSOC))
          {            
            foreach ($row as $element)
              printf("[%- 10s]",$element);
            printf("<br>"); 
          }
          while($row = mysqli_fetch_array($resultAtt3, MYSQLI_ASSOC))
          {            
            foreach ($row as $element)
              printf("[%- 13s]",$element);  
              printf("<br>");  
          }
          print "</pre>";
          mysqli_free_result($result);                    
        }
        
        function getHenchmenDamage($conn, $village)
        {     
            $query = "select h.attackMultiplier * w.attack as totdmg from Human h  
            inner join Village v on h.Village_ID = v.VillageID inner join Weapon w on h.Weapon_Name=w.Name
            where h.role='Henchman' and v.name=";
            $query = $query."'".$village."';";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $totDmg = 0;
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {            
              foreach ($row as $element)
              {
                $totDmg += $element;
              }
            }
            mysqli_free_result($result);
            printf("HenchmenDmg: %s <br>", $totDmg);
            return $totDmg;
        }       
        
        function reduceHeroHealth($conn, $hero, $dmg)
        {
            // Get the old health
            $query = "select h.health, h.defenseMultiplier, w.defense from Human h inner join Weapon w on w.Name=h.Weapon_Name where h.firstName=";
            $query = $query."'".$hero."';"; 
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
            $dmg -= $row['defense'] * $row['defenseMultiplier'];
            if ($dmg < 0)
              $dmg = 0;    
            $newHp = $row['health'] - $dmg;   
            mysqli_free_result($result);
            printf("Hero's new HP should be: %s <br>", $newHp);  
            if ($newHp < 0)
            {
              $newHp = 0;
              printf("You killed %s! <br>", $hero);
            }              

            // Set the new health
            $query = "update Human h set h.health=";
            $query = $query."'".$newHp."'where h.firstName=";
            $query = $query."'".$hero."';";  
            mysqli_query($conn, $query) or die(mysqli_error($conn));   
        }

        function getAnimalDamage($conn, $animal, $hero)
        {
          if ($animal == "None")
            return 0;
          $query = "select a.attack from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN
          where a.species=";
          $query = $query."'".$animal."' and h.firstName="; 
          $query = $query."'".$hero."' ;"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          if (count($row) == 0)
          {
            printf("There is not animal of that species with that owner <br>"); 
            return 0; 
          }     
          else
          {
            printf("Animal's damage is: %s <br>", $row['attack']); 
            return $row['attack']; 
          }  
          mysqli_free_result($result);          
        }

        function reduceAnimalHealth($conn, $animal, $hero, $dmg)
        {
          $query = "select a.health, a.defense, h.SaladSN from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN
          where a.species=";
          $query = $query."'".$animal."' and h.firstName="; 
          $query = $query."'".$hero."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);   
          if (count($row) == 0)
          {
            printf("There is not animal of that species with that owner <br>"); 
            return;
          }
          else
          {
            $dmg -= $row['defense'];
            if ($dmg < 0)
              $dmg = 0; 
            $newHp = $row['health'] - $dmg;
            $SSN = $row['SaladSN'];
          }                
          mysqli_free_result($result);
          printf("Animal's new HP should be: %s <br>", $newHp);  

          // Set the new health
          $query = "update Animal a set a.health=";
          $query = $query."'".$newHp."' where a.species=";
          $query = $query."'".$animal."' and a.HumanOwnerSSN=";  
          $query = $query."'".$SSN."';"; 
          printf("Query: %s <br>", $query);
          mysqli_query($conn, $query) or die(mysqli_error($conn));   
        }

        function reduceHenchmanHealth($conn, $village, $dmg)
        {
            $query = "select h.health, h.SaladSN, h.defenseMultiplier, w.Name from Human h  
            inner join Village v on h.Village_ID=v.VillageID
            inner join Weapon w on w.Name=h.Weapon_Name
            where h.role='Henchman' and h.health > 0 and v.name=";
            $query = $query."'".$village."';";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $dmg -= $row['defense'] * $row['defenseMultiplier'];
            if ($dmg < 0)
              $dmg = 0;  
            $newHp = $row['health'] - $dmg;
            $SSN = $row['SaladSN'];  
            mysqli_free_result($result);  
            printf("First Henchman Health should be: %s <br>", $newHp); 
            if ($newHp < 0)
              $newHp = 0;
            
            // Set the new health
            $query = "update Human h set h.health=";
            $query = $query."'".$newHp."'where h.SaladSN=";
            $query = $query."'".$SSN."';"; 
            mysqli_query($conn, $query) or die(mysqli_error($conn));
        }

        function reduceBossHealth($conn, $dmg)
        {
          // Get the old health
          $query = "select h.health, h.defenseMultiplier, w.defense from Human h inner join Weapon w on h.Weapon_Name=w.Name where h.firstName='SaladoreTheTyrant'";
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);   
          $dmg -= $row['defense'] * $row['defenseMultiplier'];
          if ($dmg < 0)
            $dmg = 0;           
          $newHp =  $row['health'] - $dmg;       
          mysqli_free_result($result);
          printf("Boss' new HP should be: %s <br>", $newHp);  

          // Set the new health
          $query = "update Human h set h.health=";
          $query = $query."'".$newHp."'where h.firstName='SaladoreTheTyrant'";
          mysqli_query($conn, $query) or die(mysqli_error($conn));  
        }

        function getDamageTotal($conn, $human)
        {
          // Get the health
          $query = "select w.attack * h.attackMultiplier as dmg, h.health from Human h inner join Weapon w on h.Weapon_Name=w.Name where h.firstName=";
          $query = $query."'".$human."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);          
          $hp = $row['health'];
          if ($hp == 0)  
          {
            $dmg = 0; 
            printf("The hero is dead so...");  
          }                     
          else   
          {
            $dmg = $row['dmg'];
          }         
          mysqli_free_result($result);
          printf("Human's total Damage is: %s <br>", $dmg);  
          return $dmg;          
        }

        function allVillagesFreed($conn)
        {
          // Return true if every village is freed, false if not
          $query = "select count(*) as pop from Village v where v.status='suppressed' and v.VillageID<5;";
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          printf("Of the four villages, %s are still suppressed <br>", $row['pop']);
          if ($row['pop'] != 0)
            return false;
          else
            return true;
          mysqli_free_result($result);
        }
        function updateHeroPosition($conn, $hero, $village)
        {
          $query = "select v.VillageID from Village v where v.name=";
          $query = $query."'".$village."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);           

          $query = "update Human h set h.Village_ID=";
          $query = $query."'".$row['VillageID']."'where h.firstName="; 
          $query = $query."'".$hero."';"; 
          mysqli_query($conn, $query) or die(mysqli_error($conn));  
          printf("%s is now located at %s <br>", $hero, $village);
        }
        function getHeroPosition($conn, $hero)
        {
          $query = "select v.name from Human h inner join Village v on v.VillageID=h.Village_ID where h.firstName=";
          $query = $query."'".$hero."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          return $row['name'];
          mysqli_free_result($result);
          printf("%s is located at %s <br>");
        }

        function checkVillageStatus($conn, $village)
        {
          // Return true if $village status is freed, false if not
          if ($village == "HellCave")
          {
            $query = "select count(*) as total from Human h inner join Village v on h.Village_ID = v.VillageID 
            where v.name=";
            $query = $query."'".$village."' and h.role='Boss' and h.health>0";
          }
          else
          {
            $query = "select count(*) as total from Human h inner join Village v on h.Village_ID = v.VillageID 
            where v.name=";
            $query = $query."'".$village."' and h.role='Henchman' and h.health>0";
          }

          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $total = $row['total'];    
          mysqli_free_result($result);
          printf("Total: %s <br>", $total); 
          if ($total == 0)
          {
            //Get Village and set to freed
            $query = "update Village v set v.status='freed' where v.name=";
            $query = $query."'".$village."';";
            mysqli_query($conn, $query) or die(mysqli_error($conn)); 
            return true;
          }
          else
            return false;
        }
      ?>
    </section>
  </body>
</html>	  