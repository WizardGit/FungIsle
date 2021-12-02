<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayHeroEat($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayHeroEat($conn)
{
       $hero = $_POST['hero_slct']; 
       $mush = $_POST['hero_mushroom_slct'];  

       $query = "select hf.remaining, h.health, f.health_recover from Human_has_Food hf 
       inner join Human h on h.SaladSN=hf.Human_SaladSN 
       inner join Food f on f.Name=hf.Food_Name
       where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."' and ;";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 

       $numush = $row['remaining'] - 1;  
       $herohealth = $row['health'];
       $healthrecover = $row['health_recover'];

       printf("%s's health is currently %s", $hero, $herohealth);
       if ($numush < 0)
       {
              printf("%s does not have any %s mushrooms to eat!", $hero, $mush);
              return;
       }
       else if (($row['health'] == 0) && ($mush !="Almighty"))
       {
              printf("%s is dead. They cannot eat anything! (Unless it's an Almighty Mushroom which can revive them!)", $hero);
              return;
       }                     
       else
              printf("%s eats one of their %s mushrooms - resulting in there being %s left", $hero, $mush, $numush);
       $herohealth += $healthrecover;
       if ($herohealth > 1000)
              $herohealth = 1000;
       printf("%s's health is now %s", $hero, $herohealth);

       // Update Hero heatlh
       $query = "update Human h set h.health="
       $query = $query."".$herohealth." where h.name="; 
       $query = $query."'".$hero."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
       mysqli_free_result($result);

       // Update Human_has_Food table
       $query = "update Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN 
       set hf.remaining=";
       $query = $query."'".$numush."' where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
       mysqli_free_result($result);
}
?>
