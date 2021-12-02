<!--
Author: Kaiser
Date last edited: 11/26/2021
Purpose: Run user MySQL query
-->

<?php
include('../Connections/connectionData.txt');
$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');
?>

<html lang="en">
<head>
  <title>Another Simple PHP-MySQL Program</title>
  <link rel="stylesheet" href="../Styles/displayStyle.css?v=<?php echo time(); ?>"> 
  </head>  
  <body>
    <section>
      <a href="./index.php">Back to Home Page</a>
      <p> <h2>The query:</h2> <p>
      <?php
        $query = $_POST['query'];
        print $query;
      ?>

      <p> <h2>Result of query:</h2> <p>

      <?php
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

        print "<pre>";
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {  
          
          foreach ($row as $element)
            printf("[%- 8s]",$element);   
          print "<br>";
        }
        print "</pre>";
        mysqli_free_result($result);
        mysqli_close($conn);
      ?>
    </section>
  </body>
</html>	  