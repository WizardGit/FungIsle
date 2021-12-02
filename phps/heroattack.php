<?php include 'first.php';?>
<div id='display_form_div'>
<?php attack($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function attack($conn)
{
  $hero = $_POST['hero_slct']; 
  $village = $_POST['village_slct']; 
  $animal = $_POST['animal_slct']; 
  if (checkVillageStatus($conn, $village) == false)
  {
    $heroPos = getHeroPosition($conn, $hero);
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
        updateFights($conn, $hero, $village, $animal);
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
      updateFights($conn, $hero, $village, $animal);
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
}
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
  printf("The henchmen in %s deal %s damage <br>", $village, $totDmg);
  return $totDmg;
  mysqli_free_result($result);
}                
               
        
function reduceHeroHealth($conn, $hero, $dmg)
{
  // Get the old health
  $query = "select h.health, h.defenseMultiplier, w.defense from Human h inner join Weapon w on w.Name=h.Weapon_Name where h.name=";
  $query = $query."'".$hero."';"; 
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
  printf("%s gets hit with %s damage but blocks up to %s ", $hero, $dmg, $row['defense'] * $row['defenseMultiplier']);   
  $dmg -= $row['defense'] * $row['defenseMultiplier'];
  if ($dmg < 0)
    $dmg = 0;    
  $newHp = $row['health'] - $dmg;   
  mysqli_free_result($result);
  printf("so that %s's new health is %s <br>", $hero, $newHp);  
  if ($newHp < 0)
  {
    $newHp = 0;
    printf("You killed %s! <br>", $hero);
  }              

  // Set the new health
  $query = "update Human h set h.health=";
  $query = $query."'".$newHp."'where h.name=";
  $query = $query."'".$hero."';";  
  mysqli_query($conn, $query) or die(mysqli_error($conn));   
}        

        function getAnimalDamage($conn, $animal, $hero)
        {
          if ($animal == "None")
            return 0;
          $query = "select a.species, a.attack from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN
          where a.name=";
          $query = $query."'".$animal."' and h.name="; 
          $query = $query."'".$hero."' ;"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          if ($row == null)
          {
            printf("There is not animal of that species with that owner <br>"); 
            return 0; 
          }     
          else
          {            
            printf("%s, a %s, deals %s damage <br>", $animal, $row['species'], $row['attack']);  
            return $row['attack']; 
          }  
          mysqli_free_result($result);          
        }

        function reduceAnimalHealth($conn, $animal, $hero, $dmg)
        {
          $query = "select a.species, a.health, a.defense, h.SaladSN from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN
          where a.name=";
          $query = $query."'".$animal."' and h.name="; 
          $query = $query."'".$hero."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
          printf("%s, a %s, gets hit with %s damage but blocks up to %s", $animal, $row['species'], $dmg, $row['defense']);   
          if (count($row) == 0)
          {
            printf("There is not animal of that name with that owner <br>"); 
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
          printf("so that its new health is %s <br>", $newHp); 
          mysqli_free_result($result); 

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
            $query = "select h.health, h.SaladSN, w.defense, h.defenseMultiplier, w.Name from Human h  
            inner join Village v on h.Village_ID=v.VillageID
            inner join Weapon w on w.Name=h.Weapon_Name
            where h.role='Henchman' and h.health > 0 and v.name=";
            $query = $query."'".$village."';";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            printf("The first henchmen in %s gets hit with %s damage but blocks up to %s ", $village, $dmg, $row['defense'] * $row['defenseMultiplier']);   
            $dmg -= $row['defense'] * $row['defenseMultiplier'];
            if ($dmg < 0)
              $dmg = 0;  
            $newHp = $row['health'] - $dmg;
            $SSN = $row['SaladSN'];  
            mysqli_free_result($result);  
            printf("so that their new health is %s <br>", $newHp); 
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
          $query = "select h.health, h.defenseMultiplier, w.defense from Human h inner join Weapon w on h.Weapon_Name=w.Name where h.name='SaladoreTheTyrant'";
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          printf("Saladore is hit with %s damage, but blocks up to %s ", $dmg, $row['defense'] * $row['defenseMultiplier']);      
          $dmg -= $row['defense'] * $row['defenseMultiplier'];
          if ($dmg < 0)
            $dmg = 0;           
          $newHp =  $row['health'] - $dmg;       
          mysqli_free_result($result);
          printf("so that his new health is %s <br>", $newHp);  

          // Set the new health
          $query = "update Human h set h.health=";
          $query = $query."'".$newHp."'where h.name='SaladoreTheTyrant'";
          mysqli_query($conn, $query) or die(mysqli_error($conn));  
        }

        function getDamageTotal($conn, $human)
        {
          // Get the health
          $query = "select w.attack * h.attackMultiplier as dmg, h.health from Human h inner join Weapon w on h.Weapon_Name=w.Name where h.name=";
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
          printf("%s's deals damage totalling %s <br>", $human, $dmg);  
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
          $newVID = $row['VillageID'];

          $query = "select v.Type, vl.Name from Vehicle v 
          inner join Human h on h.SaladSN=v.Human_SaladSN 
          inner join Village vl on h.Village_ID=vl.VillageID where h.name="; 
          $query = $query."'".$hero."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          if ($row['Name'] != $village)
          {
            printf("%s drives their %s to %s <br>", $hero, $row['Type'], $village);
          }         

          $query = "update Human h set h.Village_ID=";
          $query = $query."'".$newVID."'where h.name="; 
          $query = $query."'".$hero."';"; 
          mysqli_query($conn, $query) or die(mysqli_error($conn));
        }
        function getHeroPosition($conn, $hero)
        {
          $query = "select v.name from Human h inner join Village v on v.VillageID=h.Village_ID where h.name=";
          $query = $query."'".$hero."';"; 
          $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
          printf("%s is located at %s <br>", $hero, $row['name']);  
          return $row['name'];
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
          printf("There are %s henchmen at %s<br>", $total, $village); 
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

function updateFights($conn, $hero, $village, $animal)
{
  $query = "select h.SaladSN, h.health from Human h where h.name=";
  $query = $query."'".$hero."';"; 
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $hSSN = $row['SaladSN'];
  $hHealth = $row['health'];

  $query = "select h.SaladSN, h.health from Human h inner join Village v on v.VillageID=h.Village_ID where v.name=";
  $query = $query."'".$village."';"; 
  $result = mysqli_query($conn, $query) or die(mysqli_error($conn);
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
  {
    $vSSN = $row['SaladSN'];
    $vHealth = $row['health'];
    //if there is no data entry, create one
    $q = "select hv.Human_VictorSSN from Human_fights_Human hv
    inner join Human t on hv.Human_Fighter1SSN=";
    $q = $q."".$hSSN." where hv.Human_Fighter2SSN = (
    select h.SaladSN from Human h where h.SaladSN="; 
    $q = $q."".$vSSN.")";
    $r = mysqli_query($conn, $q) or die(mysqli_error($conn));
    $rowNew = mysqli_fetch_array($r, MYSQLI_ASSOC);    

    if (($vSSN == 0) && ($hSSN == 0))
    {
      //victor doesn't change - stays whatever it is
    }
    else if (($vSSN > 0) && ($hSSN == 0))
    {
      //victor is vSSN
      if($rowNew == null)
      {
        $q2 = "insert into Human_fights_Human values (";
        $q2 = $q2."".$hSSN." ,"; 
        $q2 = $q2."".$vSSN.", ";
        $q2 = $q2."".$animal.", ";
        $q2 = $q2."".$vSSN.");";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
      else 
      {
        //update
        $q2 = "update Human_fights_Human hv set hv.Human_VictorSSN =";
        $q2 = $q2."".$vSSN." where hv.Human_Fighter1SSN=";
        $q2 = $q2."".$hSSN." and hv.Human_Fighter2SSN="; 
        $q2 = $q2."".$vSSN.", ";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
    }
    else if (($vSSN == 0) && ($hSSN > 0))
    {
      //victor is hSSN
      if($rowNew == null)
      {
        $q2 = "insert into Human_fights_Human values (";
        $q2 = $q2."".$hSSN." ,"; 
        $q2 = $q2."".$vSSN.", ";
        $q2 = $q2."".$animal.", ";
        $q2 = $q2."".$hSSN.");";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
      else 
      {
        //update
        $q2 = "update Human_fights_Human hv set hv.Human_VictorSSN =";
        $q2 = $q2."".$hSSN." where hv.Human_Fighter1SSN=";
        $q2 = $q2."".$hSSN." and hv.Human_Fighter2SSN="; 
        $q2 = $q2."".$vSSN.", ";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
    }
    else if (($vSSN > 0) && ($hSSN > 0))
    {
      //victor is -1
      if($rowNew == null)
      {
        $q2 = "insert into Human_fights_Human values (";
        $q2 = $q2."".$hSSN." ,"; 
        $q2 = $q2."".$vSSN.", ";
        $q2 = $q2."".$animal.", 0);";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
      else 
      {
        //update
        $q2 = "update Human_fights_Human hv set hv.Human_VictorSSN = 0 where hv.Human_Fighter1SSN=";
        $q2 = $q2."".$hSSN." and hv.Human_Fighter2SSN="; 
        $q2 = $q2."".$vSSN.", ";
        mysqli_query($conn, $q2) or die(mysqli_error($conn);
      }
    }
  }
}
?>      
        