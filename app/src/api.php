<?php 
  ini_set('max_execution_time', '300');
  set_time_limit(300);

  header("Content-Type: application/json; charset=UTF-8");
    
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

  sort($conlatest_match);
  sort($conupdate_match);
  sort($conerror_match);

  $latest = array_values($conlatest_match);
  $updates = array_values($conupdate_match);
  $errors = array_values($conerror_match);

  $api_response = array( 'latest' => $latest, 'updates' => $updates, 'errors' => $errors );
  
  echo json_encode($api_response);
?>