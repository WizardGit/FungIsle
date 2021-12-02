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
              printf("%s cannot scanvage in the middle of a fight! Conquer all their enemies first! <br>");
              return;
       }
       
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
       
       $query = "select hf.remaining from Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN       
       where h.name=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 

       if ($row['remaining'] != null)
       {
              $numush = $row['remaining']+1;
              $query = "update Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN 
              set hf.remaining=";
              $query = $query."'".$numush."' where h.name=";
              $query = $query."'".$hero."' and hf.Food_Name=";
              $query = $query."'".$mush."';";
              mysqli_query($conn, $query) or die(mysqli_error($conn));              
       }              
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