<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $response = (object)array();
	    
    $query2=mysqli_query($conn, "SELECT count(sdata_barang.id_barang) as jumlah,sdata_pemilik.* FROM sdata_pemilik LEFT JOIN sdata_barang on sdata_pemilik.id_pemilik=sdata_barang.id_pemilik where sdata_pemilik.delete_status='1' GROUP BY sdata_pemilik.id_pemilik ORDER BY sdata_pemilik.id_pemilik DESC");
    $data=array();
	$index=0;
	
	while($row2= mysqli_fetch_array($query2)){
	    if($row2['jumlah']>0){
	        array_push($data,array(
    	       "index"=>$index,
    	       "id_pemilik"=>$row2['id_pemilik'],
    	       "gambar"=>$url."uploads/".$row2['gambar'],
    	       "nama"=>$row2['nama'],
    	       "title"=>$row2['nama'],
    	       "email"=>$row2['email'],
    	       "jumlah"=>$row2['jumlah'],
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
 
   
	mysqli_close($conn);

?>