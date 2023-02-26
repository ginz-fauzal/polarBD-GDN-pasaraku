<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
	
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    
    if($row['push']==$push && $push!=''){
        
        $query2 = mysqli_query($conn, "SELECT * FROM pengiriman order by harga ASC");
        $data=array();
    	$index=0;
    	
    	while($row= mysqli_fetch_array($query2)){
    	    
    	   array_push($data,array(
    	       "index"=>$index,
    	       "id_pengiriman"=>$row['id_pengiriman'],
    	       "title"=>$row['nama_pengiriman'],
    	       "harga"=>$row['harga'],
    	       "biaya"=>$row['harga'],
    	       "tambah"=>$row['tambahan'],
    	       "keterangan"=>$row['keterangan'],
    	       "visual"=>false
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	$query3 = mysqli_query($conn, "SELECT * FROM konfigurasi where id_konfigurasi='1'");
    	$row3= mysqli_fetch_array($query3);
    	
    	
    	if($query){
    	    
            $response->status=$row3['status'];
            $response->data=$data;
    	 	$response->message = "Data sukses ditampilkan";
	 	    echo json_encode($response);
	 	    
    	} else { 
    	    
            $response->status=1;
            $response->data=[];
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
    	
    }else{
        
        $response->status=1;
        $response->data=[];
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>