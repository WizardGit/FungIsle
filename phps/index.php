<?php include 'first.php';?>
<div id='display_form_div'>
<?php displayPics($conn); ?>
</div> 
<?php include 'last.php';?>
<?php
function displayPics($conn)
{
       $hero_slct = $_POST['hero_slct'];
       $village_slct = $_POST['village_slct'];
       $animal_slct = $_POST['animal_slct'];
       if($hero_slct == 'Mushronian')  
              printf("<img id='h' src='../Characters/Mushronian.png'>");  
       else 
              printf("<img id='h' src='../Characters/Amanita.png'>");
       if($animal_slct == 'Bat')  
              printf("<p><img id='a' src='../Characters/Fungivore.png'>VS</p>");  
       else 
              printf("<p>VS</p>");
       if($village_slct == 'HellCave')  
              printf("<img id='v' src='../Characters/Saladore.png'>");  
       else 
              printf("<img id='v' src='../Characters/Saladorian.png'>");
}
?>
