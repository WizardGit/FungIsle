<div id="form_div">  
              <p><a id="button" href="../HTMLs/index.html">Back to Home Page</a> </p>
              <form action="./index.php" method="POST" id="attack_form">   
                     <label for="hero_attack_slct">Choose hero:</label>
                     <?php printHeroSelect($conn); ?> 
                     <label for="villages_attack_slct">To attack henchmen in: </label>
                     <?php printVillageSelect($conn); ?>
                     <label for="animal_attack_slct">With the help of: </label>
                     <?php printAnimalSelect($conn); ?> 
                     <input formaction="./attack.php" id="attack" type="submit" value="Attack">
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
              <form action="./index.php" method="POST" id="hero_scavange_form">   
                     <label for="hero_scavange_slct"> Have</label>
                     <?php printHeroSelect($conn); ?> 
                     <label for="hero_scavange"> scavange for mushrooms</label>
                     <input formaction="./hero_scavange.php" id="hero_scavange_sub" type="submit" value="Scavange">
              </form> 
              <form action="./index.php" method="POST" id="animal_scavange_form">   
                     <label for="animal_scavange_slct"> Have</label>
                     <?php printAnimalSelect($conn); ?>
                     <label for="animal_scavange"> scavange for mushrooms</label>
                     <input formaction="./animal_scavange.php" id="animal_scavange_sub" type="submit" value="Scavange">
              </form> 
       </div>
       <div id="codeview">
              <p>
              You can view the files for this project <a href="https://github.com/WizardGit/FungIsle">HERE</a>.
              </p>
              <p>
                     Game Guide is <a href="guide.html">HERE</a>
              </p>
       </div> 
  </body>
</html>
