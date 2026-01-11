<?php

$connection= new mysqli("localhost", "root", "", "ushering");

if($connection->connect_error){
    die("Connection failed: " . $connection->connect_error);

}