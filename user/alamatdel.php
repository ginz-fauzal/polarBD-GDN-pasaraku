<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $push=$conn-> real_escape_string($json['push']);
    $id_alamat=$conn-> real_escape_string($json['id_alamat']);

    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    
    if($row['push']==$push && $push!=''){
        
        $query1 = mysqli_query($conn, "DELETE FROM `alamat` WHERE id_alamat='$id_alamat' AND id='$id'");
      
        if($query1){
            
            $response->message = "Data berhasil dihapus";
    	 	die(json_encode($response));
    	 	
        }else{
            
            $response->message = "Data gagal dihapus";
    	 	die(json_encode($response));
    	 	
        }
   
    }else{
        
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
    
	mysqli_close($conn);

?>