<?php

    $host = '';
    $port = '';
    $dbname = '';
    $username = '';
    $password = '';

    date_default_timezone_set("Asia/Jakarta");
	$ts=date("Y-m-d h:i:s");
	
	$tz = 'Asia/Jakarta';
    $timestamp = time();
    $dt = new DateTime("now", new DateTimeZone($tz)); 
    $dt->setTimestamp($timestamp); 
    $times=$dt->format('H:i:s');
    $dates=$dt->format("Y-m-d");
    
	$web="";

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        echo "Koneksi sukses!";
    }
    catch(PDOException $e) {
        echo "Koneksi gagal: " . $e->getMessage();
    }
	
?>