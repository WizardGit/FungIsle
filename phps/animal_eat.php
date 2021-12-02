<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayAnimalEat($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayAnimalEat($conn)
{
       $animal = $_POST['animal_slct']; 
       $mush = $_POST['animal_mushroom_slct'];  

       $query = "select hf.remaining, a.health, f.health_recover from Animal_has_Food hf 
       inner join Animal a on a.Name=hf.Animal_Name
       inner join Food f on f.Name=hf.Food_Name       
       where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);      
       
       $numush = $row['remaining'] - 1;  
       $animalhealth = $row['health'];
       $healthrecover = $row['health_recover'];

       printf("%s's health is currently %s", $animal, $animalhealth);
       if ($numush < 0)
       {
              printf("%s does not have any %s mushrooms to eat!", $animal, $mush);
              return;
       }
       else if (($row['health'] == 0) && ($mush !="Almighty"))
       {
              printf("%s is dead. They cannot eat anything! (Unless it's an Almighty Mushroom which can revive them!)", $hero);
              return;
       }             
       else
              printf("%s eats one of their %s mushrooms - resulting in there being %s left", $animal, $mush, $numush);
       $animalhealth += $healthrecover;
       if ($animalhealth > 200)
              $animalhealth = 200;
       printf("%s's health is now %s", $animal, $animalhealth);
       
       // Update Hero heatlh
       $query = "update Animal a set a.health=";
       $query = $query."".$animalhealth." where a.Name="; 
       $query = $query."'".$animal."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
       mysqli_free_result($result);

       $query = "update Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name 
       set hf.remaining=";
       $query = $query."'".$numush."' where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));          
       mysqli_free_result($result);
}
?>
