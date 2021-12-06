<!--
Author: Kaiser
Date last edited: 12/6/2021
Purpose: Performs an attack
-->

<?php include 'first.php';?>
<div id='display_form_div'>
<?php move($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function move($conn)
{
    $hero = $_POST['hero_slct']; 
    $village = $_POST['village_move_slct']; 

    $heroPos = getHeroPosition($conn, $hero);
    if (checkVillageStatus($conn, $heroPos) == false)
    {
        printf("%s cannot move to %s because they are in %s which has not yet been freed! <br>", $hero, $village, $heroPos);
    }
    updateHeroPosition($conn, $hero, $village);
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
?>