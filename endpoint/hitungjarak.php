<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
    $dataJson=$json['data'];
	
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
       
    	$datajarak=array();
    	$id_barang='';
	    foreach($dataJson as $item){
            $id_barang=$conn-> real_escape_string($item['id_barang']);
	        $query6 = mysqli_query($conn, "SELECT * FROM sdata_barang INNER JOIN sdata_pemilik on sdata_pemilik.id_pemilik=sdata_barang.id_pemilik WHERE sdata_barang.id_barang='$id_barang'");
	        $row6 = mysqli_fetch_array($query6);
	        array_push($datajarak,array(
	            "id"=>$row6['id_pemilik'],
    		    "latitude"=>$row6['latitude'],
    		    "longitude"=>$row6['longitude']
    		));
	    }
	    
	    $datajarak=array_unique($datajarak,SORT_REGULAR);
    	
    	if($query){
    	    
            $response->data=$datajarak;
            $response->data2=$datajarak;
    	 	$response->message = "Data sukses ditampilkan";
	 	    echo json_encode($response);
	 	    
    	} else { 
    	    
            $response->data=[];
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
    	
    }else{
        
        $response->data=[];
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>