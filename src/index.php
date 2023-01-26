<html>
    <head>
    <title>Certificate Web Request</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favico.jpeg">
    </head>
<body>
<?php


?>

<div class="content">
<h1><a href=index.php>DockCheck</a></h1>

<?
#$conlatest = file_get_contents("/app/containers");
#$conlatest = (file_get_contents("/app/containers"));


#$conlatest_regexp = "/(?<=Containers on latest version:\n)(?s).*?(?=\n\n)/";
preg_match("/(?<=Containers on latest version:\n)(?s).*?(?=\n\n)/", file_get_contents("/app/containers"), $conlatest_match);
preg_match("/(?<=Containers with errors, wont get updated:\n)(?s).*?(?=\n\n)/", file_get_contents("/app/containers"), $conerror_match);
preg_match("/(?<=Containers with updates available:\n)(?s).*?(?=\n\n)/", file_get_contents("/app/containers"), $conupdate_match);


#print_r($conlatest_match);
#print_r(array_keys($conlatest_match));

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
        <th>Updates</th>
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
        <th>No updates</th>
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
        <th>Error</th>
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
