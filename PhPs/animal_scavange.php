<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayAnimalScavange($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayAnimalScavange($conn)
{
       $animal = $_POST['animal_slct'];    
       
       $query = "select count(*) as total from Food f;";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
       $total = $row['total'];

       $query = "select f.Name from Food f;";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
      
       $num = rand(1, $total);

       if ($num == 1)
              $mush = $row['Name'];
       $counter = 2;
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($num == $counter)
                     $mush = $row['Name'];
              $counter++;
       } 
       
       $query = "select hf.remaining from Animal_has_Food hf inner join Animal a on a.Name=hf.Animal_Name       
       where a.Name=";
       $query = $query."'".$animal."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
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
       else
       {
              $numush = 1;
              $query = "insert into Animal_has_Food values (";
              $query = $query."'".$animal."', ";
              $query = $query."'".$mush."', 1)";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }       
       printf("%s finds one %s mushroom - resulting in there now being %s mushroom(s) in the backpack", $animal, $mush, $numush);       
       mysqli_free_result($result);
}
?>