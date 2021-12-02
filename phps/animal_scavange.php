/*
Author: Kaiser
Date last edited: 12/2/2021
Purpose: This is in charge of the animal_scavange feature
*/

<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayAnimalScavange($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayAnimalScavange($conn)
{
       $animal = $_POST['animal_slct'];   
       
       // First check to make sure that our animal isn't in the midst of a fight
       $query = "select v.status from Animal a
       inner join Human h on h.SaladSN=a.HumanOwnerSSN
       inner join Village v on h.Village_ID=v.VillageID where a.name=";
       $query = $query."'".$animal."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       if ($row['status'] != "freed")
       {
              printf("%s cannot scanvage in the middle of a fight! Conquer all their enemies first! <br>", $animal);
              return;
       }
       
       // Get the total number of mushrooms in our database
       $query = "select count(*) as total from Food f;";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       $total = $row['total'];

       // Get a random mushroom from our database
       // If there is no mushrooms in our database, it $mush will simply be equal to ""
       $query = "select f.Name from Food f;";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $num = rand(1, $total);
       $mush = "";
       $counter = 1;
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($num == $counter)
                     $mush = $row['Name'];
              $counter++;
       } 
       
       // Get the number of mushrooms that we have of our randomly chosen type
       $query = "select hf.remaining from Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name       
       where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       // If we already a row for that mushroom for our animal, just update the "remaining" attribute       
       if ($row['remaining'] != null)
       {
              $numush = $row['remaining']+1;
              $query = "update Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name 
              set hf.remaining=";
              $query = $query."'".$numush."' where a.Name=";
              $query = $query."'".$animal."' and hf.Food_Name=";
              $query = $query."'".$mush."';";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }     
       // Otherwise, create a new row and add it to our database      
       else
       {
              $numush = 1;
              $query = "insert into Animal_has_Food values (";
              $query = $query."'".$animal."', ";
              $query = $query."'".$mush."', 1)";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }       
       printf("%s finds one %s mushroom - resulting in there now being %s %s mushroom(s) in their backpack", $animal, $mush, $numush, $mush);      
       mysqli_free_result($result);
}
?>