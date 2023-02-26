<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $push=$conn-> real_escape_string($json['push']);
    $id_barang=$conn-> real_escape_string($json['id_barang']);
    
    $response = (object)array();
	
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        $query4= mysqli_query($conn,"UPDATE `sdata_barang` SET `status_post`='Hide' WHERE index_user='$id_barang'");
        
    	$query = mysqli_query($conn, "UPDATE barang SET status_barang='Hapus',updated_at='$ts' WHERE id_barang='$id_barang'");
    	
        if($query){
            $response->success=1;
    	 	$response->message = "Hapus data berhasil";
    	 	echo json_encode($response);
    	} else { 
            $response->success=0;
    	 	$response->message = "Hapus data gagal";
    	 	echo json_encode($response);
    	}
	
    }else{
        $response->success=0;
        $response->message = "Maaf data anda tidak dikenal.";
        die(json_encode($response));
    }
    mysqli_close($conn);


?>