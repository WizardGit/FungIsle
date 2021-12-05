<!--
Author: Kaiser
Date last edited: 12/2/2021
Purpose: This is in charge of the hero_scavange feature.
-->

<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayHeroScavange($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayHeroScavange($conn)
{
       $hero = $_POST['hero_slct'];     

       // First check to make sure that our hero isn't in the midst of a fight
       $query = "select v.status from Human h inner join Village v on h.Village_ID=v.VillageID where h.name=";
       $query = $query."'".$hero."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       if ($row['status'] != "freed")
       {
              printf("%s cannot scavenge in the middle of a fight! Conquer all their enemies first! <br>", $hero);
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
       $query = "select hf.remaining from Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN       
       where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       // If we already a row for that mushroom for our animal, just update the "remaining" attribute  
       if ($row['remaining'] != null)
       {
              $numush = $row['remaining']+1;
              if ($numush > 5)
              {
                     printf("%s finds one %s mushroom - but their backpack is full, so they are forced to leave it behind.", $hero, $mush);      
                     return;
              }
              $query = "update Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN 
              set hf.remaining=";
              $query = $query."'".$numush."' where h.name=";
              $query = $query."'".$hero."' and hf.Food_Name=";
              $query = $query."'".$mush."';";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }   
       // Otherwise, create a new row and add it to our database           
       else
       {
              $query = "select h.SaladSN from Human h where h.name=";
              $query = $query."'".$hero."';";
              $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
              $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
              $SSN = $row['SaladSN'];

              $numush = 1;
              $query = "insert into Human_has_Food values (";
              $query = $query." ".$SSN.", ";
              $query = $query."'".$mush."', 1)";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }       
       printf("%s finds one %s mushroom - resulting in there now being %s %s mushroom(s) in their backpack", $hero, $mush, $numush, $mush);       
       mysqli_free_result($result);
}
?>