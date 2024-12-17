<?php
$servername="localhost";
$usernme="root";
$password="hamdi";
$dbname="blog";

$conn=mysqli_connect($servername,$usernme,$password,$dbname);

if(!$conn){
    die("echec de la connection:".mysqli_connect_error());
} 
?>
