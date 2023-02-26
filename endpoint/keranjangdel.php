<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $dataJson=$json['data'];
    $push=$conn-> real_escape_string($json['push']);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        
        foreach($dataJson as $item){
            
            $id_listkeranjang=$conn-> real_escape_string($item['id_listkeranjang']);
            $query2 = mysqli_query($conn, "DELETE FROM `sdata_listkeranjang` WHERE id_listkeranjang='$id_listkeranjang'");
            
        }
        
        if($query2){
        
            $response->success = 1;
            $response->message = "Hapus data berhasil.";
    	 	die(json_encode($response));
            
        }else{
        
            $response->success = 0;
            $response->message = "Maaf server sedang gangguan.";
    	 	die(json_encode($response));
            
        }
   
    }else{
        
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
    
	mysqli_close($conn);

?>