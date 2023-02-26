<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);

    $response = (object)array();
    
    $push=$conn-> real_escape_string($json['push']);
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    
    if($row['push']==$push && $push!=''){
        $queryz = mysqli_query($conn, "UPDATE notifikasi SET status_baca='1' WHERE id='$id' AND status_baca='0'");
        
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