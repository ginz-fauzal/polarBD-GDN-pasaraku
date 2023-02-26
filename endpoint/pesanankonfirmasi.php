<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $response = (object)array();

    $push=$conn-> real_escape_string($json['push']);
    $id_checkout=$conn-> real_escape_string($json['id_checkout']);
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        $queryz = mysqli_query($conn, "UPDATE sdata_checkout SET status='Selesai' WHERE id_checkout='$id_checkout'");
        if($queryz){
            $response->message = "Berhasil";
    	 	die(json_encode($response));
        }else{
            $response->message = "gagal";
    	 	die(json_encode($response));
        }
   
    }else{
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
    }
    
    
	mysqli_close($conn);

?>