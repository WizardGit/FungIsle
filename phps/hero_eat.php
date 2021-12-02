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

       $query = "select hf.remaining, h.health from Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN       
       where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);      
       $numush = $row['remaining'] - 1;          

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

       $query = "update Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN 
       set hf.remaining=";
       $query = $query."'".$numush."' where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
       mysqli_free_result($result);
}
?>
