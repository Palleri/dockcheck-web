<html>
    <head>
    <title>Certificate Web Request</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favico.jpeg">
    </head>
<body>


<div class="content">
<h1><a href=index.php>Certificate Web Request</a></h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">




    <table class="tg" width="100%">
<thead>
  <tr>
    <td>    
        <p><table border=0 class="tg">
    <tbody>
		<tr>
		</tr>
  		<tr>
    		<th class="tg-0lax">FQDN (CN):</th>
		<th class="tg-0lax"><input type="text" name="CN"></th>	
  		</tr>
	        <tr>
    		<th class="tg-0lax">Password:</th>
    		<th class="tg-0lax"><input type="password" name="password"></th>
  		</tr>
  		<tr>
    		<th class="tg-0lax"><input type="submit" value="Send Request"></th>
    		<th class="tg-0lax"><input type="checkbox" name="p12">Create client cert and convert to .p12?<br></th>
            </form>
  		</tr>
        <tr>
        <th>
        <form action="settings.php">
        <input type="submit" value="Email Notification">
        </form>
        </th>
        </tr>
    </tbody>
    </table></p>
</td>

<?php
//Set checkdate to now in $CAnow variable
$CAnow = date("Y-m-d");
//Check expire on CA cert
$CAexpire = exec(' openssl x509 -in files/ca/ca.pem -noout -enddate');
$CAregexp = '/[a-zA-Z]{3}\s+[0-9].*[0-9]{4}/';
preg_match($CAregexp, $CAexpire, $CAmatches);
$CAexpiredate = date('Y-m-d', strtotime($CAmatches[0]));

$CAtxt = file_get_contents("/var/www/html/files/ca/ca.txt");
$CAC = file_get_contents("/var/www/html/files/ca/C.txt");
$CAO = file_get_contents("/var/www/html/files/ca/O.txt");
$CAKEY_renew = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");




if (isset($_POST['carenew']))
        {
        exec('script/carenew.sh '.$CAKEY_renew.'');
	    }


?>


    <td><p><table class="tg" align=center border=0>
    <thead>
        <tr>
	        <th align="center" colspan="3"><form action="files/ca.pem"><input type="submit" value="Download CA certificate" /></form><form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"><input type='submit' name='carenew' value='Renew CA' onclick="return confirm('Are you sure you want to renew CA?')"> <?php echo " " .$CAexpiredate; ?></td></form></th>
        <tr>
        </tr>
            <th colspan="3">Certificate files</th>
        <tr>
    </thead>
    <tbody>
        
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php

// Looking for files in files/
$path    = 'files/';
$files = scandir($path);
//Set $files with everything in folder except '.', '..', 'ca', 'ca.pem'
$files = array_diff(scandir($path), array('.', '..', 'ca', 'ca.pem'));
foreach($files as $file){
    //Set date to now
    $now = date("Y-m-d");
    $warning = date('Y-m-d', strtotime($now. ' + 60 days'));
    $expire = exec('openssl x509 -in files/'.$file.'/'.$file.'.crt -noout -enddate');
    $regexp = '/[a-zA-Z]{3}\s+[0-9].*[0-9]{4}/';
    preg_match($regexp, $expire, $matches);
    $expiredate = date('Y-m-d', strtotime($matches[0]));
    if($now > $expiredate) {

        //If dateexpire set color to red
        echo "<tr>";
        echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
        echo "<th style='color: red;'>$expiredate</th>";
        //If date near set expire color to yellow
    }elseif($warning > $expiredate) {
        echo "<tr>";
        echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
        echo "<th style='color: yellow;'>$expiredate</th>";
    }else{ 

        echo "<tr>";
        echo "<td><a href=files.php?name=$file>$file</a><br></th></td>";
        echo "<th>$expiredate</th>";
}




    ?>
            <th><input type="checkbox" name="fileid" value="<?php echo $file; ?>"></th>
    <?php
    echo "</tr>";
}

// Check if delid is set
if (isset($_POST['Delete'])){
    $fileid = $_POST['fileid'];
    if(!empty($_POST['fileid'])) {
        $regexp = '/-[a-zA-Z]+\.[a-zA-Z]+/';
        $trimmedfile = preg_replace($regexp, '', $fileid);
        //Remove file from disk
        exec('rm -rf /var/www/html/files/'.$fileid.'');
        echo '<meta http-equiv="refresh" content="0; URL=index.php">';
    }
} 

//Rewnew Client or Server cert

if(isset($_POST['Renew'])){


        $fileid = $_POST['fileid'];
        //Check if POST is empty
        if(!empty($_POST['fileid'])){
            $CACA = file_get_contents("/var/www/html/files/ca/CA_NAME.txt");
            $CAKEY = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");
            $p12_path = 'files/'.$fileid.'/'.$fileid.'.p12';
            //If p12 exsist, renew client cert else create server cert
            if(file_exists($p12_path))
            {
                $p12_pw = file_get_contents('/var/www/html/files/'.$fileid.'/'.$fileid.'.pw');
                exec('script/renew_client.sh '.$fileid.' '.$CAKEY.' '.$p12_pw.'');
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';
            }
            //Else
            else 
            {
                exec('script/renew_server.sh '.$fileid.' '.$CAKEY.'');
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';
            }
        }
}



?>

<td></td>
<?php 
// Show delete button if there is a file in files/
if(!empty($file)) {
echo "<td colspan='2'><input type='submit' name='Delete' value='Delete' onclick=\"return confirm('Are you sure you want to delete the certificate?')\"> <input type='submit' name='Renew' value='Renew'></td>";
}
?>
<tr>
</tbody>
</table></p>
</td>
</tr>
</thead>
</table>
    </form>
          </div>




<?php


$CACA = file_get_contents("/var/www/html/files/ca/CA_NAME.txt");
$CAKEY = file_get_contents("/var/www/html/files/ca/CA_KEY.txt");
// Check if CN is set
if (isset($_POST['CN']) ){
	$CN = $_POST['CN'];
	$pw = $_POST['password'];
        // If CN=empty return alert
        if (empty($CN) || empty($pw)) {
        ?>
            <script>
            alert('CN or PW cannot be empty');
            </script>
        <?php
        } else {
        // Check if p12 is checked
        if(!empty($_POST['p12'])) {
            // Check if password is set when p12 is checked
            if(!empty($_POST['password'])) {
                //Create client cert with san    
                exec('script/create_client.sh '.$CN.' '.$pw.' '.$CAKEY.'');            
                echo '<meta http-equiv="refresh" content="0; URL=index.php">';

            } else {
                echo "Password cannot be empty if p12 is checked";
            }
	} else {

        // Create server san
        exec('script/create_server.sh '.$CN.' '.$CAKEY.'');  
        echo '<meta http-equiv="refresh" content="0; URL=index.php">';
        }
    }

}
?>


</body>
</html>
