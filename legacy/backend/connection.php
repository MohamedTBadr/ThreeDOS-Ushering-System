<?php

$connection = new mysqli("sql313.infinityfree.com", "if0_40296491", "cEP6EEQIV9", "if0_40296491_threedosushering");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);

}