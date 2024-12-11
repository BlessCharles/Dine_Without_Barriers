<?php

$servername='localhost';
$username='root';
$password='';
$dbname='dwb';

$conn=mysqli_connect($servername,$username,$password,$dbname) or die('unable to connect');

if($conn->connect_error){
    die('connection failed');

}

?>