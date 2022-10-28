<?php

function dbConnect()
{
    static $connect = null;
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'qsoft';
    
    if ($connect === null) {
        $connect = mysqli_connect($host, $user, $password, $dbname) or die('connection error');
    }

    return $connect;
}
