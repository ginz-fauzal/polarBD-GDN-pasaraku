<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
	
    $response = (object)array();
	
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    $email=$row['email'];
    
    if($row['push']==$push && $push!=''){
        
        $query2=mysqli_query($conn, "SELECT * FROM sdata_pemilik where email='$email'");
        $data=array();
    	$index=0;
    	
    	while($row2= mysqli_fetch_array($query2)){
    	    
    	   array_push($data,array(
    	       "index"=>$index,
    	       "id_pemilik"=>$row2['id_pemilik'],
    	       "gambar"=>$url."uploads/".$row2['gambar'],
    	       "nama"=>$row2['nama'],
    	       "title"=>$row2['nama'],
    	       "email"=>$row2['email'],
    	       "catatan"=>$row2['catatan'],
    	       "telepon"=>$row2['telepon'],
    	       "provinsi"=>$row2['provinsi'],
    	       "kota"=>$row2['kota'],
    	       "kecamatan"=>$row2['kecamatan'],
    	       "latitude"=>$row2['latitude'],
    	       "longitude"=>$row2['longitude'],
    	       "desa"=>$row2['desa'],
    	       "alamat"=>$row2['alamat'],
    	       "delete_status"=>$row2['delete_status']
    	   ));
    	   $index=$index+1;
    	}
    	
    	if($query2){
    	    
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