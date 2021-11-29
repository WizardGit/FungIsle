<!--
Author: Kaiser
Date last edited: 11/28/2021
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
    <link rel="stylesheet" href="../../Styles/food.css?v=<?php echo time(); ?>">  
  </head>

  <body>
       <section>
              <h1><img src="https://fontmeme.com/permalink/211117/d85fe5edb95db7398839f42d8ceff245.png"></h1>
              <!-- Font from: https://fontmeme.com/indiana-jones-font/ -->
       </section>
       <div id="form_div">  
              <form action="./food.php" method="POST" id="hero_eat_form">                      
                     <label for="hero_eat_slct">Have </label>                     
                     <?php printHeroSelect($conn); ?>                          
                     <label for="hero_mushroom_slct"> eat </label>
                     <?php printHeroMushroomSelect($conn); ?>                     
                     <input id="hero_eat_sub" type="submit" value="Eat Mushroom">

                     <label for="animal_eat_slct">Have </label>
                     <?php printAnimalSelect($conn); ?>    
                     <label for="animal_mushroom_slct"> eat </label>
                     <?php printAnimalMushroomSelect($conn); ?> 
                     <input id="animal_eat_sub" type="submit" value="Eat Mushroom">
              </form>  
              <form action="./food.php" method="POST" id="hero_scavange_form">   
                     <label for="hero_scavange_slct"> Have</label>
                     <?php printHeroSelect($conn); ?> 
                     <label for="hero_scavange"> scavange for mushrooms</label>
                     <input id="hero_scavange_sub" type="submit" value="Scavange">
              </form> 
              <form action="./PhPs/?.php" method="POST" id="animal_scavange_form">   
                     <label for="animal_scavange_slct"> Have</label>
                     <?php printAnimalSelect($conn); ?>
                     <label for="animal_scavange"> scavange for mushrooms</label>
                     <input id="animal_scavange_sub" type="submit" value="Scavange">
              </form> 
       </div>
       <div id="scavange_form_div">
              
       </div>
  </body>
</html>

<?php
function printHeroSelect($conn)
{
       printf("<select name='hero_slct' id='hero_slct' onchange='this.form.submit()'>");
       $query = "select h.firstName from Human h where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              printf("<option value='%s'>%s</option>", $row['firstName'], $row['firstName']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printAnimalSelect($conn)
{
       printf("<select name='animal_slct' id='animal_slct' onchange='this.form.submit()'>");
       $query = "select a.Name from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              printf("<option value='%s'>%s</option>", $row['Name'], $row['Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printHeroMushroomSelect($conn)
{
       
       $hero_slct = $_POST['hero_slct']; 
       printf("h:%s", $hero_slct);
       printf("<select name='mushroom_slct' id='mushroom_slct' >");
       $query = "select hf.Food_Name from Human_has_Food hf 
       inner join Human h on h.SaladSN=hf.Human_SaladSN
       where h.firstName=";
       $query = $query."'".$hero_slct."';";
       
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              printf("<option value='%s'>%s</option>", $row['Food_Name'], $row['Food_Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printAnimalMushroomSelect($conn)
{
       
       $animal_slct = $_POST['animal_slct']; 
       printf("h:%s", $animal_slct);
       printf("<select name='mushroom_slct' id='mushroom_slct' onchange=' '>");
       $query = "select hf.Food_Name from Animal_has_Food hf 
       inner join Animal a on a.Name=hf.Animal_Name
       where a.Name=";
       $query = $query."'".$animal_slct."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              printf("<option value='%s'>%s</option>", $row['Food_Name'], $row['Food_Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
?>



