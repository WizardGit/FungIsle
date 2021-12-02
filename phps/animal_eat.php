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

       $query = "select hf.remaining from Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name       
       where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);      
       $numush = $row['remaining'] - 1;    
       mysqli_free_result($result);

       if ($numush < 0)
       {
              printf("%s does not have any %s mushrooms to eat!", $animal, $mush);
              return;
       }              
       else
              printf("%s eats one of their %s mushrooms - resulting in there being %s left", $animal, $mush, $numush);

       $query = "update Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name 
       set hf.remaining=";
       $query = $query."'".$numush."' where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
}
?>
