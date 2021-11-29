<!--
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Perform ?
-->

<?php
include('../Connections/connectionDataStrong.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>CIS 451: Final Project</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Styles/food.css" />  
    <script src="index.js" ></script>  
  </head>

  <body>
       <section>
              <h1><img src="https://fontmeme.com/permalink/211117/d85fe5edb95db7398839f42d8ceff245.png"></h1>
              <!-- Font from: https://fontmeme.com/indiana-jones-font/ -->
       </section>
<!-- 
hero scavage for mushroom
animal scavage...
hero eat mushroom...
animal eat mushroom..


-->
       <div id="form_div">  
              <form action="./PhPs/?.php" method="POST" id="hero_eat_form">   
                     <label for="hero_eat_slct">Have </label>
                     <select name="hero_eat_slct" id="hero_eat_slct" onchange=" ">
                     <?php
                     $query = "select h.firstName from Human h where h.role='Hero'";
                     $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
                     while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                     {   
                            printf("<option value='%s'>%s</option>", $row['firstName']);                        
                     }            
                     mysqli_free_result($result);
                     ?>
                            <!-- insert php here to add to the list-->
                            <option value="Mushronian">Mushronian</option>
                            <option value="Amanita">Amanita</option>
                     </select>     
                     <label for="hero_mushroom_slct"> eat </label>
                     <select name="hero_mushroom_slct" id="hero_mushroom_slct" onchange=" ">
                            <option value="?">mush3</option>
                            <option value="?">mush1</option>
                            <option value="?">mush2</option>                            
                     </select>
                     <input id="hero_eat_sub" type="submit" value="Eat Mushroom">
              </form>  
              <form action="./PhPs/?.php" method="POST" id="animal_eat_form">   
                     <label for="animal_eat_slct">Have </label>
                     <select name="animal_eat_slct" id="animal_eat_slct" onchange=" ">
                            <!-- insert php here to add to the list-->
                            <option value="Mushronian">Fungivore</option>
                            <option value="Amanita">Portabelly</option>
                     </select>     
                     <label for="animal_mushroom_slct"> eat </label>
                     <select name="animal_mushroom_slct" id="animal_mushroom_slct" onchange=" ">
                            <option value="?">mush3</option>
                            <option value="?">mush1</option>
                            <option value="?">mush2</option>                            
                     </select>
                     <input id="animal_eat_sub" type="submit" value="Eat Mushroom">
              </form>
              <form action="./PhPs/?.php" method="POST" id="hero_scavange_form">   
                     <label for="hero_scavange_slct"> Have</label>
                     <select name="hero_scavange_slct" id="hero_scavange_slct" onchange=" ">
                            <!-- insert php here to add to the list-->
                            <option value="Mushronian">Mushronian</option>
                            <option value="Amanita">Amanita</option>
                     </select>
                     <label for="hero_scavange"> scavange for mushrooms</label>
                     <input id="hero_scavange_sub" type="submit" value="Scavange">
              </form> 
              <form action="./PhPs/?.php" method="POST" id="animal_scavange_form">   
                     <label for="animal_scavange_slct"> Have</label>
                     <select name="animal_scavange_slct" id="animal_scavange_slct" onchange=" ">
                            <!-- insert php here to add to the list-->
                            <option value="Mushronian">Fungivore</option>
                            <option value="Amanita">Portabelly</option>
                     </select>
                     <label for="animal_scavange"> scavange for mushrooms</label>
                     <input id="animal_scavange_sub" type="submit" value="Scavange">
              </form> 
       </div>
       <div id="scavange_form_div">
              
       </div>
  </body>
</html>









