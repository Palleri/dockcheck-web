<?php 
    ini_set('max_execution_time', '300');
    set_time_limit(300);
function bg()
{
  $create_file_update = fopen("/var/www/update.txt", "w") or die("Unable to open file!");
  $txt = '1';
  fwrite($create_file_update, $txt);
  $read_file = file_get_contents('/var/www/update.txt');
  if($read_file == '1'){
    while($read_file == '1'){
      $read_file = file_get_contents('/var/www/update.txt');
      if($read_file == '1'){
      flush();
      }else{
      $url = $_SERVER['REQUEST_URI'];
      $url_stripped = str_replace("?update", "", $url);
      echo "<script>window.location = '$url_stripped'</script>";
      }
    }
  }
}
?>
<html>
    <head>
    <title>Docker Updates</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favico.jpeg">
    </head>
<body>
<?php
?>
<div class="content">
<h1><a href=index.php>Dockcheck</a></h1>
<?php
if(isset($_GET['update'])){
  unset($_GET['update']);
  echo "<div class=\"loading-container\">
  <div class=\"loading\"></div>
  <div id=\"loading-text\">loading</div>
  </div>";
  echo "This might take a while, it depends on how many containers are running";
  bg();
}

?>
<header>
  <h1><a href=index.php?update>Check for updates</a></h1>
</header>
<div class="row">
  <div class="column">
    <table>
      <tr>
        <th class="latest">Containers on latest version:</th>
        <th></th>
        <th></th>
      </tr>
      <?php

$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres");  
if (!$conn) {  
 echo "An error occurred.\n";  
 exit;  
}  

$results = pg_query($conn, "SELECT * FROM CONTAINERS WHERE LATEST='true' ORDER BY HOST");  




Foreach ($results as $row)
{
  echo '<tr>';
  echo '<td>'. $row ['NAME'].'</td>';
  echo '<td>'. $row ['HOST'].'</td>';
  echo '<td></td>';
  echo '</tr>';
}



        ?>
    </table>
  </div>
  <div class="column">
    <table>
      <tr>
        <th class="update">Containers with updates available:</th>
        <th></th>
        <th></th>
      </tr>
      <?php

$file_db = new PDO ('sqlite:/app/containers.db');
$results = $file_db-> query ('select * FROM CONTAINERS WHERE NEW="true" ORDER BY HOST');
Foreach ($results as $row)
{
  echo '<tr>';
  echo '<td>'. $row ['NAME'].'</td>';
  echo '<td>'. $row ['HOST'].'</td>';
  echo '<td></td>';
  echo '</tr>';
}



        ?>
    </table>

  </div>

</div>
<div class="row">
<div class="error">
    <hr>
    <table>
      <tr>
        <th class="error">Containers with errors, wont get updated:</th>
        <th></th>
        <th></th>
      </tr>
      <?php

$file_db = new PDO ('sqlite:/app/containers.db');
$results = $file_db-> query ('select * FROM CONTAINERS WHERE ERROR="true" ORDER BY HOST');


Foreach ($results as $row)
{
  echo '<tr>';
  echo '<td>'. $row ['NAME'].'</td>';
  echo '<td>'. $row ['HOST'].'</td>';
  echo '<td></td>';
  echo '</tr>';
}


        ?>
    </table>

  </div>
</div>

</body>
</html>
