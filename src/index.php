

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
<header>
  <h1><a href=index.php?update>Check for updates</a></h1>
  
</header>
<?






if(isset($_GET['update'])){
  
  $create_file_update = fopen("/var/www/html/update.txt", "w") or die("Unable to open file!");
  $txt = '1';
  fwrite($create_file_update, $txt);
  
  
  $read_file = file_get_contents('/var/www/html/update.txt');
  
  
  if($read_file == '1'){
    echo "<div class=\"loader\"></div>";
    echo "This might take a while, it depends on how many containers are running";
    $i = '0';
    while($read_file == '1'){
      $read_file = file_get_contents('/var/www/html/update.txt');
      
      
flush();

    }
    

      if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
      $url = "https://";   
      else  
      $url = "http://";   
      
      $url.= $_SERVER['HTTP_HOST'];   
      $url.= $_SERVER['REQUEST_URI'];    
      
      $url_stripped = str_replace("?update", "", $url);
      
    echo "<script>window.location = '$url_stripped'</script>";
    

  }

}


$filename = '/app/containers';
$f = fopen($filename, 'r');

if ($f) {
    $contents = fread($f, filesize($filename));
    fclose($f);
    preg_match("/(?<=Containers with errors, wont get updated:\n)(?s).*?(?=\n\n)/", $contents, $conerror_match); 
    $string_output_error = implode('', $conerror_match);
    $conerror_match = preg_split('`\n`', $string_output_error);

    preg_match("/(?<=Containers on latest version:\n)(?s).*?(?=\n\n)/", $contents, $conlatest_match);    
    $string_output_latest = implode('', $conlatest_match);
    $conlatest_match = preg_split('`\n`', $string_output_latest);

    preg_match("/(?<=Containers with updates available:\n)(?s).*?(?=\n\n)/", $contents, $conupdate_match);
    $string_output_update = implode('', $conupdate_match);
    $conupdate_match = preg_split('`\n`', $string_output_update);
}


$keyslatest = array_keys($conlatest_match);
$arraysizelatest = count($conlatest_match); 

$keyserror = array_keys($conerror_match);
$arraysizeerror = count($conerror_match);


$keysupdate = array_keys($conupdate_match);
$arraysizeupdate = count($conupdate_match);

?>
<div class="row">
  <div class="column">
    <table>
      <tr>
        <th class="latest">Containers on latest version:</th>
        <th></th>
        <th></th>
      </tr>
      <?php
      if(!empty($conlatest_match)) {
            for($i=0; $i < $arraysizelatest; $i++) {
                echo '<tr>';
                echo '<td>' . $conlatest_match[$keyslatest[$i]] . '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
            }
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
      if(!empty($conupdate_match)) {
            for($i=0; $i < $arraysizeupdate; $i++) {
                echo '<tr>';
                echo '<td>' . $conupdate_match[$keysupdate[$i]] . '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
            }
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
        if(!empty($conerror_match)) {
            for($i=0; $i < $arraysizeerror; $i++) {
                echo '<tr>';
                echo '<td>' . $conerror_match[$keyserror[$i]] . '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';

            }
          }
        ?>
    </table>

  </div>
</div>

</body>
</html>
