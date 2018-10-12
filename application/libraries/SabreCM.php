<?php 
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db     = "ga_has";
    $conn   = mysqli_connect($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
    
    $sql    = "SELECT config_temp FROM has_config WHERE config_kode = 'SCM'";
    $result = mysqli_query($conn,$sql);
    $row    = mysqli_fetch_assoc($result);
    $temp   = json_decode($row['config_temp']);
?>