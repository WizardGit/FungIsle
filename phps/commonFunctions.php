<!--
Author: Kaiser
Date last edited: 12/6/2021
Purpose: Common Functions
-->

<?php
function printHeroSelect($conn)
{
       printf("<select name='hero_slct' id='hero_slct' onchange='this.form.submit()'>");

       $hero_slct = $_POST['hero_slct']; 
       if ($hero_slct == "")
              $hero_slct = "Mushronian";
       printf("<option value='%s'>%s</option>", $hero_slct, $hero_slct);
       
       $query = "select h.name from Human h where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $hero_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printAnimalSelect($conn)
{
       printf("<select name='animal_slct' id='animal_slct' onchange='this.form.submit()'>");

       $animal_slct = $_POST['animal_slct']; 
       if ($animal_slct  == "")
              $animal_slct  = "Bat";
       printf("<option value='%s'>%s</option>", $animal_slct, $animal_slct);

       $query = "select a.Name from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['Name'] != $animal_slct)
                     printf("<option value='%s'>%s</option>", $row['Name'], $row['Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printAnimalAttackSelect($conn)
{
       printf("<select name='animal_attack_slct' id='animal_attack_slct' onchange='this.form.submit()'>");

       $animal_slct = $_POST['animal_attack_slct']; 
       $hero_slct = $_POST['hero_slct'];
       if ($hero_slct == "")
              $hero_slct = "Mushronian";              
       if ($animal_slct  == "")
              $animal_slct  = "Bat";
       if (herohasanimal($conn, $hero_slct, $animal_slct) == true)
              printf("<option value='%s'>%s</option>", $animal_slct, $animal_slct);

       $query = "select a.Name from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN where h.name=";
       $query = $query."'".$hero_slct."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['Name'] != $animal_slct)
                     printf("<option value='%s'>%s</option>", $row['Name'], $row['Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function herohasanimal($conn, $hero, $animal)
{
       $query = "select a.Name from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN where h.name=";
       $query = $query."'".$hero."' and a.Name=";
       $query = $query."'".$animal."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       if (mysqli_fetch_array($result, MYSQLI_ASSOC) == "")
              return false;
       return true;
}
function printVillageMoveSelect($conn)
{
       printf("<select name='village_move_slct' id='village_move_slct' onchange='this.form.submit()'>");

       $village_slct = $_POST['village_move_slct']; 
       if ($village_slct  == "")
              $village_slct  = "TreeBase";
       printf("<option value='%s'>%s</option>", $village_slct, $village_slct);

       $query = "select v.name from Village v";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $village_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printVillageAttackSelect($conn)
{
       printf("<select name='village_attack_slct' id='village_attack_slct' onchange='this.form.submit()'>");

       $village_slct = $_POST['village_attack_slct']; 
       if ($village_slct  == "")
              $village_slct  = "Northland";
       if (checkVillageStatus($conn, $village_slct) == false)
              printf("<option value='%s'>%s</option>", $village_slct, $village_slct);

       $query = "select v.name from Village v where v.status='suppressed'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $village_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printHeroMushroomSelect($conn)
{       
       printf("<select name='hero_mushroom_slct' id='hero_mushroom_slct' onchange='this.form.submit()'>");
      
       $hero_slct = $_POST['hero_slct']; 
       if ($hero_slct == "")
              $hero_slct = "Mushronian"; 
       $query = "select hf.Food_Name, hf.remaining from Human_has_Food hf 
       inner join Human h on h.SaladSN=hf.Human_SaladSN where h.name=";
       $query = $query."'".$hero_slct."';";       
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
       if($row == null)
       {
              printf("<option value='none'>none</option>"); 
              printf("</select>");
              return;
       }
       $mushroom_slct = $_POST['hero_mushroom_slct']; 
       if($mushroom_slct  != "")
       {
              $q = "select hf.remaining from Human_has_Food hf 
              inner join Human h on h.SaladSN=hf.Human_SaladSN where h.name=";
              $q = $q."'".$hero_slct."' and hf.Food_Name=";
              $q = $q."'".$mushroom_slct."';";
              $r = mysqli_query($conn, $q) or die(mysqli_error($conn));
              $rw = mysqli_fetch_array($r, MYSQLI_ASSOC);
              if ($rw != null)
                     printf("<option value='%s'>%s (%s)</option>", $mushroom_slct, $mushroom_slct, $rw['remaining']); 
              mysqli_free_result($r);
       }
       do {
              if ($row['Food_Name'] != $mushroom_slct)
                     printf("<option value='%s'>%s (%s)</option>", $row['Food_Name'], $row['Food_Name'], $row['remaining']);                        
       }while($row = mysqli_fetch_array($result, MYSQLI_ASSOC));
                      
       mysqli_free_result($result);
       printf("</select>");
}
function printAnimalMushroomSelect($conn)
{       
       printf("<select name='animal_mushroom_slct' id='animal_mushroom_slct' onchange='this.form.submit()'>");
              
       $animal_slct = $_POST['animal_slct']; 
       if ($animal_slct == "")
              $animal_slct = "Bat";
       $query = "select hf.Food_Name, hf.remaining from Animal_has_Food hf 
       inner join Animal a on a.Name=hf.Animal_Name where a.Name=";
       $query = $query."'".$animal_slct."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
       if($row == null)
       {
              printf("<option value='none'>none</option>"); 
              printf("</select>");
              return;
       }
       $mushroom_slct = $_POST['animal_mushroom_slct']; 
       if($mushroom_slct  != "")
       {
              $q = "select hf.remaining from Animal_has_Food hf 
              inner join Animal a on a.Name=hf.Animal_Name where a.Name=";
              $q = $q."'".$animal_slct."' and hf.Food_Name=";
              $q = $q."'".$mushroom_slct."';";
              $r = mysqli_query($conn, $q) or die(mysqli_error($conn));
              $rw = mysqli_fetch_array($r, MYSQLI_ASSOC);
              if ($rw != null)
                     printf("<option value='%s'>%s (%s)</option>", $mushroom_slct, $mushroom_slct, $rw['remaining']); 
              mysqli_free_result($r);
       }
       do {
              if ($row['Food_Name'] != $mushroom_slct)
                     printf("<option value='%s'>%s (%s)</option>", $row['Food_Name'], $row['Food_Name'], $row['remaining']);                        
       }while($row = mysqli_fetch_array($result, MYSQLI_ASSOC));
                      
       mysqli_free_result($result);
       printf("</select>");
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
?>