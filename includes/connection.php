<?php 
$mysqli = new mysqli("127.0.0.1","root","mysql","tone_analyzer", "3306");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>