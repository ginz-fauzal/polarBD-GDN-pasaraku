<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    
    
    $query5 = mysqli_query($conn, "SELECT * FROM konfigurasi where id_konfigurasi='3'");
	$row5= mysqli_fetch_array($query5);
	 $query6 = mysqli_query($conn, "SELECT * FROM konfigurasi where id_konfigurasi='4'");
	$row6= mysqli_fetch_array($query6);
	$query7 = mysqli_query($conn, "SELECT * FROM konfigurasi where id_konfigurasi='7'");
	$row7= mysqli_fetch_array($query7);
	$query8 = mysqli_query($conn, "SELECT * FROM konfigurasi where id_konfigurasi='8'");
	$row8= mysqli_fetch_array($query8);
   
    $response->teleponcs = '62'.substr($row6['status'], 1);
    $response->teleponadmin = '62'.substr($row5['status'], 1);
    $response->flashsale = $row7['status'];
    $response->tokobaru = $row8['status'];
    $response->success = 1;
     die(json_encode($response));
    
    
    
	mysqli_close($conn);

?>