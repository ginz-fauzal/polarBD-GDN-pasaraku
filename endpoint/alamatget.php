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
        
        $query1=mysqli_query($conn, "SELECT * FROM alamat WHERE id='$id'");
        $data=array();
    	$index=0;
    	
    	while($row1= mysqli_fetch_array($query1)){
    	    
    	   array_push($data,array(
    	       "index"=>$index,
    	       "id_alamat"=>$row1['id_alamat'],
    	       "id"=>$row1['id'],
    	       "nama_alamat"=>$row1['nama_alamat'],
    	       "telepon"=>$row1['telepon'],
    	       "provinsi"=>$row1['provinsi'],
    	       "kota"=>$row1['kota'],
    	       "kecamatan"=>$row1['kecamatan'],
    	       "latitude"=>$row1['latitude'],
    	       "longitude"=>$row1['longitude'],
    	       "desa"=>$row1['desa'],
    	       "alamat"=>$row1['alamat'],
    	       "title"=>$row1['alamat'],
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	if($query){
    	    
            $response->data=$data;
            $response->success = 1;
    	 	$response->message = "Data sukses ditampilkan";
	 	    echo json_encode($response);
	 	    
    	} else { 
    	    
            $response->data=[];
            $response->success = 0;
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
    	
    }else{
        
        $response->data=[];
        $response->success = 0;
        $response->message = "API ini telah dilindungi. ::)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>