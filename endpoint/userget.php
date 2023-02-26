<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $push=$conn-> real_escape_string($json['push']);
	
	$response = (object)array();
	
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
    	  
        if ($query) {
            
    	 	$response->success = 1;
	        $response->tanggal_lahir =$row['tanggal_lahir'];
	        $response->telepon =$row['telepon'];
	        $response->nama =$row['name'];
	        $response->email =$row['email'];
	        $response->jenis_kelamin =$row['jenis_kelamin'];
	        $response->gambar =$web."uploads/".$row['gambar'];
    	 	die(json_encode($response));
    	 	
        }else {
            
            $response->success = 0;
    	 	die(json_encode($response));
    	 	
        }
    
    }else{
        
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
	mysqli_close($conn);

?>