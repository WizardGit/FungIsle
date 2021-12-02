<!--
Author: Kaiser
Date last edited: 12/1/2021
Purpose: Perform ?
-->

<?php
include('../Connections/connectionDataStrong.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
?>

<html lang="en">
  <head>
    <title>CIS 451: Final Project</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../Styles/index.css?v=<?php echo time(); ?>">  
  </head>

  <body>
       <section>
              <h1><img src="https://fontmeme.com/permalink/211117/d85fe5edb95db7398839f42d8ceff245.png"></h1>
              <!-- Font from: https://fontmeme.com/indiana-jones-font/ -->
              <h2>Query Current Events!</h2>                            
              <form action="./queryDB.php" method="POST">       
                     <input type="text" name="query">
                     <input type="submit" value="Submit">
                     <input type="reset" value="Erase">
              </form> 
              <form action="../phps/resetDB.php" method="POST">  
                     <input id="reset" type="submit" value="Reset">
              </form> 
       </section>