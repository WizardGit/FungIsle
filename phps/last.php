<!--
Author: Kaiser
Date last edited: 12/2/2021
Purpose: This is part of the main game html structure
and the accompanying php methods
-->

<div id="form_div">  
       <form action="./index.php" method="POST" id="move_form">   
              <label for="hero_move_slct">Choose hero</label>
              <?php printHeroSelect($conn); ?> 
              <label for="village_move_slct">to move to</label>
              <?php printVillageMoveSelect($conn); ?>
              <input formaction="./heromove.php" id="move" type="submit" value="Move">
       </form>
       <form action="./index.php" method="POST" id="attack_form">   
              <label for="hero_attack_slct">Choose hero</label>
              <?php printHeroSelect($conn); ?> 
              <label for="villages_attack_slct">to attack henchmen in</label>
              <?php printVillageAttackSelect($conn); ?>
              <label for="animal_attack_slct">with the help of</label>
              <?php printAnimalSelect($conn); ?> 
              <input formaction="./heroattack.php" id="attack" type="submit" value="Attack">
       </form>
       <form action="./index.php" method="POST" id="hero_eat_form">                      
              <label for="hero_eat_slct">Have </label>                     
              <?php printHeroSelect($conn); ?>                          
              <label for="hero_mushroom_slct"> eat </label>
              <?php printHeroMushroomSelect($conn); ?>                     
              <input formaction="./hero_eat.php" id="hero_eat_sub" type="submit" value="Eat Mushroom">
       </form>                     
       <form action="./index.php" method="POST" id="animal_eat_form">   
              <label for="animal_eat_slct">Have </label>
              <?php printAnimalSelect($conn); ?>    
              <label for="animal_mushroom_slct"> eat </label>
              <?php printAnimalMushroomSelect($conn); ?> 
              <input formaction="./animal_eat.php" id="animal_eat_sub" type="submit" value="Eat Mushroom">
       </form> 
       <form action="./index.php" method="POST" id="hero_scavenge_form">   
              <label for="hero_scavenge_slct"> Have</label>
              <?php printHeroSelect($conn); ?> 
              <label for="hero_scavenge"> scavenge for mushrooms</label>
              <input formaction="./hero_scavenge.php" id="hero_scavenge_sub" type="submit" value="Scavenge">
       </form> 
       <form action="./index.php" method="POST" id="animal_scavenge_form">   
              <label for="animal_scavenge_slct"> Have</label>
              <?php printAnimalSelect($conn); ?>
              <label for="animal_scavenge"> scavenge for mushrooms</label>
              <input formaction="./animal_scavenge.php" id="animal_scavenge_sub" type="submit" value="Scavenge">
       </form> 
       </div>
       <div id="codeview">
              <p>
              You can view the files for this project <a href="https://github.com/WizardGit/FungIsle">HERE</a>.
              </p>
              <p>
                     Game Guide is <a href="../guide.html">HERE</a>
              </p>
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
       
       $query = "select h.name from Human h where h.role='Hero'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $hero_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
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
function printAnimalAttackSelect($conn)
{
       printf("<select name='animal_attack_slct' id='animal_attack_slct' onchange='this.form.submit()'>");

       $animal_slct = $_POST['animal_slct']; 
       $hero_slct = $_POST['hero_slct'];
       if ($animal_slct  == "")
              $animal_slct  = "Bat";
       printf("<option value='%s'>%s</option>", $animal_slct, $animal_slct);

       $query = "select a.Name from Animal a inner join Human h on a.HumanOwnerSSN=h.SaladSN where h.name=";
       $query = $query."'".$hero_slct."';";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['Name'] != $animal_slct)
                     printf("<option value='%s'>%s</option>", $row['Name'], $row['Name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printVillageMoveSelect($conn)
{
       printf("<select name='village_move_slct' id='village_move_slct' onchange='this.form.submit()'>");

       $village_slct = $_POST['village_slct']; 
       if ($village_slct  == "")
              $village_slct  = "TreeBase";
       printf("<option value='%s'>%s</option>", $village_slct, $village_slct);

       $query = "select v.name from Village v";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $village_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
       }            
       mysqli_free_result($result);
       printf("</select>");
}
function printVillageAttackSelect($conn)
{
       printf("<select name='village_attack_slct' id='village_attack_slct' onchange='this.form.submit()'>");

       $village_slct = $_POST['village_slct']; 
       if ($village_slct  == "")
              $village_slct  = "Northland";
       printf("<option value='%s'>%s</option>", $village_slct, $village_slct);

       $query = "select v.name from Village v where v.status='suppressed'";
       $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
       while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
       {   
              if ($row['name'] != $village_slct)
                     printf("<option value='%s'>%s</option>", $row['name'], $row['name']);                        
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
       inner join Human h on h.SaladSN=hf.Human_SaladSN where h.name=";
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
              inner join Human h on h.SaladSN=hf.Human_SaladSN where h.name=";
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
?>