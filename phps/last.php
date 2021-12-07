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
              <?php printAnimalAttackSelect($conn); ?> 
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
              You can view the files/documentation for this project <a href="https://github.com/WizardGit/FungIsle">HERE</a>.
              </p>
              <p>
                     Game Guide is <a href="../guide.html">HERE</a>
              </p>
       </div> 
  </body>
</html>
