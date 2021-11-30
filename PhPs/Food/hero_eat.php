<!--
Author: Kaiser
Date last edited: 11/29/2021
Purpose: Perform ?
-->

<?php
include('../../Connections/connectionDataStrong.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
?>

<html lang="en">
  <head>
    <title>CIS 451: Final Project</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../Styles/food_display.css?v=<?php echo time(); ?>">  
  </head>

  <body>
       <h1><img src="https://fontmeme.com/permalink/211117/d85fe5edb95db7398839f42d8ceff245.png"></h1> 
       <div id='display_form_div'>
       <?php displayHeroEat($conn); ?>
       </div>
       <div id="form_div">  
              <form action="./food.php" method="POST" id="hero_eat_form">                      
                     <label for="hero_eat_slct">Have </label>                     
                     <?php printHeroSelect($conn); ?>                          
                     <label for="hero_mushroom_slct"> eat </label>
                     <?php printHeroMushroomSelect($conn); ?>                     
                     <input formaction="./hero_eat.php" id="hero_eat_sub" type="submit" value="Eat Mushroom">
              </form>                     
              <form action="./food.php" method="POST" id="animal_eat_form">   
                     <label for="animal_eat_slct">Have </label>
                     <?php printAnimalSelect($conn); ?>    
                     <label for="animal_mushroom_slct"> eat </label>
                     <?php printAnimalMushroomSelect($conn); ?> 
                     <input formaction="./animal_eat.php" id="animal_eat_sub" type="submit" value="Eat Mushroom">
              </form> 
              <form action="./food.php" method="POST" id="hero_scavange_form">   
                     <label for="hero_scavange_slct"> Have</label>
                     <?php printHeroSelect($conn); ?> 
                     <label for="hero_scavange"> scavange for mushrooms</label>
                     <input id="hero_scavange_sub" type="submit" value="Scavange">
              </form> 
              <form action="./food.php" method="POST" id="animal_scavange_form">   
                     <label for="animal_scavange_slct"> Have</label>
                     <?php printAnimalSelect($conn); ?>
                     <label for="animal_scavange"> scavange for mushrooms</label>
                     <input id="animal_scavange_sub" type="submit" value="Scavange">
              </form> 
       </div>
  </body>
</html>

<?php
function printHeroSelect($conn)
{
       printf("<select name='hero_slct' id='hero_slct' onchange='this.form.submit()'>");

       $hero_slct = $_POST['hero_slct']; 
       if ($hero_slct == "")
              $hero_slct = "Mushronian";
       printf("<option value='%s'>%s</option>", $hero_slct, $hero_slct);
       
       $query = "select h.firstName from Human h where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['firstName'] != $hero_slct)
                     printf("<option value='%s'>%s</option>", $row['firstName'], $row['firstName']);                        
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
function printHeroMushroomSelect($conn)
{       
       printf("<select name='hero_mushroom_slct' id='hero_mushroom_slct' onchange='this.form.submit()'>");
      
       $hero_slct = $_POST['hero_slct']; 
       if ($hero_slct == "")
              $hero_slct = "Mushronian"; 
       $query = "select hf.Food_Name, hf.remaining from Human_has_Food hf 
       inner join Human h on h.SaladSN=hf.Human_SaladSN where h.firstName=";
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
              inner join Human h on h.SaladSN=hf.Human_SaladSN where h.firstName=";
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
function displayHeroEat($conn)
{
       $hero = $_POST['hero_slct']; 
       $mush = $_POST['hero_mushroom_slct'];  

       $query = "select hf.remaining from Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN       
       where h.firstName=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       $row = mysqli_fetch_array($result, MYSQLI_ASSOC);      
       $numush = $row['remaining'] - 1;    
       mysqli_free_result($result);

       if ($numush < 0)
       {
              printf("%s does not have any %s mushrooms to eat!", $hero, $mush);
              return;
       }              
       else
              printf("%s eats one of their %s mushrooms - resulting in there being %s left", $hero, $mush, $numush);

       $query = "update Human_has_Food hf inner join Human h on h.SaladSN=hf.Human_SaladSN 
       set hf.remaining=";
       $query = $query."'".$numush."' where h.firstName=";
       $query = $query."'".$hero."' and hf.Food_Name=";
       $query = $query."'".$mush."';";
       mysqli_query($conn, $query) or die(mysqli_error($conn));
}
?>
