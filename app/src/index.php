<?php 
    ini_set('max_execution_time', '300');
    set_time_limit(300);
function bg()
{
  $create_file_update = fopen("/var/www/html/update.txt", "w") or die("Unable to open file!");
  $txt = '1';
  fwrite($create_file_update, $txt);
  $read_file = file_get_contents('/var/www/html/update.txt');
  if($read_file == '1'){
    while($read_file == '1'){
      $read_file = file_get_contents('/var/www/html/update.txt');
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
<?
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
       sort($conlatest_match);
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
       sort($conupdate_match);
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
       sort($conerror_match);
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

<div class="row">
  <a href=api.php>JSON API</a>
</div>

</body>
</html>
